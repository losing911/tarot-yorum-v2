<?php
/**
 * AI Service
 * Handle AI API calls for OpenAI and Google Gemini
 */

class AIService
{
    private $provider;
    private $apiKey;
    private $model;
    
    public function __construct()
    {
        $this->provider = $this->getActiveProvider();
        $this->apiKey = $this->getApiKey();
        $this->model = $this->getModel();
    }
    
    /**
     * Generate zodiac horoscope
     */
    public function generateZodiacReading($signName, $type = 'daily', $date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $prompts = [
            'daily' => "Günlük burç yorumu oluştur. {$signName} burcu için {$date} tarihine özel pozitif ve umut verici bir yorum yaz. Aşk, kariyer, sağlık ve para konularında kısa tavsiyelerde bulun. Türkçe, samimi ve anlaşılır dilde yaz. 150-200 kelime olsun.",
            'weekly' => "Haftalık burç yorumu oluştur. {$signName} burcu için bu hafta için genel bir bakış yaz. Aşk, kariyer, sağlık ve para konularında detaylı tavsiyelerde bulun. Türkçe, samimi ve anlaşılır dilde yaz. 250-300 kelime olsun.",
            'monthly' => "Aylık burç yorumu oluştur. {$signName} burcu için bu ay için detaylı bir analiz yaz. Aşk, kariyer, sağlık ve para konularında kapsamlı tavsiyelerde bulun. Türkçe, samimi ve anlaşılır dilde yaz. 400-500 kelime olsun."
        ];
        
        $prompt = $prompts[$type] ?? $prompts['daily'];
        
        try {
            $response = $this->makeApiCall($prompt);
            
            // Extract scores from the response if possible
            $scores = $this->extractScores($response);
            
            return [
                'content' => $response,
                'scores' => $scores,
                'provider' => $this->provider
            ];
            
        } catch (Exception $e) {
            error_log('AI Zodiac Generation Error: ' . $e->getMessage());
            throw new Exception('Burç yorumu oluşturulamadı. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    /**
     * Generate tarot reading interpretation
     */
    public function generateTarotReading($cards, $spread, $question)
    {
        $prompt = $this->buildTarotPrompt($cards, $spread, $question);
        
        try {
            $response = $this->makeApiCall($prompt);
            
            return [
                'content' => $response,
                'provider' => $this->provider
            ];
            
        } catch (Exception $e) {
            error_log('AI Tarot Reading Error: ' . $e->getMessage());
            throw new Exception('Tarot yorumu oluşturulamadı. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    /**
     * Generate zodiac compatibility analysis
     */
    public function generateCompatibilityReading($sign1, $sign2)
    {
        $prompt = "{$sign1} ve {$sign2} burçları arasındaki uyumluluk analizi yap. Aşk, arkadaşlık ve iş ilişkileri açısından detaylı bir değerlendirme yaz. Güçlü ve zayıf yönlerini belirt. Pozitif yaklaşım sergile ve önerilerde bulun. Türkçe, samimi ve anlaşılır dilde yaz. 300-400 kelime olsun.";
        
        try {
            $response = $this->makeApiCall($prompt);
            
            // Extract compatibility score
            $score = $this->extractCompatibilityScore($response);
            
            return [
                'analysis' => $response,
                'score' => $score,
                'provider' => $this->provider
            ];
            
        } catch (Exception $e) {
            error_log('AI Compatibility Generation Error: ' . $e->getMessage());
            throw new Exception('Uyumluluk analizi oluşturulamadı. Lütfen daha sonra tekrar deneyin.');
        }
    }
    
    /**
     * Generate daily card reading
     */
    public function generateDailyCardReading($card)
    {
        $prompt = "Günlük tarot kartı için kısa ve öz bir yorum yaz.\n\n";
        $prompt .= "Kart: {$card['name']}\n";
        $prompt .= "Anlamı: {$card['meaning_upright']}\n";
        $prompt .= "Anahtar Kelimeler: {$card['keywords']}\n\n";
        $prompt .= "Bu kartın bugün için rehberlik mesajını 2-3 cümle ile açıkla. Pozitif ve cesaretlendirici ol.";
        
        try {
            $response = $this->makeApiCall($prompt);
            
            return [
                'content' => $response,
                'provider' => $this->provider
            ];
            
        } catch (Exception $e) {
            error_log('AI Daily Card Error: ' . $e->getMessage());
            return [
                'content' => "Bu kart size bugün için özel bir mesaj getiriyor. {$card['name']} kartı, {$card['meaning_upright']} Bu kartın enerjisi bugün sizinle birlikte olsun.",
                'provider' => 'fallback'
            ];
        }
    }
    
    /**
     * Build tarot prompt from cards and spread
     */
    private function buildTarotPrompt($cards, $spread, $question)
    {
        $prompt = "Tarot falı yorumu yap.\n\n";
        $prompt .= "Soru: '{$question}'\n";
        $prompt .= "Yayılım: {$spread['name']} - {$spread['description']}\n\n";
        $prompt .= "Çekilen kartlar:\n";
        
        foreach ($cards as $index => $card) {
            $position = $index + 1;
            $prompt .= "{$position}. {$card['name']} ({$card['arcana']} Arcana)\n";
            $prompt .= "   - Anlam: {$card['meaning_upright']}\n";
            $prompt .= "   - Anahtar Kelimeler: {$card['keywords']}\n\n";
        }
        
        $prompt .= "Bu kartların anlamlarını yorumlayarak soruya detaylı ve içten bir cevap ver. ";
        $prompt .= "Kartlar arasındaki bağlantıları kur ve genel bir hikaye oluştur. ";
        $prompt .= "Pozitif yaklaşım sergile ve pratik önerilerde bulun. ";
        $prompt .= "Türkçe, samimi ve anlaşılır dilde yaz. 400-500 kelime olsun.";
        
        return $prompt;
    }
    
    /**
     * Generate blog content suggestions
     */
    public function generateBlogSuggestion($topic, $type)
    {
        $prompts = [
            'title' => "Lütfen '{$topic}' konusu hakkında astroloji ve tarot blogu için çekici bir başlık öner. Başlık SEO dostu, merak uyandırıcı ve Türkçe olmalı. Sadece başlığı döndür, açıklama yapma.",
            
            'outline' => "'{$topic}' konusu için astroloji blogu yazı taslağı oluştur. Giriş, 3-4 ana bölüm ve sonuç içeren yapılı bir taslak sun. Her bölüm için kısa açıklama ekle. Türkçe olmalı.",
            
            'intro' => "'{$topic}' konulu astroloji blog yazısı için ilgi çekici bir giriş paragrafı yaz. Okuyucuyu konuya çekmeli, merak uyandırmalı ve yazının devamını okumaya teşvik etmeli. Türkçe olmalı.",
            
            'content' => "'{$topic}' hakkında 300-400 kelimelik astroloji blog yazısı içeriği oluştur. İçerik bilgilendirici, ilgi çekici ve SEO dostu olmalı. Astroloji terimleri doğru kullanılmalı. Türkçe olmalı.",
            
            'conclusion' => "'{$topic}' konulu astroloji blog yazısı için etkili bir sonuç paragrafı yaz. Yazının ana noktalarını özetlemeli ve okuyucuyu düşünmeye sevk etmeli. Türkçe olmalı.",
            
            'meta_description' => "'{$topic}' konulu astroloji blog yazısı için 150-160 karakter arası SEO meta açıklaması yaz. Anahtar kelimeler içermeli ve tıklamaya teşvik etmeli. Türkçe olmalı.",
            
            'keywords' => "'{$topic}' konulu astroloji blog yazısı için 5-8 adet SEO anahtar kelimesi öner. Kelimeler Türkçe olmalı ve virgülle ayrılmalı. Sadece anahtar kelimeleri döndür."
        ];
        
        if (!isset($prompts[$type])) {
            throw new Exception('Geçersiz öneri türü');
        }
        
        $prompt = $prompts[$type];
        
        try {
            // Try OpenAI first
            if ($this->openaiApiKey) {
                $response = $this->callOpenAI($prompt, 'gpt-4', 0.7, 500);
                return [
                    'suggestion' => $response,
                    'provider' => 'OpenAI GPT-4'
                ];
            }
            
            // Fallback to Gemini
            if ($this->geminiApiKey) {
                $response = $this->callGemini($prompt);
                return [
                    'suggestion' => $response,
                    'provider' => 'Google Gemini'
                ];
            }
            
            throw new Exception('AI servisleri kullanılamıyor');
            
        } catch (Exception $e) {
            error_log('AI Blog Suggestion Error: ' . $e->getMessage());
            throw new Exception('Blog önerisi oluşturulamadı: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate complete blog post
     */
    public function generateBlogPost($topic, $category, $keywords = [])
    {
        $keywordStr = !empty($keywords) ? implode(', ', $keywords) : '';
        
        $prompt = "Lütfen '{$topic}' konusu hakkında '{$category}' kategorisinde kapsamlı bir astroloji blog yazısı oluştur.
        
        İstenilen format:
        1. Başlık (SEO dostu, çekici)
        2. Meta açıklama (150-160 karakter)
        3. Giriş paragrafı
        4. Ana içerik (500-800 kelime, alt başlıklar ile)
        5. Sonuç paragrafı
        6. Anahtar kelimeler
        
        " . ($keywordStr ? "Bu anahtar kelimeleri kullan: {$keywordStr}" : "") . "
        
        Yazı astroloji konularında uzman bir editör tarafından yazılmış gibi olmalı. Türkçe, bilgilendirici ve SEO dostu olmalı.";
        
        try {
            if ($this->openaiApiKey) {
                $response = $this->callOpenAI($prompt, 'gpt-4', 0.7, 1500);
                return [
                    'content' => $response,
                    'provider' => 'OpenAI GPT-4'
                ];
            }
            
            if ($this->geminiApiKey) {
                $response = $this->callGemini($prompt);
                return [
                    'content' => $response,
                    'provider' => 'Google Gemini'
                ];
            }
            
            throw new Exception('AI servisleri kullanılamıyor');
            
        } catch (Exception $e) {
            error_log('AI Blog Post Error: ' . $e->getMessage());
            throw new Exception('Blog yazısı oluşturulamadı: ' . $e->getMessage());
        }
    }
    
    /**
     * Make API call to active provider
     */
    private function makeApiCall($prompt)
    {
        switch ($this->provider) {
            case 'openai':
                return $this->callOpenAI($prompt);
            case 'gemini':
                return $this->callGemini($prompt);
            default:
                throw new Exception('Geçersiz AI sağlayıcısı');
        }
    }
    
    /**
     * Call OpenAI API
     */
    private function callOpenAI($prompt)
    {
        $url = 'https://api.openai.com/v1/chat/completions';
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Sen Türkiye\'nin en iyi astroloji ve tarot uzmanısın. Pozitif, umut verici ve pratik tavsiyelerde bulunan bir danışmansın.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => AI_MAX_TOKENS,
            'temperature' => 0.7
        ];
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_error($ch)) {
            curl_close($ch);
            throw new Exception('API bağlantı hatası: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("API hatası: HTTP {$httpCode}");
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['choices'][0]['message']['content'])) {
            throw new Exception('Geçersiz API yanıtı');
        }
        
        return trim($result['choices'][0]['message']['content']);
    }
    
    /**
     * Call Google Gemini API
     */
    private function callGemini($prompt)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$this->apiKey}";
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Sen Türkiye'nin en iyi astroloji ve tarot uzmanısın. Pozitif, umut verici ve pratik tavsiyelerde bulunan bir danışmansın. " . $prompt
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 1,
                'topP' => 1,
                'maxOutputTokens' => AI_MAX_TOKENS
            ]
        ];
        
        $headers = [
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_error($ch)) {
            curl_close($ch);
            throw new Exception('API bağlantı hatası: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("API hatası: HTTP {$httpCode}");
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception('Geçersiz API yanıtı');
        }
        
        return trim($result['candidates'][0]['content']['parts'][0]['text']);
    }
    
    /**
     * Get active AI provider from settings
     */
    private function getActiveProvider()
    {
        // In a real implementation, this would come from database settings
        return AI_PROVIDER;
    }
    
    /**
     * Get API key for active provider
     */
    private function getApiKey()
    {
        switch ($this->provider) {
            case 'openai':
                return OPENAI_API_KEY;
            case 'gemini':
                return GEMINI_API_KEY;
            default:
                throw new Exception('Geçersiz AI sağlayıcısı');
        }
    }
    
    /**
     * Get model for active provider
     */
    private function getModel()
    {
        switch ($this->provider) {
            case 'openai':
                return OPENAI_MODEL;
            case 'gemini':
                return 'gemini-pro';
            default:
                return 'gpt-3.5-turbo';
        }
    }
    
    /**
     * Extract numerical scores from text response
     */
    private function extractScores($text)
    {
        $scores = [
            'love_score' => rand(60, 95),
            'career_score' => rand(60, 95),
            'health_score' => rand(60, 95),
            'money_score' => rand(60, 95)
        ];
        
        // You could implement more sophisticated score extraction here
        // by analyzing the sentiment and keywords in the response
        
        return $scores;
    }
    
    /**
     * Extract compatibility score from analysis
     */
    private function extractCompatibilityScore($text)
    {
        // Simple scoring based on positive/negative words
        $positiveWords = ['uyumlu', 'harika', 'mükemmel', 'güzel', 'iyi', 'başarılı'];
        $negativeWords = ['zor', 'zorlu', 'problemli', 'çelişkili', 'karmaşık'];
        
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($positiveWords as $word) {
            $positiveCount += substr_count(strtolower($text), $word);
        }
        
        foreach ($negativeWords as $word) {
            $negativeCount += substr_count(strtolower($text), $word);
        }
        
        // Calculate score between 40-95
        $baseScore = 70;
        $score = $baseScore + ($positiveCount * 5) - ($negativeCount * 3);
        
        return max(40, min(95, $score));
    }
}