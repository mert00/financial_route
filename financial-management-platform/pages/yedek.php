<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
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

$user_id = $_SESSION['user_id']; // Oturumdaki kullanıcı kimliği

// Gelir ve Gider Girişi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'];
    $alt_kategori = $_POST['alt_kategori'];
    $tutar = $_POST['tutar'];
    $tarih = $_POST['tarih'];
    $aciklama = $_POST['aciklama'];

    $sql = "INSERT INTO gelir_gider (kategori, alt_kategori, tutar, tarih, aciklama, user_id) 
            VALUES (:kategori, :alt_kategori, :tutar, :tarih, :aciklama, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'kategori' => $kategori,
        'alt_kategori' => $alt_kategori,
        'tutar' => $tutar,
        'tarih' => $tarih,
        'aciklama' => $aciklama,
        'user_id' => $user_id
    ]);
}



// Gelirleri çek
$gelirler = $conn->prepare("SELECT alt_kategori, SUM(tutar) as toplam 
                            FROM gelir_gider 
                            WHERE kategori = 'Gelir' AND user_id = :user_id 
                            GROUP BY alt_kategori");
$gelirler->execute(['user_id' => $user_id]);
$gelirler = $gelirler->fetchAll(PDO::FETCH_ASSOC);

// Giderleri çek
$giderler = $conn->prepare("SELECT alt_kategori, SUM(tutar) as toplam 
                            FROM gelir_gider 
                            WHERE kategori = 'Gider' AND user_id = :user_id 
                            GROUP BY alt_kategori");
$giderler->execute(['user_id' => $user_id]);
$giderler = $giderler->fetchAll(PDO::FETCH_ASSOC);
?>




        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <link rel="icon" type="image/png" href="../assets/img/logo.jpg">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <title>Finans Rotası</title>
            <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
            <!--     Fonts and icons     -->
            <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
            <!-- CSS Files -->
            <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
            <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
            <!-- CSS Just for demo purpose, don't include it in your project -->
            <link href="../assets/css/demo.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        </head>

        <body>
            <div class="wrapper">
                <div class="sidebar" data-image="../assets/img/" >
                    <div class="sidebar-wrapper">
                        <div class="logo">
                            <a href="javascript:;" class="simple-text">
                                <img src="../assets/img/logo.jpg" alt="Finans Rotası Logo" style="width: 30px; height: 30px; margin-right: 10px;">
                            FİNANS ROTASI
                            </a>
                        </div>
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">
                                    <i class="nc-icon nc-paper-2"></i>
                                    <p>Gelir-Gider</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="aylik_harcama.php">
                                    <i class="nc-icon nc-paper-2"></i>
                                    <p>Aylık Harcama Analizi</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="yatirim_performansi.php">
                                    <i class="nc-icon nc-paper-2"></i>
                                    <p>Yatırım Performansı <br>
                                        İzleme</p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a class="nav-link" href="gecmis_yatirim_hesaplama.php">
                                    <i class="nc-icon nc-paper-2"></i>
                                    <p>Geçmiş Yatırım <br>
                                        Getirisi Hesaplama</p>
                                </a>
                            </li>

                            <li>
                                <a class="nav-link" href="./user.php">
                                    <i class="nc-icon nc-circle-09"></i>
                                    <p>Profilim</p>
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
                                        <a class="nav-link" href="user.php">
                                            <span class="no-icon">Hesabım</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="./login_signup.php">
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
            <div class="row">
                <!-- Gelir ve Gider Giriş Formu -->
                <div class="col-lg-6 col-md-12">
                    <div class="card h-100">
                        <div class="card-header">
                            <h4 class="card-title">Gelir ve Gider Giriş Formu</h4>
                        </div>
                        <div class="card-body">
                            <form id="formGelirGider" method="POST" action="index.php" >
                                <div class="form-group">
                                    <label for="tutar">Tutar</label>
                                    <input type="number" class="form-control" name="tutar" id="tutar" placeholder="Tutar girin" required>
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" name="kategori" id="kategori" onchange="updateSubcategory()" required>
                                        <option value="">Kategori Seçin</option>
                                        <option value="Gelir">Gelir</option>
                                        <option value="Gider">Gider</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alt_kategori">Alt Kategori</label>
                                    <select class="form-control" name="alt_kategori" id="alt_kategori" required>
                                        <option value="">Alt Kategori Seçin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tarih">Tarih</label>
                                    <input type="date" class="form-control" name="tarih" id="tarih" required>
                                </div>
                                <div class="form-group">
                                    <label for="aciklama">Açıklama</label>
                                    <textarea class="form-control" name="aciklama" id="aciklama" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-fill">Kaydet</button>
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
            <div class="btn-group" role="group" aria-label="Grafik Türü">
                <button type="button" class="btn btn-primary" onclick="showGelir()">Gelir</button>
                <button type="button" class="btn btn-warning" onclick="showGider()">Gider</button>
                <button type="button" class="btn btn-success" onclick="showGelirGider()">Gelir-Gider</button>
            </div>
        </div>
        <div class="card-body">
            <canvas id="myPieChart" style="width: 100%; max-height: 450px;"></canvas>
        </div>
    </div>


            </div>
        </div>

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