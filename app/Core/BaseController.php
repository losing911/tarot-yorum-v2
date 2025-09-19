<?php
/**
 * Base Controller Class
 * Common functionality for all controllers
 */

abstract class BaseController
{
    protected $db;
    protected $request;
    
    public function __construct(Database $database)
    {
        $this->db = $database;
        $this->request = new Request();
    }
    
    /**
     * Render view with data
     */
    protected function view($view, $data = [])
    {
        // Add global variables to data
        $data['app_name'] = APP_NAME;
        $data['app_url'] = APP_URL;
        $data['csrf_token'] = $_SESSION['csrf_token'];
        $data['current_user'] = $this->getCurrentUser();
        $data['is_admin'] = $this->isAdmin();
        
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View not found: $view");
        }
        
        // Get content and end buffering
        $content = ob_get_clean();
        
        // Include layout
        include VIEWS_PATH . '/layouts/main.php';
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url, $message = null, $type = 'info')
    {
        if ($message) {
            $_SESSION['flash'] = ['message' => $message, 'type' => $type];
        }
        
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCSRF($token)
    {
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->getCurrentUser()) {
            $this->redirect('/login', 'Lütfen giriş yapın.', 'warning');
        }
    }
    
    /**
     * Require admin access
     */
    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/', 'Yetkiniz yok.', 'error');
        }
    }
    
    /**
     * Get current authenticated user
     */
    protected function getCurrentUser()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->getUserById($_SESSION['user_id']);
        }
        return null;
    }
    
    /**
     * Check if current user is admin
     */
    protected function isAdmin()
    {
        $user = $this->getCurrentUser();
        return $user && $user['role'] === 'admin';
    }
    
    /**
     * Get user by ID
     */
    private function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id AND is_active = 1');
        $this->db->bind(':id', $id);
        return $this->db->fetch();
    }
    
    /**
     * Sanitize input
     */
    protected function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email
     */
    protected function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Generate SEO-friendly slug
     */
    protected function createSlug($text)
    {
        // Turkish character mapping
        $turkish = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $english = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
        
        $text = str_replace($turkish, $english, $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        
        return $text;
    }
    
    /**
     * Upload file with security checks
     */
    protected function uploadFile($file, $directory = 'uploads')
    {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Dosya yükleme hatası');
        }
        
        // Check file size
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            throw new Exception('Dosya boyutu çok büyük');
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            throw new Exception('Geçersiz dosya türü');
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = STORAGE_PATH . '/' . $directory . '/' . $filename;
        
        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $directory . '/' . $filename;
        }
        
        throw new Exception('Dosya yükleme başarısız');
    }
}