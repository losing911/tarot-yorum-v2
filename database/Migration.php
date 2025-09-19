<?php
/**
 * Database Migration System
 * Create and manage database schema
 */

class Migration
{
    private $db;
    
    public function __construct(Database $database)
    {
        $this->db = $database;
    }
    
    /**
     * Run all migrations
     */
    public function runMigrations()
    {
        $this->createMigrationsTable();
        
        $migrations = [
            '001_create_users_table',
            '002_create_zodiac_signs_table',
            '003_create_zodiac_readings_table',
            '004_create_tarot_cards_table',
            '005_create_tarot_readings_table',
            '006_create_blog_categories_table',
            '007_create_blog_posts_table',
            '008_create_blog_tags_table',
            '009_create_post_tags_table',
            '010_create_comments_table',
            '011_create_admin_settings_table',
            '012_create_seo_settings_table',
            '013_create_user_sessions_table',
            '014_create_email_verifications_table',
            '015_create_password_resets_table',
            '016_insert_initial_data'
        ];
        
        foreach ($migrations as $migration) {
            if (!$this->migrationExists($migration)) {
                $this->runMigration($migration);
                $this->recordMigration($migration);
                echo "Migration $migration completed\n";
            }
        }
        
        echo "All migrations completed successfully!\n";
    }
    
    /**
     * Create migrations tracking table
     */
    private function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Check if migration already exists
     */
    private function migrationExists($migration)
    {
        $this->db->query('SELECT id FROM migrations WHERE migration = :migration');
        $this->db->bind(':migration', $migration);
        return $this->db->fetch() !== false;
    }
    
    /**
     * Record completed migration
     */
    private function recordMigration($migration)
    {
        $this->db->query('INSERT INTO migrations (migration) VALUES (:migration)');
        $this->db->bind(':migration', $migration);
        $this->db->execute();
    }
    
    /**
     * Run specific migration
     */
    private function runMigration($migration)
    {
        switch ($migration) {
            case '001_create_users_table':
                $this->createUsersTable();
                break;
            case '002_create_zodiac_signs_table':
                $this->createZodiacSignsTable();
                break;
            case '003_create_zodiac_readings_table':
                $this->createZodiacReadingsTable();
                break;
            case '004_create_tarot_cards_table':
                $this->createTarotCardsTable();
                break;
            case '005_create_tarot_readings_table':
                $this->createTarotReadingsTable();
                break;
            case '006_create_blog_categories_table':
                $this->createBlogCategoriesTable();
                break;
            case '007_create_blog_posts_table':
                $this->createBlogPostsTable();
                break;
            case '008_create_blog_tags_table':
                $this->createBlogTagsTable();
                break;
            case '009_create_post_tags_table':
                $this->createPostTagsTable();
                break;
            case '010_create_comments_table':
                $this->createCommentsTable();
                break;
            case '011_create_admin_settings_table':
                $this->createAdminSettingsTable();
                break;
            case '012_create_seo_settings_table':
                $this->createSeoSettingsTable();
                break;
            case '013_create_user_sessions_table':
                $this->createUserSessionsTable();
                break;
            case '014_create_email_verifications_table':
                $this->createEmailVerificationsTable();
                break;
            case '015_create_password_resets_table':
                $this->createPasswordResetsTable();
                break;
            case '016_insert_initial_data':
                $this->insertInitialData();
                break;
        }
    }
    
