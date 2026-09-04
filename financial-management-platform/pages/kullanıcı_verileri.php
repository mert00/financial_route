<?php
session_start();

// Kullanıcının oturum açıp açmadığını kontrol edelim
if (!isset($_SESSION['user_id'])) {
    // Oturum açmamışsa, giriş sayfasına yönlendirelim
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

// Kullanıcının gelir ve gider toplamlarını hesaplama
$stmt = $conn->prepare("
    SELECT 
        COALESCE(SUM(CASE WHEN kategori = 'Gelir' THEN tutar END), 0) AS toplam_gelir,
        COALESCE(SUM(CASE WHEN kategori = 'Gider' THEN tutar END), 0) AS toplam_gider
    FROM gelir_gider
    WHERE user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$gelir = $result['toplam_gelir'];
$gider = $result['toplam_gider'];
$kalan = $gelir - $gider;
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
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
        
            <style>
    

        body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.card {
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.card h4 {
    margin: 0;
    font-size: 1.2rem;
}

#financeChart {
    max-height: 400px;
    max-width: 100%;
    display: block;
    margin: 0 auto;
}

.row .col-md-4 {
    margin-bottom: 20px;
}

.alert {
    border-radius: 10px;
    padding: 20px;
}

.row.text-center {
    margin-bottom: 30px;
}



    </style>
        </head>

        <body>
            <div class="wrapper">
                <div class="sidebar" data-image="../assets/img/" >
                    <div class="sidebar-wrapper">
                    <div class="logo">
                            <a   class="simple-text">
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

                    <script>
                        // Bugünün tarihini al ve formatla
                        const today = new Date();
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        const formattedDate = today.toLocaleDateString('tr-TR', options);
                    
                        // Tarihi #currentDate id'li öğeye yerleştir
                        document.getElementById('currentDate').textContent = formattedDate;
                    </script>
                    
                    <!-- End Navbar -->
                    

                    <div class="container my-4">
    <!-- Üst Bilgi: Gelir, Gider, Kalan -->
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <h4>Gelir</h4>
                <p class="text-success fw-bold display-6"><?php echo number_format($gelir, 2, ',', '.'); ?> ₺</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <h4>Gider</h4>
                <p class="text-danger fw-bold display-6"><?php echo number_format($gider, 2, ',', '.'); ?> ₺</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <h4>Kalan</h4>
                <p class="text-primary fw-bold display-6"><?php echo number_format($kalan, 2, ',', '.'); ?> ₺</p>
            </div>
        </div>
    </div>

    <!-- Grafik ve Öneri Alanı -->
    <div class="row align-items-center mt-4">
        <!-- Grafik -->
        <div class="col-lg-6">
            <canvas id="financeChart"></canvas>
        </div>

        <!-- Öneriler -->
        <div class="col-lg-6">
            <?php if ($kalan > 0): ?>
                <div class="alert alert-success">
                    <h5>Bu ay elinizde <strong><?php echo number_format($kalan, 2, ',', '.'); ?> ₺</strong> kaldı.</h5>
                    <p>Bu parayı şu yatırım ürünlerinde değerlendirebilirsiniz:</p>
                    <ul>
                        <li>Bireysel Emeklilik Sistemi</li>
                        <li>Hisse Senetleri</li>
                        <li>Altın ve Döviz</li>
                        <li>Mevduat Hesabı</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <h5>Bu ay giderleriniz gelirlerinizi aşmış.</h5>
                    <p>Harcamalarınızı gözden geçirmenizi öneriyoruz.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<!-- Chart.js Grafik Oluşturma -->
<script>
   const ctx = document.getElementById('financeChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Gelir', 'Gider'],
        datasets: [{
            data: [<?php echo $gelir; ?>, <?php echo $gider; ?>],
            backgroundColor: ['#4caf50', '#f44336'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});


</script>



        </body>
        </html>