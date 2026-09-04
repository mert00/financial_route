<?php
session_start();

// Kullanıcının oturum açıp açmadığını kontrol edelim
if (!isset($_SESSION['user_id'])) {
    // Oturum açmamışsa, giriş sayfasına yönlendirelim
    header('Location: login_signup.php');
    exit();
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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap JS ve jQuery (Dropdown için gerekli) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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



        <!--Butonlar Bölümü-->

           
        <!-- Doviz Butonu -->
          <button type="button" class="btn btn-primary"  onclick="showWidget('doviz-widget')" style="width: 150px !important;">Döviz</button>
        <!-- Altın Butonu -->
        <button type="button" class="btn btn-warning" style="width: 150px;" onclick="showWidget('altin-widget')">Altın</button>

        
       <!-- Kripto Butonu -->
    <button type="button" class="btn btn-secondary" style="width: 150px !important;" onclick="showWidget('kripto-widget')">Kripto</button>


        <!-- Borsa Butonu -->
    <button type="button" class="btn btn-danger" style="width: 150px !important;" onclick="showWidget('borsa-widget')">Borsa</button>

        <!--Tüm Yatırım Araçları Butonu-->
        <button type="button" class="btn btn-info" style="width: 200px !important;" onclick="showWidget('yatirim-widget')">Tüm Yatırım Araçları</button>      





<!-- Döviz Widget -->
<div id="doviz-widget" style="display: block; width: 100%; height: 80%; margin-top: 20px;">
  <div class="tradingview-widget-container">
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
      {
      "symbols": [
        [
          "DOLAR",
          "FX:USDTRY|1D"
        ],
        [
          "EURO",
          "CAPITALCOM:EURTRY|1D"
        ],
        [
          "STERLİN",
          "IBKR:GBPTRY|1D"
        ],
        [
          "İSVİÇRE FRANGI",
          "IBKR:CHFTRY|1D"
        ],
        [
          "KANADA DOLARI",
          "SAXO:CADTRY|1D"
        ]
      ],
      "chartOnly": false,
      "width": "100%",
      "height": "100%",
      "locale": "tr",
      "colorTheme": "light",
      "autosize": true,
      "showVolume": false,
      "showMA": false,
      "hideDateRanges": false,
      "hideMarketStatus": false,
      "hideSymbolLogo": false,
      "scalePosition": "right",
      "scaleMode": "Normal",
      "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
      "fontSize": "10",
      "noTimeScale": false,
      "valuesTracking": "1",
      "changeMode": "price-and-percent",
      "chartType": "area",
      "maLineColor": "#2962FF",
      "maLineWidth": 1,
      "maLength": 9,
      "headerFontSize": "medium",
      "lineWidth": 2,
      "lineType": 0,
      "dateRanges": [
        "1d|1",
        "1m|30",
        "3m|60",
        "6m|120",
        "12m|1D",
        "60m|1W",
        "all|1M"
      ]
      }
    </script>
  </div>
</div>

<!-- Altın Widget -->
<div id="altin-widget" style="display: none; width: 100%; height: 90%; margin-top: 20px;">
  <div class="tradingview-widget-container">
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
      {
      "symbols": [
        [
          "GRAM ALTIN",
          "FX_IDC:XAUTRYG|1D"
        ],
        [
          "ONS ALTIN",
          "OANDA:XAUUSD|1D"
        ]
      ],
      "chartOnly": false,
      "width": "100%",
      "height": "85%",
      "locale": "tr",
      "colorTheme": "light",
      "autosize": false,
      "showVolume": false,
      "showMA": false,
      "hideDateRanges": false,
      "hideMarketStatus": false,
      "hideSymbolLogo": false,
      "scalePosition": "right",
      "scaleMode": "Normal",
      "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
      "fontSize": "10",
      "noTimeScale": false,
      "valuesTracking": "1",
      "changeMode": "price-and-percent",
      "chartType": "area",
      "maLineColor": "#2962FF",
      "maLineWidth": 1,
      "maLength": 9,
      "headerFontSize": "medium",
      "lineWidth": 2,
      "lineType": 0,
      "dateRanges": [
        "1d|1",
        "1m|30",
        "3m|60",
        "6m|120",
        "12m|1D",
        "60m|1W",
        "all|1M"
      ]
      }
    </script>
  </div>
</div>

<!-- Kripto Widget -->
<!-- KRİPTO PARA BİRİMLERİ -->
<div id="kripto-widget" style="display: block; width: 100%; height: 80%; margin-top: 20px;">
  <div class="tradingview-widget-container">
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
      {
      "symbols": [
        [
          "BİTCOİN",
          "COINBASE:BTCUSD|1D"
        ],
        [
          "ETHERİUM",
          "COINBASE:ETHUSD|1D"
        ],
        [
          "SOLANA",
          "COINBASE:SOLUSD|1D"
        ],
        [
          "XRP",
          "COINBASE:XRPUSD|1D"
        ],
        [
          "AVAX",
          "COINBASE:AVAXUSD|1D"
        ],
        [
          "BNB",
          "BINANCE:BNBUSD|1D"
        ]
      ],
      "chartOnly": false,
      "width": "100%",
      "height": "100%",
      "locale": "tr",
      "colorTheme": "light",
      "autosize": true,
      "showVolume": false,
      "showMA": false,
      "hideDateRanges": false,
      "hideMarketStatus": false,
      "hideSymbolLogo": false,
      "scalePosition": "right",
      "scaleMode": "Normal",
      "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
      "fontSize": "10",
      "noTimeScale": false,
      "valuesTracking": "1",
      "changeMode": "price-and-percent",
      "chartType": "area",
      "maLineColor": "#2962FF",
      "maLineWidth": 1,
      "maLength": 9,
      "headerFontSize": "medium",
      "lineWidth": 2,
      "lineType": 0,
      "dateRanges": [
        "1d|1",
        "1m|30",
        "3m|60",
        "6m|120",
        "12m|1D",
        "60m|1W",
        "all|1M"
      ]
      }
    </script>
  </div>
</div>


<!--Borsa Bölümü-->
<!-- TradingView Widget BEGIN -->
<div id="borsa-widget" style="display: block; width: 100%; height: 80%; margin-top: 20px;">
  <div class="tradingview-widget-container">
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
    {
      "symbols": [
        [
          "BİST 100",
          "BIST:XU100|1D"
        ],
        [
          "BİST 50",
          "BIST:XU050|1D"
        ],
        [
          "BİST 30",
          "BIST:XU030|1D"
        ],
        [
          "THYAO",
          "BIST:THYAO|1D"
        ],
        [
          "TÜPRAŞ",
          "BIST:TUPRS|1D"
        ],
        [
          "ENERJİSA",
          "BIST:ENJSA|1D"
        ],
        [
          "YAPI KREDİ",
          "BIST:YKBNK|1D"
        ],
        [
          "KOÇ HOLDİNG",
          "BIST:KCHOL|1D"
        ]
      ],
      "chartOnly": false,
      "width": "100%",
      "height": "100%",
      "locale": "tr",
      "colorTheme": "light",
      "autosize": true,
      "showVolume": false,
      "showMA": false,
      "hideDateRanges": false,
      "hideMarketStatus": false,
      "hideSymbolLogo": false,
      "scalePosition": "right",
      "scaleMode": "Normal",
      "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
      "fontSize": "10",
      "noTimeScale": false,
      "valuesTracking": "1",
      "changeMode": "price-and-percent",
      "chartType": "area",
      "maLineColor": "#2962FF",
      "maLineWidth": 1,
      "maLength": 9,
      "headerFontSize": "medium",
      "lineWidth": 2,
      "lineType": 0,
      "dateRanges": [
        "1d|1",
        "1m|30",
        "3m|60",
        "12m|1D",
        "60m|1W",
        "all|1M"
      ]
    }
    </script>
    </div>
</div>
<!-- TradingView Widget END -->



<!-- Tüm Yatırım Enstrümanları Bölümü-->

<!-- TradingView Widget BEGIN -->
<div id="yatirim-widget" class="tradingview-widget-container" style="height:100%;width:100%">
  <div class="tradingview-widget-container__widget" style="height:calc(100% - 32px);width:100%"></div>
  <div class="tradingview-widget-copyright"><a href="https://tr.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Tüm piyasaları TradingView üzerinden takip edin</span></a></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
  {
  "autosize": true,
  "symbol": "NASDAQ:AAPL",
  "interval": "D",
  "timezone": "Etc/UTC",
  "theme": "light",
  "style": "1",
  "locale": "tr",
  "allow_symbol_change": true,
  "calendar": false,
  "support_host": "https://www.tradingview.com"
}
  </script>
</div>
<!-- TradingView Widget END -->




<!-- Script -->
<script>
  // Sayfa yüklendiğinde sadece döviz widget'ını göster
  document.addEventListener("DOMContentLoaded", function () {
    const allWidgets = document.querySelectorAll('#altin-widget, #kripto-widget, #borsa-widget, #yatirim-widget');
    allWidgets.forEach(widget => widget.style.display = 'none');
    const dovizWidget = document.getElementById('doviz-widget');
    if (dovizWidget) {
      dovizWidget.style.display = 'block';
    }
  });

  function showWidget(widgetId) {
    // Tüm widget'ları seç
    const allWidgets = document.querySelectorAll('#doviz-widget, #altin-widget, #kripto-widget, #borsa-widget, #yatirim-widget');

    // Tüm widget'ları gizle
    allWidgets.forEach(widget => widget.style.display = 'none');

    // Sadece seçilen widget'ı göster
    const selectedWidget = document.getElementById(widgetId);
    if (selectedWidget) {
      selectedWidget.style.display = 'block';
    }
  }
</script>

          
          






            <!-- Yatırım Per -->  
              









            <script>
                // Bugünün tarihini al ve formatla
                const today = new Date();
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = today.toLocaleDateString('tr-TR', options);
            
                // Tarihi #currentDate id'li öğeye yerleştir
                document.getElementById('currentDate').textContent = formattedDate;
            </script>
           
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <ul class="footer-menu">
                            <li>
                                <a href="index.html">
                                    Ana Sayfa
                                </a>
                            </li>
                            <li>
                                <a href="user.html">
                                    Profil
                                </a>
                            </li>
                            
                        </ul>
                        <p class="copyright text-center">
                            <a href="hakkimizda.php">Finans Rotası</a> tarafından tasarlandı
                            <script>document.write(new Date().getFullYear())</script>
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>
    <!--   -->
    <!-- <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
        <a href="#" data-toggle="dropdown">
            <i class="fa fa-cog fa-2x"> </i>
        </a>

        <ul class="dropdown-menu">
			<li class="header-title"> Sidebar Style</li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger">
                    <p>Background Image</p>
                    <label class="switch">
                        <input type="checkbox" data-toggle="switch" checked="" data-on-color="primary" data-off-color="primary"><span class="toggle"></span>
                    </label>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <p>Filters</p>
                    <div class="pull-right">
                        <span class="badge filter badge-black" data-color="black"></span>
                        <span class="badge filter badge-azure" data-color="azure"></span>
                        <span class="badge filter badge-green" data-color="green"></span>
                        <span class="badge filter badge-orange" data-color="orange"></span>
                        <span class="badge filter badge-red" data-color="red"></span>
                        <span class="badge filter badge-purple active" data-color="purple"></span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="header-title">Sidebar Images</li>

            <li class="active">
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="../assets/img/sidebar-1.jpg" alt="" />
                </a>
            </li>
            <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="../assets/img/sidebar-3.jpg" alt="" />
                </a>
            </li>
            <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="..//assets/img/sidebar-4.jpg" alt="" />
                </a>
            </li>
            <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="../assets/img/sidebar-5.jpg" alt="" />
                </a>
            </li>

            <li class="button-container">
                <div class="">
                    <a href="http://www.creative-tim.com/product/light-bootstrap-dashboard" target="_blank" class="btn btn-info btn-block btn-fill">Download, it's free!</a>
                </div>
            </li>

            <li class="header-title pro-title text-center">Want more components?</li>

            <li class="button-container">
                <div class="">
                    <a href="http://www.creative-tim.com/product/light-bootstrap-dashboard-pro" target="_blank" class="btn btn-warning btn-block btn-fill">Get The PRO Version!</a>
                </div>
            </li>

            <li class="header-title" id="sharrreTitle">Thank you for sharing!</li>

            <li class="button-container">
				<button id="twitter" class="btn btn-social btn-outline btn-twitter btn-round sharrre"><i class="fa fa-twitter"></i> · 256</button>
                <button id="facebook" class="btn btn-social btn-outline btn-facebook btn-round sharrre"><i class="fa fa-facebook-square"></i> · 426</button>
            </li>
        </ul>
    </div>
</div>
 -->
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
