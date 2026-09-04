<?php
session_start();

// Kullanıcı oturum açmış mı kontrol et
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

// Oturumdaki kullanıcı kimliği
$user_id = $_SESSION['user_id'];

// Formdan gelen verileri al
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $kullanici_adi = $_POST['kullanici_adi'];
    $email = $_POST['email'];
    $hakkimda = $_POST['hakkimda'];

    // Kullanıcı bilgilerini güncelleme sorgusu
    $sql = "UPDATE users 
            SET ad = :ad, soyad = :soyad, kullanici_adi = :kullanici_adi, email = :email, hakkimda = :hakkimda 
            WHERE id = :user_id";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
            ':ad' => $ad,
            ':soyad' => $soyad,
            ':kullanici_adi' => $kullanici_adi,
            ':email' => $email,
            ':hakkimda' => $hakkimda,
            ':user_id' => $user_id
        ]);

        // Başarı mesajını oturumda sakla
        $_SESSION['update_success'] = "Bilgileriniz başarıyla güncellendi.";
    } catch (Exception $e) {
        // Hata mesajını oturumda sakla
        $_SESSION['update_error'] = "Bilgiler güncellenirken bir hata oluştu: " . $e->getMessage();
    }

    // Kullanıcı sayfasına yönlendir
    header('Location: user.php');
    exit();
}
?>
