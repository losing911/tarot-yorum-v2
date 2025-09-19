-- Insert sample data for testing

-- Create admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role, status, email_verified, created_at) VALUES
('admin', 'admin@tarot-yorum.fun', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin', 'active', TRUE, NOW()),
('demo_user', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Demo User', 'user', 'active', TRUE, NOW());

-- Insert sample blog posts
INSERT INTO blog_posts (author_id, title, slug, excerpt, content, category, tags, status, published_at, created_at) VALUES
(1, 'Tarot Falında Aşk Kartları', 'tarot-falinda-ask-kartlari', 'Tarot falında aşk ile ilgili kartların anlamları ve yorumları hakkında detaylı bilgi.', 
'Tarot falında aşk konusu en çok merak edilen konulardan biridir. Aşıklar kartı, İmparatoriçe, ve diğer aşk kartlarının anlamlarını keşfedin...', 
'ask', '["tarot", "aşk", "kartlar"]', 'published', NOW(), NOW()),

(1, 'Günlük Burç Yorumlarının Önemi', 'gunluk-burc-yorumlarinin-onemi', 'Günlük burç yorumları nasıl hazırlanır ve hayatımıza nasıl yön verir?',
'Astroloji ve günlük burç yorumları hakkında bilmeniz gerekenler. Burçların günlük etkileri ve önerileri...', 
'astroloji', '["burç", "astroloji", "günlük"]', 'published', NOW(), NOW()),

(1, 'Major Arcana Kartlarının Gizemi', 'major-arcana-kartlarinin-gizemi', 'Tarot destinin en güçlü kartları olan Major Arcana hakkında everything.',
'22 Major Arcana kartının her birinin detaylı anlamları, sembolik önemi ve hayatımızdaki yansımaları...', 
'tarot', '["major arcana", "tarot", "anlam"]', 'published', NOW(), NOW());

-- Insert sample comments
INSERT INTO blog_comments (post_id, user_id, author_name, author_email, content, status, created_at) VALUES
(1, 2, 'Demo User', 'demo@example.com', 'Çok faydalı bir yazı olmuş. Aşıklar kartının anlamını daha iyi anladım.', 'approved', NOW()),
(1, NULL, 'Misafir Kullanıcı', 'misafir@example.com', 'Bu konuda daha detaylı bilgi verebilir misiniz?', 'pending', NOW()),
(2, 2, 'Demo User', 'demo@example.com', 'Günlük burç yorumlarını takip ediyorum, gerçekten etkili oluyor.', 'approved', NOW());

-- Insert sample tarot readings
INSERT INTO tarot_readings (user_id, spread_type, question, cards_drawn, interpretation, reading_date, is_daily, created_at) VALUES
(2, 'single_card', 'Bugün nasıl bir gün geçireceğim?', '[{"id": 1, "name": "The Magician", "position": "upright"}]', 
'Bugün güçlü ve yaratıcı enerjiniz yüksek. Hedeflerinize odaklanın.', CURDATE(), TRUE, NOW()),

(2, 'three_card', 'Aşk hayatım nasıl olacak?', 
'[{"id": 6, "name": "The Lovers", "position": "upright"}, {"id": 3, "name": "The Empress", "position": "upright"}, {"id": 19, "name": "The Sun", "position": "upright"}]',
'Aşk hayatınızda çok olumlu gelişmeler var. Gerçek aşk ve mutluluk sizi bekliyor.', CURDATE(), FALSE, NOW());

-- Insert today's horoscopes for all signs
INSERT INTO daily_horoscopes (zodiac_sign, date, general, love, career, health, lucky_number, lucky_color, mood_score) VALUES
('koc', CURDATE(), 'Bugün enerjiniz yüksek olacak. Yeni projelere başlamak için ideal bir gün.', 'Aşk hayatınızda heyecan verici gelişmeler olabilir.', 'İş yerinde liderlik özellikleriniz öne çıkacak.', 'Fiziksel aktivite yapmanız sağlığınız için faydalı.', 7, 'Kırmızı', 8),
('boga', CURDATE(), 'Sabır ve kararlılığınız bugün size fayda sağlayacak.', 'İlişkinizde istikrar ve huzur hakim olacak.', 'Mali konularda dikkatli olmanız gerekiyor.', 'Beslenme alışkanlıklarınızı gözden geçirin.', 15, 'Yeşil', 7),
('ikizler', CURDATE(), 'İletişim beceriniz bugün öne çıkacak. Sosyal olun.', 'Flört etme şansınız yüksek.', 'Yaratıcı projeler için uygun zaman.', 'Zihinsel yorgunluk hissedebilirsiniz.', 23, 'Sarı', 8),
('yengec', CURDATE(), 'Duygusal yoğunluk yaşayabilirsiniz. Sakin kalın.', 'Aile desteği aşk hayatınızda önemli.', 'Ev temelli işler için uygun gün.', 'Su içmeyi ihmal etmeyin.', 11, 'Beyaz', 6),
('aslan', CURDATE(), 'Bugün parlayacağınız bir gün. Kendinizi ifade edin.', 'Romantik jestler karşılık bulacak.', 'Yaratıcı projeleriniz ilgi çekecek.', 'Kalp sağlığınıza dikkat edin.', 19, 'Altın', 9),
('basak', CURDATE(), 'Detaylara odaklanmanız bugün önemli. Analitik olun.', 'İlişkinizde pratik konular ön plana çıkacak.', 'Organizasyon beceriniz takdir görecek.', 'Düzenli beslenmeye özen gösterin.', 6, 'Lacivert', 7),
('terazi', CURDATE(), 'Denge arayışınız bugün öne çıkacak. Uyum sağlayın.', 'Partnerinizle güzel anlar yaşayacaksınız.', 'Estetik konularda başarılı olacaksınız.', 'Zihin-beden dengesini koruyun.', 24, 'Mavi', 8),
('akrep', CURDATE(), 'Derin dönüşümler yaşayabilirsiniz. Kendinizi keşfedin.', 'Tutkulu anlar sizi bekliyor.', 'Gizli bilgiler ortaya çıkabilir.', 'Detoks yapmanız faydalı olacak.', 13, 'Bordo', 7),
('yay', CURDATE(), 'Macera dolu bir gün sizi bekliyor. Açık fikirli olun.', 'Uzun mesafe ilişki şansı var.', 'Eğitim ve öğretim konularında başarı.', 'Hareket etmeye ihtiyacınız var.', 21, 'Mor', 9),
('oglak', CURDATE(), 'Disiplinli yaklaşımınız bugün size fayda sağlayacak.', 'Ciddi ilişki adımları atabilirsiniz.', 'Otorite pozisyonunuz güçlenecek.', 'Kemik sağlığınıza dikkat edin.', 8, 'Siyah', 6),
('kova', CURDATE(), 'Özgün fikirleriniz bugün öne çıkacak. Farklı olun.', 'Arkadaşlık temelli aşk gelişebilir.', 'Teknoloji odaklı işler için ideal gün.', 'Sinir sisteminizi rahatlatın.', 29, 'Mavi', 8),
('balik', CURDATE(), 'Sezgileriniz bugün güçlü olacak. İç sesinizi dinleyin.', 'Platonik duygular yoğunlaşabilir.', 'Sanatsal projeler için uygun zaman.', 'Ruhsal sağlığınıza önem verin.', 12, 'Deniz yeşili', 7);