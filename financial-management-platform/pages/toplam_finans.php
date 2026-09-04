<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Kullanıcı giriş yapmamışsa login sayfasına yönlendirme
    header("Location: login_signup.php");
    exit();
}

// Kullanıcı rolünü kontrol et
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Kullanıcı admin değilse anasayfaya yönlendirme
    header("Location: index.php");
    exit();
}


// Veritabanı Bağlantısı
$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

// Kullanıcıların toplam gelir ve giderlerini al
$query = "
SELECT 
    u.id AS id,
    u.ad AS ad,
    u.soyad AS soyad,
    u.kullanici_adi AS kullanici_adi,
    SUM(CASE WHEN kategori = 'Gelir' THEN t.tutar ELSE 0 END) AS toplam_gelir,
    SUM(CASE WHEN kategori = 'Gider' THEN t.tutar ELSE 0 END) AS toplam_gider
FROM users u
LEFT JOIN gelir_gider t ON u.id = t.user_id
GROUP BY u.id, u.ad, u.soyad, u.kullanici_adi;
";

$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Kategorilere göre toplam tutarları çekme
$query = $conn->prepare("
    SELECT alt_kategori, SUM(tutar) AS toplam_tutar
    FROM gelir_gider
    WHERE kategori = 'Gider'
    GROUP BY alt_kategori
");
$query->execute();
$kategori_harcamalari = $query->fetchAll(PDO::FETCH_ASSOC);

// Kategorileri ve harcama miktarlarını ayrı dizilere ayır
$kategoriler = [];
$tutarlar = [];
foreach ($kategori_harcamalari as $row) {
    $kategoriler[] = $row['alt_kategori'];
    $tutarlar[] = $row['toplam_tutar'];
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
            <!--     Fonts and icons     -->
            <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
            <!-- CSS Files -->
            <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
            <link href="../assets/css/light-bootstrap-dashboard.css" rel="stylesheet" />
            <!-- CSS Just for demo purpose, don't include it in your project -->
            <link href="../assets/css/demo.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
        
            <style>
        /* Sidebar'ın CSS dosyasını geçersiz kılmak için */
        .sidebar {
            background-color: #1a237e !important; /* Koyu mavi renk */
            color: white; /* Yazı rengini beyaz yap */
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto; /* Dikey kaydırma */
        }

        .sidebar .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            transition: background 0.3s ease-in-out;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .logo {
            text-align: center;
            padding: 20px 0;
            font-weight: bold;
        }

        .logo img {
            width: 50px;
            height: 50px;
        }

        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-body {
    padding: 20px; /* Kart içeriğine dengeli boşluk */
}


.container {
    margin-top: 20px; /* Yukarıya taşımak için boşluğu azalt */
}
        .card-header {
            background-color: #1a237e;
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 10px;
        }

        .btn-group .btn {
            margin: 5px;
        }

        @media (max-width: 500px) {
            .card-header h4 {
                font-size: 1.2rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
        </head>

        <body>
            <div class="wrapper">
                <div class="sidebar" data-image="../assets/img/" >
                    <div class="sidebar-wrapper">
                        <div class="logo">
                            <a href="javascript:;" class="simple-text">
                                <img src="../assets/img/logo.png" alt="Finans Rotası Logo" style="width: 50px; height: 50px; margin-right: 10px;">
                            FİNANS ROTASI
                            </a>
                        </div>
                        <ul class="nav">
                        <li class="nav-item">
                                <a class="nav-link" href="admin.php">
                                <i class="fa fa-users"></i>

                                    <p>kullanıcılar</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="toplam_finans.php">
                                <i class="fa fa-credit-card"></i>


                                    <p>Toplam finans</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" href="toplam_harcama_admin.php">
                                <i class="fas fa-coins"></i>

                                    <p>Toplam Harcama</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="index.php">
                                <i class="fas fa-wallet"></i>
                                    <p>Kullanıcı Sayfası</p>
                                </a>
                            </li>

                            


                            
                            
                        </ul>
                    </div>
                </div>
                <div class="main-panel">

                    <!-- Navbar -->
                    <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                        <div class="container-fluid">
                            
                            <div class="collapse navbar-collapse justify-content-end" id="navigation">
                                
                                <ul class="navbar-nav ml-auto">
                                    
                                    <li class="nav-item">
                                        <span id="currentDate" class="nav-link"></span> <!-- Tarih buraya eklenecek -->
                                    </li>
                                    
                                    

                                    <li class="nav-item">
                                        <a class="nav-link" href="./ana_sayfa.php">
                                            <span class="no-icon">Çıkış</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <div class="container mt-5">
    
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h3>Kullanıcı Gelir ve Gider Analizi</h3>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped text-center">
                <thead class="thead-light">
                    <tr>
                        <th><i class="fas fa-id-card"></i> Kullanıcı ID</th>
                        <th><i class="fas fa-user"></i> Ad</th>
                        <th><i class="fas fa-user-tag"></i> Soyad</th>
                        <th><i class="fas fa-at"></i> Kullanıcı Adı</th>
                        <th><i class="fas fa-wallet text-success"></i> Toplam Gelir</th>
                        <th><i class="fas fa-credit-card text-danger"></i> Toplam Gider</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="font-weight-bold"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['ad']); ?></td>
                            <td><?php echo htmlspecialchars($user['soyad']); ?></td>
                            <td><?php echo htmlspecialchars($user['kullanici_adi']); ?></td>
                            <td class="text-success font-weight-bold"><?php echo number_format($user['toplam_gelir'], 2); ?> ₺</td>
                            <td class="text-danger font-weight-bold"><?php echo number_format($user['toplam_gider'], 2); ?> ₺</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




                    <script>
                        // Bugünün tarihini al ve formatla
                        const today = new Date();
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        const formattedDate = today.toLocaleDateString('tr-TR', options);
                    
                        // Tarihi #currentDate id'li öğeye yerleştir
                        document.getElementById('currentDate').textContent = formattedDate;
                    </script>
                    
                    <!-- End Navbar -->
                    <div class="container-fluid">
                    <div class="container mt-5">
        
    
  

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

     




        </body>
        </html>