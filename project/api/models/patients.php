<?php
class Patient {

    public static function all() {
        $db = Database::connect();
        return $db->query("SELECT * FROM patients");
    }

    public static function create($data) {
        $db = Database::connect();

        $stmt = $db->prepare(
            "INSERT INTO patients (name, age, gender)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param(
            "sis",
            $data['name'],
            $data['age'],
            $data['gender']
        );

        return $stmt->execute();
    }

    public static function update($id, $data) {
        $db = Database::connect();

        $stmt = $db->prepare(
            "UPDATE patients
             SET name = ?, age = ?, gender = ?
             WHERE id = ?"
        );
        $stmt->bind_param(
            "sisi",
            $data['name'],
            $data['age'],
            $data['gender'],
            $id
        );

        return $stmt->execute();
    }

    public static function delete($id) {
        $db = Database::connect();

        $stmt = $db->prepare(
            "DELETE FROM patients WHERE id = ?"
        );
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
