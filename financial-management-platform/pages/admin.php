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

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'finans_rotasi';
$username = 'root';
$password = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Kullanıcıları çek
$stmt = $conn->prepare("SELECT id, ad, soyad, kullanici_adi, email, role, hakkimda FROM users ORDER BY id ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mesaj gösterimleri
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
        . htmlspecialchars($_SESSION['message']) .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
        . htmlspecialchars($_SESSION['error']) .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['error']);
}

// Admin işlemleri burada devam eder...
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

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .form-select {
            width: 100%;
        }
        .alert {
    position: fixed; /* Sayfada sabitlenmiş pozisyon */
    top: 56px; /* Navbar yüksekliği kadar boşluk */
    left: 260px; /* Sidebar genişliği kadar boşluk */
    right: 0px; /* Sağ kenardan boşluk */
    z-index: 1050;
    border-radius: 5px;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.user-add-card {
    margin-top: 30px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.user-add-card .card-header {
    text-align: center;
    font-weight: bold;
}

.user-add-card .btn-success {
    width: 100%;
    font-size: 16px;
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
                                        <a class="nav-link" href="./logout.php">
                                            <span class="no-icon">Çıkış</span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </nav>


                    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="text-center">Admin Paneli - Kullanıcı Yönetimi</h4>
            </div>
            <div class="card-body">
            <table class="table table-hover table-responsive-lg table-striped">
            <thead class="thead-dark">
    <tr>
        <th>ID</th>
        <th>Ad</th>
        <th>Soyad</th>
        <th>Kullanıcı Adı</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Hakkımda</th>
        <th>İşlemler</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['ad']); ?></td>
            <td><?php echo htmlspecialchars($user['soyad']); ?></td>
            <td><?php echo htmlspecialchars($user['kullanici_adi']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <!-- Rol Güncelleme -->
                <form method="POST" action="admin_actions.php">
                    <input type="hidden" name="action" value="update_role">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <select class="form-select form-control-sm" name="role" onchange="this.form.submit()">
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Kullanıcı</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </form>
            </td>
            <td><?php echo $user['hakkimda'] ? htmlspecialchars($user['hakkimda']) : ' '; ?></td>
            <td>
            <div class="btn-group" role="group" style="width: 100%;">
                <!-- Düzenle Butonu -->
                <button class="btn btn-warning btn-sm w-50" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                    Düzenle
                </button>
                <!-- Sil Butonu -->
                <form method="POST" action="admin_actions.php" style="display:inline-block; width: 50%;">
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">
                        Sil
                    </button>
                </form>
            </div>

            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

        <!-- Düzenleme Modalı -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="admin_actions.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Kullanıcı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_user">
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="form-group mb-3">
                        <label>Ad</label>
                        <input type="text" id="editAd" name="ad" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Soyad</label>
                        <input type="text" id="editSoyad" name="soyad" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Kullanıcı Adı</label>
                        <input type="text" id="editKullaniciAdi" name="kullanici_adi" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" id="editEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Hakkımda</label>
                        <textarea id="editHakkimda" name="hakkimda" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card user-add-card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Kullanıcı Ekle</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="admin_actions.php">
                <input type="hidden" name="action" value="add_user">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ad" class="form-label">Ad</label>
                        <input type="text" class="form-control" id="ad" name="ad" placeholder="Ad" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="soyad" class="form-label">Soyad</label>
                        <input type="text" class="form-control" id="soyad" name="soyad" placeholder="Soyad" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-posta</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="E-posta" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user" selected>Kullanıcı</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Kullanıcı Ekle</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Mesaj Görüntüleme -->
<?php if (isset($_SESSION['message'])): ?>
            <div id="successMessage" class="alert alert-success alert-dismissible fade show message-popup" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div id="errorMessage" class="alert alert-danger alert-dismissible fade show message-popup" role="alert">
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

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

                    <script>
                                                function openEditModal(user) {
                            document.getElementById('editUserId').value = user.id;
                            document.getElementById('editAd').value = user.ad;
                            document.getElementById('editSoyad').value = user.soyad;
                            document.getElementById('editKullaniciAdi').value = user.kullanici_adi;
                            document.getElementById('editEmail').value = user.email;
                            document.getElementById('editHakkimda').value = user.hakkimda || '';
                            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                            editModal.show();
                        }   

                        // Sayfa yüklendikten sonra belirli bir süre içinde mesajı gizle
                        document.addEventListener("DOMContentLoaded", function () {
                            setTimeout(function () {
                                var alert = document.getElementById("successAlert");
                                if (alert) {
                                    // Bootstrap'in dismiss özelliğini kullanarak kapat
                                    alert.classList.remove("show");
                                    alert.classList.add("fade");
                                    setTimeout(() => alert.remove(), 500); // Elementi tamamen DOM'dan kaldır
                                }
                            }, 3000); // Mesajın ekranda kalma süresi (3000 ms = 3 saniye)
                        });


                    </script>
                    
                    <!-- End Navbar -->
                    <div class="container-fluid">
                    <div class="container mt-5">
        
    <script>
        // Form submit olayını dinle
        document.getElementById('formGelirGider').addEventListener('submit', function(event) {
            event.preventDefault(); // Sayfanın yenilenmesini engelle
            document.getElementById('successMessage').style.display = 'block'; // Başarı mesajını göster
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none'; // Mesajı 3 saniye sonra gizle
            }, 3000);
        });

        var myModal = new bootstrap.Modal(document.getElementById('editModal<?php echo $user['id']; ?>'));
        myModal.show();

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

     




</body>
</html>