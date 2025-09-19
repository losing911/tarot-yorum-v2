<?php
// Debug router işlemleri
session_start();

// Session configuration must be set before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEWS_PATH', ROOT_PATH . '/views');

echo "<h1>Router Debug</h1>";
echo "<p>URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Method: " . $_SERVER['REQUEST_METHOD'] . "</p>";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "<p>Parsed URI: " . $uri . "</p>";

// Auto-load classes
spl_autoload_register(function ($className) {
    $paths = [
        APP_PATH . '/Controllers/',
        APP_PATH . '/Models/',
        APP_PATH . '/Core/',
        APP_PATH . '/Services/',
        APP_PATH . '/Middlewares/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            echo "<p>✓ Loaded: $className from $file</p>";
            return;
        }
    }
    echo "<p>✗ Failed to load: $className</p>";
});

// Load helper functions
require_once APP_PATH . '/Helpers/functions.php';
echo "<p>✓ Functions loaded</p>";

// Load configuration
require_once CONFIG_PATH . '/config.php';
echo "<p>✓ Config loaded</p>";

require_once CONFIG_PATH . '/database.php';
echo "<p>✓ Database config loaded</p>";

// Initialize core components
$database = new Database();
echo "<p>✓ Database created</p>";

$router = new Router();
echo "<p>✓ Router created</p>";

// Define routes
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');
echo "<p>✓ Routes defined</p>";

// Test if route matches
echo "<h2>Route Testing</h2>";
echo "<p>Looking for route: GET $uri</p>";

try {
    $router->handle($uri, $_SERVER['REQUEST_METHOD'], $database);
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>