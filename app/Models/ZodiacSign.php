<?php
/**
 * Zodiac Sign Model
 * Handle zodiac sign related operations
 */

class ZodiacSign extends BaseModel
{
    protected $table = 'zodiac_signs';
    protected $fillable = ['name', 'slug', 'symbol', 'element', 'quality', 'ruling_planet', 'date_range', 'description', 'image'];
    
    /**
     * Find zodiac sign by slug
     */
    public function findBySlug($slug)
    {
        return $this->findWhere('slug', $slug);
    }
    
    /**
     * Get today's reading for a zodiac sign
     */
    public function getTodayReading($signId)
    {
        $today = date('Y-m-d');
        
        $this->db->query(
            'SELECT * FROM daily_horoscopes 
             WHERE zodiac_sign_id = :sign_id AND reading_date = :date
             ORDER BY created_at DESC LIMIT 1'
        );
        $this->db->bind(':sign_id', $signId);
        $this->db->bind(':date', $today);
        
        return $this->db->fetch();
    }
    
    /**
     * Get weekly reading for a zodiac sign
     */
    public function getWeeklyReading($signId)
    {
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        
        $this->db->query(
            'SELECT * FROM zodiac_readings 
             WHERE zodiac_sign_id = :sign_id AND reading_type = "weekly" AND reading_date = :date
             ORDER BY generated_at DESC LIMIT 1'
        );
        $this->db->bind(':sign_id', $signId);
        $this->db->bind(':date', $weekStart);
        
        return $this->db->fetch();
    }
    
    /**
     * Get monthly reading for a zodiac sign
     */
    public function getMonthlyReading($signId)
    {
        $monthStart = date('Y-m-01');
        
        $this->db->query(
            'SELECT * FROM zodiac_readings 
             WHERE zodiac_sign_id = :sign_id AND reading_type = "monthly" AND reading_date = :date
             ORDER BY generated_at DESC LIMIT 1'
        );
        $this->db->bind(':sign_id', $signId);
        $this->db->bind(':date', $monthStart);
        
        return $this->db->fetch();
    }
    
    /**
     * Create or update zodiac reading
     */
    public function createReading($signId, $type, $content, $scores = [], $aiProvider = 'openai')
    {
        $date = $this->getReadingDate($type);
        
        // Check if reading already exists
        $this->db->query(
            'SELECT id FROM zodiac_readings 
             WHERE zodiac_sign_id = :sign_id AND reading_type = :type AND reading_date = :date'
        );
        $this->db->bind(':sign_id', $signId);
        $this->db->bind(':type', $type);
        $this->db->bind(':date', $date);
        
        $existing = $this->db->fetch();
        
        if ($existing) {
            // Update existing reading
            $this->db->query(
                'UPDATE zodiac_readings SET 
                 content = :content, love_score = :love_score, career_score = :career_score,
                 health_score = :health_score, money_score = :money_score, ai_provider = :ai_provider,
                 generated_at = CURRENT_TIMESTAMP
                 WHERE id = :id'
            );
            $this->db->bind(':content', $content);
            $this->db->bind(':love_score', $scores['love_score'] ?? 0);
            $this->db->bind(':career_score', $scores['career_score'] ?? 0);
            $this->db->bind(':health_score', $scores['health_score'] ?? 0);
            $this->db->bind(':money_score', $scores['money_score'] ?? 0);
            $this->db->bind(':ai_provider', $aiProvider);
            $this->db->bind(':id', $existing['id']);
            
            $this->db->execute();
            return $existing['id'];
        } else {
            // Create new reading
            $this->db->query(
                'INSERT INTO zodiac_readings 
                 (zodiac_sign_id, reading_type, reading_date, content, love_score, career_score, health_score, money_score, ai_provider)
                 VALUES (:sign_id, :type, :date, :content, :love_score, :career_score, :health_score, :money_score, :ai_provider)'
            );
            $this->db->bind(':sign_id', $signId);
            $this->db->bind(':type', $type);
            $this->db->bind(':date', $date);
            $this->db->bind(':content', $content);
            $this->db->bind(':love_score', $scores['love_score'] ?? 0);
            $this->db->bind(':career_score', $scores['career_score'] ?? 0);
            $this->db->bind(':health_score', $scores['health_score'] ?? 0);
            $this->db->bind(':money_score', $scores['money_score'] ?? 0);
            $this->db->bind(':ai_provider', $aiProvider);
            
            $this->db->execute();
            return $this->db->lastInsertId();
        }
    }
    
    /**
     * Get reading date based on type
     */
    private function getReadingDate($type)
    {
        switch ($type) {
            case 'daily':
                return date('Y-m-d');
            case 'weekly':
                return date('Y-m-d', strtotime('monday this week'));
            case 'monthly':
                return date('Y-m-01');
            default:
                return date('Y-m-d');
        }
    }
    
    /**
     * Get compatibility between two signs
     */
    public function getCompatibility($sign1Id, $sign2Id)
    {
        // Simple compatibility matrix - in real implementation, this could be more sophisticated
        $compatibilityMatrix = [
            'koc' => ['aslan' => 90, 'yay' => 85, 'ikizler' => 80, 'kova' => 75],
            'boga' => ['basak' => 90, 'oglak' => 85, 'yengec' => 80, 'balik' => 75],
            'ikizler' => ['terazi' => 90, 'kova' => 85, 'koc' => 80, 'aslan' => 75],
            'yengec' => ['akrep' => 90, 'balik' => 85, 'boga' => 80, 'basak' => 75],
            'aslan' => ['koc' => 90, 'yay' => 85, 'ikizler' => 80, 'terazi' => 75],
            'basak' => ['boga' => 90, 'oglak' => 85, 'yengec' => 80, 'akrep' => 75],
            'terazi' => ['ikizler' => 90, 'kova' => 85, 'aslan' => 80, 'yay' => 75],
            'akrep' => ['yengec' => 90, 'balik' => 85, 'basak' => 80, 'oglak' => 75],
            'yay' => ['koc' => 90, 'aslan' => 85, 'terazi' => 80, 'kova' => 75],
            'oglak' => ['boga' => 90, 'basak' => 85, 'akrep' => 80, 'balik' => 75],
            'kova' => ['ikizler' => 90, 'terazi' => 85, 'yay' => 80, 'koc' => 75],
            'balik' => ['yengec' => 90, 'akrep' => 85, 'boga' => 80, 'oglak' => 75]
        ];
        
        $sign1 = $this->find($sign1Id);
        $sign2 = $this->find($sign2Id);
        
        if (!$sign1 || !$sign2) {
            return null;
        }
        
        $score = $compatibilityMatrix[$sign1['slug']][$sign2['slug']] ?? 60;
        
        return [
            'sign1' => $sign1,
            'sign2' => $sign2,
            'compatibility_score' => $score
        ];
    }
}