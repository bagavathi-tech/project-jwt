<?php
class JWT {

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function generate($payload) {

        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        // Add expiry only if not already set
        if (!isset($payload['exp'])) {
            $payload['exp'] = time() + JWT_EXPIRY;
        }

        $headerEncoded  = self::base64UrlEncode(json_encode($header));
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(
            "sha256",
            "$headerEncoded.$payloadEncoded",
            JWT_SECRET,
            true
        );

        $signatureEncoded = self::base64UrlEncode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public static function verify($token) {

        // Clean token
        $token = trim($token);
        
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        $calc = self::base64UrlEncode(
            hash_hmac("sha256", "$header.$payload", JWT_SECRET, true)
        );

        if (!hash_equals($calc, $signature)) {
            // Log for debugging
            error_log("JWT Signature mismatch. Expected: $calc, Got: $signature");
            Response::json(401, "JWT signature mismatch");
        }

        $data = json_decode(self::base64UrlDecode($payload), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Response::json(401, "Invalid JWT payload");
        }

        if (!isset($data['exp'])) {
            Response::json(401, "JWT expiry missing");
        }

        if ($data['exp'] < time()) {
            Response::json(401, "JWT expired");
        }

        return $data;
    }
}