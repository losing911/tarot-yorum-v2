-- Site Settings Table for Admin Panel
-- This table stores configuration settings for the site

CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'Tarot-Yorum.fun', 'string', 'Site name'),
('site_description', 'AI ile Tarot Falı ve Astroloji', 'string', 'Site description'),
('site_keywords', 'tarot, fal, astroloji, burç, yapay zeka', 'string', 'Site keywords'),
('contact_email', 'info@tarot-yorum.fun', 'string', 'Contact email'),
('social_facebook', '', 'string', 'Facebook URL'),
('social_twitter', '', 'string', 'Twitter URL'),
('social_instagram', '', 'string', 'Instagram URL'),
('social_youtube', '', 'string', 'YouTube URL'),
('analytics_google', '', 'string', 'Google Analytics ID'),
('analytics_facebook', '', 'string', 'Facebook Pixel ID'),
('maintenance_mode', '0', 'boolean', 'Maintenance mode enabled'),
('user_registration', '1', 'boolean', 'User registration enabled'),
('comments_enabled', '1', 'boolean', 'Comments enabled'),
('comments_moderation', '1', 'boolean', 'Comment moderation enabled'),
('max_upload_size', '5242880', 'integer', 'Maximum file upload size in bytes'),
('session_timeout', '3600', 'integer', 'Session timeout in seconds'),
('rate_limit_enabled', '1', 'boolean', 'Rate limiting enabled'),
('rate_limit_requests', '60', 'integer', 'Rate limit requests per minute'),
('ai_service_primary', 'openai', 'string', 'Primary AI service (openai/gemini)'),
('ai_service_fallback', 'gemini', 'string', 'Fallback AI service'),
('email_smtp_host', '', 'string', 'SMTP host'),
('email_smtp_port', '587', 'integer', 'SMTP port'),
('email_smtp_username', '', 'string', 'SMTP username'),
('email_smtp_password', '', 'string', 'SMTP password'),
('email_from_address', 'noreply@tarot-yorum.fun', 'string', 'From email address'),
('email_from_name', 'Tarot Yorum', 'string', 'From name'),
('seo_title_template', '{title} | Tarot-Yorum.fun', 'string', 'SEO title template'),
('seo_meta_description', 'AI destekli tarot falı ve astroloji platformu. Ücretsiz günlük burç yorumları ve kişiselleştirilmiş tarot falları.', 'string', 'Default meta description'),
('backup_enabled', '1', 'boolean', 'Automatic backup enabled'),
('backup_frequency', 'daily', 'string', 'Backup frequency (daily/weekly/monthly)'),
('backup_retention', '30', 'integer', 'Backup retention days'),
('security_password_min_length', '8', 'integer', 'Minimum password length'),
('security_password_require_special', '1', 'boolean', 'Require special characters in password'),
('security_login_attempts', '5', 'integer', 'Maximum login attempts'),
('security_lockout_time', '900', 'integer', 'Account lockout time in seconds');

-- Create indexes
CREATE INDEX idx_setting_key ON site_settings(setting_key);
CREATE INDEX idx_setting_type ON site_settings(setting_type);