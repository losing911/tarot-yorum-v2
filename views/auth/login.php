<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-primary fw-bold">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Giriş Yap
                        </h2>
                        <p class="text-muted">Hesabınıza giriş yaparak kişisel deneyiminize devam edin</p>
                    </div>

                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/login">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta Adresi</label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" 
                                   class="form-control form-control-lg" 
                                   id="password" 
                                   name="password" 
                                   required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Beni hatırla
                            </label>
                        </div>

                        <button type="submit" class="btn btn-gradient btn-lg w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Giriş Yap
                        </button>

                        <div class="text-center">
                            <a href="/forgot-password" class="text-decoration-none">
                                Şifrenizi mi unuttunuz?
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-3">Henüz hesabınız yok mu?</p>
                        <a href="/register" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-person-plus me-2"></i>Yeni Hesap Oluştur
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Demo Account Info -->
            <div class="card mt-4 bg-light border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">🚀 Demo ile Deneyin</h6>
                    <p class="text-muted small mb-0">
                        <strong>Admin:</strong> admin@tarot-yorum.fun / admin123<br>
                        <strong>Test:</strong> test@example.com / test123
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php unset($_SESSION['old']); ?>

<style>
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .form-control-lg {
        padding: 0.75rem 1rem;
        border-radius: 10px;
    }
</style>