    /**
     * Create users table
     */
    private function createUsersTable()
    {
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            birth_date DATE,
            birth_time TIME,
            birth_place VARCHAR(255),
            zodiac_sign VARCHAR(20),
            avatar VARCHAR(255),
            role ENUM('user', 'admin') DEFAULT 'user',
            is_active BOOLEAN DEFAULT TRUE,
            is_email_verified BOOLEAN DEFAULT FALSE,
            email_verified_at TIMESTAMP NULL,
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_username (username),
            INDEX idx_zodiac_sign (zodiac_sign)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create zodiac signs table
     */
    private function createZodiacSignsTable()
    {
        $sql = "CREATE TABLE zodiac_signs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            slug VARCHAR(50) UNIQUE NOT NULL,
            symbol VARCHAR(10),
            element VARCHAR(20),
            quality VARCHAR(20),
            ruling_planet VARCHAR(50),
            date_range VARCHAR(50),
            description TEXT,
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create zodiac readings table
     */
    private function createZodiacReadingsTable()
    {
        $sql = "CREATE TABLE zodiac_readings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            zodiac_sign_id INT NOT NULL,
            reading_type ENUM('daily', 'weekly', 'monthly') NOT NULL,
            reading_date DATE NOT NULL,
            content TEXT NOT NULL,
            love_score INT DEFAULT 0,
            career_score INT DEFAULT 0,
            health_score INT DEFAULT 0,
            money_score INT DEFAULT 0,
            ai_provider VARCHAR(20),
            generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (zodiac_sign_id) REFERENCES zodiac_signs(id) ON DELETE CASCADE,
            UNIQUE KEY unique_reading (zodiac_sign_id, reading_type, reading_date),
            INDEX idx_reading_date (reading_date),
            INDEX idx_reading_type (reading_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create tarot cards table
     */
    private function createTarotCardsTable()
    {
        $sql = "CREATE TABLE tarot_cards (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE NOT NULL,
            suit VARCHAR(20),
            number INT,
            card_type ENUM('major_arcana', 'minor_arcana') NOT NULL,
            upright_meaning TEXT,
            reversed_meaning TEXT,
            description TEXT,
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_suit (suit),
            INDEX idx_type (card_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create tarot readings table
     */
    private function createTarotReadingsTable()
    {
        $sql = "CREATE TABLE tarot_readings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            question TEXT,
            spread_type VARCHAR(50) NOT NULL,
            cards_drawn JSON NOT NULL,
            interpretation TEXT NOT NULL,
            is_public BOOLEAN DEFAULT FALSE,
            ai_provider VARCHAR(20),
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_created_at (created_at),
            INDEX idx_spread_type (spread_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create blog categories table
     */
    private function createBlogCategoriesTable()
    {
        $sql = "CREATE TABLE blog_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE NOT NULL,
            description TEXT,
            image VARCHAR(255),
            seo_title VARCHAR(255),
            seo_description TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_slug (slug),
            INDEX idx_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create blog posts table
     */
    private function createBlogPostsTable()
    {
        $sql = "CREATE TABLE blog_posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            category_id INT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            excerpt TEXT,
            content TEXT NOT NULL,
            featured_image VARCHAR(255),
            status ENUM('draft', 'published', 'private') DEFAULT 'draft',
            is_featured BOOLEAN DEFAULT FALSE,
            view_count INT DEFAULT 0,
            like_count INT DEFAULT 0,
            seo_title VARCHAR(255),
            seo_description TEXT,
            seo_keywords VARCHAR(500),
            published_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL,
            INDEX idx_slug (slug),
            INDEX idx_status (status),
            INDEX idx_published_at (published_at),
            INDEX idx_featured (is_featured),
            FULLTEXT KEY ft_content (title, content, excerpt)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create blog tags table
     */
    private function createBlogTagsTable()
    {
        $sql = "CREATE TABLE blog_tags (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) UNIQUE NOT NULL,
            description TEXT,
            usage_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_slug (slug),
            INDEX idx_usage_count (usage_count)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create post tags relationship table
     */
    private function createPostTagsTable()
    {
        $sql = "CREATE TABLE post_tags (
            post_id INT NOT NULL,
            tag_id INT NOT NULL,
            PRIMARY KEY (post_id, tag_id),
            FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create comments table
     */
    private function createCommentsTable()
    {
        $sql = "CREATE TABLE comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT,
            parent_id INT NULL,
            author_name VARCHAR(100),
            author_email VARCHAR(255),
            content TEXT NOT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
            INDEX idx_post_id (post_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create admin settings table
     */
    private function createAdminSettingsTable()
    {
        $sql = "CREATE TABLE admin_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            setting_type ENUM('string', 'text', 'boolean', 'integer', 'json') DEFAULT 'string',
            description TEXT,
            is_public BOOLEAN DEFAULT FALSE,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_key (setting_key),
            INDEX idx_public (is_public)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create SEO settings table
     */
    private function createSeoSettingsTable()
    {
        $sql = "CREATE TABLE seo_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page_type VARCHAR(50) NOT NULL,
            page_identifier VARCHAR(255),
            meta_title VARCHAR(255),
            meta_description TEXT,
            meta_keywords VARCHAR(500),
            og_title VARCHAR(255),
            og_description TEXT,
            og_image VARCHAR(255),
            twitter_title VARCHAR(255),
            twitter_description TEXT,
            twitter_image VARCHAR(255),
            canonical_url VARCHAR(500),
            robots VARCHAR(100) DEFAULT 'index,follow',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_page (page_type, page_identifier),
            INDEX idx_page_type (page_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create user sessions table
     */
    private function createUserSessionsTable()
    {
        $sql = "CREATE TABLE user_sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            payload TEXT NOT NULL,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            INDEX idx_last_activity (last_activity)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create email verifications table
     */
    private function createEmailVerificationsTable()
    {
        $sql = "CREATE TABLE email_verifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(255) UNIQUE NOT NULL,
            expires_at TIMESTAMP NOT NULL,
            used_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_token (token),
            INDEX idx_expires_at (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Create password resets table
     */
    private function createPasswordResetsTable()
    {
        $sql = "CREATE TABLE password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            token VARCHAR(255) UNIQUE NOT NULL,
            expires_at TIMESTAMP NOT NULL,
            used_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_token (token),
            INDEX idx_expires_at (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->exec($sql);
    }
    
    /**
     * Insert initial data
     */
    private function insertInitialData()
    {
        // Insert zodiac signs
        $zodiacSigns = [
            ['name' => 'Koç', 'slug' => 'koc', 'symbol' => '♈', 'element' => 'Ateş', 'quality' => 'Kardinal', 'ruling_planet' => 'Mars', 'date_range' => '21 Mart - 20 Nisan'],
            ['name' => 'Boğa', 'slug' => 'boga', 'symbol' => '♉', 'element' => 'Toprak', 'quality' => 'Sabit', 'ruling_planet' => 'Venüs', 'date_range' => '21 Nisan - 21 Mayıs'],
            ['name' => 'İkizler', 'slug' => 'ikizler', 'symbol' => '♊', 'element' => 'Hava', 'quality' => 'Değişken', 'ruling_planet' => 'Merkür', 'date_range' => '22 Mayıs - 21 Haziran'],
            ['name' => 'Yengeç', 'slug' => 'yengec', 'symbol' => '♋', 'element' => 'Su', 'quality' => 'Kardinal', 'ruling_planet' => 'Ay', 'date_range' => '22 Haziran - 22 Temmuz'],
            ['name' => 'Aslan', 'slug' => 'aslan', 'symbol' => '♌', 'element' => 'Ateş', 'quality' => 'Sabit', 'ruling_planet' => 'Güneş', 'date_range' => '23 Temmuz - 22 Ağustos'],
            ['name' => 'Başak', 'slug' => 'basak', 'symbol' => '♍', 'element' => 'Toprak', 'quality' => 'Değişken', 'ruling_planet' => 'Merkür', 'date_range' => '23 Ağustos - 22 Eylül'],
            ['name' => 'Terazi', 'slug' => 'terazi', 'symbol' => '♎', 'element' => 'Hava', 'quality' => 'Kardinal', 'ruling_planet' => 'Venüs', 'date_range' => '23 Eylül - 22 Ekim'],
            ['name' => 'Akrep', 'slug' => 'akrep', 'symbol' => '♏', 'element' => 'Su', 'quality' => 'Sabit', 'ruling_planet' => 'Mars/Plüton', 'date_range' => '23 Ekim - 21 Kasım'],
            ['name' => 'Yay', 'slug' => 'yay', 'symbol' => '♐', 'element' => 'Ateş', 'quality' => 'Değişken', 'ruling_planet' => 'Jüpiter', 'date_range' => '22 Kasım - 21 Aralık'],
            ['name' => 'Oğlak', 'slug' => 'oglak', 'symbol' => '♑', 'element' => 'Toprak', 'quality' => 'Kardinal', 'ruling_planet' => 'Satürn', 'date_range' => '22 Aralık - 20 Ocak'],
            ['name' => 'Kova', 'slug' => 'kova', 'symbol' => '♒', 'element' => 'Hava', 'quality' => 'Sabit', 'ruling_planet' => 'Satürn/Uranüs', 'date_range' => '21 Ocak - 19 Şubat'],
            ['name' => 'Balık', 'slug' => 'balik', 'symbol' => '♓', 'element' => 'Su', 'quality' => 'Değişken', 'ruling_planet' => 'Jüpiter/Neptün', 'date_range' => '20 Şubat - 20 Mart']
        ];
        
        foreach ($zodiacSigns as $sign) {
            $this->db->query(
                'INSERT INTO zodiac_signs (name, slug, symbol, element, quality, ruling_planet, date_range) 
                 VALUES (:name, :slug, :symbol, :element, :quality, :ruling_planet, :date_range)'
            );
            $this->db->bind(':name', $sign['name']);
            $this->db->bind(':slug', $sign['slug']);
            $this->db->bind(':symbol', $sign['symbol']);
            $this->db->bind(':element', $sign['element']);
            $this->db->bind(':quality', $sign['quality']);
            $this->db->bind(':ruling_planet', $sign['ruling_planet']);
            $this->db->bind(':date_range', $sign['date_range']);
            $this->db->execute();
        }
        
        // Insert default admin user
        $this->db->query(
            'INSERT INTO users (username, email, password, first_name, last_name, role, is_active, is_email_verified) 
             VALUES (:username, :email, :password, :first_name, :last_name, :role, :is_active, :is_email_verified)'
        );
        $this->db->bind(':username', 'admin');
        $this->db->bind(':email', 'admin@tarot-yorum.fun');
        $this->db->bind(':password', password_hash('admin123', PASSWORD_BCRYPT));
        $this->db->bind(':first_name', 'Admin');
        $this->db->bind(':last_name', 'User');
        $this->db->bind(':role', 'admin');
        $this->db->bind(':is_active', 1);
        $this->db->bind(':is_email_verified', 1);
        $this->db->execute();
        
        // Insert default settings
        $defaultSettings = [
            ['ai_provider', 'openai', 'string', 'Active AI provider (openai or gemini)'],
            ['openai_api_key', '', 'string', 'OpenAI API Key'],
            ['gemini_api_key', '', 'string', 'Google Gemini API Key'],
            ['google_analytics_id', '', 'string', 'Google Analytics 4 Tracking ID'],
            ['google_adsense_client', '', 'string', 'Google AdSense Client ID'],
            ['adsense_enabled', '1', 'boolean', 'Enable Google AdSense ads'],
            ['site_maintenance', '0', 'boolean', 'Site maintenance mode'],
            ['user_registration', '1', 'boolean', 'Allow user registration'],
            ['email_verification', '1', 'boolean', 'Require email verification'],
            ['max_tarot_readings_per_day', '5', 'integer', 'Maximum tarot readings per day for free users']
        ];
        
        foreach ($defaultSettings as $setting) {
            $this->db->query(
                'INSERT INTO admin_settings (setting_key, setting_value, setting_type, description) 
                 VALUES (:key, :value, :type, :description)'
            );
            $this->db->bind(':key', $setting[0]);
            $this->db->bind(':value', $setting[1]);
            $this->db->bind(':type', $setting[2]);
            $this->db->bind(':description', $setting[3]);
            $this->db->execute();
        }
        
        // Insert blog categories
        $categories = [
            ['name' => 'Astroloji', 'slug' => 'astroloji', 'description' => 'Astroloji ile ilgili makaleler'],
            ['name' => 'Tarot', 'slug' => 'tarot', 'description' => 'Tarot kartları ve falcılık'],
            ['name' => 'Burç Yorumları', 'slug' => 'burc-yorumlari', 'description' => 'Günlük, haftalık ve aylık burç yorumları'],
            ['name' => 'Rüya Tabirleri', 'slug' => 'ruya-tabirleri', 'description' => 'Rüyaların anlamları ve tabirleri'],
            ['name' => 'Numeroloji', 'slug' => 'numeroloji', 'description' => 'Sayıların gizemi ve anlamları']
        ];
        
        foreach ($categories as $category) {
            $this->db->query(
                'INSERT INTO blog_categories (name, slug, description) 
                 VALUES (:name, :slug, :description)'
            );
            $this->db->bind(':name', $category['name']);
            $this->db->bind(':slug', $category['slug']);
            $this->db->bind(':description', $category['description']);
            $this->db->execute();
        }
    }
}