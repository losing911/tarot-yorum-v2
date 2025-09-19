<?php
/**
 * Home Controller
 * Handle homepage and main navigation
 */

class HomeController extends BaseController
{
    private $zodiacModel;
    private $blogModel;
    private $tarotModel;
    
    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->zodiacModel = new ZodiacSign($database);
        $this->blogModel = new BlogPost($database);
        $this->tarotModel = new TarotReading($database);
    }
    
    /**
     * Display homepage
     */
    public function index($params = [])
    {
        try {
            // Get all zodiac signs for the grid (with fallback)
            $zodiacSigns = [];
            try {
                $zodiacSigns = $this->zodiacModel->findAll();
            } catch (Exception $e) {
                error_log('Zodiac Signs Error: ' . $e->getMessage());
                // Fallback zodiac signs data
                $zodiacSigns = [
                    ['sign' => 'koc', 'name' => 'Koç', 'symbol' => '♈', 'element' => 'Ateş', 'date_range' => '21 Mart - 19 Nisan'],
                    ['sign' => 'boga', 'name' => 'Boğa', 'symbol' => '♉', 'element' => 'Toprak', 'date_range' => '20 Nisan - 20 Mayıs'],
                    ['sign' => 'ikizler', 'name' => 'İkizler', 'symbol' => '♊', 'element' => 'Hava', 'date_range' => '21 Mayıs - 20 Haziran'],
                    ['sign' => 'yengec', 'name' => 'Yengeç', 'symbol' => '♋', 'element' => 'Su', 'date_range' => '21 Haziran - 22 Temmuz'],
                    ['sign' => 'aslan', 'name' => 'Aslan', 'symbol' => '♌', 'element' => 'Ateş', 'date_range' => '23 Temmuz - 22 Ağustos'],
                    ['sign' => 'basak', 'name' => 'Başak', 'symbol' => '♍', 'element' => 'Toprak', 'date_range' => '23 Ağustos - 22 Eylül'],
                    ['sign' => 'terazi', 'name' => 'Terazi', 'symbol' => '♎', 'element' => 'Hava', 'date_range' => '23 Eylül - 22 Ekim'],
                    ['sign' => 'akrep', 'name' => 'Akrep', 'symbol' => '♏', 'element' => 'Su', 'date_range' => '23 Ekim - 21 Kasım'],
                    ['sign' => 'yay', 'name' => 'Yay', 'symbol' => '♐', 'element' => 'Ateş', 'date_range' => '22 Kasım - 21 Aralık'],
                    ['sign' => 'oglak', 'name' => 'Oğlak', 'symbol' => '♑', 'element' => 'Toprak', 'date_range' => '22 Aralık - 19 Ocak'],
                    ['sign' => 'kova', 'name' => 'Kova', 'symbol' => '♒', 'element' => 'Hava', 'date_range' => '20 Ocak - 18 Şubat'],
                    ['sign' => 'balik', 'name' => 'Balık', 'symbol' => '♓', 'element' => 'Su', 'date_range' => '19 Şubat - 20 Mart']
                ];
            }
            
            // Get featured blog posts (with fallback)
            $featuredPosts = [];
            try {
                $featuredPosts = $this->blogModel->getFeaturedPosts(6);
            } catch (Exception $e) {
                error_log('Featured Posts Error: ' . $e->getMessage());
                $featuredPosts = [];
            }
            
            // Get recent tarot readings (with fallback)
            $recentReadings = [];
            try {
                $recentReadings = $this->tarotModel->getPublicReadings(3);
            } catch (Exception $e) {
                error_log('Recent Readings Error: ' . $e->getMessage());
                $recentReadings = [];
            }
            
            // Get today's horoscope summary (with fallback)
            $todayHoroscope = $this->getTodayHoroscopeSummary();
            
            $data = [
                'page_title' => 'Ana Sayfa',
                'meta_description' => 'Yapay zeka destekli tarot falı, günlük burç yorumları ve astroloji rehberi. Ücretsiz tarot okuma ve kişiselleştirilmiş burç analizleri.',
                'zodiac_signs' => $zodiacSigns,
                'featured_posts' => $featuredPosts,
                'recent_readings' => $recentReadings,
                'today_horoscope' => $todayHoroscope
            ];
            
            $this->view('home.index', $data);
            
        } catch (Exception $e) {
            error_log('HomePage Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Get today's horoscope summary for all signs
     */
    private function getTodayHoroscopeSummary()
    {
        $summary = [];
        
        try {
            $zodiacSigns = $this->zodiacModel->findAll();
            
            foreach ($zodiacSigns as $sign) {
                $todayReading = $this->zodiacModel->getTodayReading($sign['id']);
                
                if ($todayReading && isset($todayReading['content'])) {
                    $summary[] = [
                        'sign' => $sign,
                        'reading' => $todayReading,
                        'summary' => $this->createSummary($todayReading['content'], 100)
                    ];
                } else {
                    // Add placeholder if no reading exists
                    $summary[] = [
                        'sign' => $sign,
                        'reading' => null,
                        'summary' => 'Günlük burç yorumu yakında eklenecek.'
                    ];
                }
            }
        } catch (Exception $e) {
            // Log error and return empty array
            error_log('Horoscope Summary Error: ' . $e->getMessage());
            return [];
        }
        
        return $summary;
    }
    
    /**
     * Create a short summary from content
     */
    private function createSummary($content, $length = 100)
    {
        $content = strip_tags($content);
        if (strlen($content) <= $length) {
            return $content;
        }
        
        return substr($content, 0, $length) . '...';
    }
}