<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finans Rotası</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: white;
            scroll-behavior: smooth; /* Yumuşak kaydırma */
        }

  /* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background: rgba(0, 0, 0, 0.2); /* %50 saydam navbar */
    position: absolute;
    width: 100%;
    z-index: 100;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Hafif gölge efekti */
    border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* Alt çizgi */
}

/* Logo */
.logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
}

/* Giriş Yap Butonu */
.nav-actions {
    display: flex;
    align-items: center;
}

.login-button {
    padding: 0.5rem 1rem;
    background: #00d4ff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

.login-button:hover {
    background: #009ac2;
}


        /* Hero Section */
        .hero {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh; /* Tüm ekran yüksekliğini kaplar */
    background: url('../assets/img/ana_görsel.jpg') no-repeat center center/cover;
}

.hero-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%; /* Ebeveyn alanını doldurur */
    text-align: center; /* Tüm metni ortalar */
    padding: 0 2rem; /* Yanlardan boşluk ekler */
}

.hero-content h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    color: white;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    max-width: 550px;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.8;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
}


        .cta-button {
            padding: 1rem 2rem;
            background: #00d4ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background 0.3s;
        }

        .cta-button:hover {
            background: #009ac2;
        }

        /* Services Section */
        /* Services Section */
.services {
    padding: 3rem 2rem;
    background: #f8f8f8;
}

.services h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 2rem;
    color: #333;
}

/* Grid Düzeni */
.services-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Her satırda 3 sütun */
    gap: 2rem; /* Kartlar arası boşluk */
}

.service-item {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center; /* İkonları ve metni ortalar */
    justify-content: space-between;
    height: 100%; /* Kartların aynı yükseklikte olmasını sağlar */
}

.service-item:hover {
    transform: translateY(-10px); /* Hover efekti */
}

.service-item i {
    font-size: 3rem;
    color: #0078d7;
    margin-bottom: 1rem; /* İkon ve başlık arasına boşluk */
}

.service-item h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #0078d7;
}

.service-item p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.service-item a {
    text-decoration: none;
    font-size: 1rem;
    font-weight: bold;
    color: #0078d7;
}

.service-item a:hover {
    text-decoration: underline;
}

/* Responsive Tasarım */
@media (max-width: 992px) {
    .services-grid {
        grid-template-columns: repeat(2, 1fr); /* Orta ekranlarda 2 sütun */
    }
}

@media (max-width: 576px) {
    .services-grid {
        grid-template-columns: 1fr; /* Küçük ekranlarda tek sütun */
    }
}


        /* Responsive Tasarım */
        @media screen and (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr; /* Daha küçük ekranlarda tek sütun */
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">Finans Rotası</div>
        <div class="nav-actions">
            <button class="login-button" onclick="window.location.href='login_signup.php';">Giriş Yap</button>
        </div>
    </header>

    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Finans Rotasına Hoşgeldiniz</h1>
            <p>
                 Finans Rotası ile tasarruf ve yatırım kararları almanın ayrıcaklarına hazır mısın?
            </p>
            <button class="cta-button" onclick="document.querySelector('#services').scrollIntoView({ behavior: 'smooth' });">Keşfet</button>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
    <h2>Ürünler ve Hizmetler</h2>
    <div class="services-grid">
        <div class="service-item">
            <i class="fas fa-chart-line"></i>
            <h3>Aylık Harcama Analizi</h3>
            <p>Aylık harcamalarınızı analiz ederek bütçenizi daha verimli yönetmenize yardımcı oluyoruz. Gelir ve giderlerinizi takip ederek finansal durumunuzu iyileştirin.</p>
            
        </div>
        <div class="service-item">
            <i class="fas fa-wallet"></i>
            <h3>Gelir-Gider</h3>
            <p>Gelir ve giderlerinizi kolayca kaydedip takip edin. Harcamalarınızı kontrol altına alın ve finansal hedeflerinize ulaşmak için bir adım öne geçin.</p>
           
        </div>
        <div class="service-item">
            <i class="fas fa-chart-pie"></i>
            <h3>Yatırım Performansı İzleme</h3>
            <p>Yatırımlarınızı analiz ederek performansınızı takip edin. Geçmiş verilerle karşılaştırmalar yapın ve daha bilinçli kararlar alın.</p>
            
        </div>
        <div class="service-item">
            <i class="fas fa-calculator"></i>
            <h3>Geçmiş Yatırım Getirisi Hesaplama</h3>
            <p>Belirli bir dönem için yatırım getirilerinizi hesaplayın. Döviz, altın veya kripto para birimlerindeki kazancınızı öğrenin.</p>
            
        </div>
        <div class="service-item">
            <i class="fas fa-map-signs"></i>
            <h3>Rota Önerileri</h3>
            <p>Finansal hedeflerinize en uygun rotaları öneriyoruz. Tasarruf ve yatırım planlamalarınızı daha verimli hale getirin.</p>
            
        </div>
        <div class="service-item">
            <i class="fas fa-info-circle"></i>
            <h3>Hakkımızda</h3>
            <p>Finans Rotası olarak, kullanıcılarımızın finansal hedeflerine ulaşmalarına yardımcı olmak için modern çözümler sunuyoruz. Güvenilir ve yenilikçi hizmetlerimizle yanınızdayız.</p>
            
        </div>
    </div>
</section>


</body>
</html>
