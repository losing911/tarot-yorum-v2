<?php
/**
 * Tarot Controller
 * Handle tarot card readings and spreads
 */

class TarotController extends BaseController
{
    private $tarotModel;
    private $aiService;
    
    public function __construct(Database $database)
    {
        parent::__construct($database);
        $this->tarotModel = new TarotReading($database);
        $this->aiService = new AIService();
    }
    
    /**
     * Display tarot reading selection page
     */
    public function index($params = [])
    {
        try {
            // Get available tarot spreads
            $spreads = $this->tarotModel->getAvailableSpreads();
            
            // Get recent readings for logged in users
            $recentReadings = [];
            if ($this->isLoggedIn()) {
                $recentReadings = $this->tarotModel->getUserRecentReadings($_SESSION['user_id'], 5);
            }
            
            $data = [
                'page_title' => 'Tarot Falı - AI Destekli Kart Yorumu',
                'meta_description' => 'Profesyonel tarot falı ile geleceğinizi keşfedin. Celtic Cross, 3 kart ve Yes/No yayılımları ile AI destekli yorumlar.',
                'meta_keywords' => 'tarot falı, tarot kartları, fal bak, gelecek, ai tarot, celtic cross',
                'spreads' => $spreads,
                'recent_readings' => $recentReadings
            ];
            
            $this->view('tarot.index', $data);
            
        } catch (Exception $e) {
            error_log('Tarot Index Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display card selection for a specific spread
     */
    public function spread($params = [])
    {
        $spreadType = $params['spread'] ?? '';
        
        if (empty($spreadType)) {
            $this->redirect('/tarot', 'Geçersiz tarot yayılımı.', 'error');
        }
        
        try {
            $spread = $this->tarotModel->getSpreadByType($spreadType);
            
            if (!$spread) {
                $this->view('errors.404');
                return;
            }
            
            // Get all tarot cards
            $cards = $this->tarotModel->getAllCards();
            
            $data = [
                'page_title' => $spread['name'] . ' Tarot Falı',
                'meta_description' => $spread['description'],
                'spread' => $spread,
                'cards' => $cards,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('tarot.spread', $data);
            
        } catch (Exception $e) {
            error_log('Tarot Spread Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Process tarot reading request
     */
    public function reading($params = [])
    {
        if (!$this->verifyCSRFToken()) {
            $this->jsonResponse(['error' => 'Geçersiz güvenlik token\'ı'], 403);
        }
        
        $data = $this->request->getJSON();
        
        if (!$data || !isset($data['spread_type']) || !isset($data['selected_cards']) || !isset($data['question'])) {
            $this->jsonResponse(['error' => 'Eksik veri'], 400);
        }
        
        try {
            $spreadType = $data['spread_type'];
            $selectedCards = $data['selected_cards'];
            $question = trim($data['question']);
            $userId = $this->isLoggedIn() ? $_SESSION['user_id'] : null;
            
            // Validate spread type
            $spread = $this->tarotModel->getSpreadByType($spreadType);
            if (!$spread) {
                $this->jsonResponse(['error' => 'Geçersiz yayılım türü'], 400);
            }
            
            // Validate card count
            if (count($selectedCards) !== $spread['card_count']) {
                $this->jsonResponse(['error' => 'Yanlış kart sayısı'], 400);
            }
            
            // Get card details
            $cardDetails = [];
            foreach ($selectedCards as $cardId) {
                $card = $this->tarotModel->getCardById($cardId);
                if ($card) {
                    $cardDetails[] = $card;
                }
            }
            
            if (count($cardDetails) !== count($selectedCards)) {
                $this->jsonResponse(['error' => 'Geçersiz kart seçimi'], 400);
            }
            
            // Generate AI interpretation
            $interpretation = $this->aiService->generateTarotReading(
                $cardDetails,
                $spread,
                $question
            );
            
            // Save reading to database
            $readingId = $this->tarotModel->createReading([
                'user_id' => $userId,
                'spread_type' => $spreadType,
                'question' => $question,
                'selected_cards' => json_encode($selectedCards),
                'interpretation' => $interpretation['content'],
                'ai_provider' => $interpretation['provider'],
                'session_id' => session_id()
            ]);
            
            $this->jsonResponse([
                'success' => true,
                'reading_id' => $readingId,
                'interpretation' => $interpretation,
                'cards' => $cardDetails,
                'spread' => $spread
            ]);
            
        } catch (Exception $e) {
            error_log('Tarot Reading Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Okuma oluşturulamadı'], 500);
        }
    }
    
    /**
     * Display reading result
     */
    public function result($params = [])
    {
        $readingId = $params['id'] ?? '';
        
        if (empty($readingId)) {
            $this->redirect('/tarot', 'Geçersiz okuma ID\'si.', 'error');
        }
        
        try {
            $reading = $this->tarotModel->getReadingById($readingId);
            
            if (!$reading) {
                $this->view('errors.404');
                return;
            }
            
            // Check if user has access to this reading
            if ($reading['user_id'] && (!$this->isLoggedIn() || $_SESSION['user_id'] !== $reading['user_id'])) {
                // Allow access via session for anonymous readings
                if ($reading['session_id'] !== session_id()) {
                    $this->view('errors.403');
                    return;
                }
            }
            
            // Get spread details
            $spread = $this->tarotModel->getSpreadByType($reading['spread_type']);
            
            // Get card details
            $selectedCards = json_decode($reading['selected_cards'], true);
            $cardDetails = [];
            foreach ($selectedCards as $cardId) {
                $card = $this->tarotModel->getCardById($cardId);
                if ($card) {
                    $cardDetails[] = $card;
                }
            }
            
            $data = [
                'page_title' => 'Tarot Falı Sonucu - ' . $spread['name'],
                'meta_description' => 'Tarot falı sonucunuz hazır. AI destekli profesyonel kart yorumu.',
                'reading' => $reading,
                'spread' => $spread,
                'cards' => $cardDetails
            ];
            
            $this->view('tarot.result', $data);
            
        } catch (Exception $e) {
            error_log('Tarot Result Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * Display user's reading history
     */
    public function history($params = [])
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login', 'Bu sayfaya erişmek için giriş yapmalısınız.', 'warning');
        }
        
        try {
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $limit = 12;
            $offset = ($page - 1) * $limit;
            
            $readings = $this->tarotModel->getUserReadings($_SESSION['user_id'], $limit, $offset);
            $totalReadings = $this->tarotModel->getUserReadingCount($_SESSION['user_id']);
            $totalPages = ceil($totalReadings / $limit);
            
            $data = [
                'page_title' => 'Tarot Geçmişim - Tüm Okumalarım',
                'meta_description' => 'Geçmişteki tüm tarot okumalarınızı görüntüleyin ve tekrar inceleyin.',
                'readings' => $readings,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_readings' => $totalReadings
            ];
            
            $this->view('tarot.history', $data);
            
        } catch (Exception $e) {
            error_log('Tarot History Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
    
    /**
     * API endpoint to get all cards
     */
    public function getAllCards($params = [])
    {
        try {
            $cards = $this->tarotModel->getAllCards();
            $this->jsonResponse(['cards' => $cards]);
            
        } catch (Exception $e) {
            error_log('Get All Cards Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Kartlar alınamadı'], 500);
        }
    }
    
    /**
     * API endpoint to get random cards
     */
    public function getRandomCards($params = [])
    {
        $count = isset($params['count']) ? (int)$params['count'] : 3;
        $count = max(1, min(78, $count)); // Limit between 1 and 78
        
        try {
            $cards = $this->tarotModel->getRandomCards($count);
            $this->jsonResponse(['cards' => $cards]);
            
        } catch (Exception $e) {
            error_log('Get Random Cards Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Kartlar alınamadı'], 500);
        }
    }
    
    /**
     * API endpoint to get card details
     */
    public function getCard($params = [])
    {
        $cardId = $params['id'] ?? '';
        
        if (empty($cardId)) {
            $this->jsonResponse(['error' => 'Kart ID\'si gerekli'], 400);
        }
        
        try {
            $card = $this->tarotModel->getCardById($cardId);
            
            if (!$card) {
                $this->jsonResponse(['error' => 'Kart bulunamadı'], 404);
            }
            
            $this->jsonResponse(['card' => $card]);
            
        } catch (Exception $e) {
            error_log('Get Card Error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Kart bilgisi alınamadı'], 500);
        }
    }
    
    /**
     * Daily card for registered users
     */
    public function dailyCard($params = [])
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login', 'Günlük kart için giriş yapmalısınız.', 'warning');
        }
        
        try {
            $userId = $_SESSION['user_id'];
            $today = date('Y-m-d');
            
            // Check if user already has a daily card for today
            $dailyCard = $this->tarotModel->getDailyCard($userId, $today);
            
            if (!$dailyCard) {
                // Generate new daily card
                $randomCard = $this->tarotModel->getRandomCards(1)[0];
                $interpretation = $this->aiService->generateDailyCardReading($randomCard);
                
                $dailyCard = $this->tarotModel->createDailyCard([
                    'user_id' => $userId,
                    'card_id' => $randomCard['id'],
                    'date' => $today,
                    'interpretation' => $interpretation['content'],
                    'ai_provider' => $interpretation['provider']
                ]);
                
                $dailyCard['card'] = $randomCard;
                $dailyCard['interpretation'] = $interpretation['content'];
            } else {
                $dailyCard['card'] = $this->tarotModel->getCardById($dailyCard['card_id']);
            }
            
            $data = [
                'page_title' => 'Günlük Tarot Kartım - ' . date('d.m.Y'),
                'meta_description' => 'Bugün için özel tarot kartınız ve AI destekli yorumu.',
                'daily_card' => $dailyCard
            ];
            
            $this->view('tarot.daily', $data);
            
        } catch (Exception $e) {
            error_log('Daily Card Error: ' . $e->getMessage());
            $this->view('errors.500');
        }
    }
}