<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php'); // Veritabanı bağlantısını dahil et
require 'vendor/autoload.php'; // PHPMailer otomatik yükleyici dosyasını dahil et
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);

  // Kullanıcının e-posta adresini kontrol et
  $stmt = $conn->prepare("SELECT * FROM kullanici WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Eski ve süresi dolmuş token'ları temizle
    $stmt = $conn->prepare("UPDATE kullanici SET reset_token=NULL, reset_expiry=NULL WHERE email=? OR reset_expiry < NOW()");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Yeni token oluştur
    if (function_exists('random_bytes')) {
      $token = bin2hex(random_bytes(50)); // Rastgele bir token oluştur (PHP 7 ve üstü)
    } else {
      $token = bin2hex(openssl_random_pseudo_bytes(50)); // PHP 7 altı için alternatif
    }
    $reset_link = "http://localhost/javaprojelerim/html-proje/reset_password.php?token=$token";

    // Kullanıcının veritabanına yeni token ve son kullanma tarihi ekle
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // 1 saat geçerli
    $stmt = $conn->prepare("UPDATE kullanici SET reset_token=?, reset_expiry=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expiry, $email);
    $stmt->execute();

    // PHPMailer ile e-posta gönderimi
    $mail = new PHPMailer(true);
    try {
      // Sunucu ayarları
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'yasirdonmez12345@gmail.com'; // Kendi e-posta adresinizi girin
      $mail->Password = 'qnom lgdl yjbf mips'; // E-posta şifrenizi girin
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Karakter seti ve kodlama ayarları
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';

      // Alıcı bilgileri
      $mail->setFrom('your-email@gmail.com', 'Gülcan Ticaret');
      $mail->addAddress($email);

      // İçerik
      $mail->isHTML(true);
      $mail->Subject = 'Parola Sıfırlama İsteği';
      $mail->Body = "Parolanızı sıfırlamak için aşağıdaki bağlantıya tıklayın:<br><br><a href='$reset_link'>$reset_link</a>";
      $mail->AltBody = "Parolanızı sıfırlamak için aşağıdaki bağlantıya tıklayın:\n\n$reset_link";

      $mail->send();
      echo "<script>alert('Parola sıfırlama bağlantısı e-posta adresinize gönderildi.'); window.location.href = 'sing.html';</script>";
    } catch (Exception $e) {
      echo "E-posta gönderimi başarısız oldu. Hata: {$mail->ErrorInfo}";
    }
  } else {
    echo "<script>alert('Hata: Bu e-posta adresi kayıtlı değil.'); window.location.href = 'reset_request.html';</script>";
  }
}

