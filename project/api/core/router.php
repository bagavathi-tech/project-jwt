<?php
class Router {

    public static function route($uri, $method, $body) {

        // DEBUG (temporary – remove later)
        // echo $uri . " | " . $method; exit;

        // ---------- AUTH ROUTES ----------
        if ($uri === '/api/register' && $method === 'POST') {
            AuthController::register($body);
            return;
        }

        if ($uri === '/api/login' && $method === 'POST') {
            AuthController::login($body);
            return;
        }

        // ---------- PATIENT ROUTES ----------
        if ($uri === '/api/patients' && $method === 'GET') {
            PatientController::index();
            return;
        }

        if ($uri === '/api/patients' && $method === 'POST') {
            PatientController::store($body);
            return;
        }

        if (preg_match('#^/api/patients/(\d+)$#', $uri, $matches)) {

            $id = $matches[1];

            if ($method === 'PUT' || $method === 'PATCH') {
                PatientController::update($id, $body);
                return;
            }

            if ($method === 'DELETE') {
                PatientController::delete($id);
                return;
            }
        }

        // ---------- NO ROUTE ----------
        Response::json(404, 'Route not found');
    }
}