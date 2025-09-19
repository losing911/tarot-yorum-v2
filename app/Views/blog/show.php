<?php $this->layout('layouts.main', ['page_title' => $page_title, 'meta_description' => $meta_description, 'meta_keywords' => $meta_keywords]) ?>

<?php $this->section('content') ?>

<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="blog-post">
                <!-- Post Header -->
                <header class="post-header mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
                            <li class="breadcrumb-item"><a href="/blog">Blog</a></li>
                            <li class="breadcrumb-item">
                                <a href="/blog?category=<?= urlencode($post['category']) ?>">
                                    <?= ucfirst(htmlspecialchars($post['category'])) ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?= htmlspecialchars($post['title']) ?>
                            </li>
                        </ol>
                    </nav>

                    <div class="post-meta mb-3">
                        <span class="badge bg-primary category-badge me-2">
                            <?= ucfirst(htmlspecialchars($post['category'])) ?>
                        </span>
                        <?php if ($post['is_featured']): ?>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> Öne Çıkan
                        </span>
                        <?php endif; ?>
                    </div>

                    <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>

                    <div class="post-info d-flex flex-wrap align-items-center text-muted mb-4">
                        <div class="me-4 mb-2">
                            <i class="fas fa-user me-1"></i>
                            <span><?= htmlspecialchars($post['author_name']) ?></span>
                        </div>
                        <div class="me-4 mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            <span><?= date('d F Y', strtotime($post['created_at'])) ?></span>
                        </div>
                        <div class="me-4 mb-2">
                            <i class="fas fa-eye me-1"></i>
                            <span><?= $post['view_count'] ?? 0 ?> görüntülenme</span>
                        </div>
                        <div class="me-4 mb-2">
                            <i class="fas fa-comments me-1"></i>
                            <span><?= count($comments) ?> yorum</span>
                        </div>
                        <?php if ($post['updated_at'] && $post['updated_at'] !== $post['created_at']): ?>
                        <div class="mb-2">
                            <i class="fas fa-edit me-1"></i>
                            <span>Güncellendi: <?= date('d.m.Y', strtotime($post['updated_at'])) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Featured Image -->
                    <?php if ($post['featured_image']): ?>
                    <div class="post-featured-image mb-4">
                        <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                             alt="<?= htmlspecialchars($post['title']) ?>" 
                             class="img-fluid rounded shadow-sm">
                    </div>
                    <?php endif; ?>

                    <!-- Social Share -->
                    <div class="social-share mb-4">
                        <span class="me-3">Paylaş:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(getBaseUrl() . '/blog/' . $post['slug']) ?>" 
                           target="_blank" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(getBaseUrl() . '/blog/' . $post['slug']) ?>&text=<?= urlencode($post['title']) ?>" 
                           target="_blank" class="btn btn-outline-info btn-sm me-2">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(getBaseUrl() . '/blog/' . $post['slug']) ?>" 
                           target="_blank" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fab fa-linkedin-in"></i> LinkedIn
                        </a>
                        <a href="whatsapp://send?text=<?= urlencode($post['title'] . ' - ' . getBaseUrl() . '/blog/' . $post['slug']) ?>" 
                           target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </header>

                <!-- Post Content -->
                <div class="post-content">
                    <!-- Excerpt -->
                    <?php if ($post['excerpt']): ?>
                    <div class="post-excerpt alert alert-light border-start border-primary border-4 mb-4">
                        <strong>Özet:</strong> <?= htmlspecialchars($post['excerpt']) ?>
                    </div>
                    <?php endif; ?>

                    <!-- Main Content -->
                    <div class="content-body">
                        <?= $post['content'] ?>
                    </div>
                </div>

                <!-- Post Footer -->
                <footer class="post-footer mt-5 pt-4 border-top">
                    <!-- Tags -->
                    <div class="post-tags mb-4">
                        <strong>Etiketler:</strong>
                        <?php 
                        $keywords = explode(',', $post['meta_keywords'] ?? '');
                        foreach ($keywords as $keyword): 
                            $keyword = trim($keyword);
                            if ($keyword):
                        ?>
                        <span class="badge bg-light text-dark me-1 mb-1"><?= htmlspecialchars($keyword) ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>

                    <!-- Author Info -->
                    <div class="author-info bg-light p-4 rounded">
                        <div class="d-flex align-items-center">
                            <div class="author-avatar me-3">
                                <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; font-size: 24px;">
                                    <?= strtoupper(substr($post['author_name'], 0, 1)) ?>
                                </div>
                            </div>
                            <div class="author-details">
                                <h5 class="mb-1"><?= htmlspecialchars($post['author_name']) ?></h5>
                                <p class="text-muted mb-0">
                                    Astroloji ve tarot konularında uzman yazar. 
                                    Ruhsal gelişim ve önsezi konularında rehberlik sağlıyor.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="post-navigation mt-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="/blog" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Tüm Yazılara Dön
                                </a>
                            </div>
                            <div class="col-md-6 mb-3 text-md-end">
                                <a href="/blog?category=<?= urlencode($post['category']) ?>" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-folder me-1"></i>
                                    <?= ucfirst(htmlspecialchars($post['category'])) ?> Kategorisi
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>
            </article>

            <!-- Related Posts -->
            <?php if (!empty($related_posts)): ?>
            <section class="related-posts mt-5">
                <h3 class="section-title mb-4">
                    <i class="fas fa-lightbulb me-2"></i>
                    İlgili Yazılar
                </h3>
                <div class="row">
                    <?php foreach ($related_posts as $related_post): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if ($related_post['featured_image']): ?>
                            <div class="card-img-wrapper" style="height: 150px; overflow: hidden;">
                                <img src="<?= htmlspecialchars($related_post['featured_image']) ?>" 
                                     class="card-img-top" 
                                     style="width: 100%; height: 100%; object-fit: cover;"
                                     alt="<?= htmlspecialchars($related_post['title']) ?>">
                            </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/blog/<?= htmlspecialchars($related_post['slug']) ?>" 
                                       class="text-decoration-none">
                                        <?= htmlspecialchars($related_post['title']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <?= htmlspecialchars($related_post['excerpt']) ?>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?= date('d.m.Y', strtotime($related_post['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Comments Section -->
            <section class="comments-section mt-5">
                <h3 class="section-title mb-4">
                    <i class="fas fa-comments me-2"></i>
                    Yorumlar (<?= count($comments) ?>)
                </h3>

                <!-- Comment Form -->
                <div class="comment-form mb-5">
                    <h4 class="mb-3">Yorum Yap</h4>
                    <form id="commentForm" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="author_name" class="form-label">Adınız *</label>
                                <input type="text" class="form-control" id="author_name" name="author_name" required>
                                <div class="invalid-feedback">
                                    Lütfen adınızı girin.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="author_email" class="form-label">E-posta *</label>
                                <input type="email" class="form-control" id="author_email" name="author_email" required>
                                <div class="invalid-feedback">
                                    Geçerli bir e-posta adresi girin.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Yorumunuz *</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" 
                                      placeholder="Düşüncelerinizi paylaşın..." required></textarea>
                            <div class="invalid-feedback">
                                Lütfen yorumunuzu yazın.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                * Yorumunuz moderasyon sonrası yayınlanacaktır.
                                E-posta adresiniz paylaşılmayacaktır.
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="submitComment">
                            <i class="fas fa-paper-plane me-1"></i>
                            Yorum Gönder
                        </button>
                    </form>
                </div>

                <!-- Comments List -->
                <?php if (!empty($comments)): ?>
                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                    <div class="comment-item mb-4 p-4 bg-light rounded">
                        <div class="comment-header d-flex justify-content-between align-items-start mb-2">
                            <div class="comment-author">
                                <strong><?= htmlspecialchars($comment['author_name']) ?></strong>
                                <small class="text-muted ms-2">
                                    <i class="fas fa-clock me-1"></i>
                                    <?= date('d F Y H:i', strtotime($comment['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                        <div class="comment-content">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="no-comments text-center py-4 text-muted">
                    <i class="fas fa-comments fa-2x mb-3"></i>
                    <p>Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                </div>
                <?php endif; ?>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="blog-sidebar">
                
                <!-- Categories -->
                <?php if (!empty($categories)): ?>
                <div class="sidebar-widget mb-4">
                    <h5 class="widget-title">
                        <i class="fas fa-folder-open me-2"></i>
                        Kategoriler
                    </h5>
                    <div class="widget-content">
                        <ul class="list-unstyled category-list">
                            <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="/blog?category=<?= urlencode($category['category']) ?>" 
                                   class="d-flex justify-content-between align-items-center">
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
                <div class="sidebar-widget mb-4">
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

                <!-- Table of Contents -->
                <div class="sidebar-widget mb-4" id="tableOfContents" style="display: none;">
                    <h5 class="widget-title">
                        <i class="fas fa-list me-2"></i>
                        İçindekiler
                    </h5>
                    <div class="widget-content">
                        <ul class="list-unstyled toc-list" id="tocList">
                        </ul>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="sidebar-widget mb-4">
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
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->section('styles') ?>
<style>
.post-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
    margin-bottom: 1rem;
}

.post-info {
    font-size: 0.9rem;
}

.post-featured-image img {
    max-height: 400px;
    width: 100%;
    object-fit: cover;
}

.social-share .btn {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}

.post-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
}

.post-excerpt {
    font-style: italic;
    font-size: 1.1rem;
}

.content-body h1,
.content-body h2,
.content-body h3,
.content-body h4,
.content-body h5,
.content-body h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #2c3e50;
    font-weight: 600;
}

.content-body h2 {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.content-body p {
    margin-bottom: 1.5rem;
}

.content-body ul,
.content-body ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.content-body blockquote {
    border-left: 4px solid #007bff;
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
}

.content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin: 1rem 0;
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

.category-list a:hover {
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

.comment-item {
    border-left: 4px solid #007bff;
}

.comment-form {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.author-info {
    border-left: 4px solid #28a745;
}

.avatar-placeholder {
    font-weight: bold;
}

.toc-list {
    max-height: 400px;
    overflow-y: auto;
}

.toc-list a {
    color: #495057;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    display: block;
    transition: all 0.3s ease;
}

.toc-list a:hover,
.toc-list a.active {
    background: #007bff;
    color: white;
}

@media (max-width: 768px) {
    .post-title {
        font-size: 1.8rem;
    }
    
    .social-share {
        text-align: center;
    }
    
    .social-share .btn {
        margin-bottom: 0.5rem;
    }
    
    .post-info {
        justify-content: center;
        text-align: center;
    }
    
    .post-info > div {
        margin-bottom: 0.5rem;
    }
}
</style>
<?php $this->stop() ?>

<?php $this->section('scripts') ?>
<script>
// Comment form submission
document.getElementById('commentForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('submitComment');
    const originalText = submitBtn.innerHTML;
    
    // Validation
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
    }
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Gönderiliyor...';
    
    try {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        const response = await fetch('/blog/comment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            form.reset();
            form.classList.remove('was-validated');
        } else {
            showToast(result.error || 'Yorum gönderilemedi', 'error');
        }
    } catch (error) {
        console.error('Comment submission error:', error);
        showToast('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Generate table of contents
function generateTOC() {
    const content = document.querySelector('.content-body');
    const tocList = document.getElementById('tocList');
    const tocWidget = document.getElementById('tableOfContents');
    
    if (!content || !tocList) return;
    
    const headings = content.querySelectorAll('h2, h3, h4');
    
    if (headings.length === 0) return;
    
    tocWidget.style.display = 'block';
    
    headings.forEach((heading, index) => {
        // Add ID to heading if not present
        if (!heading.id) {
            heading.id = `heading-${index}`;
        }
        
        const li = document.createElement('li');
        li.className = heading.tagName.toLowerCase() === 'h3' ? 'ms-3' : 
                       heading.tagName.toLowerCase() === 'h4' ? 'ms-4' : '';
        
        const a = document.createElement('a');
        a.href = `#${heading.id}`;
        a.textContent = heading.textContent;
        a.addEventListener('click', function(e) {
            e.preventDefault();
            heading.scrollIntoView({ behavior: 'smooth' });
        });
        
        li.appendChild(a);
        tocList.appendChild(li);
    });
    
    // Highlight current section on scroll
    function highlightCurrentSection() {
        const scrollPos = window.scrollY + 100;
        const tocLinks = tocList.querySelectorAll('a');
        
        headings.forEach((heading, index) => {
            const headingTop = heading.offsetTop;
            const nextHeading = headings[index + 1];
            const headingBottom = nextHeading ? nextHeading.offsetTop : document.body.scrollHeight;
            
            if (scrollPos >= headingTop && scrollPos < headingBottom) {
                tocLinks.forEach(link => link.classList.remove('active'));
                tocLinks[index]?.classList.add('active');
            }
        });
    }
    
    window.addEventListener('scroll', highlightCurrentSection);
    highlightCurrentSection();
}

// Copy link to clipboard
function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        showToast('Link kopyalandı!', 'success');
    }).catch(() => {
        showToast('Link kopyalanamadı', 'error');
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    generateTOC();
    
    // Add copy link button to social share
    const socialShare = document.querySelector('.social-share');
    if (socialShare) {
        const copyBtn = document.createElement('button');
        copyBtn.className = 'btn btn-outline-secondary btn-sm';
        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Linki Kopyala';
        copyBtn.onclick = copyLink;
        socialShare.appendChild(copyBtn);
    }
});

// Share functionality
function sharePost(platform) {
    const url = window.location.href;
    const title = document.querySelector('.post-title').textContent;
    
    let shareUrl = '';
    
    switch(platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
            break;
        case 'whatsapp':
            shareUrl = `whatsapp://send?text=${encodeURIComponent(title + ' - ' + url)}`;
            break;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
}

// Auto-resize comment textarea
document.getElementById('comment')?.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});
</script>
<?php $this->stop() ?>