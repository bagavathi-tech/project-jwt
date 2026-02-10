<?php
class AuthController {

    public static function register($data) {

        // ✅ SAFETY CHECK (VERY IMPORTANT)
        if (!$data) {
            Response::json(400, "Request body missing");
        }

        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            Response::json(400, "Name, email and password required");
        }

        if (User::findByEmail($data['email'])) {
            Response::json(400, "Email already exists");
        }

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);

        User::create(
            $data['name'],
            $data['email'],
            $hash
        );

        Response::json(201, "User registered");
    }

    public static function login($data) {

        if (!$data) {
            Response::json(400, "Request body missing");
        }

        if (
            empty($data['email']) ||
            empty($data['password'])
        ) {
            Response::json(400, "Email and password required");
        }

        $user = User::findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::json(401, "Invalid credentials");
        }

        $payload = [
            "user_id" => $user['id'],
            "email"   => $user['email'],
            "iat"     => time(),
            "exp"     => time() + JWT_EXPIRY
        ];

        Response::json(200, "Login success", [
            "token" => JWT::generate($payload)
        ]);
    }
}
