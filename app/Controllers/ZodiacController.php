<?php
/**
 * Zodiac Controller
 * Handle zodiac sign pages and horoscope readings
 */

class ZodiacController extends BaseController
{
    private $zodiacModel;
    private $aiService;
    
    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->zodiacModel = new ZodiacSign($database);
        $this->aiService = new AIService();
    }
    
    /**
     * Display all zodiac signs
     */
    public function index($params = [])
    {
        try {
            $zodiacSigns = $this->zodiacModel->findAll();
            
            // Get today's readings for all signs
            $todayReadings = [];
            foreach ($zodiacSigns as $sign) {
                $reading = $this->zodiacModel->getTodayReading($sign['id']);
                if ($reading) {
                    $todayReadings[$sign['id']] = $reading;
                }
            }
            
            $data = [
                'page_title' => 'Burç Yorumları - Günlük, Haftalık, Aylık',
                'meta_description' => 'Tüm burçlar için günlük, haftalık ve aylık burç yorumları. AI destekli kişiselleştirilmiş astroloji rehberi.',
                'zodiac_signs' => $zodiacSigns,
                'today_readings' => $todayReadings
            ];
            
            $this->view('zodiac.index', $data);
            
        } catch (Exception $e) {
            error_log('Zodiac Index Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display specific zodiac sign page
     */
    public function show($params = [])
    {
        $slug = $params['sign'] ?? '';
        
        if (empty($slug)) {
            $this->redirect('/zodiac', 'Geçersiz burç seçimi.', 'error');
        }
        
        try {
            $sign = $this->zodiacModel->findBySlug($slug);
            
            if (!$sign) {
                $this->view('errors.404');
                return;
            }
            
            // Get all reading types for this sign
            $dailyReading = $this->zodiacModel->getTodayReading($sign['id']);
            $weeklyReading = $this->zodiacModel->getWeeklyReading($sign['id']);
            $monthlyReading = $this->zodiacModel->getMonthlyReading($sign['id']);
            
            // Generate readings if they don't exist
            if (!$dailyReading) {
                $dailyReading = $this->generateAndSaveReading($sign, 'daily');
            }
            
            if (!$weeklyReading) {
                $weeklyReading = $this->generateAndSaveReading($sign, 'weekly');
            }
            
            if (!$monthlyReading) {
                $monthlyReading = $this->generateAndSaveReading($sign, 'monthly');
            }
            
            // Get other zodiac signs for navigation
            $allSigns = $this->zodiacModel->findAll();
            
            $data = [
                'page_title' => $sign['name'] . ' Burcu Yorumu - ' . date('Y'),
                'meta_description' => $sign['name'] . ' burcu için günlük, haftalık ve aylık astroloji yorumları. ' . $sign['date_range'] . ' tarihleri arası.',
                'meta_keywords' => $sign['name'] . ' burcu, astroloji, burç yorumu, ' . $sign['element'] . ' elementi',
                'sign' => $sign,
                'daily_reading' => $dailyReading,
                'weekly_reading' => $weeklyReading,
                'monthly_reading' => $monthlyReading,
                'all_signs' => $allSigns
            ];
            
            $this->view('zodiac.show', $data);
            
        } catch (Exception $e) {
            error_log('Zodiac Show Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display daily horoscope
     */
    public function daily($params = [])
    {
        $slug = $params['sign'] ?? '';
        
        if (empty($slug)) {
            $this->redirect('/zodiac', 'Geçersiz burç seçimi.', 'error');
        }
        
        try {
            $sign = $this->zodiacModel->findBySlug($slug);
            
            if (!$sign) {
                $this->view('errors.404');
                return;
            }
            
            $dailyReading = $this->zodiacModel->getTodayReading($sign['id']);
            
            if (!$dailyReading) {
                $dailyReading = $this->generateAndSaveReading($sign, 'daily');
            }
            
            $data = [
                'page_title' => $sign['name'] . ' Burcu Günlük Yorumu - ' . date('d.m.Y'),
                'meta_description' => $sign['name'] . ' burcu için ' . date('d.m.Y') . ' tarihli günlük astroloji yorumu ve önerileri.',
                'sign' => $sign,
                'reading' => $dailyReading,
                'reading_type' => 'daily'
            ];
            
            $this->view('zodiac.reading', $data);
            
        } catch (Exception $e) {
            error_log('Zodiac Daily Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display weekly horoscope
     */
    public function weekly($params = [])
    {
        $slug = $params['sign'] ?? '';
        
        if (empty($slug)) {
            $this->redirect('/zodiac', 'Geçersiz burç seçimi.', 'error');
        }
        
        try {
            $sign = $this->zodiacModel->findBySlug($slug);
            
            if (!$sign) {
                $this->view('errors.404');
                return;
            }
            
            $weeklyReading = $this->zodiacModel->getWeeklyReading($sign['id']);
            
            if (!$weeklyReading) {
                $weeklyReading = $this->generateAndSaveReading($sign, 'weekly');
            }
            
            $weekStart = date('d.m.Y', strtotime('monday this week'));
            $weekEnd = date('d.m.Y', strtotime('sunday this week'));
            
            $data = [
                'page_title' => $sign['name'] . ' Burcu Haftalık Yorumu - ' . $weekStart . ' / ' . $weekEnd,
                'meta_description' => $sign['name'] . ' burcu için bu hafta astroloji yorumu ve önerileri.',
                'sign' => $sign,
                'reading' => $weeklyReading,
                'reading_type' => 'weekly',
                'week_range' => $weekStart . ' - ' . $weekEnd
            ];
            
            $this->view('zodiac.reading', $data);
            
        } catch (Exception $e) {
            error_log('Zodiac Weekly Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display monthly horoscope
     */
    public function monthly($params = [])
    {
        $slug = $params['sign'] ?? '';
        
        if (empty($slug)) {
            $this->redirect('/zodiac', 'Geçersiz burç seçimi.', 'error');
        }
        
        try {
            $sign = $this->zodiacModel->findBySlug($slug);
            
            if (!$sign) {
                $this->view('errors.404');
                return;
            }
            
            $monthlyReading = $this->zodiacModel->getMonthlyReading($sign['id']);
            
            if (!$monthlyReading) {
                $monthlyReading = $this->generateAndSaveReading($sign, 'monthly');
            }
            
            $monthName = date('F Y');
            $monthNameTr = $this->getMonthNameTurkish(date('n'));
            
            $data = [
                'page_title' => $sign['name'] . ' Burcu Aylık Yorumu - ' . $monthNameTr . ' ' . date('Y'),
                'meta_description' => $sign['name'] . ' burcu için ' . $monthNameTr . ' ' . date('Y') . ' aylık astroloji yorumu ve önerileri.',
                'sign' => $sign,
                'reading' => $monthlyReading,
                'reading_type' => 'monthly',
                'month_name' => $monthNameTr . ' ' . date('Y')
            ];
            
            $this->view('zodiac.reading', $data);
            
        } catch (Exception $e) {
            error_log('Zodiac Monthly Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display compatibility between two signs
     */
    public function compatibility($params = [])
    {
        $sign1Slug = $params['sign1'] ?? '';
        $sign2Slug = $params['sign2'] ?? '';
        
        if (empty($sign1Slug) || empty($sign2Slug)) {
            $this->redirect('/zodiac', 'Geçersiz burç seçimi.', 'error');
        }
        
        try {
            $sign1 = $this->zodiacModel->findBySlug($sign1Slug);
            $sign2 = $this->zodiacModel->findBySlug($sign2Slug);
            
            if (!$sign1 || !$sign2) {
                $this->view('errors.404');
                return;
            }
            
            // Get or generate compatibility analysis
            $compatibility = $this->zodiacModel->getCompatibility($sign1['id'], $sign2['id']);
            
            // Generate AI analysis
            $aiAnalysis = null;
            try {
                $aiResult = $this->aiService->generateCompatibilityReading($sign1['name'], $sign2['name']);
                $aiAnalysis = $aiResult;
            } catch (Exception $e) {
                error_log('AI Compatibility Error: ' . $e->getMessage());
            }
            
            $data = [
                'page_title' => $sign1['name'] . ' ve ' . $sign2['name'] . ' Uyumluluğu',
                'meta_description' => $sign1['name'] . ' ve ' . $sign2['name'] . ' burçları arasındaki aşk, arkadaşlık ve iş uyumluluğu analizi.',
                'sign1' => $sign1,
                'sign2' => $sign2,
                'compatibility' => $compatibility,
                'ai_analysis' => $aiAnalysis
            ];
            
            $this->view('zodiac.compatibility', $data);
            
        } catch (Exception $e) {
            error_log('Compatibility Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Generate and save reading using AI
     */
    private function generateAndSaveReading($sign, $type)
    {
        try {
            $aiResult = $this->aiService->generateZodiacReading($sign['name'], $type);
            
            $readingId = $this->zodiacModel->createReading(
                $sign['id'],
                $type,
                $aiResult['content'],
                $aiResult['scores'],
                $aiResult['provider']
            );
            
            // Get the created reading
            $reading = $this->db->query('SELECT * FROM zodiac_readings WHERE id = :id')->bind(':id', $readingId)->fetch();
            
            return $reading;
            
        } catch (Exception $e) {
            error_log('Generate Reading Error: ' . $e->getMessage());
            
            // Return a fallback reading
            return [
                'id' => 0,
                'content' => 'Bugün için özel bir yorumunuz hazırlanıyor. Lütfen daha sonra tekrar kontrol edin.',
                'love_score' => 70,
                'career_score' => 70,
                'health_score' => 70,
                'money_score' => 70,
                'generated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    /**
     * Get Turkish month name
     */
    private function getMonthNameTurkish($month)
    {
        $months = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];
        
        return $months[$month] ?? 'Ocak';
    }
}