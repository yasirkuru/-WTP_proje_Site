<?php
global $conn;
session_start(); // Oturum başlat
include('db.php'); // Veritabanı bağlantısı

header('Content-Type: text/plain; charset=utf-8'); // Yanıtın düz metin olmasını sağla

$email = trim($_POST['email']);
$sifre = trim($_POST['sifre']);

// Admin e-posta adresini kontrol et
$admin_stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
$admin_stmt->bind_param("s", $email);
$admin_stmt->execute();
$admin_result = $admin_stmt->get_result();

if ($admin_result->num_rows > 0) {
  // Admin şifresini kontrol et
  $row = $admin_result->fetch_assoc();
  if (password_verify($sifre, $row['sifre'])) {
    $_SESSION['is_admin'] = true; // Admin kontrolü için oturumda flag
    $_SESSION['admin_email'] = $email; // Admin e-posta adresini oturumda sakla
    echo "Giriş başarılı - Admin"; // Admin giriş mesajı
    $admin_stmt->close();
    $conn->close();
    exit(); // İşlemi burada sonlandır
  }
}

// Kullanıcı e-posta adresini kontrol et
$user_stmt = $conn->prepare("SELECT * FROM kullanici WHERE email = ?");
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_valid = $user_result->num_rows > 0;

if (!$user_valid) {
  echo "Hata: E-posta bulunamadı.";
} else {
  // Kullanıcı şifresini kontrol et
  $row = $user_result->fetch_assoc();
  if (password_verify($sifre, $row['sifre'])) {
    $_SESSION['is_admin'] = false; // Normal kullanıcı için oturumda flag
    echo "Giriş başarılı - Kullanıcı"; // Normal kullanıcı giriş mesajı
  } else {
    echo "Hata: Şifre yanlış.";
  }
}

$admin_stmt->close();
$user_stmt->close();
$conn->close();

