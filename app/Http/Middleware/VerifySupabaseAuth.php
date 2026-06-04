<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifySupabaseAuth
{
    public function handle(Request $request, Closure $next): mixed
    {
        $url = config('supabase.url', '');

        // Bypass when Supabase is not configured
        if (!$url || str_contains($url, 'your-project-ref')) {
            return $next($request);
        }

        $token = $this->extractToken($request);

        if (!$token) {
            return $this->unauthorised($request, 'Authentication required.');
        }

        $user = $this->verifyToken($token, $url);

        if (!$user) {
            return $this->unauthorised($request, 'Invalid or expired session. Please sign in again.');
        }

        $request->attributes->set('supabase_user', $user);
        $request->attributes->set('supabase_token', $token);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        if ($header = $request->bearerToken()) return $header;
        if ($custom = $request->header('X-Supabase-Token')) return $custom;
        return $request->input('supabase_token') ?? $request->cookie('sb_token');
    }

    private function verifyToken(string $token, string $url): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'apikey'        => config('supabase.anon_key'),
            ])->timeout(5)->get("{$url}/auth/v1/user");

            if (!$response->successful()) return null;

            $user = $response->json();

            // Normalise to the shape the rest of the app expects
            return [
                'sub'           => $user['id'] ?? null,
                'id'            => $user['id'] ?? null,
                'email'         => $user['email'] ?? null,
                'user_metadata' => $user['user_metadata'] ?? [],
                'exp'           => PHP_INT_MAX, // Supabase already validated expiry
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    private function unauthorised(Request $request, string $message): mixed
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json(['error' => $message], 401);
        }

        return redirect()->route('auth.login')->with('intended', $request->fullUrl());
    }
}
