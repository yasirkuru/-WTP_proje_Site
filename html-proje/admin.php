<?php
// Veritabanı bağlantısı için kullanılan global değişken
global $conn;

// Oturum başlatılır
session_start();

// Eğer oturum açan kullanıcı admin değilse veya oturum açılmamışsa, giriş sayfasına yönlendirilir
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header("Location: sing.html");
  exit();
}

// Veritabanı bağlantısını içeren dosya dahil edilir
include('db.php');

// Eğer formdan veri gönderilmişse ve e-posta ile şifre alanları doluysa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
  // Yeni e-posta ve şifre değerlerini al ve boşlukları temizle
  $new_email = trim($_POST['email']);
  $new_password = trim($_POST['password']);

  // Mevcut admin e-posta adresini oturumdan al
  $current_email = $_SESSION['admin_email'];

  // Şifreyi şifrelemek için `password_hash` fonksiyonu kullanılır
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // Yeni e-posta ve şifreyi güncelleyen SQL sorgusu hazırlanır
  $update_sql = "UPDATE admin SET email=?, sifre=? WHERE email=?";
  $stmt = $conn->prepare($update_sql); // SQL sorgusu hazırlanır
  if ($stmt === false) {
    // Eğer sorgu hazırlanamazsa hata mesajı gösterilir
    die("Hata: SQL sorgusu hazırlanamadı. " . $conn->error);
  }

  // Hazırlanan sorguya parametreler bağlanır
  $stmt->bind_param("sss", $new_email, $hashed_password, $current_email);

  // Sorgu çalıştırılır ve sonuç kontrol edilir
  if ($stmt->execute()) {
    // Güncelleme başarılıysa oturumdaki admin e-postası da güncellenir
    $_SESSION['admin_email'] = $new_email;
    echo "<script>alert('Bilgiler başarıyla güncellendi.');</script>";
  } else {
    // Güncelleme başarısızsa hata mesajı gösterilir
    echo "<script>alert('Hata: Bilgiler güncellenemedi. " . htmlspecialchars($stmt->error) . "');</script>";
  }

  // Sorgu kapatılır
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gülcan Ticaret</title>
  <link rel="stylesheet" href="css/admin.css">
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
<body>


<!-- Main Container -->
<div class="container">
  <h2>Admin E-posta ve Şifre Değiştir</h2>
  <form method="POST">
    <label for="email">Yeni E-posta:</label>
    <input type="email" id="email" name="email" placeholder="admin@ornek.com" required>
    <label for="password">Yeni Şifre:</label>
    <input type="password" id="password" name="password" placeholder="*******" required>
    <button type="submit">Güncelle</button>
  </form>

  <h2>Kullanıcılar</h2>
  <table class="table">
    <thead>
    <tr>
      <th>ID</th>
      <th>Ad</th>
      <th>Soyad</th>
      <th>Email</th>
      <th>Telefon</th>
    </tr>
    </thead>
    <tbody>
    <?php
    include('db.php'); // Veritabanı bağlantısını tekrar oluştur

    $sql = "SELECT * FROM kullanici";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["ad"]) . "</td><td>" . htmlspecialchars($row["soyad"]) . "</td><td>" . htmlspecialchars($row["email"]) . "</td><td>" . htmlspecialchars($row["telefon"]) . "</td></tr>";
      }
    } else {
      echo "<tr><td colspan='5'>Kayıt bulunamadı</td></tr>";
    }
    ?>
    </tbody>
  </table>

  <h2>Adresler</h2>
  <table class="table">
    <thead>
    <tr>
      <th>ID</th>
      <th>İl</th>
      <th>İlçe</th>
      <th>Mahalle</th>
      <th>Sokak</th>
      <th>Bina No</th>
      <th>Kapı No</th>
      <th>Apartman Adı</th>
      <th>Adres Tarif</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM adres";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["il"]) . "</td><td>" . htmlspecialchars($row["ilce"]) . "</td><td>" . htmlspecialchars($row["mahalle"]) . "</td><td>" . htmlspecialchars($row["sokak"]) . "</td><td>" . htmlspecialchars($row["binaNo"]) . "</td><td>" . htmlspecialchars($row["kapiNo"]) . "</td><td>" . htmlspecialchars($row["apartmanAdi"]) . "</td><td>" . htmlspecialchars($row["adresTarif"]) . "</td></tr>";
      }
    } else {
      echo "<tr><td colspan='9'>Kayıt bulunamadı</td></tr>";
    }
    ?>
    </tbody>
  </table>
</div>

<script src="js/main.js" defer></script>
<script src="js/open.js" defer></script>
<script src="js/oturum.js" defer></script>
</body>
</html>
