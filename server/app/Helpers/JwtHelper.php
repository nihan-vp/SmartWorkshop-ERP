<?php

namespace App\Helpers;

use App\Models\User;

class JwtHelper
{
    /**
     * Generate a JWT token for a user.
     */
    public static function generateToken(User $user, int $expirationSeconds = 86400): string
    {
        $header = json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]);

        $payload = json_encode([
            'sub' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'iat' => time(),
            'exp' => time() + $expirationSeconds
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSigningKey(), true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Decode and validate a JWT token, returning the payload array.
     */
    public static function validateToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;

        $header = json_decode(self::base64UrlDecode($base64UrlHeader), true);
        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);

        if (!$header || !$payload) {
            return null;
        }

        // Verify expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        // Verify signature
        $signature = self::base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSigningKey(), true);

        if (!hash_equals($signature, $expectedSignature)) {
            return null;
        }

        return $payload;
    }

    private static function getSigningKey(): string
    {
        $key = config('app.key');
        return str_replace('base64:', '', $key);
    }

    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
