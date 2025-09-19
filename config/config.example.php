<?php
/**
 * Application Configuration
 * Production-ready settings for Tarot-Yorum.fun
 */

// Environment settings
define('DEBUG_MODE', false); // Set to false in production
define('APP_NAME', 'Tarot-Yorum.fun');
define('APP_URL', 'https://your-domain.com'); // Change to your domain
define('APP_VERSION', '1.0.0');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'tarot_db');
define('DB_USER', 'your_db_user'); // Change for production
define('DB_PASS', 'your_db_password'); // Change for production
define('DB_CHARSET', 'utf8mb4');

// Security settings
define('SECRET_KEY', 'generate-a-unique-secret-key-here'); // Change this!
define('CSRF_TOKEN_EXPIRY', 3600); // 1 hour
define('SESSION_LIFETIME', 86400); // 24 hours
define('PASSWORD_MIN_LENGTH', 8);

// Email configuration (for email verification)
define('MAIL_HOST', 'smtp.your-provider.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@your-domain.com'); // Change this
define('MAIL_PASSWORD', 'your-email-password'); // Change this
define('MAIL_FROM_EMAIL', 'noreply@your-domain.com');
define('MAIL_FROM_NAME', 'Tarot-Yorum.fun');

// AI API Configuration
define('OPENAI_API_KEY', 'your-openai-api-key'); // Add your OpenAI API key
define('OPENAI_MODEL', 'gpt-4');
define('GEMINI_API_KEY', 'your-gemini-api-key'); // Add your Gemini API key
define('AI_PROVIDER', 'openai'); // 'openai' or 'gemini'
define('AI_MAX_TOKENS', 2000);

// Google Services
define('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX'); // Add your GA4 tracking ID
define('GOOGLE_ADSENSE_CLIENT', 'ca-pub-xxxxxxxxxx'); // Add your AdSense client ID
define('GOOGLE_ADSENSE_ENABLED', true);

// Rate limiting
define('API_RATE_LIMIT', 60); // Requests per hour per IP
define('TAROT_READING_LIMIT', 5); // Readings per day for free users

// File upload settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Cache settings
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 hour

// SEO Settings
define('DEFAULT_META_TITLE', 'Tarot-Yorum.fun - AI Destekli Tarot ve Astroloji Platformu');
define('DEFAULT_META_DESCRIPTION', 'Yapay zeka destekli tarot falı, günlük burç yorumları ve astroloji rehberi. Ücretsiz tarot okuma ve kişiselleştirilmiş burç analizleri.');
define('DEFAULT_META_KEYWORDS', 'tarot, astroloji, burç, fal, yapay zeka, günlük burç, tarot okuma');

// Timezone
date_default_timezone_set('Europe/Istanbul');

// Locale settings
setlocale(LC_TIME, 'tr_TR.UTF-8');

// Error logging
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . '/logs/app_errors.log');

// Create necessary directories if they don't exist
$directories = [
    ROOT_PATH . '/logs',
    ROOT_PATH . '/storage/uploads',
    ROOT_PATH . '/storage/cache',
    ROOT_PATH . '/storage/sessions'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Function to get configuration value
function config($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}