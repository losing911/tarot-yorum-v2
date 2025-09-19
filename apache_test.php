<?php
echo "<h1>🔮 Tarot-Yorum.fun Test</h1>";
echo "<p>Apache Test Sayfası</p>";
echo "<p>Zaman: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Versiyonu: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

if (isset($_SERVER['REQUEST_URI'])) {
    echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";
}

echo "<h2>Dosya Durumu:</h2>";
echo "<p>index.php var mı: " . (file_exists('index.php') ? '✅ Evet' : '❌ Hayır') . "</p>";
echo "<p>.htaccess var mı: " . (file_exists('.htaccess') ? '✅ Evet' : '❌ Hayır') . "</p>";

echo "<h2>Linkler:</h2>";
echo "<a href='/tarotv2/index.php'>index.php direkt</a><br>";
echo "<a href='/tarotv2/simple_home.php'>simple_home.php</a><br>";
echo "<a href='/tarotv2/test.php'>test.php</a><br>";
?>