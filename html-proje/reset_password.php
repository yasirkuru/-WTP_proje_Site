<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php'); // Veritabanı bağlantısını dahil et
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
  $token = $_GET['token'];

  // Tokeni veritabanında kontrol et
  $stmt = $conn->prepare("SELECT reset_token, reset_expiry FROM kullanici WHERE reset_token = ?");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Veritabanındaki bilgileri al
    $row = $result->fetch_assoc();
    $db_token = $row['reset_token'];
    $db_expiry = $row['reset_expiry'];

    // Süresini kontrol et
    $current_time = date("Y-m-d H:i:s");

    if ($db_expiry > $current_time) {
      // Token ve süre geçerli, parola sıfırlama formunu göster
      echo '
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gülcan Ticaret</title>
    <link rel="stylesheet" href="css/reset_password.css">
    <link rel="stylesheet" href="css/header.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!--  Bar  -->
    <header>
        <div class="grid-1">
            <div class="header_h1">
                <h1>Gülcan Ticaret</h1>
            </div>
            <div class="header_nav">
                <nav>
                    <ul>
                        <li><a href="main.html">Ana Sayfa</a></li>
                        <li><a href="#contact">İletişim</a></li>
                        <li><a href="products.html">Ürünler</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="grid-2">
            <ul id="auth-buttons">
                <li class="button-item">
                    <a href="sing.html">
                        <i class="fa fa-shopping-cart"></i> Giriş
                    </a>
                </li>
                <li class="button-item">
                    <a href="login-İlk.html">
                        <i class="fa fa-user"></i> Kayıt Ol
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <!--  Main Page -->
    <main>
        <section id="login-page-section">
            <!--  Form Page Header -->
            <div class="login-page">
                <div class="login-page-header">
                    <!-- Aşamalar Başlığı -->
                    <div class="steps">
                        <div class="step" id="step1">
                            <span class="step-number">1</span>
                        </div>
                        <div class="connector" id="connector"></div>
                        <div class="step" id="step2">
                            <span class="step-number">2</span>
                        </div>
                    </div>
                    <h3>Parolayı Sıfırlama </h3>
                </div>
                <!-- Şifre Bilgiler Formu -->
                <form class="kayitFormu" id="reset-password-form" action="update_password.php" method="POST">
                    <div class="form-group">
                        <label for="password">Yeni Parola</label>
                        <input type="password" id="password" name="password" placeholder="Yeni parolanızı girin" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Parolayı Onayla</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="Parolanızı tekrar girin" required>
                    </div>
                    <input type="hidden" name="token" value="' . $token . '">
                    <button type="submit">Parolayı Güncelle</button>
                </form>
            </div>
        </section>
    </main>
    <script src="js/sing.js" defer></script>
    <script src="js/open.js" defer></script>
</body>
</html>';
    } else {
      // Süresi dolmuşsa
      echo "Hata: Bu parola sıfırlama bağlantısının süresi dolmuş.<br>";
      echo "Gönderilen token: " . htmlspecialchars($token) . "<br>";
      echo "Veritabanındaki token: " . htmlspecialchars($db_token) . "<br>";
      echo "Veritabanındaki süre: " . htmlspecialchars($db_expiry) . "<br>";
      echo "Şu anki zaman: " . htmlspecialchars($current_time) . "<br>";
    }
  } else {
    // Token karşılaştırma hatası
    $stmt = $conn->prepare("SELECT reset_token, reset_expiry FROM kullanici");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      echo "Kullanıcı Token: " . htmlspecialchars($row['reset_token']) . " - Süresi: " . htmlspecialchars($row['reset_expiry']) . "<br>";
    }
    echo "Hata: Bu parola sıfırlama bağlantısı geçersiz.<br>";
    echo "Gönderilen token: " . htmlspecialchars($token) . "<br>";
  }
}

