<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= $page_title ?? DEFAULT_META_TITLE ?></title>
    <meta name="description" content="<?= $meta_description ?? DEFAULT_META_DESCRIPTION ?>">
    <meta name="keywords" content="<?= $meta_keywords ?? DEFAULT_META_KEYWORDS ?>">
    <meta name="author" content="<?= $app_name ?>">
    <meta name="robots" content="<?= $robots ?? 'index,follow' ?>">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?= $og_title ?? $page_title ?? DEFAULT_META_TITLE ?>">
    <meta property="og:description" content="<?= $og_description ?? $meta_description ?? DEFAULT_META_DESCRIPTION ?>">
    <meta property="og:image" content="<?= $og_image ?? $app_url . '/assets/images/og-default.jpg' ?>">
    <meta property="og:url" content="<?= $og_url ?? $app_url . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:site_name" content="<?= $app_name ?>">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $twitter_title ?? $page_title ?? DEFAULT_META_TITLE ?>">
    <meta name="twitter:description" content="<?= $twitter_description ?? $meta_description ?? DEFAULT_META_DESCRIPTION ?>">
    <meta name="twitter:image" content="<?= $twitter_image ?? $og_image ?? $app_url . '/assets/images/og-default.jpg' ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $canonical_url ?? $app_url . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="/assets/css/style.css?v=<?= time() ?>" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <?php if (defined('GOOGLE_ANALYTICS_ID') && GOOGLE_ANALYTICS_ID): ?>
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= GOOGLE_ANALYTICS_ID ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= GOOGLE_ANALYTICS_ID ?>');
    </script>
    <?php endif; ?>
    
    <?php if (defined('GOOGLE_ADSENSE_CLIENT') && GOOGLE_ADSENSE_CLIENT && GOOGLE_ADSENSE_ENABLED): ?>
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?= GOOGLE_ADSENSE_CLIENT ?>" crossorigin="anonymous"></script>
    <?php endif; ?>
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }
        
        .gradient-bg {
            background: var(--gradient);
        }
        
        .btn-gradient {
            background: var(--gradient);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .hero-section {
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        
        .zodiac-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .zodiac-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }
        
        .zodiac-card:hover {
            transform: translateY(-5px);
            color: var(--primary-color);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .zodiac-symbol {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .footer {
            background: var(--gradient);
            color: white;
            margin-top: 4rem;
        }
        
        .adsense-container {
            margin: 2rem 0;
            text-align: center;
        }
        
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
        
        @media (max-width: 768px) {
            .zodiac-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .zodiac-card {
                padding: 1rem;
            }
            
            .zodiac-symbol {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="/">
                <i class="bi bi-stars me-2"></i><?= $app_name ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/zodiac">Burçlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tarot">Tarot Falı</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blog">Blog</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if ($current_user): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= htmlspecialchars($current_user['first_name'] ?? $current_user['username']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profilim</a></li>
                                <li><a class="dropdown-item" href="/tarot/history"><i class="bi bi-clock-history me-2"></i>Tarot Geçmişi</a></li>
                                <li><a class="dropdown-item" href="/blog/create"><i class="bi bi-pencil me-2"></i>Yazı Yaz</a></li>
                                <?php if ($is_admin): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/admin"><i class="bi bi-gear me-2"></i>Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Çıkış</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Giriş</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-gradient ms-2" href="/register">Kayıt Ol</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-message">
            <div class="alert alert-<?= $_SESSION['flash']['type'] === 'error' ? 'danger' : $_SESSION['flash']['type'] ?> alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- AdSense Banner (if enabled) -->
    <?php if (defined('GOOGLE_ADSENSE_ENABLED') && GOOGLE_ADSENSE_ENABLED): ?>
    <div class="adsense-container">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="<?= GOOGLE_ADSENSE_CLIENT ?>"
             data-ad-slot="1234567890"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-stars me-2"></i><?= $app_name ?>
                    </h5>
                    <p class="mb-3">Yapay zeka destekli tarot falı ve astroloji platformu. Geleceğinizi keşfedin, burç yorumlarınızı okuyun.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Hızlı Linkler</h6>
                    <ul class="list-unstyled">
                        <li><a href="/zodiac" class="text-white-50">Burçlar</a></li>
                        <li><a href="/tarot" class="text-white-50">Tarot Falı</a></li>
                        <li><a href="/blog" class="text-white-50">Blog</a></li>
                        <li><a href="/about" class="text-white-50">Hakkımızda</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Kategoriler</h6>
                    <ul class="list-unstyled">
                        <li><a href="/category/astroloji" class="text-white-50">Astroloji</a></li>
                        <li><a href="/category/tarot" class="text-white-50">Tarot</a></li>
                        <li><a href="/category/numeroloji" class="text-white-50">Numeroloji</a></li>
                        <li><a href="/category/ruya-tabirleri" class="text-white-50">Rüya Tabirleri</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">İletişim</h6>
                    <p class="text-white-50">
                        <i class="bi bi-envelope me-2"></i>
                        info@tarot-yorum.fun
                    </p>
                    <p class="text-white-50">
                        <i class="bi bi-geo-alt me-2"></i>
                        İstanbul, Türkiye
                    </p>
                    
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Günlük Burç Yorumu</h6>
                        <p class="text-white-50 small">Her gün güncel burç yorumları için e-posta listemize katılın.</p>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="E-posta adresiniz">
                            <button class="btn btn-light" type="button">Abone Ol</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">
                        &copy; <?= date('Y') ?> <?= $app_name ?>. Tüm hakları saklıdır.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="/privacy" class="text-white-50 me-3">Gizlilik</a>
                    <a href="/terms" class="text-white-50">Kullanım Şartları</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessage = document.querySelector('.flash-message');
            if (flashMessage) {
                flashMessage.style.opacity = '0';
                setTimeout(() => flashMessage.remove(), 300);
            }
        }, 5000);
        
        // Smooth scrolling for anchor links
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
        
        // Add loading states to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Yükleniyor...';
                }
            });
        });
    </script>
    
    <!-- Additional page-specific scripts -->
    <?php if (isset($additional_scripts)): ?>
        <?= $additional_scripts ?>
    <?php endif; ?>
</body>
</html>