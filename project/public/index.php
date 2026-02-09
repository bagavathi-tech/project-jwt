<?php
// ===============================
// BOOTSTRAP
// ===============================
require_once __DIR__ . '/../config/config.php';

// ===============================
// CORE HELPERS
// ===============================
require_once __DIR__ . "/../api/helpers/response.php";
require_once __DIR__ . "/../api/helpers/jwt.php";

// ===============================
// CORE
// ===============================
require_once __DIR__ . "/../api/core/database.php";
require_once __DIR__ . "/../api/core/router.php";

// ===============================
// MIDDLEWARE
// ===============================
require_once __DIR__ . "/../api/middleware/JsonMiddleware.php";
require_once __DIR__ . "/../api/middleware/AuthMiddleware.php";

// ===============================
// MODELS
// ===============================
require_once __DIR__ . "/../api/models/user.php";
require_once __DIR__ . "/../api/models/patients.php";

// ===============================
// CONTROLLERS
// ===============================
require_once __DIR__ . "/../api/controllers/AuthController.php";
require_once __DIR__ . "/../api/controllers/PatientController.php";

// ===============================
// JSON + CORS HANDLING
// ===============================
$body = JsonMiddleware::handle();

// ===============================
// URI NORMALIZATION
// ===============================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove project folder from URI if present
$basePath = "/project"; // Change to your folder name
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// ===============================
// ROUTE REQUEST
// ===============================
$method = $_SERVER['REQUEST_METHOD'];
Router::route($uri, $method, $body);