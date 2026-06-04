<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseAuthController extends Controller
{
    /**
     * Sync a verified Supabase session with a local Customer record,
     * then start a Laravel customer session so the existing checkout flow works.
     */
    public function sync(Request $request)
    {
        // The VerifySupabaseAuth middleware already validated the token and
        // attached the Supabase payload to the request.
        $supaUser = $request->attributes->get('supabase_user');

        if (!$supaUser) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $email       = $supaUser['email'] ?? null;
        $supabaseId  = $supaUser['sub']   ?? ($supaUser['id'] ?? null);

        if (!$email) {
            return response()->json(['error' => 'No email in Supabase token.'], 422);
        }

        // Find or create the local Customer record
        $customer = Customer::firstOrCreate(
            ['email' => $email],
            [
                'name'        => $supaUser['user_metadata']['full_name'] ?? explode('@', $email)[0],
                'password'    => bcrypt(Str::random(32)), // random password — login is via Supabase only
                'supabase_id' => $supabaseId,
                'is_active'   => true,
            ]
        );

        // Update supabase_id if missing (for existing customers)
        if (!$customer->supabase_id && $supabaseId) {
            $customer->update(['supabase_id' => $supabaseId]);
        }

        // Start a Laravel customer session
        Auth::guard('customer')->login($customer, remember: true);

        return response()->json([
            'success'  => true,
            'customer' => [
                'id'    => $customer->id,
                'name'  => $customer->name,
                'email' => $customer->email,
            ],
        ]);
    }

    /**
     * Accept a Supabase token via POST body, verify it directly with Supabase,
     * create the Laravel customer session, then redirect to the intended destination.
     * This bypasses all cookie/header issues for browser navigation.
     */
    public function tokenLogin(Request $request)
    {
        $token = $request->input('sb_token');

        if (!$token) {
            return response()->json(['ok' => false, 'reason' => 'no_token'], 400);
        }

        $url     = config('supabase.url');
        $anonKey = config('supabase.anon_key');

        if (!$url || str_contains($url, 'your-project-ref')) {
            return response()->json(['ok' => false, 'reason' => 'supabase_not_configured'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'apikey'        => $anonKey,
            ])->timeout(10)->get("{$url}/auth/v1/user");

            if (!$response->successful()) {
                return response()->json([
                    'ok'     => false,
                    'reason' => 'supabase_rejected',
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ], 401);
            }

            $supaUser   = $response->json();
            $email       = $supaUser['email'] ?? null;
            $supabaseId  = $supaUser['id'] ?? null;

            if (!$email) {
                return response()->json(['ok' => false, 'reason' => 'no_email'], 422);
            }

            $customer = Customer::firstOrCreate(
                ['email' => $email],
                [
                    'name'        => $supaUser['user_metadata']['full_name']
                                     ?? $supaUser['user_metadata']['name']
                                     ?? explode('@', $email)[0],
                    'password'    => bcrypt(Str::random(32)),
                    'supabase_id' => $supabaseId,
                    'is_active'   => true,
                ]
            );

            if (!$customer->supabase_id && $supabaseId) {
                $customer->update(['supabase_id' => $supabaseId]);
            }

            Auth::guard('customer')->login($customer, remember: true);

            return response()->json(['ok' => true]);

        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'reason' => 'exception', 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Sign out — clears the Laravel customer session.
     * The Supabase session is cleared client-side by the JS client.
     */
    public function signout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }
}
