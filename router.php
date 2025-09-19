<?php
/**
 * Router file for PHP built-in server
 * This handles routing when using php -S
 */

// Check if file exists and serve it directly
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// For everything else, use index.php
require_once __DIR__ . '/index.php';