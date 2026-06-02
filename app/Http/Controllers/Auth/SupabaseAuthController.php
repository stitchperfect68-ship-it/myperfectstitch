<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
