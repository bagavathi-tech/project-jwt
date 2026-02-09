<?php
// config.php - Only load .env, don't define constants here
$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    die(".env file missing");
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $line = trim($line);
    
    if ($line === '' || $line[0] === '#') {
        continue;
    }
    
    if (strpos($line, '=') === false) {
        continue;
    }
    
    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    $value = trim($value, "\"'");
    
    if (!defined($key)) {
        define($key, $value);
    }
}

// Convert JWT_EXPIRY to integer if it's string
if (defined('JWT_EXPIRY') && is_string(JWT_EXPIRY)) {
    define('JWT_EXPIRY_INT', (int) JWT_EXPIRY);
} else {
    define('JWT_EXPIRY_INT', 3600);
}