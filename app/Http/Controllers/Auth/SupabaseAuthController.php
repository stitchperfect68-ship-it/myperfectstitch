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

        // Only login (which regenerates session) if not already authenticated —
        // calling login() on every sync invalidates the CSRF token in rendered forms
        if (!Auth::guard('customer')->check()) {
            Auth::guard('customer')->login($customer, remember: true);
        }

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
            return redirect()->route('auth.login');
        }

        $url     = config('supabase.url');
        $anonKey = config('supabase.anon_key');

        if (!$url || str_contains($url, 'your-project-ref')) {
            return redirect()->route('auth.login');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'apikey'        => $anonKey,
            ])->timeout(10)->get("{$url}/auth/v1/user");

            if (!$response->successful()) {
                return redirect()->route('auth.login')
                    ->with('error', 'Session expired. Please sign in again.');
            }

            $supaUser   = $response->json();
            $email       = $supaUser['email'] ?? null;
            $supabaseId  = $supaUser['id'] ?? null;

            if (!$email) {
                return redirect()->route('auth.login');
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

            if (!Auth::guard('customer')->check()) {
                Auth::guard('customer')->login($customer, remember: true);
            }

            return redirect()->route('checkout.index');

        } catch (\Throwable $e) {
            return redirect()->route('auth.login')
                ->with('error', 'Sign-in failed: ' . $e->getMessage());
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
