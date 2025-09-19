<?php
/**
 * Blog Controller
 * Handle blog posts, categories, comments and AI content assistance
 */

class BlogController extends BaseController
{
    private $blogModel;
    private $aiService;
    
    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->blogModel = new BlogPost($database);
        $this->aiService = new AIService();
    }
    
    /**
     * Display blog index with posts and categories
     */
    public function index($params = [])
    {
        try {
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $category = isset($params['category']) ? $params['category'] : null;
            $search = isset($params['search']) ? trim($params['search']) : null;
            
            $limit = 12;
            $offset = ($page - 1) * $limit;
            
            // Get posts with filters
            $posts = $this->blogModel->getPosts($limit, $offset, $category, $search);
            $totalPosts = $this->blogModel->getPostCount($category, $search);
            $totalPages = ceil($totalPosts / $limit);
            
            // Get categories
            $categories = $this->blogModel->getCategories();
            
            // Get featured posts
            $featuredPosts = $this->blogModel->getFeaturedPosts(3);
            
            // Get recent posts
            $recentPosts = $this->blogModel->getRecentPosts(5);
            
            $data = [
                'page_title' => $search ? "'{$search}' Arama Sonuçları" : ($category ? ucfirst($category) . ' Yazıları' : 'Astroloji ve Tarot Blog'),
                'meta_description' => 'Astroloji, tarot ve ruhsal gelişim konularında güncel yazılar. Uzman görüşleri ve rehberlik içerikleri.',
                'meta_keywords' => 'astroloji blog, tarot yazıları, burç yorumları, ruhsal gelişim',
                'posts' => $posts,
                'categories' => $categories,
                'featured_posts' => $featuredPosts,
                'recent_posts' => $recentPosts,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_posts' => $totalPosts,
                'current_category' => $category,
                'current_search' => $search
            ];
            
            $this->view('blog.index', $data);
            
        } catch (Exception $e) {
            error_log('Blog Index Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display single blog post
     */
    public function show($params = [])
    {
        $slug = $params['slug'] ?? '';
        
        if (empty($slug)) {
            $this->view('errors.404');
            return;
        }
        
        try {
            $post = $this->blogModel->getPostBySlug($slug);
            
            if (!$post) {
                $this->view('errors.404');
                return;
            }
            
            // Check if post is published
            if ($post['status'] !== 'published' && !$this->isAdmin()) {
                $this->view('errors.404');
                return;
            }
            
            // Increment view count
            $this->blogModel->incrementViewCount($post['id']);
            
            // Get comments
            $comments = $this->blogModel->getPostComments($post['id']);
            
            // Get related posts
            $relatedPosts = $this->blogModel->getRelatedPosts($post['id'], $post['category'], 4);
            
            // Get categories for sidebar
            $categories = $this->blogModel->getCategories();
            
            // Get recent posts for sidebar
            $recentPosts = $this->blogModel->getRecentPosts(5);
            
            $data = [
                'page_title' => htmlspecialchars($post['title']),
                'meta_description' => htmlspecialchars($post['excerpt']),
                'meta_keywords' => htmlspecialchars($post['meta_keywords']),
                'post' => $post,
                'comments' => $comments,
                'related_posts' => $relatedPosts,
                'categories' => $categories,
                'recent_posts' => $recentPosts,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('blog.show', $data);
            
        } catch (Exception $e) {
            error_log('Blog Show Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display blog creation form (admin only)
     */
    public function create($params = [])
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login', 'Bu sayfaya erişmek için admin yetkisi gerekiyor.', 'error');
        }
        
        try {
            $categories = $this->blogModel->getCategories();
            
            $data = [
                'page_title' => 'Yeni Blog Yazısı Oluştur',
                'meta_description' => 'Yeni blog yazısı oluşturun',
                'categories' => $categories,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('blog.create', $data);
            
        } catch (Exception $e) {
            error_log('Blog Create Form Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Store new blog post
     */
    public function store($params = [])
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Yetki gerekli'], 403);
        }
        
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        
        if (!$data) {
            $this->jsonResponse(['error' => 'Geçersiz veri'], 400);
        }
        
        // Validate required fields
        $required = ['title', 'content', 'category', 'excerpt'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->jsonResponse(['error' => ucfirst($field) . ' alanı gerekli'], 400);
            }
        }
        
        try {
            // Generate slug
            $slug = $this->generateSlug($data['title']);
            
            // Check slug uniqueness
            if ($this->blogModel->getPostBySlug($slug)) {
                $slug .= '-' . time();
            }
            
            $postData = [
                'title' => trim($data['title']),
                'slug' => $slug,
                'content' => trim($data['content']),
                'excerpt' => trim($data['excerpt']),
                'category' => trim($data['category']),
                'meta_title' => trim($data['meta_title'] ?? $data['title']),
                'meta_description' => trim($data['meta_description'] ?? $data['excerpt']),
                'meta_keywords' => trim($data['meta_keywords'] ?? ''),
                'featured_image' => trim($data['featured_image'] ?? ''),
                'status' => $data['status'] ?? 'draft',
                'is_featured' => isset($data['is_featured']) ? 1 : 0,
                'author_id' => $_SESSION['user_id']
            ];
            
            $postId = $this->blogModel->createPost($postData);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Blog yazısı başarıyla oluşturuldu',
                'post_id' => $postId,
                'slug' => $slug
            ]);
            
        } catch (Exception $e) {
            error_log('Blog Store Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Blog yazısı oluşturulamadı'], 500);
        }
    }
    
    /**
     * Display blog edit form (admin only)
     */
    public function edit($params = [])
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login', 'Bu sayfaya erişmek için admin yetkisi gerekiyor.', 'error');
        }
        
        $slug = $params['slug'] ?? '';
        
        if (empty($slug)) {
            $this->view('errors.404');
            return;
        }
        
        try {
            $post = $this->blogModel->getPostBySlug($slug);
            
            if (!$post) {
                $this->view('errors.404');
                return;
            }
            
            $categories = $this->blogModel->getCategories();
            
            $data = [
                'page_title' => 'Blog Yazısını Düzenle: ' . $post['title'],
                'meta_description' => 'Blog yazısını düzenleyin',
                'post' => $post,
                'categories' => $categories,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('blog.edit', $data);
            
        } catch (Exception $e) {
            error_log('Blog Edit Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Update blog post
     */
    public function update($params = [])
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Yetki gerekli'], 403);
        }
        
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $slug = $params['slug'] ?? '';
        $data = $this->request->getJSON();
        
        if (empty($slug) || !$data) {
            $this->jsonResponse(['error' => 'Geçersiz veri'], 400);
        }
        
        try {
            $post = $this->blogModel->getPostBySlug($slug);
            
            if (!$post) {
                $this->jsonResponse(['error' => 'Yazı bulunamadı'], 404);
            }
            
            $updateData = [
                'title' => trim($data['title']),
                'content' => trim($data['content']),
                'excerpt' => trim($data['excerpt']),
                'category' => trim($data['category']),
                'meta_title' => trim($data['meta_title'] ?? $data['title']),
                'meta_description' => trim($data['meta_description'] ?? $data['excerpt']),
                'meta_keywords' => trim($data['meta_keywords'] ?? ''),
                'featured_image' => trim($data['featured_image'] ?? ''),
                'status' => $data['status'] ?? 'draft',
                'is_featured' => isset($data['is_featured']) ? 1 : 0
            ];
            
            $this->blogModel->updatePost($post['id'], $updateData);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Blog yazısı başarıyla güncellendi'
            ]);
            
        } catch (Exception $e) {
            error_log('Blog Update Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Blog yazısı güncellenemedi'], 500);
        }
    }
    
    /**
     * Add comment to blog post
     */
    public function addComment($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        
        if (!$data || empty($data['post_id']) || empty($data['comment']) || empty($data['author_name']) || empty($data['author_email'])) {
            $this->jsonResponse(['error' => 'Tüm alanlar zorunludur'], 400);
        }
        
        try {
            // Validate email
            if (!filter_var($data['author_email'], FILTER_VALIDATE_EMAIL)) {
                $this->jsonResponse(['error' => 'Geçerli bir email adresi girin'], 400);
            }
            
            // Check if post exists
            $post = $this->blogModel->getPostById($data['post_id']);
            if (!$post) {
                $this->jsonResponse(['error' => 'Yazı bulunamadı'], 404);
            }
            
            // Rate limiting - check if user already commented recently
            $recentComment = $this->blogModel->getUserRecentComment($data['author_email'], 5); // 5 minutes
            if ($recentComment) {
                $this->jsonResponse(['error' => 'Çok sık yorum gönderiyorsunuz. Lütfen bekleyin.'], 429);
            }
            
            $commentData = [
                'post_id' => $data['post_id'],
                'author_name' => trim($data['author_name']),
                'author_email' => trim($data['author_email']),
                'comment' => trim($data['comment']),
                'status' => 'pending', // Comments need approval
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ];
            
            $commentId = $this->blogModel->createComment($commentData);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Yorumunuz gönderildi. Onaylandıktan sonra görünecektir.',
                'comment_id' => $commentId
            ]);
            
        } catch (Exception $e) {
            error_log('Add Comment Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Yorum gönderilemedi'], 500);
        }
    }
    
    /**
     * Get AI content suggestions
     */
    public function aiSuggestion($params = [])
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Yetki gerekli'], 403);
        }
        
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        
        if (!$data || empty($data['topic']) || empty($data['type'])) {
            $this->jsonResponse(['error' => 'Konu ve tip gerekli'], 400);
        }
        
        try {
            $suggestion = $this->aiService->generateBlogSuggestion($data['topic'], $data['type']);
            
            $this->jsonResponse([
                'success' => true,
                'suggestion' => $suggestion['suggestion'],
                'provider' => $suggestion['provider']
            ]);
            
        } catch (Exception $e) {
            error_log('AI Suggestion Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Search blog posts
     */
    public function search($params = [])
    {
        $query = isset($params['q']) ? trim($params['q']) : '';
        
        if (strlen($query) < 2) {
            $this->jsonResponse(['error' => 'Arama terimi en az 2 karakter olmalı'], 400);
        }
        
        try {
            $results = $this->blogModel->searchPosts($query, 20);
            
            $this->jsonResponse([
                'success' => true,
                'results' => $results,
                'count' => count($results)
            ]);
            
        } catch (Exception $e) {
            error_log('Blog Search Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Arama yapılamadı'], 500);
        }
    }
    
    /**
     * Get category posts
     */
    public function category($params = [])
    {
        $category = $params['category'] ?? '';
        
        if (empty($category)) {
            $this->view('errors.404');
            return;
        }
        
        // Redirect to index with category filter
        $this->redirect("/blog?category=" . urlencode($category));
    }
    
    /**
     * Generate SEO-friendly slug
     */
    private function generateSlug($title)
    {
        // Turkish character conversion
        $turkishChars = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü'];
        $englishChars = ['c', 'g', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'i', 'o', 's', 'u'];
        
        $title = str_replace($turkishChars, $englishChars, $title);
        $title = strtolower($title);
        $title = preg_replace('/[^a-z0-9\s-]/', '', $title);
        $title = preg_replace('/[\s-]+/', '-', $title);
        $title = trim($title, '-');
        
        return $title;
    }
    
    /**
     * Check if current user is admin
     */
    private function isAdmin()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}