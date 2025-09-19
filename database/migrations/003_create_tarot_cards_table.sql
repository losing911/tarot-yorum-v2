-- Tarot Cards table with complete deck information
CREATE TABLE IF NOT EXISTS tarot_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_tr VARCHAR(100) NOT NULL,
    card_number INT,
    suit VARCHAR(50),
    suit_tr VARCHAR(50),
    type ENUM('major', 'minor') NOT NULL,
    image_path VARCHAR(255),
    description TEXT,
    description_tr TEXT,
    upright_meaning TEXT,
    upright_meaning_tr TEXT,
    reversed_meaning TEXT,
    reversed_meaning_tr TEXT,
    keywords JSON,
    keywords_tr JSON,
    element VARCHAR(20),
    planet VARCHAR(30),
    zodiac_sign VARCHAR(20),
    numerology INT,
    symbolism TEXT,
    love_meaning TEXT,
    love_meaning_tr TEXT,
    career_meaning TEXT,
    career_meaning_tr TEXT,
    health_meaning TEXT,
    health_meaning_tr TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Major Arcana cards
INSERT INTO tarot_cards (name, name_tr, card_number, type, description_tr, upright_meaning_tr, reversed_meaning_tr, keywords_tr, love_meaning_tr, career_meaning_tr, health_meaning_tr) VALUES
('The Fool', 'Deli', 0, 'major', 'Yeni başlangıçlar, masumiyet, spontanlık', 'Yeni bir yolculuğun başlangıcı, risk alma', 'Düşüncesiz davranışlar, dikkatsizlik', '["yeni başlangıç", "macera", "risk"]', 'Yeni bir aşk, masum duygular', 'Yeni kariyer fırsatları, girişimcilik', 'Genel sağlık durumu iyi'),
('The Magician', 'Büyücü', 1, 'major', 'Güç, beceri, konsantrasyon', 'Hedeflerinize ulaşma gücü, yaratıcılık', 'Manipülasyon, güç kötüye kullanımı', '["güç", "beceri", "manifestasyon"]', 'Çekicilik, karizma', 'Liderlik, başarı', 'Enerji dolu'),
('The High Priestess', 'Yüksek Rahibe', 2, 'major', 'Sezgi, gizem, bilinçaltı', 'İç sesinizi dinleme, gizli bilgi', 'Sırların açığa çıkması, yanılgı', '["sezgi", "gizem", "bilgelik"]', 'Platonik aşk, ruhsal bağ', 'Danışmanlık, araştırma', 'Zihinsel sağlık'),
('The Empress', 'İmparatoriçe', 3, 'major', 'Bereket, annelik, doğa', 'Bolluk, yaratıcılık, büyüme', 'Bağımlılık, boşa harcama', '["bereket", "annelik", "yaratıcılık"]', 'Aşkta bereket, hamilelik', 'Yaratıcı işler, sanat', 'Doğurganlık'),
('The Emperor', 'İmparator', 4, 'major', 'Otorite, yapı, kontrol', 'Liderlik, istikrar, düzen', 'Zorbalık, katılık', '["otorite", "liderlik", "düzen"]', 'Kararlı ilişki, evlilik', 'Yöneticilik, otorite', 'Güçlü yapı'),
('The Hierophant', 'Aziz', 5, 'major', 'Gelenek, eğitim, manevi rehberlik', 'Öğretim, geleneksel değerler', 'Dogmatizm, baskı', '["gelenek", "eğitim", "rehberlik"]', 'Geleneksel evlilik', 'Eğitim, öğretmenlik', 'Geleneksel tedavi'),
('The Lovers', 'Aşıklar', 6, 'major', 'Aşk, seçim, birliktelik', 'Gerçek aşk, uyum, birleşme', 'İlişki sorunları, ayrılık', '["aşk", "seçim", "uyum"]', 'Gerçek aşk, ruh eşi', 'İş ortaklığı', 'Uyumlu yaşam'),
('The Chariot', 'Savaş Arabası', 7, 'major', 'Zafer, irade gücü, kontrol', 'Başarı, kararlılık, ilerleme', 'Kontrolsüzlük, agresiflik', '["zafer", "kontrol", "ilerleme"]', 'İlişkide ilerleme', 'Kariyer ilerlemesi', 'Güçlü irade'),
('Strength', 'Güç', 8, 'major', 'İç güç, cesaret, sabır', 'Manevi güç, öz kontrol', 'Şüphe, korku, zayıflık', '["güç", "cesaret", "sabır"]', 'İlişkide güç', 'Zorlukları aşma', 'İç güç'),
('The Hermit', 'Münzevi', 9, 'major', 'İç arayış, yalnızlık, bilgelik', 'İç gözlem, rehberlik arayışı', 'İzolasyon, kaybolmuşluk', '["arayış", "bilgelik", "yalnızlık"]', 'Tek başına zaman', 'Mentörlük arama', 'İç huzur'),
('Wheel of Fortune', 'Kader Çarkı', 10, 'major', 'Kader, şans, döngüler', 'Şans dönüşü, pozitif değişim', 'Kötü şans, kontrolsüzlük', '["kader", "şans", "değişim"]', 'Kadersel karşılaşma', 'Şanslı fırsatlar', 'Döngüsel sağlık'),
('Justice', 'Adalet', 11, 'major', 'Adalet, denge, sorumluluk', 'Adil karar, denge', 'Adaletsizlik, önyargı', '["adalet", "denge", "karar"]', 'Adil ilişki', 'Hukuki işler', 'Dengeye dönüş'),
('The Hanged Man', 'Asılan Adam', 12, 'major', 'Fedakarlık, farklı bakış açısı', 'Sabır, yeni perspektif', 'Direnç, gecikme', '["fedakarlık", "sabır", "perspektif"]', 'Fedakarlık', 'Bekleme dönemi', 'Sabırlı iyileşme'),
('Death', 'Ölüm', 13, 'major', 'Dönüşüm, son, yeni başlangıç', 'Radikal değişim, yenilenme', 'Değişime direnç, stagnasyon', '["dönüşüm", "son", "yenilenme"]', 'İlişki sonu/başlangıcı', 'Kariyer değişimi', 'Yenilenme'),
('Temperance', 'Ölçülülük', 14, 'major', 'Denge, uyum, sabır', 'Harmoni, ölçülülük', 'Dengesizlik, aşırılık', '["denge", "uyum", "sabır"]', 'Uyumlu ilişki', 'İş-yaşam dengesi', 'Sağlıklı denge'),
('The Devil', 'Şeytan', 15, 'major', 'Bağımlılık, materyal esaret', 'Bağımlılık farkındalığı', 'Esaret, illüzyon', '["bağımlılık", "esaret", "materyal"]', 'Tutkulu ama sağlıksız', 'Para odaklı seçimler', 'Bağımlılık'),
('The Tower', 'Kule', 16, 'major', 'Ani değişim, yıkım, aydınlanma', 'Ani gerçekler, temizlik', 'Kaos, yıkım', '["ani değişim", "yıkım", "aydınlanma"]', 'İlişki krizi', 'İş kaybı/değişim', 'Ani hastalık'),
('The Star', 'Yıldız', 17, 'major', 'Umut, ilham, ruhsal rehberlik', 'Umut, iyileşme, ilham', 'Karamsarlık, hayal kırıklığı', '["umut", "ilham", "rehberlik"]', 'Umutlu aşk', 'İlham verici iş', 'İyileşme'),
('The Moon', 'Ay', 18, 'major', 'İllüzyon, korku, bilinçaltı', 'Sezgiler, gizli gerçekler', 'Yanılgı, korku, kaygı', '["illüzyon", "korku", "sezgi"]', 'Belirsiz duygular', 'Gizli düşmanlar', 'Zihinsel karışıklık'),
('The Sun', 'Güneş', 19, 'major', 'Mutluluk, başarı, canlılık', 'Neşe, başarı, pozitif enerji', 'Aşırı iyimserlik, ego', '["mutluluk", "başarı", "enerji"]', 'Mutlu ilişki', 'Başarılı kariyer', 'Güçlü sağlık'),
('Judgement', 'Mahkeme', 20, 'major', 'Değerlendirme, affetme, yenilenme', 'İkinci şans, affetme', 'Kendini yargılama, suçluluk', '["değerlendirme", "affetme", "yenilenme"]', 'İkinci şans', 'Kariyer değerlendirmesi', 'İyileşme süreci'),
('The World', 'Dünya', 21, 'major', 'Tamamlanma, başarı, bütünlük', 'Hedeflere ulaşma, tatmin', 'Eksiklik, tamamlanmamışlık', '["tamamlanma", "başarı", "bütünlük"]', 'Mükemmel birliktelik', 'Kariyer zirvesi', 'Tam sağlık');

-- Indexes
CREATE INDEX idx_tarot_type ON tarot_cards(type);
CREATE INDEX idx_tarot_suit ON tarot_cards(suit);
CREATE INDEX idx_tarot_number ON tarot_cards(card_number);