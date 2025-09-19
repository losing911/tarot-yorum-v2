<?php
/**
 * Tarot-Yorum.fun - AI Powered Tarot & Astrology Platform
 * Entry Point & Router
 * 
 * @author PHP Expert Developer
 * @version 1.0.0
 */

// Session configuration must be set before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Define constants
define('ROOT_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEWS_PATH', ROOT_PATH . '/views');

// Load helper functions
require_once APP_PATH . '/Helpers/functions.php';

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

// Load configuration
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';

// Initialize core components
$database = new Database();
$router = new Router();
$app = new App($router, $database);

// Define routes
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

// Authentication routes
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/verify-email/{token}', 'AuthController@verifyEmail');
$router->get('/forgot-password', 'AuthController@forgotPasswordForm');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password/{token}', 'AuthController@resetPasswordForm');
$router->post('/reset-password', 'AuthController@resetPassword');

// Zodiac routes
$router->get('/zodiac', 'ZodiacController@index');
$router->get('/zodiac/{sign}', 'ZodiacController@show');
$router->get('/zodiac/{sign}/daily', 'ZodiacController@daily');
$router->get('/zodiac/{sign}/weekly', 'ZodiacController@weekly');
$router->get('/zodiac/{sign}/monthly', 'ZodiacController@monthly');
$router->get('/compatibility/{sign1}/{sign2}', 'ZodiacController@compatibility');

// Tarot routes
$router->get('/tarot', 'TarotController@index');
$router->get('/tarot/spread/{spread}', 'TarotController@spread');
$router->post('/tarot/reading', 'TarotController@reading');
$router->get('/tarot/result/{id}', 'TarotController@result');
$router->get('/tarot/history', 'TarotController@history');
$router->get('/tarot/daily', 'TarotController@dailyCard');
$router->get('/tarot/api/random-cards/{count}', 'TarotController@getRandomCards');
$router->get('/tarot/api/cards', 'TarotController@getAllCards');
$router->get('/tarot/api/card/{id}', 'TarotController@getCard');

// Blog routes
$router->get('/blog', 'BlogController@index');
$router->get('/blog/create', 'BlogController@create');
$router->post('/blog/store', 'BlogController@store');
$router->get('/blog/search', 'BlogController@search');
$router->post('/blog/comment', 'BlogController@addComment');
$router->post('/blog/ai-suggestion', 'BlogController@aiSuggestion');
$router->get('/blog/{slug}/edit', 'BlogController@edit');
$router->post('/blog/{slug}/update', 'BlogController@update');
$router->get('/blog/{slug}', 'BlogController@show');
$router->get('/category/{category}', 'BlogController@category');

// User profile routes
$router->get('/profile', 'UserController@profile');
$router->post('/profile', 'UserController@updateProfile');
$router->get('/profile/{username}', 'UserController@publicProfile');

// Comment routes
$router->post('/comments', 'CommentController@store');
$router->post('/comments/{id}/delete', 'CommentController@delete');

// Admin routes
$router->get('/admin', 'AdminController@index');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/content', 'AdminController@content');
$router->get('/admin/comments', 'AdminController@comments');
$router->get('/admin/analytics', 'AdminController@analytics');
$router->get('/admin/settings', 'AdminController@settings');

// Admin API routes (PATCH, DELETE)
$router->patch('/admin/users/{id}', 'AdminController@updateUser');
$router->delete('/admin/users/{id}', 'AdminController@deleteUser');
$router->patch('/admin/comments/{id}', 'AdminController@updateComment');
$router->delete('/admin/comments/{id}', 'AdminController@deleteComment');
$router->post('/admin/settings/update', 'AdminController@updateSettings');
$router->post('/admin/backup', 'AdminController@backup');

// Admin export routes
$router->get('/admin/export/{type}/{format}', 'AdminController@export');

// API routes
$router->get('/api/zodiac/{sign}/today', 'ApiController@zodiacToday');
$router->post('/api/tarot/generate', 'ApiController@generateTarotReading');
$router->post('/api/blog/suggest', 'ApiController@suggestBlogContent');

// SEO routes
$router->get('/sitemap.xml', 'SeoController@sitemap');
$router->get('/robots.txt', 'SeoController@robots');

// Run the application
try {
    $app->run();
} catch (Exception $e) {
    error_log('Application Error: ' . $e->getMessage());
    
    if (DEBUG_MODE) {
        echo '<h1>Application Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        include VIEWS_PATH . '/errors/500.php';
    }
}