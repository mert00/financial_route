<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login_signup.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Oturumdaki kullanıcı kimliği


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Finans Rotası - Aylık Harcama</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .navbar-custom {
            background-color: #002855;
            color: white;
        }
        .navbar-custom a {
            color: white;
        }
        .section-title {
            text-align: center;
            margin-bottom: 20px;
            color: #002855;
        }
        .vision-mission {
            margin-top: 30px;
        }
        .team-card {
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .team-card:hover {
            transform: translateY(-10px);
        }
        footer {
            margin-top: 30px;
            text-align: center;
            padding: 10px;
            background-color: #002855;
            color: white;
        }

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
                            <!-- Navbar -->
                    <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                        <div class="container-fluid">
                            
                            <div class="collapse navbar-collapse justify-content-end" id="navigation">
                                
                                <ul class="navbar-nav ml-auto">
                                    
                                    <li class="nav-item">
                                        <span id="currentDate" class="nav-link"></span> <!-- Tarih buraya eklenecek -->
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="user.php">
                                            <span class="no-icon">Hesabım</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="./logout.php">
                                            <span class="no-icon">Çıkış</span>
                                        </a>
                                    </li>

                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </nav>
                            
                     
     

<div class="container my-5">
    <h2 class="section-title">Hakkımızda</h2>
    <p>Finans Rotası, finansal bilgilerinizi analiz etmenize ve daha bilinçli yatırım kararları almanıza yardımcı olan bir platformdur. Ekibimiz, finansal danışmanlar, yazılım geliştiriciler ve tasarımcılardan oluşmaktadır. Kullanıcılarımız için en iyi deneyimi sunmayı hedefliyoruz.</p>

    <div class="row vision-mission">
        <div class="col-md-6">
            <h3 class="section-title">Vizyonumuz</h3>
            <p>Finansal analiz ve yönetim konusunda lider bir platform olmak ve bireylerin finansal hedeflerine ulaşmalarını kolaylaştırmak.</p>
        </div>
        <div class="col-md-6">
            <h3 class="section-title">Misyonumuz</h3>
            <p>Kullanıcılarımızın finansal durumlarını daha iyi yönetmelerine yardımcı olmak için güvenilir ve yenilikçi araçlar sunmak.</p>
        </div>
    </div>

    <h2 class="section-title mt-5">İletişim</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card team-card p-3">
                <h5>Proje Geliştirici</h5>
                <p><strong>Ad Soyad:</strong> Mert Kapar</p>
                <p><strong>E-posta:</strong> mertkapar00@gmail.com</p>
                <p><strong>Telefon:</strong> +90 544 606 08 36</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card team-card p-3">
                <h5>Proje Geliştirici </h5>
                <p><strong>Ad Soyad:</strong> Tuğbanur Dualı</p>
                <p><strong>E-posta:</strong> tugbanurduali@gmail.com</p>
                <p><strong>Telefon:</strong> +90 505 172 54 50</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card team-card p-3">
                <h5>Proje Geliştirici </h5>
                <p><strong>Ad Soyad:</strong> Mete Yusuf Gündoğdu</p>
                <p><strong>E-posta:</strong> meteyusufgundogdu@gmail.com</p>
                <p><strong>Telefon:</strong> +90 534 250 03 10</p>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>Telif Hakkı © 2024 Finans Rotası. Tüm Hakları Saklıdır.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
