<?php
class User {

    public static function findByEmail($email) {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public static function create($name, $email, $password) {
        $db = Database::connect();

        $stmt = $db->prepare(
            "INSERT INTO users (name, email, password)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $password);
        return $stmt->execute();
    }
}