<?php
/**
 * Blog Post Model
 * Handle database operations for blog posts, categories and comments
 */

class BlogPost
{
    private $db;
    
    public function __construct(Database $database)
    {
        $this->db = $database;
    }
    
    /**
     * Get posts with pagination and filters
     */
    public function getPosts($limit = 10, $offset = 0, $category = null, $search = null, $status = 'published')
    {
        $sql = "SELECT p.*, u.name as author_name,
                       (SELECT COUNT(*) FROM blog_comments c WHERE c.post_id = p.id AND c.status = 'approved') as comment_count
                FROM blog_posts p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.status = :status";
        
        $params = ['status' => $status];
        
        if ($category) {
            $sql .= " AND p.category = :category";
            $params['category'] = $category;
        }
        
        if ($search) {
            $sql .= " AND (p.title LIKE :search OR p.content LIKE :search OR p.excerpt LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get total post count with filters
     */
    public function getPostCount($category = null, $search = null, $status = 'published')
    {
        $sql = "SELECT COUNT(*) FROM blog_posts WHERE status = :status";
        $params = ['status' => $status];
        
        if ($category) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }
        
        if ($search) {
            $sql .= " AND (title LIKE :search OR content LIKE :search OR excerpt LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Get post by slug
     */
    public function getPostBySlug($slug)
    {
        $sql = "SELECT p.*, u.name as author_name, u.email as author_email
                FROM blog_posts p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.slug = :slug";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get post by ID
     */
    public function getPostById($id)
    {
        $sql = "SELECT p.*, u.name as author_name
                FROM blog_posts p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get featured posts
     */
    public function getFeaturedPosts($limit = 3)
    {
        $sql = "SELECT p.*, u.name as author_name,
                       (SELECT COUNT(*) FROM blog_comments c WHERE c.post_id = p.id AND c.status = 'approved') as comment_count
                FROM blog_posts p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.status = 'published' AND p.is_featured = 1
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get recent posts
     */
    public function getRecentPosts($limit = 5)
    {
        $sql = "SELECT id, title, slug, created_at, featured_image
                FROM blog_posts 
                WHERE status = 'published'
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get related posts
     */
    public function getRelatedPosts($postId, $category, $limit = 4)
    {
        $sql = "SELECT id, title, slug, excerpt, featured_image, created_at
                FROM blog_posts 
                WHERE status = 'published' AND id != :post_id AND category = :category
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get categories with post counts
     */
    public function getCategories()
    {
        $sql = "SELECT category, COUNT(*) as post_count 
                FROM blog_posts 
                WHERE status = 'published'
                GROUP BY category 
                ORDER BY category";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new blog post
     */
    public function createPost($data)
    {
        $sql = "INSERT INTO blog_posts (title, slug, content, excerpt, category, meta_title, 
                meta_description, meta_keywords, featured_image, status, is_featured, author_id, created_at) 
                VALUES (:title, :slug, :content, :excerpt, :category, :meta_title, 
                :meta_description, :meta_keywords, :featured_image, :status, :is_featured, :author_id, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update blog post
     */
    public function updatePost($id, $data)
    {
        $sql = "UPDATE blog_posts SET 
                title = :title, content = :content, excerpt = :excerpt, category = :category,
                meta_title = :meta_title, meta_description = :meta_description, meta_keywords = :meta_keywords,
                featured_image = :featured_image, status = :status, is_featured = :is_featured, updated_at = NOW()
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    /**
     * Delete blog post
     */
    public function deletePost($id)
    {
        // First delete comments
        $this->db->prepare("DELETE FROM blog_comments WHERE post_id = ?")->execute([$id]);
        
        // Then delete post
        $stmt = $this->db->prepare("DELETE FROM blog_posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Increment post view count
     */
    public function incrementViewCount($id)
    {
        $sql = "UPDATE blog_posts SET view_count = view_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Get post comments
     */
    public function getPostComments($postId, $status = 'approved')
    {
        $sql = "SELECT * FROM blog_comments 
                WHERE post_id = :post_id AND status = :status 
                ORDER BY created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $postId, 'status' => $status]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create comment
     */
    public function createComment($data)
    {
        $sql = "INSERT INTO blog_comments (post_id, author_name, author_email, comment, status, ip_address, user_agent, created_at) 
                VALUES (:post_id, :author_name, :author_email, :comment, :status, :ip_address, :user_agent, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Get user's recent comment (for rate limiting)
     */
    public function getUserRecentComment($email, $minutes = 5)
    {
        $sql = "SELECT id FROM blog_comments 
                WHERE author_email = :email 
                AND created_at > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'minutes' => $minutes]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update comment status
     */
    public function updateCommentStatus($id, $status)
    {
        $sql = "UPDATE blog_comments SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }
    
    /**
     * Search posts
     */
    public function searchPosts($query, $limit = 20)
    {
        $sql = "SELECT id, title, slug, excerpt, category, created_at
                FROM blog_posts 
                WHERE status = 'published' 
                AND (title LIKE :query OR content LIKE :query OR excerpt LIKE :query)
                ORDER BY 
                    CASE 
                        WHEN title LIKE :exact_query THEN 1
                        WHEN title LIKE :query THEN 2
                        WHEN excerpt LIKE :query THEN 3
                        ELSE 4
                    END,
                    created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->bindValue(':exact_query', $query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get popular posts
     */
    public function getPopularPosts($limit = 5, $days = 30)
    {
        $sql = "SELECT id, title, slug, view_count, created_at
                FROM blog_posts 
                WHERE status = 'published' 
                AND created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                ORDER BY view_count DESC, created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get blog statistics
     */
    public function getBlogStats()
    {
        $stats = [];
        
        // Total posts
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM blog_posts WHERE status = 'published'");
        $stmt->execute();
        $stats['total_posts'] = $stmt->fetchColumn();
        
        // Total views
        $stmt = $this->db->prepare("SELECT SUM(view_count) FROM blog_posts WHERE status = 'published'");
        $stmt->execute();
        $stats['total_views'] = $stmt->fetchColumn() ?: 0;
        
        // Total comments
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM blog_comments WHERE status = 'approved'");
        $stmt->execute();
        $stats['total_comments'] = $stmt->fetchColumn();
        
        // Categories count
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT category) FROM blog_posts WHERE status = 'published'");
        $stmt->execute();
        $stats['categories_count'] = $stmt->fetchColumn();
        
        return $stats;
    }
}