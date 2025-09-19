-- Tarot Readings table for storing user readings
CREATE TABLE IF NOT EXISTS tarot_readings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(100),
    spread_type VARCHAR(50) NOT NULL,
    question TEXT,
    cards_drawn JSON NOT NULL,
    interpretation TEXT,
    ai_interpretation TEXT,
    reading_date DATE NOT NULL,
    is_daily BOOLEAN DEFAULT FALSE,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Blog Posts table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content TEXT NOT NULL,
    featured_image VARCHAR(255),
    category VARCHAR(100),
    tags JSON,
    status ENUM('draft', 'published', 'private') DEFAULT 'draft',
    meta_title VARCHAR(255),
    meta_description TEXT,
    view_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    ai_generated BOOLEAN DEFAULT FALSE,
    ai_prompt TEXT,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Blog Comments table
CREATE TABLE IF NOT EXISTS blog_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT,
    parent_id INT,
    author_name VARCHAR(100),
    author_email VARCHAR(100),
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'spam') DEFAULT 'pending',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES blog_comments(id) ON DELETE CASCADE
);

-- Daily Horoscopes table
CREATE TABLE IF NOT EXISTS daily_horoscopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zodiac_sign VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    general TEXT,
    love TEXT,
    career TEXT,
    health TEXT,
    lucky_number INT,
    lucky_color VARCHAR(50),
    mood_score INT,
    ai_generated BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_sign_date (zodiac_sign, date),
    FOREIGN KEY (zodiac_sign) REFERENCES zodiac_signs(sign) ON DELETE CASCADE
);

-- User Sessions table for session management
CREATE TABLE IF NOT EXISTS user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity Log table for security and audit
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Indexes for better performance
CREATE INDEX idx_readings_user_id ON tarot_readings(user_id);
CREATE INDEX idx_readings_date ON tarot_readings(reading_date);
CREATE INDEX idx_readings_spread ON tarot_readings(spread_type);
CREATE INDEX idx_readings_daily ON tarot_readings(is_daily);

CREATE INDEX idx_posts_author ON blog_posts(author_id);
CREATE INDEX idx_posts_slug ON blog_posts(slug);
CREATE INDEX idx_posts_status ON blog_posts(status);
CREATE INDEX idx_posts_category ON blog_posts(category);
CREATE INDEX idx_posts_published ON blog_posts(published_at);
CREATE INDEX idx_posts_featured ON blog_posts(is_featured);

CREATE INDEX idx_comments_post ON blog_comments(post_id);
CREATE INDEX idx_comments_user ON blog_comments(user_id);
CREATE INDEX idx_comments_status ON blog_comments(status);
CREATE INDEX idx_comments_parent ON blog_comments(parent_id);

CREATE INDEX idx_horoscopes_sign ON daily_horoscopes(zodiac_sign);
CREATE INDEX idx_horoscopes_date ON daily_horoscopes(date);

CREATE INDEX idx_sessions_user ON user_sessions(user_id);
CREATE INDEX idx_sessions_expires ON user_sessions(expires_at);

CREATE INDEX idx_activity_user ON activity_logs(user_id);
CREATE INDEX idx_activity_action ON activity_logs(action);
CREATE INDEX idx_activity_table ON activity_logs(table_name);
CREATE INDEX idx_activity_created ON activity_logs(created_at);