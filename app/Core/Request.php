<?php
/**
 * Request Class
 * Handle HTTP request data
 */

class Request
{
    private $data;
    
    public function __construct()
    {
        $this->data = $this->parseInput();
    }
    
    /**
     * Parse input data based on request method
     */
    private function parseInput()
    {
        $data = [];
        
        // GET parameters
        $data = array_merge($data, $_GET);
        
        // POST parameters
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SERVER['CONTENT_TYPE']) && 
                strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                // JSON input
                $json = file_get_contents('php://input');
                $jsonData = json_decode($json, true);
                if ($jsonData) {
                    $data = array_merge($data, $jsonData);
                }
            } else {
                // Form data
                $data = array_merge($data, $_POST);
            }
        }
        
        return $data;
    }
    
    /**
     * Get input value
     */
    public function input($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Get all input data
     */
    public function all()
    {
        return $this->data;
    }
    
    /**
     * Check if input exists
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Get file from $_FILES
     */
    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }
    
    /**
     * Validate input data
     */
    public function validate($rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $value = $this->input($field);
            $fieldRules = explode('|', $ruleSet);
            
            foreach ($fieldRules as $rule) {
                $error = $this->validateRule($field, $value, $rule);
                if ($error) {
                    $errors[$field] = $error;
                    break; // Stop at first error for this field
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Validate individual rule
     */
    private function validateRule($field, $value, $rule)
    {
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $parameter = $parts[1] ?? null;
        
        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    return ucfirst($field) . ' alanı zorunludur.';
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return 'Geçerli bir e-posta adresi giriniz.';
                }
                break;
                
            case 'min':
                if (!empty($value) && strlen($value) < $parameter) {
                    return ucfirst($field) . " en az $parameter karakter olmalıdır.";
                }
                break;
                
            case 'max':
                if (!empty($value) && strlen($value) > $parameter) {
                    return ucfirst($field) . " en fazla $parameter karakter olmalıdır.";
                }
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                $confirmValue = $this->input($confirmField);
                if ($value !== $confirmValue) {
                    return ucfirst($field) . ' onayı eşleşmiyor.';
                }
                break;
                
            case 'unique':
                // This would require database access
                // Implementation depends on specific needs
                break;
        }
        
        return null;
    }
    
    /**
     * Get request method
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Check if request is AJAX
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get client IP address
     */
    public function ip()
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
     * Get user agent
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
}