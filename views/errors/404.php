<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Bulunamadı - Tarot-Yorum.fun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
            color: #333;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            text-shadow: 3px 3px 0px rgba(0,0,0,0.1);
            margin-bottom: 0;
            color: #6c5ce7;
        }
        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: #2d3436;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .btn-home {
            background: #6c5ce7;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: #5f3dc4;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="error-card">
                    <div class="error-container">
                        <i class="fas fa-search fa-4x mb-4 text-muted"></i>
                        <h1 class="error-code">404</h1>
                        <p class="error-message">Sayfa Bulunamadı</p>
                        <p class="mb-4">Aradığınız sayfa bulunamadı. Sayfa silinmiş, taşınmış veya geçici olarak erişilemiyor olabilir.</p>
                        <div class="d-grid gap-2 d-md-block">
                            <a href="/" class="btn btn-home me-2">
                                <i class="fas fa-home me-2"></i>
                                Ana Sayfa
                            </a>
                            <a href="/blog" class="btn btn-outline-primary">
                                <i class="fas fa-blog me-2"></i>
                                Blog
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>