<?php
class AuthController {

    public static function register($data) {

        if (User::findByEmail($data['email'])) {
            Response::json(400, "Email already exists");
        }

        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        User::create($data['name'], $data['email'], $hash);

        Response::json(201, "User registered");
    }

    public static function login($data) {

        $user = User::findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::json(401, "Invalid credentials");
        }

        $payload = [
            "user_id" => $user['id'],
            "email"   => $user['email'],
            "iat"     => time()
            // REMOVED expiry here - JWT::generate will add it
        ];

        Response::json(200, "Login success", [
            "token" => JWT::generate($payload)
        ]);
    }
}