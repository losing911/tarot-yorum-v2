<?php
// Basic debug test
echo "<h1>Debug Test</h1>";

try {
    // Define constants first
    define('ROOT_PATH', __DIR__);
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('APP_PATH', ROOT_PATH . '/app');
    define('PUBLIC_PATH', ROOT_PATH . '/public');
    define('STORAGE_PATH', ROOT_PATH . '/storage');
    define('VIEWS_PATH', ROOT_PATH . '/views');
    echo "✓ Constants defined<br>";

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
    echo "✓ Autoloader registered<br>";
    
    // Test autoload
    require_once APP_PATH . '/Helpers/functions.php';
    echo "✓ Functions loaded<br>";
    
    // Test configuration
    require_once CONFIG_PATH . '/config.php';
    echo "✓ Config loaded<br>";
    
    require_once CONFIG_PATH . '/database.php';
    echo "✓ Database config loaded<br>";
    
    // Test database
    $database = new Database();
    echo "✓ Database object created<br>";
    
    $connection = $database->getConnection();
    echo "✓ Database connection successful<br>";
    
    // Test models
    $zodiacModel = new ZodiacSign($database);
    echo "✓ ZodiacSign model created<br>";
    
    $signs = $zodiacModel->findAll();
    echo "✓ Zodiac signs loaded: " . count($signs) . " items<br>";
    
    echo "<h2>Success! All components working.</h2>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}