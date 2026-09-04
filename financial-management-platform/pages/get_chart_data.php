<?php
header('Content-Type: application/json');
session_start();

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Kullanıcı oturum açmamış.']);
    exit();
}

$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => "Veritabanı bağlantı hatası: " . $e->getMessage()]);
    exit();
}


$user_id = $_SESSION['user_id'];

// Seçilen ayı al
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';

$whereClause = "WHERE user_id = :user_id";
$params = ['user_id' => $user_id];

// Eğer bir ay seçilmişse filtre ekle
if (!empty($selectedMonth)) {
    $whereClause .= " AND DATE_FORMAT(tarih, '%Y-%m') = :month";
    $params['month'] = $selectedMonth;
}


try {
    // Gelir verilerini sorgula
    $gelirler = $conn->prepare("SELECT alt_kategori, SUM(tutar) as toplam 
                                FROM gelir_gider 
                                WHERE kategori = 'Gelir' AND user_id = :user_id 
                                GROUP BY alt_kategori");
    $gelirler->execute(['user_id' => $user_id]);
    $gelirler = $gelirler->fetchAll(PDO::FETCH_ASSOC);

    // Gider verilerini sorgula
    $giderler = $conn->prepare("SELECT alt_kategori, SUM(tutar) as toplam 
                                FROM gelir_gider 
                                WHERE kategori = 'Gider' AND user_id = :user_id 
                                GROUP BY alt_kategori");
    $giderler->execute(['user_id' => $user_id]);
    $giderler = $giderler->fetchAll(PDO::FETCH_ASSOC);

    // Eğer veri yoksa boş döndür
    if (empty($gelirler) && empty($giderler)) {
        echo json_encode(['gelirler' => [], 'giderler' => []]);
        exit();
    }

    // Verileri JSON formatında döndür
    echo json_encode(['gelirler' => $gelirler, 'giderler' => $giderler]);
} catch (Exception $e) {
    echo json_encode(['error' => "Veri sorgulama hatası: " . $e->getMessage()]);
}
?>
