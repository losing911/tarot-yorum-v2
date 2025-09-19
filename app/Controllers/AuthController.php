<?php
/**
 * Authentication Controller
 * Handle user registration, login, logout, and email verification
 */

class AuthController extends BaseController
{
    private $userModel;
    private $emailService;
    
    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->userModel = new User($database);
        $this->emailService = new EmailService();
    }
    
    /**
     * Show login form
     */
    public function loginForm($params = [])
    {
        // Redirect if already logged in
        if ($this->getCurrentUser()) {
            $this->redirect('/profile');
        }
        
        $data = [
            'page_title' => 'Giriş Yap',
            'meta_description' => 'Tarot-Yorum.fun hesabınıza giriş yaparak kişisel burç yorumlarınıza ve tarot geçmişinize erişin.'
        ];
        
        $this->view('auth.login', $data);
    }
    
    /**
     * Process login
     */
    public function login($params = [])
    {
        if (!$this->validateCSRF($this->request->input('csrf_token'))) {
            $this->redirect('/login', 'Güvenlik hatası. Lütfen tekrar deneyin.', 'error');
        }
        
        $errors = $this->request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $this->request->all();
            $this->redirect('/login');
        }
        
        $email = $this->sanitize($this->request->input('email'));
        $password = $this->request->input('password');
        $remember = $this->request->input('remember');
        
        // Find user by email
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !$this->userModel->verifyPassword($user, $password)) {
            $this->redirect('/login', 'E-posta veya şifre hatalı.', 'error');
        }
        
        if (!$user['is_active']) {
            $this->redirect('/login', 'Hesabınız devre dışı bırakılmış.', 'error');
        }
        
        // Check if email verification is required
        if (config('email_verification') && !$user['is_email_verified']) {
            $this->redirect('/login', 'Lütfen e-posta adresinizi doğrulayın.', 'warning');
        }
        
        // Rate limiting check
        $this->checkLoginAttempts($email);
        
        // Create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Handle remember me
        if ($remember) {
            $this->setRememberMeCookie($user['id']);
        }
        
        $redirectUrl = $_SESSION['redirect_after_login'] ?? '/profile';
        unset($_SESSION['redirect_after_login']);
        
        $this->redirect($redirectUrl, 'Başarıyla giriş yaptınız.', 'success');
    }
    
    /**
     * Show registration form
     */
    public function registerForm($params = [])
    {
        if ($this->getCurrentUser()) {
            $this->redirect('/profile');
        }
        
        $data = [
            'page_title' => 'Kayıt Ol',
            'meta_description' => 'Tarot-Yorum.fun\'a üye olarak kişisel burç yorumlarınıza erişin, tarot geçmişinizi takip edin ve blog yazıları paylaşın.'
        ];
        
        $this->view('auth.register', $data);
    }
    
    /**
     * Process registration
     */
    public function register($params = [])
    {
        if (!$this->validateCSRF($this->request->input('csrf_token'))) {
            $this->redirect('/register', 'Güvenlik hatası. Lütfen tekrar deneyin.', 'error');
        }
        
        $errors = $this->request->validate([
            'username' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|confirmed',
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'birth_date' => 'required'
        ]);
        
        // Additional validation
        $email = $this->sanitize($this->request->input('email'));
        $username = $this->sanitize($this->request->input('username'));
        
        if ($this->userModel->findByEmail($email)) {
            $errors['email'] = 'Bu e-posta adresi zaten kullanılıyor.';
        }
        
        if ($this->userModel->findByUsername($username)) {
            $errors['username'] = 'Bu kullanıcı adı zaten alınmış.';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $this->request->all();
            $this->redirect('/register');
        }
        
        try {
            $this->db->beginTransaction();
            
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $this->request->input('password'),
                'first_name' => $this->sanitize($this->request->input('first_name')),
                'last_name' => $this->sanitize($this->request->input('last_name')),
                'birth_date' => $this->request->input('birth_date'),
                'birth_time' => $this->request->input('birth_time'),
                'birth_place' => $this->sanitize($this->request->input('birth_place'))
            ];
            
            $userId = $this->userModel->createUser($userData);
            
            // Send email verification if enabled
            if (config('email_verification')) {
                $this->sendEmailVerification($userId, $email);
                $message = 'Kayıt başarılı! Lütfen e-posta adresinizi doğrulayın.';
                $redirectUrl = '/login';
            } else {
                // Auto-verify if email verification is disabled
                $this->userModel->verifyEmail($userId);
                
                // Auto-login
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_role'] = 'user';
                
                $message = 'Kayıt başarılı! Hoş geldiniz.';
                $redirectUrl = '/profile';
            }
            
            $this->db->commit();
            $this->redirect($redirectUrl, $message, 'success');
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('Registration Error: ' . $e->getMessage());
            $this->redirect('/register', 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.', 'error');
        }
    }
    
    /**
     * Logout user
     */
    public function logout($params = [])
    {
        // Clear session
        session_destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        $this->redirect('/', 'Başarıyla çıkış yaptınız.', 'success');
    }
    
    /**
     * Verify email address
     */
    public function verifyEmail($params = [])
    {
        $token = $params['token'] ?? '';
        
        if (empty($token)) {
            $this->redirect('/login', 'Geçersiz doğrulama bağlantısı.', 'error');
        }
        
        // Find verification record
        $this->db->query(
            'SELECT ev.*, u.id as user_id FROM email_verifications ev
             JOIN users u ON ev.user_id = u.id
             WHERE ev.token = :token AND ev.expires_at > NOW() AND ev.used_at IS NULL'
        );
        $this->db->bind(':token', $token);
        $verification = $this->db->fetch();
        
        if (!$verification) {
            $this->redirect('/login', 'Doğrulama bağlantısı geçersiz veya süresi dolmuş.', 'error');
        }
        
        try {
            $this->db->beginTransaction();
            
            // Mark email as verified
            $this->userModel->verifyEmail($verification['user_id']);
            
            // Mark token as used
            $this->db->query('UPDATE email_verifications SET used_at = NOW() WHERE id = :id');
            $this->db->bind(':id', $verification['id']);
            $this->db->execute();
            
            $this->db->commit();
            
            $this->redirect('/login', 'E-posta adresiniz başarıyla doğrulandı. Şimdi giriş yapabilirsiniz.', 'success');
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('Email Verification Error: ' . $e->getMessage());
            $this->redirect('/login', 'Doğrulama sırasında bir hata oluştu.', 'error');
        }
    }
    
    /**
     * Show forgot password form
     */
    public function forgotPasswordForm($params = [])
    {
        $data = [
            'page_title' => 'Şifremi Unuttum',
            'meta_description' => 'Şifrenizi mi unuttunuz? E-posta adresinizi girerek yeni şifre oluşturma bağlantısı alın.'
        ];
        
        $this->view('auth.forgot-password', $data);
    }
    
    /**
     * Process forgot password
     */
    public function forgotPassword($params = [])
    {
        if (!$this->validateCSRF($this->request->input('csrf_token'))) {
            $this->redirect('/forgot-password', 'Güvenlik hatası. Lütfen tekrar deneyin.', 'error');
        }
        
        $email = $this->sanitize($this->request->input('email'));
        
        if (!$this->validateEmail($email)) {
            $this->redirect('/forgot-password', 'Geçerli bir e-posta adresi giriniz.', 'error');
        }
        
        $user = $this->userModel->findByEmail($email);
        
        // Always show success message for security
        if ($user) {
            $this->sendPasswordResetEmail($user);
        }
        
        $this->redirect('/login', 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.', 'success');
    }
    
    /**
     * Show reset password form
     */
    public function resetPasswordForm($params = [])
    {
        $token = $params['token'] ?? '';
        
        if (empty($token)) {
            $this->redirect('/login', 'Geçersiz sıfırlama bağlantısı.', 'error');
        }
        
        // Verify token
        $this->db->query(
            'SELECT * FROM password_resets 
             WHERE token = :token AND expires_at > NOW() AND used_at IS NULL'
        );
        $this->db->bind(':token', $token);
        $reset = $this->db->fetch();
        
        if (!$reset) {
            $this->redirect('/login', 'Sıfırlama bağlantısı geçersiz veya süresi dolmuş.', 'error');
        }
        
        $data = [
            'page_title' => 'Şifre Sıfırla',
            'token' => $token
        ];
        
        $this->view('auth.reset-password', $data);
    }
    
    /**
     * Process password reset
     */
    public function resetPassword($params = [])
    {
        if (!$this->validateCSRF($this->request->input('csrf_token'))) {
            $this->redirect('/login', 'Güvenlik hatası. Lütfen tekrar deneyin.', 'error');
        }
        
        $token = $this->request->input('token');
        $password = $this->request->input('password');
        $passwordConfirm = $this->request->input('password_confirmation');
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $this->redirect("/reset-password/{$token}", 'Şifre en az ' . PASSWORD_MIN_LENGTH . ' karakter olmalıdır.', 'error');
        }
        
        if ($password !== $passwordConfirm) {
            $this->redirect("/reset-password/{$token}", 'Şifreler eşleşmiyor.', 'error');
        }
        
        // Verify token
        $this->db->query(
            'SELECT * FROM password_resets 
             WHERE token = :token AND expires_at > NOW() AND used_at IS NULL'
        );
        $this->db->bind(':token', $token);
        $reset = $this->db->fetch();
        
        if (!$reset) {
            $this->redirect('/login', 'Sıfırlama bağlantısı geçersiz veya süresi dolmuş.', 'error');
        }
        
        try {
            $this->db->beginTransaction();
            
            // Update user password
            $user = $this->userModel->findByEmail($reset['email']);
            if ($user) {
                $this->userModel->updatePassword($user['id'], $password);
            }
            
            // Mark token as used
            $this->db->query('UPDATE password_resets SET used_at = NOW() WHERE id = :id');
            $this->db->bind(':id', $reset['id']);
            $this->db->execute();
            
            $this->db->commit();
            
            $this->redirect('/login', 'Şifreniz başarıyla güncellendi. Şimdi giriş yapabilirsiniz.', 'success');
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('Password Reset Error: ' . $e->getMessage());
            $this->redirect('/login', 'Şifre sıfırlama sırasında bir hata oluştu.', 'error');
        }
    }
    
    /**
     * Send email verification
     */
    private function sendEmailVerification($userId, $email)
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 86400); // 24 hours
        
        // Store verification token
        $this->db->query(
            'INSERT INTO email_verifications (user_id, token, expires_at) 
             VALUES (:user_id, :token, :expires_at)'
        );
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_at', $expiresAt);
        $this->db->execute();
        
        // Send email
        $verificationUrl = APP_URL . "/verify-email/{$token}";
        $this->emailService->sendEmailVerification($email, $verificationUrl);
    }
    
    /**
     * Send password reset email
     */
    private function sendPasswordResetEmail($user)
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour
        
        // Store reset token
        $this->db->query(
            'INSERT INTO password_resets (email, token, expires_at) 
             VALUES (:email, :token, :expires_at)'
        );
        $this->db->bind(':email', $user['email']);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_at', $expiresAt);
        $this->db->execute();
        
        // Send email
        $resetUrl = APP_URL . "/reset-password/{$token}";
        $this->emailService->sendPasswordReset($user['email'], $resetUrl, $user['first_name']);
    }
    
    /**
     * Check login attempts for rate limiting
     */
    private function checkLoginAttempts($email)
    {
        $key = 'login_attempts_' . $email;
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'count' => 0,
                'time' => time()
            ];
        }
        
        $attempts = $_SESSION[$key];
        
        // Reset counter if hour has passed
        if (time() - $attempts['time'] > 3600) {
            $_SESSION[$key] = [
                'count' => 1,
                'time' => time()
            ];
        } else {
            $_SESSION[$key]['count']++;
            
            if ($_SESSION[$key]['count'] > 5) {
                $this->redirect('/login', 'Çok fazla başarısız giriş denemesi. Lütfen daha sonra tekrar deneyin.', 'error');
            }
        }
    }
    
    /**
     * Set remember me cookie
     */
    private function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expires = time() + (30 * 24 * 60 * 60); // 30 days
        
        setcookie('remember_token', $token, $expires, '/', '', isset($_SERVER['HTTPS']), true);
        
        // Store token in database (you may want to create a remember_tokens table)
        $_SESSION['remember_token'] = $token;
    }
}