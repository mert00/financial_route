<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Kullanıcı giriş yapmamışsa login sayfasına yönlendirme
    header("Location: login_signup.php");
    exit();
}





// Kullanıcı giriş yapmışsa oturumdaki user_id'yi alın
$user_id = $_SESSION['user_id'];

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

// Gelir ve Gider Girişi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'] ?? '';
    $alt_kategori = $_POST['alt_kategori'] ?? '';
    $tutar = $_POST['tutar'] ?? 0;
    $tarih = $_POST['tarih'] ?? '';
    $aciklama = $_POST['aciklama'] ?? '';

    // Geçerli veri kontrolü
    if (!empty($kategori) && !empty($alt_kategori) && $tutar > 0 && !empty($tarih)) {
        $sql = "INSERT INTO gelir_gider (kategori, alt_kategori, tutar, tarih, aciklama, user_id) 
                VALUES (:kategori, :alt_kategori, :tutar, :tarih, :aciklama, :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'kategori' => $kategori,
            'alt_kategori' => $alt_kategori,
            'tutar' => $tutar,
            'tarih' => $tarih,
            'aciklama' => $aciklama,
            'user_id' => $user_id,
        ]);
    }
}



// Gelirleri çek
function getGelirGider($conn, $user_id, $kategori) {
    $sql = "SELECT alt_kategori, SUM(tutar) as toplam 
            FROM gelir_gider 
            WHERE kategori = :kategori AND user_id = :user_id 
            GROUP BY alt_kategori";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['kategori' => $kategori, 'user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$gelirler = getGelirGider($conn, $user_id, 'Gelir');
$giderler = getGelirGider($conn, $user_id, 'Gider');
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
        .btn {
  background-color: transparent;
  border: 2px solid #007bff;
  color: #007bff;
}

.btn:hover {
  background-color: #007bff;
  color: white;
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
        <div class="row g-4">
            <!-- Gelir ve Gider Giriş Formu -->
            <div class="col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title">Gelir ve Gider Girişi</h4>
                    </div>
                    <div id="successMessage" class="alert alert-success mt-3" style="display: none;">
                    Kayıt başarılı!
                </div>
                    <div class="card-body">
                        <form id="formGelirGider" method="POST" action="index.php">
                            <div class="mb-3">
                                <label for="tutar" class="form-label">Tutar</label>
                                <input type="number" class="form-control" name="tutar" id="tutar" placeholder="Tutar girin" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-control" name="kategori" id="kategori" onchange="updateSubcategory()" required>
                                    <option value="">Kategori Seçin</option>
                                    <option value="Gelir">Gelir</option>
                                    <option value="Gider">Gider</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="alt_kategori" class="form-label">Alt Kategori</label>
                                <select class="form-control" name="alt_kategori" id="alt_kategori" required>
                                    <option value="">Alt Kategori Seçin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tarih" class="form-label">Tarih</label>
                                <input type="date" class="form-control" name="tarih" id="tarih" required>
                            </div>
                            <div class="mb-3">
                                <label for="aciklama" class="form-label">Açıklama</label>
                                <textarea class="form-control" name="aciklama" id="aciklama" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kaydet</button>
                        </form>
                        
                    </div>
                </div>
            </div>

            <!-- Gelir ve Gider Dağılımı -->
            <div class="col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title">Gelir ve Gider Dağılımı</h4>
                        <!-- Butonlar -->
                        <div class="btn-group d-flex" role="group" aria-label="Grafik Türü">
                            <button type="button" class="btn btn-primary flex-fill" onclick="showGelir()">Gelir</button>
                            <button type="button" class="btn btn-warning flex-fill" onclick="showGider()">Gider</button>
                            <button type="button" class="btn btn-success flex-fill" onclick="showGelirGider()">Gelir-Gider</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myPieChart" style="width: 100%; max-height: 450px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Form submit olayını dinle
        document.getElementById('formGelirGider').addEventListener('submit', function(event) {
            event.preventDefault(); // Sayfanın yenilenmesini engelle
            document.getElementById('successMessage').style.display = 'block'; // Başarı mesajını göster
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none'; // Mesajı 3 saniye sonra gizle
            }, 3000);
        });
    </script>
    


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

        <script>
    // Alt kategori güncelleme
    function updateSubcategory() {
        const kategori = document.getElementById("kategori").value;
        const altKategori = document.getElementById("alt_kategori");

        const gelirSubcategories = ["Maaş", "Faiz", "Kira Gelirleri", "Diğer Gelirler"];
        const giderSubcategories = [
            "Kira",
            "Fatura",
            "Yiyecek ve İçecek",
            "Ulaşım",
            "Kredi Kartı ve Borç Ödemeleri",
            "Sağlık ve Bakım",
            "Eğitim",
            "Eğlence ve Hobiler",
            "Giyim ve Aksesuar"
        ];

        altKategori.innerHTML = "<option value=''>Alt Kategori Seçin</option>";
        let options = kategori === "Gelir" ? gelirSubcategories : giderSubcategories;

        options.forEach(function (item) {
            const option = document.createElement("option");
            option.value = item;
            option.textContent = item;
            altKategori.appendChild(option);
        });
    }

    // Renkleri tanımla
    const categoryColors = {
        "Maaş": "#4CAF50",
        "Faiz": "#2196F3",
        "Kira Gelirleri": "#FFC107",
        "Diğer Gelirler": "#9C27B0",
        "Kira": "#FF5722",
        "Fatura": "#E91E63",
        "Yiyecek ve İçecek": "#FF9800",
        "Ulaşım": "#3F51B5",
        "Kredi Kartı ve Borç Ödemeleri": "#8BC34A",
        "Sağlık ve Bakım": "#00BCD4",
        "Eğitim": "#009688",
        "Eğlence ve Hobiler": "#673AB7",
        "Giyim ve Aksesuar": "#FFEB3B"
    };

    // Grafik verileri
    let chart;
    const ctx = document.getElementById('myPieChart').getContext('2d');

    async function fetchChartData(type) {
        try {
            const response = await fetch('get_chart_data.php'); // PHP API'ye istek gönder
            const data = await response.json();

            if (type === 'gelir') {
                createChart(
                    data.gelirler.map(item => item.alt_kategori),
                    data.gelirler.map(item => item.toplam),
                    data.gelirler.map(item => categoryColors[item.alt_kategori])
                );
            } else if (type === 'gider') {
                createChart(
                    data.giderler.map(item => item.alt_kategori),
                    data.giderler.map(item => item.toplam),
                    data.giderler.map(item => categoryColors[item.alt_kategori])
                );
            } else if (type === 'gelir-gider') {
                const totalGelir = data.gelirler.reduce((sum, item) => sum + parseFloat(item.toplam), 0);
                const totalGider = data.giderler.reduce((sum, item) => sum + parseFloat(item.toplam), 0);
                createChart(['Gelir', 'Gider'], [totalGelir, totalGider], ['#4CAF50', '#FF5722']);
            } else {
                createDefaultChart();
            }
        } catch (error) {
            createDefaultChart();
        }
    }

    function createChart(labels, data, colors) {
        if (chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }

    function createDefaultChart() {
        if (chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Lütfen veri girişi yapın'],
                datasets: [{ data: [100], backgroundColor: ['#FF0000'] }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function () {
                                return "Lütfen veri girişi yapın";
                            }
                        }
                    },
                    legend: { position: 'top' }
                }
            }
        });
    }

    // Grafik türüne göre butonlarla veri çekimi
    function showGelir() { fetchChartData('gelir'); }
    function showGider() { fetchChartData('gider'); }
    function showGelirGider() { fetchChartData('gelir-gider'); }

    // Form gönderiminde grafik güncelle
    document.getElementById('formGelirGider').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Form verilerini logla
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    await fetch('index.php', {
        method: 'POST',
        body: formData
    });

    fetchChartData('gelir-gider');
    this.reset();
});


    // Sayfa yüklendiğinde gelir-gider grafiği yükle
    fetchChartData('gider');
</script>




        </body>
        </html>