<?php $this->layout('layouts.main', ['page_title' => $page_title, 'meta_description' => $meta_description, 'meta_keywords' => $meta_keywords]) ?>

<?php $this->section('content') ?>

<!-- Hero Section -->
<section class="blog-hero py-5 bg-gradient text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-feather-alt me-3"></i>
                    Astroloji ve Tarot Blog
                </h1>
                <p class="lead mb-4">
                    Astroloji dünyasının derinliklerinde keşfedilecek bilgiler, 
                    tarot kartlarının gizli mesajları ve ruhsal gelişim yolculuğunuzda size rehberlik edecek içerikler.
                </p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="search-box">
                    <form method="GET" action="/blog" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" 
                               placeholder="Blog yazılarında ara..." 
                               value="<?= htmlspecialchars($current_search ?? '') ?>">
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            
            <!-- Featured Posts -->
            <?php if (!empty($featured_posts) && !$current_search): ?>
            <section class="featured-posts mb-5">
                <h2 class="section-title mb-4">
                    <i class="fas fa-star text-warning me-2"></i>
                    Öne Çıkan Yazılar
                </h2>
                <div class="row">
                    <?php foreach ($featured_posts as $post): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card blog-card featured-card h-100">
                            <?php if ($post['featured_image']): ?>
                            <div class="card-img-wrapper">
                                <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($post['title']) ?>">
                                <div class="featured-badge">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <div class="mb-auto">
                                    <span class="badge bg-primary category-badge mb-2">
                                        <?= ucfirst(htmlspecialchars($post['category'])) ?>
                                    </span>
                                    <h5 class="card-title">
                                        <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" 
                                           class="text-decoration-none">
                                            <?= htmlspecialchars($post['title']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars($post['excerpt']) ?>
                                    </p>
                                </div>
                                <div class="post-meta mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        <?= htmlspecialchars($post['author_name']) ?>
                                        <i class="fas fa-calendar ms-2 me-1"></i>
                                        <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                        <i class="fas fa-comments ms-2 me-1"></i>
                                        <?= $post['comment_count'] ?> yorum
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Filter Results -->
            <?php if ($current_search || $current_category): ?>
            <div class="filter-results mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <?php if ($current_search): ?>
                        <h3>
                            "<?= htmlspecialchars($current_search) ?>" için arama sonuçları
                            <span class="text-muted">(<?= $total_posts ?> yazı bulundu)</span>
                        </h3>
                        <?php elseif ($current_category): ?>
                        <h3>
                            <?= ucfirst(htmlspecialchars($current_category)) ?> Kategorisi
                            <span class="text-muted">(<?= $total_posts ?> yazı)</span>
                        </h3>
                        <?php endif; ?>
                    </div>
                    <a href="/blog" class="btn btn-outline-primary">
                        <i class="fas fa-times me-1"></i>
                        Filtreyi Temizle
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Blog Posts -->
            <section class="blog-posts">
                <?php if (!empty($posts)): ?>
                    <div class="row">
                        <?php foreach ($posts as $post): ?>
                        <div class="col-md-6 mb-4">
                            <article class="card blog-card h-100">
                                <?php if ($post['featured_image']): ?>
                                <div class="card-img-wrapper">
                                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($post['title']) ?>">
                                </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-auto">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-secondary category-badge">
                                                <?= ucfirst(htmlspecialchars($post['category'])) ?>
                                            </span>
                                            <?php if ($post['is_featured']): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star"></i> Öne Çıkan
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="card-title">
                                            <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" 
                                               class="text-decoration-none">
                                                <?= htmlspecialchars($post['title']) ?>
                                            </a>
                                        </h4>
                                        <p class="card-text text-muted">
                                            <?= htmlspecialchars($post['excerpt']) ?>
                                        </p>
                                    </div>
                                    <div class="post-meta mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                <?= htmlspecialchars($post['author_name']) ?>
                                            </small>
                                            <small class="text-muted">
                                                <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-comments me-1"></i>
                                                <?= $post['comment_count'] ?> yorum
                                                <i class="fas fa-eye ms-2 me-1"></i>
                                                <?= $post['view_count'] ?? 0 ?> görüntülenme
                                            </small>
                                            <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                Devamını Oku
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Blog sayfaları" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" 
                                   href="/blog?page=<?= $current_page - 1 ?><?= $current_category ? '&category=' . urlencode($current_category) : '' ?><?= $current_search ? '&search=' . urlencode($current_search) : '' ?>">
                                    <i class="fas fa-chevron-left"></i> Önceki
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                            <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                <a class="page-link" 
                                   href="/blog?page=<?= $i ?><?= $current_category ? '&category=' . urlencode($current_category) : '' ?><?= $current_search ? '&search=' . urlencode($current_search) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" 
                                   href="/blog?page=<?= $current_page + 1 ?><?= $current_category ? '&category=' . urlencode($current_category) : '' ?><?= $current_search ? '&search=' . urlencode($current_search) : '' ?>">
                                    Sonraki <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h3 class="text-muted">
                            <?php if ($current_search): ?>
                                Arama sonucu bulunamadı
                            <?php elseif ($current_category): ?>
                                Bu kategoride henüz yazı yok
                            <?php else: ?>
                                Henüz blog yazısı yok
                            <?php endif; ?>
                        </h3>
                        <p class="text-muted">Lütfen farklı anahtar kelimeler deneyin veya tüm yazıları görüntüleyin.</p>
                        <a href="/blog" class="btn btn-primary">Tüm Yazılar</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="blog-sidebar">
                
                <!-- Categories -->
                <?php if (!empty($categories)): ?>
                <div class="sidebar-widget mb-5">
                    <h5 class="widget-title">
                        <i class="fas fa-folder-open me-2"></i>
                        Kategoriler
                    </h5>
                    <div class="widget-content">
                        <ul class="list-unstyled category-list">
                            <li>
                                <a href="/blog" class="d-flex justify-content-between align-items-center <?= !$current_category ? 'active' : '' ?>">
                                    <span>Tüm Yazılar</span>
                                    <span class="badge bg-primary"><?= $total_posts ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="/blog?category=<?= urlencode($category['category']) ?>" 
                                   class="d-flex justify-content-between align-items-center <?= $current_category === $category['category'] ? 'active' : '' ?>">
                                    <span><?= ucfirst(htmlspecialchars($category['category'])) ?></span>
                                    <span class="badge bg-secondary"><?= $category['post_count'] ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Recent Posts -->
                <?php if (!empty($recent_posts)): ?>
                <div class="sidebar-widget mb-5">
                    <h5 class="widget-title">
                        <i class="fas fa-clock me-2"></i>
                        Son Yazılar
                    </h5>
                    <div class="widget-content">
                        <?php foreach ($recent_posts as $recent_post): ?>
                        <div class="recent-post-item mb-3">
                            <div class="d-flex">
                                <?php if ($recent_post['featured_image']): ?>
                                <div class="recent-post-thumb me-3">
                                    <img src="<?= htmlspecialchars($recent_post['featured_image']) ?>" 
                                         alt="<?= htmlspecialchars($recent_post['title']) ?>" 
                                         class="img-fluid rounded">
                                </div>
                                <?php endif; ?>
                                <div class="recent-post-content">
                                    <h6 class="mb-1">
                                        <a href="/blog/<?= htmlspecialchars($recent_post['slug']) ?>" 
                                           class="text-decoration-none">
                                            <?= htmlspecialchars($recent_post['title']) ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d.m.Y', strtotime($recent_post['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Links -->
                <div class="sidebar-widget mb-5">
                    <h5 class="widget-title">
                        <i class="fas fa-compass me-2"></i>
                        Keşfet
                    </h5>
                    <div class="widget-content">
                        <div class="quick-links">
                            <a href="/zodiac" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                <i class="fas fa-star me-1"></i>
                                Burç Yorumları
                            </a>
                            <a href="/tarot" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                <i class="fas fa-magic me-1"></i>
                                Tarot Falı
                            </a>
                            <a href="/compatibility" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                <i class="fas fa-heart me-1"></i>
                                Burç Uyumu
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="sidebar-widget newsletter-widget">
                    <h5 class="widget-title">
                        <i class="fas fa-envelope me-2"></i>
                        Bülten
                    </h5>
                    <div class="widget-content">
                        <p class="mb-3">Yeni yazılarımızdan haberdar olmak için e-posta listemize katılın.</p>
                        <form class="newsletter-form">
                            <div class="input-group mb-2">
                                <input type="email" class="form-control" placeholder="E-posta adresiniz" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <small class="text-muted">
                                Spam göndermiyoruz. İstediğiniz zaman çıkabilirsiniz.
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->section('styles') ?>
<style>
.blog-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.blog-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="stars" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23stars)"/></svg>');
    opacity: 0.3;
}

