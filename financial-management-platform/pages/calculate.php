<?php
// JSON dosyasını yükle
$json_data = file_get_contents("../assets/json/USD_prices.json");
$usd_prices = json_decode($json_data, true);

// Tarihe göre fiyatı bulma fonksiyonu


function get_usd_price_by_date($date) {
    global $usd_prices;

    foreach ($usd_prices as $price_entry) {
        if (strpos($price_entry['Date'], $date) !== false) { // Ay/Yıl kontrolü
            return $price_entry['USD_Price'];
        }
    }
    return null; // Fiyat bulunamadı
}

// Formdan gelen veriler
$start_date = $_POST['start_date'];
$investment_amount = floatval($_POST['investment_amount']);

// Başlangıç fiyatını bul
$start_price = get_usd_price_by_date($start_date);

// Güncel fiyatı sabit olarak tanımlayabilirsiniz (örneğin, 35.0 TL)
$current_price = 35.0;

if ($start_price) {
    // Hesaplama işlemleri
    $units = $investment_amount / $start_price;
    $current_value = $units * $current_price;

    echo "<h2>Sonuç:</h2>";
    echo "<p>Başlangıç Tarihi: $start_date</p>";
    echo "<p>Başlangıç Fiyatı: $start_price TL</p>";
    echo "<p>Şu Anki Fiyat: $current_price TL</p>";
    echo "<p>Yatırımınız şu anda: <strong>" . round($current_value, 2) . " TL</strong> değerinde.</p>";
} else {
    echo "<p>Seçilen tarih için dolar fiyatı bulunamadı.</p>";
}
?>
