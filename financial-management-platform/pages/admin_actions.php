<?php
session_start();
$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);


// Kullanıcı bilgilerini ID'ye göre sıralama
$stmt = $conn->prepare("SELECT * FROM users ORDER BY id ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $user_id = $_POST['user_id'];

    try {
        if ($action === 'update_role') {
            $role = $_POST['role'];
            $stmt = $conn->prepare("UPDATE users SET role = :role WHERE id = :id");
            $stmt->execute(['role' => $role, 'id' => $user_id]);
            $_SESSION['message'] = "Kullanıcı rolü başarıyla güncellendi.";
        } elseif ($action === 'edit_user') {
            $ad = $_POST['ad'];
            $soyad = $_POST['soyad'];
            $kullanici_adi = $_POST['kullanici_adi'];
            $email = $_POST['email'];
            $hakkimda = $_POST['hakkimda'];
            $stmt = $conn->prepare("UPDATE users SET ad = :ad, soyad = :soyad, kullanici_adi = :kullanici_adi, email = :email, hakkimda = :hakkimda WHERE id = :id");
            $stmt->execute([
                'ad' => $ad,
                'soyad' => $soyad,
                'kullanici_adi' => $kullanici_adi,
                'email' => $email,
                'hakkimda' => $hakkimda,
                'id' => $user_id
            ]);
            $_SESSION['message'] = "Kullanıcı bilgileri başarıyla düzenlendi.";
        } elseif ($action === 'delete_user') {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $_SESSION['message'] = "Kullanıcı başarıyla silindi.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Bir hata oluştu: " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
    
        try {
            if ($action === 'add_user') {
                // Kullanıcı ekleme
                $ad = $_POST['ad'];
                $soyad = $_POST['soyad'];
                $kullanici_adi = $_POST['kullanici_adi'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $role = $_POST['role'];
    
                $stmt = $conn->prepare("INSERT INTO users (ad, soyad, kullanici_adi, email, parola, role) VALUES (:ad, :soyad, :kullanici_adi, :email, :password, :role)");
                $stmt->execute([
                    'ad' => $ad,
                    'soyad' => $soyad,
                    'kullanici_adi' => $kullanici_adi,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role
                ]);
    
                $_SESSION['message'] = "Kullanıcı başarıyla eklendi.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Bir hata oluştu: " . $e->getMessage();
        }
    
        header('Location: admin.php');
        exit();
    }
    
    header('Location: admin.php');
    exit();
}

?>