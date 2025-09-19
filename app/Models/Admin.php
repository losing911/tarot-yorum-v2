<?php
/**
 * Admin Model
 * Handle admin-related database operations
 */

class Admin
{
    private Database $db;
    
    public function __construct(Database $database)
    {
        $this->db = $database;
    }
    
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        try {
            $stats = [];
            
            // Total users
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE deleted_at IS NULL");
            $stmt->execute();
            $stats['total_users'] = $stmt->fetch()['total'];
            
            // New users this month
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND deleted_at IS NULL");
            $stmt->execute();
            $stats['new_users_month'] = $stmt->fetch()['total'];
            
            // Total blog posts
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM blog_posts WHERE deleted_at IS NULL");
            $stmt->execute();
            $stats['total_posts'] = $stmt->fetch()['total'];
            
            // Published posts
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published' AND deleted_at IS NULL");
            $stmt->execute();
            $stats['published_posts'] = $stmt->fetch()['total'];
            
            // Total comments
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM blog_comments WHERE deleted_at IS NULL");
            $stmt->execute();
            $stats['total_comments'] = $stmt->fetch()['total'];
            
            // Pending comments
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM blog_comments WHERE status = 'pending' AND deleted_at IS NULL");
            $stmt->execute();
            $stats['pending_comments'] = $stmt->fetch()['total'];
            
            // Total tarot readings
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM tarot_readings WHERE deleted_at IS NULL");
            $stmt->execute();
            $stats['total_readings'] = $stmt->fetch()['total'];
            
            // Readings this month
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM tarot_readings WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND deleted_at IS NULL");
            $stmt->execute();
            $stats['readings_month'] = $stmt->fetch()['total'];
            
            // Active users (logged in last 30 days)
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND deleted_at IS NULL");
            $stmt->execute();
            $stats['active_users'] = $stmt->fetch()['total'];
            
            // Popular categories
            $stmt = $this->db->prepare("
                SELECT category, COUNT(*) as count 
                FROM blog_posts 
                WHERE status = 'published' AND deleted_at IS NULL 
                GROUP BY category 
                ORDER BY count DESC 
                LIMIT 5
            ");
            $stmt->execute();
            $stats['popular_categories'] = $stmt->fetchAll();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Admin getDashboardStats Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get analytics data for charts
     */
    public function getAnalyticsData()
    {
        try {
            $analytics = [];
            
            // User registrations over time (last 30 days)
            $stmt = $this->db->prepare("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM users 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                AND deleted_at IS NULL
                GROUP BY DATE(created_at)
                ORDER BY date ASC
            ");
            $stmt->execute();
            $analytics['user_registrations'] = $stmt->fetchAll();
            
            // Blog posts over time (last 30 days)
            $stmt = $this->db->prepare("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM blog_posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                AND deleted_at IS NULL
                GROUP BY DATE(created_at)
                ORDER BY date ASC
            ");
            $stmt->execute();
            $analytics['blog_posts'] = $stmt->fetchAll();
            
            // Tarot readings over time (last 30 days)
            $stmt = $this->db->prepare("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM tarot_readings 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                AND deleted_at IS NULL
                GROUP BY DATE(created_at)
                ORDER BY date ASC
            ");
            $stmt->execute();
            $analytics['tarot_readings'] = $stmt->fetchAll();
            
            // Most viewed blog posts
            $stmt = $this->db->prepare("
                SELECT title, view_count
                FROM blog_posts 
                WHERE status = 'published' AND deleted_at IS NULL
                ORDER BY view_count DESC
                LIMIT 10
            ");
            $stmt->execute();
            $analytics['popular_posts'] = $stmt->fetchAll();
            
            return $analytics;
            
        } catch (Exception $e) {
            error_log('Admin getAnalyticsData Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get system information
     */
    public function getSystemInfo()
    {
        try {
            return [
                'php_version' => PHP_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'database_version' => $this->getDatabaseVersion(),
                'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'disk_free_space' => $this->formatBytes(disk_free_space('.')),
                'disk_total_space' => $this->formatBytes(disk_total_space('.')),
                'timezone' => date_default_timezone_get(),
                'current_time' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            error_log('Admin getSystemInfo Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get pending comments for moderation
     */
    public function getPendingComments($limit = 10)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT bc.*, bp.title as post_title, u.username 
                FROM blog_comments bc
                JOIN blog_posts bp ON bc.post_id = bp.id
                LEFT JOIN users u ON bc.user_id = u.id
                WHERE bc.status = 'pending' AND bc.deleted_at IS NULL
                ORDER BY bc.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('Admin getPendingComments Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get comments with filtering and pagination
     */
    public function getComments($limit = 20, $offset = 0, $status = null)
    {
        try {
            $whereClause = "WHERE bc.deleted_at IS NULL";
            $params = [];
            
            if ($status) {
                $whereClause .= " AND bc.status = :status";
                $params['status'] = $status;
            }
            
            $stmt = $this->db->prepare("
                SELECT bc.*, bp.title as post_title, u.username 
                FROM blog_comments bc
                JOIN blog_posts bp ON bc.post_id = bp.id
                LEFT JOIN users u ON bc.user_id = u.id
                {$whereClause}
                ORDER BY bc.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('Admin getComments Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get comment count for pagination
     */
    public function getCommentCount($status = null)
    {
        try {
            $whereClause = "WHERE deleted_at IS NULL";
            $params = [];
            
            if ($status) {
                $whereClause .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM blog_comments {$whereClause}");
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->execute();
            
            return $stmt->fetch()['total'];
            
        } catch (Exception $e) {
            error_log('Admin getCommentCount Error: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        try {
            $stats = [];
            
            // Total users by role
            $stmt = $this->db->prepare("
                SELECT role, COUNT(*) as count 
                FROM users 
                WHERE deleted_at IS NULL 
                GROUP BY role
            ");
            $stmt->execute();
            $stats['by_role'] = $stmt->fetchAll();
            
            // Users by status
            $stmt = $this->db->prepare("
                SELECT status, COUNT(*) as count 
                FROM users 
                WHERE deleted_at IS NULL 
                GROUP BY status
            ");
            $stmt->execute();
            $stats['by_status'] = $stmt->fetchAll();
            
            // Registration trend (last 12 months)
            $stmt = $this->db->prepare("
                SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count
                FROM users 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                AND deleted_at IS NULL
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC
            ");
            $stmt->execute();
            $stats['registration_trend'] = $stmt->fetchAll();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Admin getUserStats Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get content statistics
     */
    public function getContentStats()
    {
        try {
            $stats = [];
            
            // Posts by status
            $stmt = $this->db->prepare("
                SELECT status, COUNT(*) as count 
                FROM blog_posts 
                WHERE deleted_at IS NULL 
                GROUP BY status
            ");
            $stmt->execute();
            $stats['by_status'] = $stmt->fetchAll();
            
            // Posts by category
            $stmt = $this->db->prepare("
                SELECT category, COUNT(*) as count 
                FROM blog_posts 
                WHERE deleted_at IS NULL 
                GROUP BY category
                ORDER BY count DESC
            ");
            $stmt->execute();
            $stats['by_category'] = $stmt->fetchAll();
            
            // Publishing trend (last 12 months)
            $stmt = $this->db->prepare("
                SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count
                FROM blog_posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                AND deleted_at IS NULL
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC
            ");
            $stmt->execute();
            $stats['publishing_trend'] = $stmt->fetchAll();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Admin getContentStats Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get comment statistics
     */
    public function getCommentStats()
    {
        try {
            $stats = [];
            
            // Comments by status
            $stmt = $this->db->prepare("
                SELECT status, COUNT(*) as count 
                FROM blog_comments 
                WHERE deleted_at IS NULL 
                GROUP BY status
            ");
            $stmt->execute();
            $stats['by_status'] = $stmt->fetchAll();
            
            // Comments trend (last 12 months)
            $stmt = $this->db->prepare("
                SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count
                FROM blog_comments 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                AND deleted_at IS NULL
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC
            ");
            $stmt->execute();
            $stats['comment_trend'] = $stmt->fetchAll();
            
            return $stats;
            
        } catch (Exception $e) {
            error_log('Admin getCommentStats Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get comprehensive analytics
     */
    public function getAnalytics($period = '30')
    {
        try {
            $analytics = [];
            $days = (int)$period;
            
            // Traffic analytics
            $analytics['traffic'] = $this->getTrafficAnalytics($days);
            
            // User engagement
            $analytics['engagement'] = $this->getEngagementAnalytics($days);
            
            // Content performance
            $analytics['content'] = $this->getContentAnalytics($days);
            
            // Popular pages
            $analytics['popular_pages'] = $this->getPopularPages($days);
            
            return $analytics;
            
        } catch (Exception $e) {
            error_log('Admin getAnalytics Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get site settings
     */
    public function getSettings()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM site_settings");
            $stmt->execute();
            $settings = $stmt->fetchAll();
            
            // Convert to key-value array
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting['setting_key']] = $setting['setting_value'];
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log('Admin getSettings Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update site settings
     */
    public function updateSettings($settings)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $value) {
                $stmt = $this->db->prepare("
                    INSERT INTO site_settings (setting_key, setting_value, updated_at) 
                    VALUES (:key, :value, NOW())
                    ON DUPLICATE KEY UPDATE 
                    setting_value = :value, updated_at = NOW()
                ");
                $stmt->execute([
                    'key' => $key,
                    'value' => $value
                ]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Admin updateSettings Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete comment
     */
    public function deleteComment($commentId)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE blog_comments 
                SET deleted_at = NOW() 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $commentId]);
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log('Admin deleteComment Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all comments for export
     */
    public function getAllComments()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    bc.*,
                    bp.title as post_title,
                    u.username
                FROM blog_comments bc
                JOIN blog_posts bp ON bc.post_id = bp.id
                LEFT JOIN users u ON bc.user_id = u.id
                WHERE bc.deleted_at IS NULL
                ORDER BY bc.created_at DESC
            ");
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('Admin getAllComments Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create system backup
     */
    public function createBackup()
    {
        try {
            $backupDir = __DIR__ . '/../../storage/backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . '/' . $filename;
            
            // Get database connection info
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $database = $_ENV['DB_NAME'] ?? 'tarot_db';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';
            
            // Create mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                return $filename;
            } else {
                throw new Exception('Backup command failed');
            }
            
        } catch (Exception $e) {
            error_log('Admin createBackup Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get database version
     */
    private function getDatabaseVersion()
    {
        try {
            $stmt = $this->db->prepare("SELECT VERSION() as version");
            $stmt->execute();
            return $stmt->fetch()['version'];
        } catch (Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Get traffic analytics
     */
    private function getTrafficAnalytics($days)
    {
        // This would integrate with analytics service
        // For now, return mock data
        return [
            'page_views' => rand(1000, 5000),
            'unique_visitors' => rand(500, 2000),
            'bounce_rate' => rand(30, 70),
            'avg_session_duration' => rand(120, 300)
        ];
    }
    
    /**
     * Get engagement analytics
     */
    private function getEngagementAnalytics($days)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(DISTINCT tr.user_id) as active_users,
                    AVG(bp.view_count) as avg_post_views,
                    COUNT(bc.id) as total_comments
                FROM tarot_readings tr
                LEFT JOIN blog_posts bp ON DATE(bp.created_at) >= DATE_SUB(NOW(), INTERVAL :days DAY)
                LEFT JOIN blog_comments bc ON DATE(bc.created_at) >= DATE_SUB(NOW(), INTERVAL :days DAY)
                WHERE DATE(tr.created_at) >= DATE_SUB(NOW(), INTERVAL :days DAY)
            ");
            $stmt->execute(['days' => $days]);
            
            return $stmt->fetch() ?: [];
            
        } catch (Exception $e) {
            error_log('Admin getEngagementAnalytics Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get content analytics
     */
    private function getContentAnalytics($days)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_posts,
                    SUM(view_count) as total_views,
                    AVG(view_count) as avg_views_per_post
                FROM blog_posts 
                WHERE DATE(created_at) >= DATE_SUB(NOW(), INTERVAL :days DAY)
                AND deleted_at IS NULL
            ");
            $stmt->execute(['days' => $days]);
            
            return $stmt->fetch() ?: [];
            
        } catch (Exception $e) {
            error_log('Admin getContentAnalytics Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get popular pages
     */
    private function getPopularPages($days)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT title, slug, view_count
                FROM blog_posts 
                WHERE DATE(created_at) >= DATE_SUB(NOW(), INTERVAL :days DAY)
                AND status = 'published' 
                AND deleted_at IS NULL
                ORDER BY view_count DESC
                LIMIT 10
            ");
            $stmt->execute(['days' => $days]);
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('Admin getPopularPages Error: ' . $e->getMessage());
            return [];
        }
    }
}