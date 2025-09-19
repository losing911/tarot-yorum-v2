<?php
/**
 * Email Service
 * Handle email sending for verification, password reset, etc.
 */

class EmailService
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;
    
    public function __construct()
    {
        $this->host = MAIL_HOST;
        $this->port = MAIL_PORT;
        $this->username = MAIL_USERNAME;
        $this->password = MAIL_PASSWORD;
        $this->fromEmail = MAIL_FROM_EMAIL;
        $this->fromName = MAIL_FROM_NAME;
    }
    
    /**
     * Send email verification
     */
    public function sendEmailVerification($email, $verificationUrl)
    {
        $subject = 'E-posta Adresinizi Doğrulayın - ' . APP_NAME;
        
        $body = $this->getEmailTemplate('verification', [
            'verification_url' => $verificationUrl,
            'app_name' => APP_NAME,
            'app_url' => APP_URL
        ]);
        
        return $this->sendMail($email, $subject, $body);
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordReset($email, $resetUrl, $firstName)
    {
        $subject = 'Şifre Sıfırlama - ' . APP_NAME;
        
        $body = $this->getEmailTemplate('password-reset', [
            'reset_url' => $resetUrl,
            'first_name' => $firstName,
            'app_name' => APP_NAME,
            'app_url' => APP_URL
        ]);
        
        return $this->sendMail($email, $subject, $body);
    }
    
    /**
     * Send welcome email
     */
    public function sendWelcomeEmail($email, $firstName)
    {
        $subject = 'Hoş Geldiniz - ' . APP_NAME;
        
        $body = $this->getEmailTemplate('welcome', [
            'first_name' => $firstName,
            'app_name' => APP_NAME,
            'app_url' => APP_URL
        ]);
        
        return $this->sendMail($email, $subject, $body);
    }
    
    /**
     * Send email using SMTP
     */
    private function sendMail($to, $subject, $body)
    {
        try {
            // Use basic PHP mail() function or implement PHPMailer for production
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: ' . $this->fromName . ' <' . $this->fromEmail . '>',
                'Reply-To: ' . $this->fromEmail,
                'X-Mailer: PHP/' . phpversion()
            ];
            
            $success = mail($to, $subject, $body, implode("\r\n", $headers));
            
            if (!$success) {
                error_log("Email sending failed to: {$to}");
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log('Email Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get email template
     */
    private function getEmailTemplate($template, $variables = [])
    {
        $templates = [
            'verification' => $this->getVerificationTemplate(),
            'password-reset' => $this->getPasswordResetTemplate(),
            'welcome' => $this->getWelcomeTemplate()
        ];
        
        $content = $templates[$template] ?? '';
        
        // Replace variables
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }
    
    /**
     * Email verification template
     */
    private function getVerificationTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>E-posta Doğrulama</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #667eea; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>{{app_name}}</h1>
                    <p>E-posta Adresinizi Doğrulayın</p>
                </div>
                <div class="content">
                    <h2>Merhaba!</h2>
                    <p>{{app_name}} platformuna hoş geldiniz! Hesabınızı aktifleştirmek için lütfen aşağıdaki butona tıklayarak e-posta adresinizi doğrulayın.</p>
                    
                    <div style="text-align: center;">
                        <a href="{{verification_url}}" class="button">E-posta Adresimi Doğrula</a>
                    </div>
                    
                    <p>Eğer yukarıdaki buton çalışmıyorsa, aşağıdaki bağlantıyı tarayıcınıza kopyalayabilirsiniz:</p>
                    <p style="word-break: break-all; color: #667eea;">{{verification_url}}</p>
                    
                    <p>Bu bağlantı 24 saat geçerlidir. Eğer bu e-postayı siz talep etmediyseniz, lütfen görmezden gelin.</p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 {{app_name}}. Tüm hakları saklıdır.</p>
                    <p><a href="{{app_url}}">{{app_url}}</a></p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Password reset template
     */
    private function getPasswordResetTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Şifre Sıfırlama</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #e74c3c; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>{{app_name}}</h1>
                    <p>Şifre Sıfırlama</p>
                </div>
                <div class="content">
                    <h2>Merhaba {{first_name}}!</h2>
                    <p>Hesabınız için şifre sıfırlama talebinde bulundunuz. Yeni şifre oluşturmak için aşağıdaki butona tıklayın.</p>
                    
                    <div style="text-align: center;">
                        <a href="{{reset_url}}" class="button">Şifremi Sıfırla</a>
                    </div>
                    
                    <p>Eğer yukarıdaki buton çalışmıyorsa, aşağıdaki bağlantıyı tarayıcınıza kopyalayabilirsiniz:</p>
                    <p style="word-break: break-all; color: #e74c3c;">{{reset_url}}</p>
                    
                    <p><strong>Güvenlik uyarısı:</strong> Bu bağlantı 1 saat geçerlidir. Eğer bu talebi siz yapmadıysanız, lütfen hesabınızın güvenliği için bizimle iletişime geçin.</p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 {{app_name}}. Tüm hakları saklıdır.</p>
                    <p><a href="{{app_url}}">{{app_url}}</a></p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Welcome email template
     */
    private function getWelcomeTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Hoş Geldiniz</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #27ae60; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
                .features { margin: 20px 0; }
                .feature { margin: 10px 0; padding: 10px; background: white; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>{{app_name}}</h1>
                    <p>Hoş Geldiniz!</p>
                </div>
                <div class="content">
                    <h2>Merhaba {{first_name}}!</h2>
                    <p>{{app_name}} ailesine katıldığınız için çok mutluyuz! Artık yapay zeka destekli astroloji ve tarot dünyasının tüm özelliklerinden faydalanabilirsiniz.</p>
                    
                    <div class="features">
                        <div class="feature">
                            <strong>🔮 AI Destekli Tarot Falı:</strong> Sorularınızı sorun, kartlarınızı çekin ve kişiselleştirilmiş yorumlar alın.
                        </div>
                        <div class="feature">
                            <strong>⭐ Günlük Burç Yorumları:</strong> Her gün güncel ve kişisel burç yorumlarınızı okuyun.
                        </div>
                        <div class="feature">
                            <strong>📝 Blog Yazıları:</strong> Astroloji hakkında yazılar yazın ve topluluğumuzla paylaşın.
                        </div>
                        <div class="feature">
                            <strong>💫 Uyumluluk Analizi:</strong> Sevdiklerinizle burç uyumluluğunuzu keşfedin.
                        </div>
                    </div>
                    
                    <div style="text-align: center;">
                        <a href="{{app_url}}" class="button">Platformu Keşfet</a>
                    </div>
                    
                    <p>Herhangi bir sorunuz olursa, her zaman yanınızdayız. İyi fallar!</p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 {{app_name}}. Tüm hakları saklıdır.</p>
                    <p><a href="{{app_url}}">{{app_url}}</a></p>
                </div>
            </div>
        </body>
        </html>';
    }
}