<?php
session_start();

// Tüm oturum verilerini temizle
session_unset();
session_destroy();

// Tarayıcı önbelleğini temizlemek için HTTP başlıkları
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxy'ler
header("Location: ana_sayfa.php"); // Giriş sayfasına yönlendirme
exit();
