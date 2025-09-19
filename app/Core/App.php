<?php
/**
 * Core Application Class
 * Main application orchestrator
 */

class App
{
    private $router;
    private $database;
    
    public function __construct(Router $router, Database $database)
    {
        $this->router = $router;
        $this->database = $database;
        
        // Initialize security middleware
        $this->initializeSecurity();
    }
    
    /**
     * Initialize security measures
     */
    private function initializeSecurity()
    {
        // CSRF protection
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Rate limiting
        $this->checkRateLimit();
    }
    
    /**
     * Check rate limiting
     */
    private function checkRateLimit()
    {
        $ip = $this->getClientIP();
        $key = 'rate_limit_' . $ip;
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'count' => 0,
                'time' => time()
            ];
        }
        
        $rateData = $_SESSION[$key];
        
        // Reset counter if hour has passed
        if (time() - $rateData['time'] > 3600) {
            $_SESSION[$key] = [
                'count' => 1,
                'time' => time()
            ];
        } else {
            $_SESSION[$key]['count']++;
            
            if ($_SESSION[$key]['count'] > API_RATE_LIMIT) {
                http_response_code(429);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Rate limit exceeded']);
                exit;
            }
        }
    }
    
    /**
     * Get real client IP address
     */
    private function getClientIP()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Run the application
     */
    public function run()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        
        // Remove query string
        $uri = strtok($uri, '?');
        
        // Handle the request
        $this->router->handle($uri, $method, $this->database);
    }
}