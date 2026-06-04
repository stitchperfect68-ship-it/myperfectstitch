<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RequireCustomerAuth
{
    public function handle(Request $request, Closure $next): mixed
    {
        // Already authenticated via Laravel session — pass through
        if (Auth::guard('customer')->check()) {
            return $next($request);
        }

        // Try to establish session from Supabase token cookie
        $token = $request->cookie('sb_token');

        if ($token && $this->syncFromToken($token)) {
            return $next($request);
        }

        // Not authenticated
        return redirect()->route('auth.login')
            ->with('intended', $request->fullUrl());
    }

    private function syncFromToken(string $token): bool
    {
        $url = config('supabase.url', '');
        if (!$url || str_contains($url, 'your-project-ref')) return false;

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'apikey'        => config('supabase.anon_key'),
            ])->timeout(5)->get("{$url}/auth/v1/user");

            if (!$response->successful()) return false;

            $supaUser   = $response->json();
            $email       = $supaUser['email'] ?? null;
            $supabaseId  = $supaUser['id'] ?? null;

            if (!$email) return false;

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
            return true;

        } catch (\Throwable) {
            return false;
        }
    }
}
