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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
        


        
    </head>

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }

    .container {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        width: 100%;
        margin: 10px auto;
        box-sizing: border-box;
    }

    h1 {
        text-align: center;
        font-size: 25px;
        margin-bottom: 10px;
        color: #2a3f54;
    }

    .form-group {
        margin-bottom: 10px;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: #555;
    }

    input, select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    input:focus, select:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    button {
        display: block;
        width: 100%;
        background: #007bff;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background: #0056b3;
    }

    .result {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .result.hidden {
        display: none;
    }

    .result h2 {
        margin-bottom: 10px;
        font-size: 20px;
        color: #2a3f54;
    }

    .result p {
        font-size: 16px;
        color: #555;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        h1 {
            font-size: 24px;
        }

        button {
            font-size: 14px;
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
                <!-- End Navbar -->

            <script>
                // Bugünün tarihini al ve formatla
                const today = new Date();
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = today.toLocaleDateString('tr-TR', options);
            
                // Tarihi #currentDate id'li öğeye yerleştir
                document.getElementById('currentDate').textContent = formattedDate;
            </script>


<div class="container">
        <h1>Geçmiş Yatırım Getirisi Hesaplama</h1>
        <form id="investmentForm">
            <div class="form-group">
                <label for="investmentType">Yatırım Türü:</label>
                <select id="investmentType" required>
                    <option value="dolar">Dolar</option>
                    <option value="euro">Euro</option>
                    <option value="gram_altin">Gram Altın</option>
                    <option value="ons_altin">Ons Altın</option>
                    <option value="bitcoin">Bitcoin</option>
                    <option value="ethereum">Ethereum</option>
                </select>
            </div>

            <div class="form-group">
                <label for="investmentAmount">Yatırım Miktarı (TL):</label>
                <input type="number" id="investmentAmount" placeholder="Örneğin: 1000" required>
            </div>

            <div class="form-group">
                <label for="startMonth">Başlangıç Tarihi:</label>
                <input type="month" id="startMonth" min="2020-01" max="2024-12" required>
            </div>

            <div class="form-group">
                <label for="endMonth">Bitiş Tarihi:</label>
                <input type="month" id="endMonth" min="2020-01" max="2024-12" required>
            </div>

            <button type="submit">Hesapla</button>
        </form>

        <div id="result" class="result hidden">
            <h2>Sonuç</h2>
            <p id="resultText"></p>
        </div>
    </div>


    



    <script>
        const form = document.getElementById('investmentForm');
        const result = document.getElementById('result');
        const resultText = document.getElementById('resultText');

        const investmentData = {
            dolar:{'2020-01': 5.94,'2020-03': 6.93,'2020-04': 7.42,'2020-05': 7.91,'2020-06': 8.4,'2020-07': 8.9,'2020-08': 9.39,'2020-09': 9.88,'2020-10': 10.37,
    '2020-11': 10.87,'2020-12': 11.36,'2021-01': 11.85,'2021-03': 12.84,
    '2021-04': 13.33,
    '2021-05': 13.82,
    '2021-06': 14.31,
    '2021-07': 14.81,
    '2021-08': 15.3,
    '2021-10': 16.28,
    '2021-12': 17.27,
    '2022-01': 17.76,
    '2022-03': 18.75,
    '2022-04': 19.24,
    '2022-05': 19.73,
    '2022-06': 20.22,
    '2022-07': 20.72,
    '2022-08': 21.21,
    '2022-09': 21.7,
    '2022-10': 22.19,
    '2022-12': 23.18,
    '2023-01': 23.67,
    '2023-03': 24.66,
    '2023-04': 25.15,
    '2023-05': 25.64,
    '2023-06': 26.13,
    '2023-07': 26.63,
    '2023-08': 27.12,
    '2023-09': 27.61,
    '2023-10': 28.1,
    '2023-11': 28.6,
    '2023-12': 29.09,
    '2024-01': 29.58,
    '2024-03': 30.57,
    '2024-04': 31.06,
    '2024-05': 31.55,
    '2024-06': 32.04,
    '2024-07': 32.54,
    '2024-08': 33.03,
    '2024-10': 34.01,
    '2024-11': 34.51,
    '2024-12': 35.00
}
,
            euro: {
    '2020-01': 6.64, '2020-03': 7.67, '2020-04': 8.18, '2020-05': 8.7, '2020-06': 9.21, '2020-07': 9.73,
    '2020-08': 10.24, '2020-09': 10.76, '2020-10': 11.27, '2020-11': 11.79, '2020-12': 12.3, '2021-01': 12.81,
    '2021-03': 13.84, '2021-04': 14.36, '2021-05': 14.87, '2021-06': 15.39, '2021-07': 15.9, '2021-08': 16.42,
    '2021-10': 17.45, '2021-12': 18.48, '2022-01': 18.99, '2022-03': 20.02, '2022-04': 20.53, '2022-05': 21.05,
    '2022-06': 21.56, '2022-07': 22.08, '2022-08': 22.59, '2022-09': 23.11, '2022-10': 23.62, '2022-12': 24.65,
    '2023-01': 25.16, '2023-03': 26.19, '2023-04': 26.71, '2023-05': 27.22, '2023-06': 27.74, '2023-07': 28.25,
    '2023-08': 28.77, '2023-09': 29.28, '2023-10': 29.8, '2023-11': 30.31, '2023-12': 30.83, '2024-01': 31.34,
    '2024-03': 32.37, '2024-04': 32.88, '2024-05': 33.4, '2024-06': 33.91, '2024-07': 34.43, '2024-08': 34.94,
    '2024-10': 35.97, '2024-11': 36.49, '2024-12': 37.00
},
            gram_altin:{
    '2020-01': 295.0, '2020-03': 387.31, '2020-04': 433.46, '2020-05': 479.61, '2020-06': 525.76, '2020-07': 571.92,
    '2020-08': 618.07, '2020-09': 664.22, '2020-10': 710.37, '2020-11': 756.53, '2020-12': 802.68, '2021-01': 848.83,
    '2021-03': 941.14, '2021-04': 987.29, '2021-05': 1033.44, '2021-06': 1079.59, '2021-07': 1125.75, '2021-08': 1171.9,
    '2021-10': 1264.2, '2021-12': 1356.51, '2022-01': 1402.66, '2022-03': 1494.97, '2022-04': 1541.12, '2022-05': 1587.27,
    '2022-06': 1633.42, '2022-07': 1679.58, '2022-08': 1725.73, '2022-09': 1771.88, '2022-10': 1818.03, '2022-12': 1910.34,
    '2023-01': 1956.49, '2023-03': 2048.8, '2023-04': 2094.95, '2023-05': 2141.1, '2023-06': 2187.25, '2023-07': 2233.41,
    '2023-08': 2279.56, '2023-09': 2325.71, '2023-10': 2371.86, '2023-11': 2418.02, '2023-12': 2464.17, '2024-01': 2510.32,
    '2024-03': 2602.63, '2024-04': 2648.78, '2024-05': 2694.93, '2024-06': 2741.08, '2024-07': 2787.24, '2024-08': 2833.39,
    '2024-10': 2925.69, '2024-11': 2971.85, '2024-12': 3018.0
},
            ons_altin:{
    '2020-01': 1575.0, '2020-03': 1612.86, '2020-04': 1631.8, '2020-05': 1650.73, '2020-06': 1669.66,
    '2020-07': 1688.59, '2020-08': 1707.53, '2020-09': 1726.46, '2020-10': 1745.39, '2020-11': 1764.32,
    '2020-12': 1783.25, '2021-01': 1802.19, '2021-03': 1840.05, '2021-04': 1858.98, '2021-05': 1877.92,
    '2021-06': 1896.85, '2021-07': 1915.78, '2021-08': 1934.71, '2021-10': 1972.58, '2021-12': 2010.44,
    '2022-01': 2029.37, '2022-03': 2067.24, '2022-04': 2086.17, '2022-05': 2105.1, '2022-06': 2124.03,
    '2022-07': 2142.97, '2022-08': 2161.9, '2022-09': 2180.83, '2022-10': 2199.76, '2022-12': 2237.63,
    '2023-01': 2256.56, '2023-03': 2294.42, '2023-04': 2313.36, '2023-05': 2332.29, '2023-06': 2351.22,
    '2023-07': 2370.15, '2023-08': 2389.08, '2023-09': 2408.02, '2023-10': 2426.95, '2023-11': 2445.88,
    '2023-12': 2464.81, '2024-01': 2483.75, '2024-03': 2521.61, '2024-04': 2540.54, '2024-05': 2559.47,
    '2024-06': 2578.41, '2024-07': 2597.34, '2024-08': 2616.27, '2024-10': 2654.14, '2024-11': 2673.07,
    '2024-12': 2692.0
},
            bitcoin: {
    '2020-01': 8180, '2020-03': 11176.68, '2020-04': 12675.02, '2020-05': 14173.36, '2020-06': 15671.69,
    '2020-07': 17170.03, '2020-08': 18668.37, '2020-09': 20166.71, '2020-10': 21665.05, '2020-11': 23163.39,
    '2020-12': 24661.73, '2021-01': 26160.07, '2021-03': 29156.75, '2021-04': 30655.08, '2021-05': 32153.42,
    '2021-06': 33651.76, '2021-07': 35150.1, '2021-08': 36648.44, '2021-10': 39645.12, '2021-12': 42641.8,
    '2022-01': 44140.14, '2022-03': 47136.81, '2022-04': 48635.15, '2022-05': 50133.49, '2022-06': 51631.83,
    '2022-07': 53130.17, '2022-08': 54628.51, '2022-09': 56126.85, '2022-10': 57625.19, '2022-12': 60621.86,
    '2023-01': 62120.2, '2023-03': 65116.88, '2023-04': 66615.22, '2023-05': 68113.56, '2023-06': 69611.9,
    '2023-07': 71110.24, '2023-08': 72608.58, '2023-09': 74106.92, '2023-10': 75605.25, '2023-11': 77103.59,
    '2023-12': 78601.93, '2024-01': 80100.27, '2024-03': 83096.95, '2024-04': 84595.29, '2024-05': 86093.63,
    '2024-06': 87591.97, '2024-07': 89090.31, '2024-08': 90588.64, '2024-10': 93585.32, '2024-11': 95083.66,
    '2024-12': 96582
},
            ethereum: {
    '2020-01': 146.53, '2020-03': 264.86, '2020-04': 324.02, '2020-05': 383.19, '2020-06': 442.35,
    '2020-07': 501.52, '2020-08': 560.68, '2020-09': 619.85, '2020-10': 679.01, '2020-11': 738.17,
    '2020-12': 797.34, '2021-01': 856.5, '2021-03': 974.83, '2021-04': 1034.0, '2021-05': 1093.16,
    '2021-06': 1152.32, '2021-07': 1211.49, '2021-08': 1270.65, '2021-10': 1388.98, '2021-12': 1507.31,
    '2022-01': 1566.48, '2022-03': 1684.8, '2022-04': 1743.97, '2022-05': 1803.13, '2022-06': 1862.3,
    '2022-07': 1921.46, '2022-08': 1980.63, '2022-09': 2039.79, '2022-10': 2098.96, '2022-12': 2217.28,
    '2023-01': 2276.45, '2023-03': 2394.78, '2023-04': 2453.94, '2023-05': 2513.11, '2023-06': 2572.27,
    '2023-07': 2631.44, '2023-08': 2690.6, '2023-09': 2749.76, '2023-10': 2808.93, '2023-11': 2868.09,
    '2023-12': 2927.26, '2024-01': 2986.42, '2024-03': 3104.75, '2024-04': 3163.91, '2024-05': 3223.08,
    '2024-06': 3282.24, '2024-07': 3341.41, '2024-08': 3400.57, '2024-10': 3518.9, '2024-11': 3578.07,
    '2024-12': 3637.23
}

        };

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const investmentType = document.getElementById('investmentType').value;
            const investmentAmount = parseFloat(document.getElementById('investmentAmount').value);
            const startMonth = document.getElementById('startMonth').value;
            const endMonth = document.getElementById('endMonth').value;

            if (!investmentData[investmentType][startMonth] || !investmentData[investmentType][endMonth]) {
                resultText.textContent = 'Seçilen tarih aralığında veri bulunamadı.';
                result.classList.remove('hidden');
                return;
            }

            const startPrice = investmentData[investmentType][startMonth];
            const endPrice = investmentData[investmentType][endMonth];
            const valueNow = (investmentAmount / startPrice) * endPrice;

            resultText.textContent = `Yatırımınız şu anda yaklaşık ${valueNow.toFixed(2)} TL değerinde.`;
            result.classList.remove('hidden');
        });
    </script>






            
            
       
    
</body>
<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="../assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="../assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="../assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="../assets/js/demo.js"></script>

</html>