.blog-card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.featured-card {
    border: 2px solid #ffc107;
}

.card-img-wrapper {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.card-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card:hover .card-img-wrapper img {
    transform: scale(1.05);
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ffc107;
    color: #000;
    padding: 5px 8px;
    border-radius: 15px;
    font-size: 12px;
}

.category-badge {
    font-size: 0.7rem;
    font-weight: 500;
}

.section-title {
    font-weight: 700;
    color: #2c3e50;
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
    display: inline-block;
}

.blog-sidebar .sidebar-widget {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.widget-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 8px;
}

.category-list li {
    margin-bottom: 8px;
}

.category-list a {
    color: #495057;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.category-list a:hover,
.category-list a.active {
    background: #007bff;
    color: white;
}

.recent-post-thumb {
    width: 60px;
    flex-shrink: 0;
}

.recent-post-thumb img {
    width: 100%;
    height: 40px;
    object-fit: cover;
}

.newsletter-widget {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.newsletter-widget .widget-title {
    color: white;
    border-bottom-color: rgba(255, 255, 255, 0.3);
}

.search-box .form-control {
    background: rgba(255, 255, 255, 0.9);
    border: none;
}

.pagination .page-link {
    color: #007bff;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.filter-results {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #2196f3;
}

@media (max-width: 768px) {
    .blog-hero .display-4 {
        font-size: 2rem;
    }
    
    .card-img-wrapper {
        height: 150px;
    }
    
    .col-md-6 {
        margin-bottom: 2rem !important;
    }
}
</style>
<?php $this->stop() ?>

<?php $this->section('scripts') ?>
<script>
// Newsletter form
document.querySelector('.newsletter-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    
    if (email) {
        // Here you would typically send to your newsletter service
        showToast('Bülten kaydınız alındı! Teşekkürler.', 'success');
        this.reset();
    }
});

// Smooth scroll for page navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Auto-hide flash messages
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('alert-success')) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    });
}, 3000);
</script>
<?php $this->stop() ?>