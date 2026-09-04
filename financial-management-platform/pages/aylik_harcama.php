<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login_signup.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Oturumdaki kullanıcı kimliği

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

// Kullanıcının gider verilerini ay ve kategori bazında gruplama
$sql = "SELECT 
            DATE_FORMAT(tarih, '%Y-%m') as month, 
            alt_kategori, 
            SUM(tutar) as total 
        FROM gelir_gider 
        WHERE kategori = 'Gider' AND user_id = :user_id 
        GROUP BY month, alt_kategori 
        ORDER BY month";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $month = $row['month'];
    $alt_kategori = $row['alt_kategori'];
    $total = $row['total'];

    if (!isset($data[$month])) {
        $data[$month] = [];
    }
    $data[$month][$alt_kategori] = $total;
}

// Verileri JSON formatına dönüştür
$jsonData = json_encode($data);
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
                            
                                    <div class="container">
                                        <h2 class="text-center">Aylık Harcama Analizi</h2>
                                        <div id="chart-container" style="width: 90%; margin: 30px auto;">
                                            <canvas id="monthlyChart"></canvas>
                                        </div>
                                    </div>

                                    <script>
    // PHP'den gelen JSON verisini al
    const expenseData = <?php echo $jsonData; ?>;

    // Kategorilere sabit renk atayan renk haritası
    const colorMap = {
        "Maaş": "#4CAF50",
        "Faiz": "#2196F3",
        "Kira Gelirleri": "#154C79",
        "Diğer Gelirler": "#9C27B0",
        "Kira": "#224125",
        "Fatura": "#E91E63",
        "Yiyecek ve İçecek": "#FF9800",
        "Ulaşım": "#3F51B5",
        "Kredi Kartı ve Borç Ödemeleri": "#8BC34A",
        "Sağlık ve Bakım": "#00BCD4",
        "Eğitim": "#009688",
        "Eğlence ve Hobiler": "#673AB7",
        "Giyim ve Aksesuar": "#FFEB3B",
        "Market Alışverişi": "#795548"
    };

    // Kategoriler için renk seçici (renk yoksa rastgele renk atanır)
    function getCategoryColor(category) {
        return colorMap[category] || getRandomColor();
    }

    // Rastgele renk oluştur (kategoriler için sabit renk olmayan durumlar için)
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Aylık verileri işleme
    const months = Object.keys(expenseData);
    const categories = [...new Set(months.flatMap(month => Object.keys(expenseData[month])))];
    const datasets = categories.map(category => ({
        label: category,
        data: months.map(month => expenseData[month][category] || 0),
        backgroundColor: getCategoryColor(category)
    }));

    // Grafiği oluştur
    function renderChart(labels, datasets) {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets },
            options: {
                plugins: {
                    title: { display: true, text: 'Aylık Harcamalar' },
                    tooltip: { mode: 'index', intersect: false },
                    legend: { position: 'top' }
                },
                responsive: true,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true }
                }
            }
        });
    }

    // Sayfa yüklendiğinde grafiği oluştur
    renderChart(months, datasets);
</script>

                        </div>
    </div>
</body>

</html>
