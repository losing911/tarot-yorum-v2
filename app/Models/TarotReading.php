<?php
/**
 * Tarot Reading Model
 * Handle tarot reading operations
 */

class TarotReading extends BaseModel
{
    protected $table = 'tarot_readings';
    protected $fillable = ['user_id', 'question', 'spread_type', 'cards_drawn', 'interpretation', 'is_public', 'ai_provider', 'ip_address'];
    
    /**
     * Create new tarot reading
     */
    public function createReading($data)
    {
        $data['cards_drawn'] = json_encode($data['cards_drawn']);
        $data['ip_address'] = $this->getClientIP();
        
        return $this->create($data);
    }
    
    /**
     * Get user's reading history
     */
    public function getUserReadings($userId, $limit = 20, $offset = 0)
    {
        $this->db->query(
            'SELECT * FROM tarot_readings 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC 
             LIMIT :limit OFFSET :offset'
        );
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        $readings = $this->db->fetchAll();
        
        // Decode JSON cards for each reading
        foreach ($readings as &$reading) {
            $reading['cards_drawn'] = json_decode($reading['cards_drawn'], true);
        }
        
        return $readings;
    }
    
    /**
     * Get public readings for homepage
     */
    public function getPublicReadings($limit = 5)
    {
        $this->db->query(
            'SELECT tr.*, u.username, u.first_name 
             FROM tarot_readings tr
             LEFT JOIN users u ON tr.user_id = u.id
             WHERE tr.is_public = 1
             ORDER BY tr.created_at DESC 
             LIMIT :limit'
        );
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        $readings = $this->db->fetchAll();
        
        // Decode JSON cards and create summary
        foreach ($readings as &$reading) {
            $reading['cards_drawn'] = json_decode($reading['cards_drawn'], true);
            $reading['summary'] = $this->createSummary($reading['interpretation'], 150);
            
            // Hide sensitive info for privacy
            if ($reading['username']) {
                $reading['display_name'] = $reading['first_name'] ?: $reading['username'];
            } else {
                $reading['display_name'] = 'Anonim';
            }
        }
        
        return $readings;
    }
    
    /**
     * Get reading by ID with user check
     */
    public function getReadingForUser($readingId, $userId = null)
    {
        $sql = 'SELECT tr.*, u.username, u.first_name 
                FROM tarot_readings tr
                LEFT JOIN users u ON tr.user_id = u.id
                WHERE tr.id = :id';
        
        $params = [':id' => $readingId];
        
        // If user is specified, check ownership or public status
        if ($userId !== null) {
            $sql .= ' AND (tr.user_id = :user_id OR tr.is_public = 1)';
            $params[':user_id'] = $userId;
        } else {
            $sql .= ' AND tr.is_public = 1';
        }
        
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        $reading = $this->db->fetch();
        
        if ($reading) {
            $reading['cards_drawn'] = json_decode($reading['cards_drawn'], true);
        }
        
        return $reading;
    }
    
    /**
     * Check daily reading limit for user
     */
    public function checkDailyLimit($userId = null, $ipAddress = null)
    {
        $maxReadings = MAX_DAILY_TAROT_READINGS ?? 5;
        $today = date('Y-m-d');
        
        if ($userId) {
            // Check for logged in user
            $this->db->query(
                'SELECT COUNT(*) as count FROM tarot_readings 
                 WHERE user_id = :user_id AND DATE(created_at) = :date'
            );
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':date', $today);
        } else {
            // Check for guest user by IP
            $this->db->query(
                'SELECT COUNT(*) as count FROM tarot_readings 
                 WHERE ip_address = :ip_address AND user_id IS NULL AND DATE(created_at) = :date'
            );
            $this->db->bind(':ip_address', $ipAddress);
            $this->db->bind(':date', $today);
        }
        
        $result = $this->db->fetch();
        return $result['count'] < $maxReadings;
    }
    
    /**
     * Get random tarot cards
     */
    public function getRandomCards($count = 3, $spreadType = 'three_card')
    {
        // Major Arcana cards
        $majorArcana = [
            ['id' => 0, 'name' => 'Deli', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 1, 'name' => 'Büyücü', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 2, 'name' => 'Yüksek Rahibe', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 3, 'name' => 'İmparatoriçe', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 4, 'name' => 'İmparator', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 5, 'name' => 'Hierophant', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 6, 'name' => 'Aşıklar', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 7, 'name' => 'Savaş Arabası', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 8, 'name' => 'Güç', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 9, 'name' => 'Münzevi', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 10, 'name' => 'Kader Çarkı', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 11, 'name' => 'Adalet', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 12, 'name' => 'Asılan Adam', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 13, 'name' => 'Ölüm', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 14, 'name' => 'Ölçülülük', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 15, 'name' => 'Şeytan', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 16, 'name' => 'Kule', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 17, 'name' => 'Yıldız', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 18, 'name' => 'Ay', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 19, 'name' => 'Güneş', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 20, 'name' => 'Mahkeme', 'suit' => null, 'type' => 'major_arcana'],
            ['id' => 21, 'name' => 'Dünya', 'suit' => null, 'type' => 'major_arcana']
        ];
        
        // Minor Arcana suits
        $suits = ['Kupa', 'Kılıç', 'Değnek', 'Madeni Para'];
        $minorArcana = [];
        $id = 22;
        
        foreach ($suits as $suit) {
            // Number cards 1-10
            for ($i = 1; $i <= 10; $i++) {
                $minorArcana[] = [
                    'id' => $id++,
                    'name' => $i . ' ' . $suit,
                    'suit' => $suit,
                    'type' => 'minor_arcana'
                ];
            }
            
            // Court cards
            $courtCards = ['Vale', 'Şövalye', 'Kraliçe', 'Kral'];
            foreach ($courtCards as $court) {
                $minorArcana[] = [
                    'id' => $id++,
                    'name' => $court . ' ' . $suit,
                    'suit' => $suit,
                    'type' => 'minor_arcana'
                ];
            }
        }
        
        $allCards = array_merge($majorArcana, $minorArcana);
        
        // Shuffle and select random cards
        shuffle($allCards);
        $selectedCards = array_slice($allCards, 0, $count);
        
        // Add position and reversed status based on spread type
        $positions = $this->getSpreadPositions($spreadType);
        
        foreach ($selectedCards as $index => &$card) {
            $card['position'] = $positions[$index] ?? "Kart " . ($index + 1);
            $card['reversed'] = rand(0, 1) == 1; // 50% chance of reversed
        }
        
        return $selectedCards;
    }
    
    /**
     * Get spread positions
     */
    private function getSpreadPositions($spreadType)
    {
        $spreads = [
            'single_card' => ['Günün Mesajı'],
            'three_card' => ['Geçmiş', 'Şimdi', 'Gelecek'],
            'love_spread' => ['Sen', 'Partner', 'İlişki'],
            'career_spread' => ['Mevcut Durum', 'Fırsatlar', 'Tavsiye'],
            'celtic_cross' => [
                'Mevcut Durum', 'Zorluk', 'Uzak Geçmiş', 'Yakın Geçmiş',
                'Olası Sonuç', 'Yakın Gelecek', 'Yaklaşımın', 'Dış Etkiler',
                'Umutlar ve Korkular', 'Nihai Sonuç'
            ]
        ];
        
        return $spreads[$spreadType] ?? $spreads['three_card'];
    }
    
    /**
     * Create summary from interpretation
     */
    private function createSummary($text, $length = 150)
    {
        $text = strip_tags($text);
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
    
    /**
     * Get client IP address
     */
    private function getClientIP()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }
        
        return '0.0.0.0';
    }
}