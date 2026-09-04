<?php
session_start();

// Kullanıcı oturum açmış mı kontrol et
if (!isset($_SESSION['user_id'])) {
    header('Location: login_signup.php');
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

// Kullanıcı bilgilerini sorgula
$stmt = $conn->prepare("SELECT ad, soyad, kullanici_adi, email, role, hakkimda FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Eğer kullanıcı bulunamazsa
if (!$user) {
    echo "Kullanıcı bilgileri bulunamadı.";
    exit();
}

// Başarı veya hata mesajlarını kontrol et
if (isset($_SESSION['update_success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['update_success'] . '</div>';
    unset($_SESSION['update_success']);
}
if (isset($_SESSION['update_error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['update_error'] . '</div>';
    unset($_SESSION['update_error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Finans Rotası</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css" rel="stylesheet" />
    <link href="../assets/css/demo.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Ek CSS -->
    <style>
    /* Genel Kart Tasarımı */
    .card {
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card-header {
        background: linear-gradient(135deg,#1a237e, #5c6bc0);
        color: #fff;
        text-align: center;
        padding: 1.5rem;
        font-size: 1.25rem;
        font-weight: bold;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    /* Form Elemanları */
    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border: 1px solid #ccc;
        transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #3949ab;
        box-shadow: 0 0 8px rgba(57, 73, 171, 0.5);
    }

    /* Buton Tasarımı */
    .btn-primary {
        background: linear-gradient(135deg, #3949ab, #5c6bc0);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        border-radius: 50px;
        transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5c6bc0, #7986cb);
        box-shadow: 0 4px 12px rgba(57, 73, 171, 0.5);
    }

    .btn-block {
        width: 100%;
    }

    /* Hakkımda Kısmı */
    .card-body p {
        font-size: 1rem;
        font-style: normal;
        color: #333;
        margin-bottom: 0;
    }

    /* Mobil ve Küçük Ekranlar İçin */
    @media (max-width: 768px) {
        .card-header {
            font-size: 1rem;
            padding: 1rem;
        }

        .form-control {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .btn-primary {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }

    /* Daha Büyük Ekranlar İçin */
    @media (min-width: 992px) {
        .form-control {
            font-size: 1.1rem;
        }

        .btn-primary {
            font-size: 1.1rem;
        }
    }
</style>

</head>

<body>
<div class="wrapper">
                <div class="sidebar" data-image="../assets/img/" >
                    <div class="sidebar-wrapper">
                        <div class="logo">
                            <a href="index.php" class="simple-text">
                                <img src="../assets/img/logo.png" alt="Finans Rotası Logo" style="width: 50px; height: 50px; margin-right: 10px;">
                            FİNANS ROTASI
                            </a>
                        </div>
                        <ul class="nav">
                        <li class="nav-item">
                                <a class="nav-link" href="index.php">
                                <i class="fas fa-wallet"></i>
                                    <p>GELİR-GİDER</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="aylik_harcama.php">
                                <i class="fas fa-chart-pie"></i>
                                    <p>AYLIK HARCAMA ANALİZİ</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="yatirim_performansi.php">
                                <i class="fa-solid fa-chart-simple"></i>
                                    <p>YATIRIM PERFORMANSI <br>
                                        İZLEME</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="gecmis_yatirim_hesaplama.php">
                                <i class="fas fa-calculator"></i>
                                    <p>GEÇMİŞ YATIRIM <br>
                                        GETİRİSİ HESAPLAMA</p>
                                </a>
                            </li>

                            <li>
                                <a class="nav-link" href="./kullanıcı_verileri.php">
                                <i class="fas fa-chart-bar"></i>
                                    <p>ROTA ÖNERİLERİ</p>
                                </a>
                            </li>

                            <li>
                                <a class="nav-link" href="./user.php">
                                <i class="fas fa-user-circle"></i>
                                    <p>PROFİLİM</p>
                                </a>
                            </li>

                            <li>
                                <a class="nav-link" href="./hakkimizda.php">
                                <i class="fas fa-info-circle"></i>
                                    <p>HAKKIMIZDA</p>
                                </a>
                            </li>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="admin.php">
                                        <i class="fas fa-user-shield"></i>
                                        <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Admin Paneli</p>
                                    </a>
                                </li>
                            <?php endif; ?>


                            
                            
                        </ul>
                    </div>
                </div>
        <div class="main-panel">
            <nav class="navbar navbar-expand-lg" color-on-scroll="500">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item"><span id="currentDate" class="nav-link"></span></li>
                            <li class="nav-item"><a class="nav-link" href="user.php"><span class="no-icon">Hesabım</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="./logout.php"><span class="no-icon">Çıkış</span></a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <script>
                const today = new Date();
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('currentDate').textContent = today.toLocaleDateString('tr-TR', options);
            </script>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Profil Bilgileri -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title" style="color: white;">Profil Bilgilerim</h4>
                </div>

                    <div class="card-body">
                        <form method="POST" action="update_profile.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ad</label>
                                        <input type="text" class="form-control rounded-pill" name="ad" value="<?php echo htmlspecialchars($user['ad']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Soyad</label>
                                        <input type="text" class="form-control rounded-pill" name="soyad" value="<?php echo htmlspecialchars($user['soyad']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kullanıcı Adı</label>
                                        <input type="text" class="form-control rounded-pill" name="kullanici_adi" value="<?php echo htmlspecialchars($user['kullanici_adi']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">E-posta Adresi</label>
                                        <input type="email" class="form-control rounded-pill" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Hakkımda</label>
                                <textarea rows="4" class="form-control rounded-3" name="hakkimda" placeholder="Hakkımda kısmını doldurun"><?php echo htmlspecialchars($user['hakkimda']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4 rounded-pill">Profili Güncelle</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hakkımda Kısmı -->
            <div class="col-md-4">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white text-center">
            <h5>Hakkımda</h5>
        </div>
        <div class="card-body text-center">
            <p class="description">
                <?php 
                echo $user['hakkimda'] !== null && $user['hakkimda'] !== '' 
                    ? htmlspecialchars($user['hakkimda']) 
                    : 'Bilgi bulunmamaktadır.'; 
                ?>
            </p>
        </div>
    </div>
</div>

        </div>
    </div>
</div>



            <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright text-center">
                        <a href="http://www.creative-tim.com">Finans Rotası</a> tarafından tasarlandı
                        <script>document.write(new Date().getFullYear())</script>
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
