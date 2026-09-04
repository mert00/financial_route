<?php
session_start();

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

// Hata mesajı ve yönlendirme için değişkenler
$error = '';
$success = false;

// Kayıt işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $ad = $_POST['ad'] ?? '';
    $soyad = $_POST['soyad'] ?? '';
    $kullanici_adi = $_POST['kullanici_adi'] ?? '';
    $email = $_POST['email'] ?? '';
    $parola = $_POST['parola'] ?? '';

    if (empty($ad) || empty($soyad) || empty($kullanici_adi) || empty($email) || empty($parola)) {
        $error = "Tüm alanları doldurmanız gerekiyor.";
    } else {
        // Kullanıcı adı veya e-posta kontrolü
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR kullanici_adi = :kullanici_adi");
        $stmt->execute(['email' => $email, 'kullanici_adi' => $kullanici_adi]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $error = "Bu e-posta veya kullanıcı adı zaten kayıtlı.";
        } else {
            // Kullanıcıyı kaydet
            try {
                $sql = "INSERT INTO users (ad, soyad, kullanici_adi, email, parola) VALUES (:ad, :soyad, :kullanici_adi, :email, :parola)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'ad' => $ad,
                    'soyad' => $soyad,
                    'kullanici_adi' => $kullanici_adi,
                    'email' => $email,
                    'parola' => password_hash($parola, PASSWORD_DEFAULT),
                ]);
                $success = true; // Kayıt başarılı
            } catch (PDOException $e) {
                $error = "Kayıt sırasında bir hata oluştu.";
            }
        }
    }
}

// Giriş işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $parola = $_POST['parola'] ?? '';

    if (empty($email) || empty($parola)) {
        $error = "E-posta ve şifre alanlarını doldurmanız gerekiyor.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($parola, $user['parola'])) {
            // Kullanıcı oturumu başlat
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Rol bazlı yönlendirme
            $redirect = $user['role'] === 'admin' ? 'admin.php' : 'index.php';
            header("Location: $redirect");
            exit();
        } else {
            $error = "Geçersiz e-posta veya şifre.";
        }
    }
}

// Çıkış işlemi
if (isset($_GET['logout'])) {
    session_destroy();
    session_unset();

    // Tarayıcı önbelleğini engellemek için HTTP başlıklarını ayarla
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
    header("Pragma: no-cache"); // HTTP 1.0
    header("Expires: 0"); // Proxies
    header("Location: $redirect");
    exit();
}

// Oturum kontrolü - Kullanıcı giriş yapmamışsa yönlendirme
function oturum_kontrol($required_role = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login_signup.php");
        exit();
    }

    if ($required_role && $_SESSION['role'] !== $required_role) {
        header("Location: index.php");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <title>Giriş ve Kayıt Ekranı</title>
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

    <!-- CSS -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f6f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: -20px 0 50px;
        }
        .navbar {
    width: 100%;
    background-color: #1a237e;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.navbar .logo {
    font-size: 24px;
    font-weight: bold;
    color: #ffffff;
}

.navbar ul {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

.navbar ul li {
    margin: 0 15px;
}

.navbar ul li a {
    color: #ffffff;
    text-decoration: none;
    font-size: 16px;
    transition: color 0.3s;
}

.navbar ul li a:hover {
    color: #ffcc00;
}

        h1 {
            font-weight: bold;
            margin: 0;
        }

        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        span {
            font-size: 12px;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        button {
            border-radius: 20px;
            border: 1px solid #1a237e;
            background-color: #1a237e;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
        }

        .container {
            background-color: #b8b8b8;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
                        0 10px 10px rgba(0, 0, 0, 0.22);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }

            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: #1a237e; /* Koyu mavi */
            background: -webkit-linear-gradient(to right, #1a237e, #3949ab); /* Gradient koyu mavi tonları */
            background: linear-gradient(to right, #1a237e, #3949ab);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    padding: 20px;
    z-index: 1000;
    text-align: center;
    width: 300px;
    border-radius: 8px;
}

.popup p {
    font-size: 16px;
    margin-bottom: 15px;
}

.popup button {
    background-color: #1a237e;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
}

.popup button:hover {
    background-color: #3949ab;
}


        
    </style>
</head>

<body>

<!-- Navbar -->
<header class="navbar">
        <div class="logo">Finans Rotası</div>
        <ul>
            <li><a href="ana_sayfa.php">Ana Sayfa</a></li>
            
        </ul>
    </header>

    <!-- Hata Mesajı -->
<?php if (!empty($error)): ?>
    <div id="error-popup" class="popup">
        <p><?php echo htmlspecialchars($error); ?></p>
        
    </div>
<?php endif; ?>

<!-- Başarı Mesajı -->
<?php if ($success): ?>
    <div id="success-popup" class="popup">
        <p>Kayıt başarılı!</p>
        
    </div>
<?php endif; ?>

        
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form method="POST" action="">
                <h1>Hesap Oluştur</h1>
                <input type="text" name="ad" placeholder="Ad" required pattern="[a-zA-ZÇçĞğİıÖöŞşÜü\s]+" title="Sadece harf girilebilir." />
                <input type="text" name="soyad" placeholder="Soyad" required pattern="[a-zA-ZÇçĞğİıÖöŞşÜü\s]+" title="Sadece harf girilebilir." />
                <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required />
                <input type="email" name="email" placeholder="E-posta" required />
                <input type="password" name="parola" placeholder="Parola" required />
                <button type="submit" name="register">Kayıt Ol</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form method="POST" action="">
                <h1>Giriş Yap</h1>
                <input type="email" name="email" placeholder="E-posta" required />
                <input type="password" name="parola" placeholder="Parola" required />
                <button type="submit" name="login">Giriş Yap</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Hoşgeldin, Finans Rotalı!</h1>
                    <p>Bilgilerinizi görüntülemek için lütfen giriş yapın!</p>
                    <button class="ghost" id="signIn">Giriş Yap</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hoşgeldin, Finans Rotalı!</h1>
                    <p>Hesabınız yoksa yeni bir hesap oluşturmak için aşağıdaki butona tıklayınız!</p>
                    <button class="ghost" id="signUp">Kayıt Ol</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>
            Bu web sitesi Finans Rotası tarafından
            oluşturulmuştur. © 2024 Tüm Hakları Saklıdır.
        </p>
    </footer>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });

        function closePopup(popupId) {
        const popup = document.getElementById(popupId);
        if (popup) {
            popup.style.display = 'none';
        }
    }

    // Pop-up'ları otomatik kapatma işlevi
    document.addEventListener('DOMContentLoaded', () => {
        const errorPopup = document.getElementById('error-popup');
        const successPopup = document.getElementById('success-popup');
        
        if (errorPopup) {
            setTimeout(() => closePopup('error-popup'), 2000); // 3 saniye sonra hata pop-up'ını kapat
        }
        
        if (successPopup) {
            setTimeout(() => closePopup('success-popup'), 2000); // 3 saniye sonra başarı pop-up'ını kapat
        }
    });
    </script>
</body>

</html>
