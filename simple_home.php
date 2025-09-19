<?php
// Session configuration must be set before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();

// Create CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Define constants
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEWS_PATH', ROOT_PATH . '/views');

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
            return;
        }
    }
});

// Load helper functions
require_once APP_PATH . '/Helpers/functions.php';

// Load configuration
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';

// Initialize database
$database = new Database();

// Get zodiac signs
$zodiacModel = new ZodiacSign($database);
$zodiacSigns = $zodiacModel->findAll();

echo "<!DOCTYPE html>";
echo "<html><head><title>Tarot-Yorum.fun - Ana Sayfa</title>";
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo "</head><body>";
echo "<div class='container mt-5'>";
echo "<h1 class='text-center'>🔮 Tarot-Yorum.fun</h1>";
echo "<h2>Burçlar</h2>";
echo "<div class='row'>";

foreach ($zodiacSigns as $sign) {
    echo "<div class='col-md-3 mb-3'>";
    echo "<div class='card'>";
    echo "<div class='card-body text-center'>";
    echo "<h5>" . $sign['name'] . "</h5>";
    echo "<p>" . $sign['symbol'] . "</p>";
    echo "<p><small>" . $sign['date_range'] . "</small></p>";
    echo "</div></div></div>";
}

echo "</div>";
echo "<h2>Sistem Durumu</h2>";
echo "<p>✅ Database bağlantısı başarılı</p>";
echo "<p>✅ " . count($zodiacSigns) . " burç yüklendi</p>";
echo "<p>✅ Sistem çalışıyor</p>";
echo "</div></body></html>";
?>