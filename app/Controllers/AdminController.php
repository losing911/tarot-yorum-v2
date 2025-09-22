<?php
/**
 * Admin Controller
 * Handle administrative functions and dashboard
 */

class AdminController extends BaseController
{
    private $adminModel;
    private $userModel;
    private $blogModel;
    private $tarotModel;
    
    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->adminModel = new Admin($database);
        $this->userModel = new User($database);
        $this->blogModel = new BlogPost($database);
        $this->tarotModel = new TarotReading($database);
        
        // Check admin access
        if (!$this->isAdmin()) {
            $this->redirect('/login', 'Bu sayfaya erişim için admin yetkisi gerekiyor.', 'error');
        }
    }
    
    /**
     * Admin dashboard
     */
    public function index($params = [])
    {
        try {
            // Get dashboard statistics
            $stats = $this->adminModel->getDashboardStats();
            
            // Get recent activities
            $recentUsers = $this->userModel->getRecentUsers(5);
            $recentPosts = $this->blogModel->getRecentPosts(5);
            $recentReadings = $this->tarotModel->getRecentReadings(5);
            $pendingComments = $this->adminModel->getPendingComments(5);
            
            // Get analytics data
            $analyticsData = $this->adminModel->getAnalyticsData();
            
            // Get system info
            $systemInfo = $this->adminModel->getSystemInfo();
            
            $data = [
                'page_title' => 'Admin Dashboard - Tarot Yorum',
                'meta_description' => 'Admin yönetim paneli',
                'stats' => $stats,
                'recent_users' => $recentUsers,
                'recent_posts' => $recentPosts,
                'recent_readings' => $recentReadings,
                'pending_comments' => $pendingComments,
                'analytics' => $analyticsData,
                'system_info' => $systemInfo,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('admin.dashboard', $data);
            
        } catch (Exception $e) {
            error_log('Admin Dashboard Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * User management
     */
    public function users($params = [])
    {
        try {
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $search = isset($params['search']) ? trim($params['search']) : null;
            $role = isset($params['role']) ? $params['role'] : null;
            $status = isset($params['status']) ? $params['status'] : null;
            
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            $users = $this->userModel->getUsers($limit, $offset, $search, $role, $status);
            $totalUsers = $this->userModel->getUserCount($search, $role, $status);
            $totalPages = ceil($totalUsers / $limit);
            
            // Get user statistics
            $userStats = $this->adminModel->getUserStats();
            
            $data = [
                'page_title' => 'Kullanıcı Yönetimi - Admin Panel',
                'meta_description' => 'Kullanıcı yönetim paneli',
                'users' => $users,
                'user_stats' => $userStats,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_users' => $totalUsers,
                'current_search' => $search,
                'current_role' => $role,
                'current_status' => $status,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('admin.users', $data);
            
        } catch (Exception $e) {
            error_log('Admin Users Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Content management (blog posts)
     */
    public function content($params = [])
    {
        try {
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $search = isset($params['search']) ? trim($params['search']) : null;
            $category = isset($params['category']) ? $params['category'] : null;
            $status = isset($params['status']) ? $params['status'] : null;
            
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            $posts = $this->blogModel->getPosts($limit, $offset, $category, $search, $status);
            $totalPosts = $this->blogModel->getPostCount($category, $search, $status);
            $totalPages = ceil($totalPosts / $limit);
            
            // Get content statistics
            $contentStats = $this->adminModel->getContentStats();
            
            $data = [
                'page_title' => 'İçerik Yönetimi - Admin Panel',
                'meta_description' => 'Blog yazıları yönetim paneli',
                'posts' => $posts,
                'content_stats' => $contentStats,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_posts' => $totalPosts,
                'current_search' => $search,
                'current_category' => $category,
                'current_status' => $status,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('admin.content', $data);
            
        } catch (Exception $e) {
            error_log('Admin Content Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Comment moderation
     */
    public function comments($params = [])
    {
        try {
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $status = isset($params['status']) ? $params['status'] : 'pending';
            
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            $comments = $this->adminModel->getComments($limit, $offset, $status);
            $totalComments = $this->adminModel->getCommentCount($status);
            $totalPages = ceil($totalComments / $limit);
            
            // Get comment statistics
            $commentStats = $this->adminModel->getCommentStats();
            
            $data = [
                'page_title' => 'Yorum Yönetimi - Admin Panel',
                'meta_description' => 'Yorum moderasyon paneli',
                'comments' => $comments,
                'comment_stats' => $commentStats,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_comments' => $totalComments,
                'current_status' => $status,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('admin.comments', $data);
            
        } catch (Exception $e) {
            error_log('Admin Comments Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Analytics and reports
     */
    public function analytics($params = [])
    {
        try {
            $period = isset($params['period']) ? $params['period'] : '30';
            
            // Get comprehensive analytics
            $analytics = $this->adminModel->getAnalytics($period);
            
            $data = [
                'page_title' => 'Analytics ve Raporlar - Admin Panel',
                'meta_description' => 'Site istatistikleri ve analytics',
                'analytics' => $analytics,
                'current_period' => $period,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('admin.analytics', $data);
            
        } catch (Exception $e) {
            error_log('Admin Analytics Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * System settings
     */
    public function settings($params = [])
    {
        try {
            $settings = $this->adminModel->getSettings();
            
            $data = [
                'page_title' => 'Sistem Ayarları - Admin Panel',
                'meta_description' => 'Site ayarları ve konfigürasyon',
                'settings' => $settings,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('admin.settings', $data);
            
        } catch (Exception $e) {
            error_log('Admin Settings Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Update user status/role
     */
    public function updateUser($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        $userId = $params['id'] ?? null;
        
        if (!$userId || !$data) {
            $this->jsonResponse(['error' => 'Geçersiz veri'], 400);
        }
        
        try {
            $result = $this->userModel->updateUserAdmin($userId, $data);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Kullanıcı başarıyla güncellendi'
                ]);
            } else {
                $this->jsonResponse(['error' => 'Kullanıcı güncellenemedi'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Admin Update User Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Bir hata oluştu'], 500);
        }
    }
    
    /**
     * Update comment status
     */
    public function updateComment($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        $commentId = $params['id'] ?? null;
        
        if (!$commentId || !isset($data['status'])) {
            $this->jsonResponse(['error' => 'Geçersiz veri'], 400);
        }
        
        try {
            $result = $this->blogModel->updateCommentStatus($commentId, $data['status']);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Yorum durumu güncellendi'
                ]);
            } else {
                $this->jsonResponse(['error' => 'Yorum güncellenemedi'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Admin Update Comment Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Bir hata oluştu'], 500);
        }
    }
    
    /**
     * Update settings
     */
    public function updateSettings($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        
        if (!$data) {
            $this->jsonResponse(['error' => 'Geçersiz veri'], 400);
        }
        
        try {
            $result = $this->adminModel->updateSettings($data);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Ayarlar başarıyla güncellendi'
                ]);
            } else {
                $this->jsonResponse(['error' => 'Ayarlar güncellenemedi'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Admin Update Settings Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Bir hata oluştu'], 500);
        }
    }
    
    /**
     * Delete user
     */
    public function deleteUser($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $userId = $params['id'] ?? null;
        
        if (!$userId) {
            $this->jsonResponse(['error' => 'Geçersiz kullanıcı ID'], 400);
        }
        
        // Prevent self-deletion
        if ($userId == $_SESSION['user_id']) {
            $this->jsonResponse(['error' => 'Kendi hesabınızı silemezsiniz'], 400);
        }
        
        try {
            $result = $this->userModel->deleteUser($userId);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Kullanıcı başarıyla silindi'
                ]);
            } else {
                $this->jsonResponse(['error' => 'Kullanıcı silinemedi'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Admin Delete User Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Bir hata oluştu'], 500);
        }
    }
    
    /**
     * Delete comment
     */
    public function deleteComment($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $commentId = $params['id'] ?? null;
        
        if (!$commentId) {
            $this->jsonResponse(['error' => 'Geçersiz yorum ID'], 400);
        }
        
        try {
            $result = $this->adminModel->deleteComment($commentId);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Yorum başarıyla silindi'
                ]);
            } else {
                $this->jsonResponse(['error' => 'Yorum silinemedi'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Admin Delete Comment Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Bir hata oluştu'], 500);
        }
    }
    
    /**
     * Export data
     */
    public function export($params = [])
    {
        $type = $params['type'] ?? 'users';
        $format = $params['format'] ?? 'csv';
        
        try {
            switch ($type) {
                case 'users':
                    $data = $this->userModel->getAllUsers();
                    $filename = 'users_export_' . date('Y-m-d');
                    break;
                    
                case 'posts':
                    $data = $this->blogModel->getAllPosts();
                    $filename = 'posts_export_' . date('Y-m-d');
                    break;
                    
                case 'comments':
                    $data = $this->adminModel->getAllComments();
                    $filename = 'comments_export_' . date('Y-m-d');
                    break;
                    
                case 'readings':
                    $data = $this->tarotModel->getAllReadings();
                    $filename = 'readings_export_' . date('Y-m-d');
                    break;
                    
                default:
                    $this->jsonResponse(['error' => 'Geçersiz export türü'], 400);
                    return;
            }
            
            if ($format === 'csv') {
                $this->exportCSV($data, $filename);
            } else {
                $this->exportJSON($data, $filename);
            }
            
        } catch (Exception $e) {
            error_log('Admin Export Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Export başarısız'], 500);
        }
    }
    
    /**
     * System backup
     */
    public function backup($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        try {
            $backupFile = $this->adminModel->createBackup();
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Yedekleme başarıyla oluşturuldu',
                'backup_file' => $backupFile
            ]);
            
        } catch (Exception $e) {
            error_log('Admin Backup Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Yedekleme başarısız'], 500);
        }
    }
    
    /**
     * Export data as CSV
     */
    private function exportCSV($data, $filename)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // Write header
            fputcsv($output, array_keys($data[0]));
            
            // Write data
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export data as JSON
     */
    private function exportJSON($data, $filename)
    {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '.json"');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Check if current user is admin
     */
    protected function isAdmin()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}