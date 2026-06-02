<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySupabaseAuth
{
    public function handle(Request $request, Closure $next): mixed
    {
        // Bypass entirely when Supabase credentials are not yet configured
        $url    = config('supabase.url', '');
        $secret = config('supabase.jwt_secret', '');
        if (!$url || !$secret || str_contains($url, 'your-project-ref')) {
            return $next($request);
        }

        $token = $this->extractToken($request);

        if (!$token) {
            return $this->unauthorised($request, 'Authentication required.');
        }

        $user = $this->verifyToken($token);

        if (!$user) {
            return $this->unauthorised($request, 'Invalid or expired session. Please sign in again.');
        }

        $request->attributes->set('supabase_user', $user);
        $request->attributes->set('supabase_token', $token);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        // 1. Authorization: Bearer <token>
        $header = $request->bearerToken();
        if ($header) return $header;

        // 2. X-Supabase-Token header
        $custom = $request->header('X-Supabase-Token');
        if ($custom) return $custom;

        // 3. supabase_token cookie / request field (for form submissions)
        return $request->input('supabase_token') ?? $request->cookie('sb_token');
    }

    private function verifyToken(string $token): ?array
    {
        $secret = config('supabase.jwt_secret');
        $url    = config('supabase.url');

        // If no config yet, allow through in dev to avoid lockout during setup
        if (!$secret || !$url || str_contains($url, 'your-project-ref')) {
            return ['id' => 'dev', 'email' => 'dev@localhost'];
        }

        // Verify HS256 JWT locally (no external HTTP call needed)
        try {
            [$headerB64, $payloadB64, $sigB64] = explode('.', $token);

            $sig      = base64_decode(strtr($sigB64, '-_', '+/') . str_repeat('=', (4 - strlen($sigB64) % 4) % 4));
            $expected = hash_hmac('sha256', "$headerB64.$payloadB64", $secret, true);

            if (!hash_equals($expected, $sig)) return null;

            $payload = json_decode(base64_decode(strtr($payloadB64, '-_', '+/') . str_repeat('=', (4 - strlen($payloadB64) % 4) % 4)), true);

            if (!$payload || ($payload['exp'] ?? 0) < time()) return null;

            return $payload;
        } catch (\Throwable) {
            return null;
        }
    }

    private function unauthorised(Request $request, string $message): mixed
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['error' => $message], 401);
        }

        // Web redirect — include intended URL so we can restore after login
        return redirect()->route('auth.login')->with('intended', $request->fullUrl());
    }
}
