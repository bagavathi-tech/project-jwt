<?php
class PatientController {

    public static function index() {
        AuthMiddleware::handle();
        $res = Patient::all();
        Response::json(200, "Patients", $res->fetch_all(MYSQLI_ASSOC));
    }

    public static function store($data) {
        AuthMiddleware::handle();
        Patient::create($data);
        Response::json(201, "Patient created");
    }

    public static function update($id, $data) {
        AuthMiddleware::handle();
        Patient::update($id, $data);
        Response::json(200, "Patient updated");
    }

    public static function delete($id) {
        AuthMiddleware::handle();
        Patient::delete($id);
        Response::json(200, "Patient deleted");
    }
}
