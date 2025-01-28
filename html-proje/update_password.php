<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php'); // Veritabanı bağlantısını dahil et
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $token = $_POST['token'];
  $new_password = trim($_POST['password']);
  $confirm_password = trim($_POST['confirm_password']);

  // Parolaların eşleştiğini kontrol et
  if ($new_password !== $confirm_password) {
    echo "<script>alert('Hata: Parolalar eşleşmiyor.'); window.location.href = 'reset_password.php?token=$token';</script>";
    exit();
  }

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
      // Token ve süre geçerli, parolayı güncelle
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE kullanici SET sifre=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?");
      $stmt->bind_param("ss", $hashed_password, $token);
      $stmt->execute();

      echo "<script>alert('Parolanız başarıyla güncellendi.'); window.location.href = 'sing.html';</script>";
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
    echo "Hata: Bu parola sıfırlama bağlantısı geçersiz.<br>";
    echo "Gönderilen token: " . htmlspecialchars($token) . "<br>";
  }
}


