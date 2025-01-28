<?php
global $conn;
header('Content-Type: application/json; charset=utf-8');

include('db.php'); // Veritabanı bağlantısını dahil et

// Bağlantıyı kontrol et
if ($conn->connect_error) {
  $response = array('error' => true, 'message' => "Bağlantı hatası: " . $conn->connect_error);
  echo json_encode($response);
  exit();
}

// Form verilerini al
$ad = $_POST['ad'];
$soyad = $_POST['soyad'];
$email = $_POST['email'];
$telefon = $_POST['telefon'];
$sifre = $_POST['sifre'];
$sifreTekrar = $_POST['sifreTekrar'];

// Şifrelerin eşleştiğini kontrol et
if ($sifre !== $sifreTekrar) {
  $response = array('error' => true, 'message' => "Hata: Şifreler eşleşmiyor.");
  echo json_encode($response);
  exit();
}

// E-posta adresinin zaten kayıtlı olup olmadığını kontrol et
$sql = "SELECT * FROM kullanici WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $response = array('error' => true, 'message' => "Hata: Bu e-posta adresi zaten kayıtlı.");
  echo json_encode($response);
  exit();
}

// Şifreyi şifrele
$hashed_password = password_hash($sifre, PASSWORD_DEFAULT);

// Veritabanına ekle
$sql = "INSERT INTO kullanici (ad, soyad, email, telefon, sifre)
VALUES ('$ad', '$soyad', '$email', '$telefon', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
  $response = array('error' => false, 'message' => "Başarılı kayıt");
  echo json_encode($response);
  exit();
} else {
  $response = array('error' => true, 'message' => "Hata: " . $sql . "<br>" . $conn->error);
  echo json_encode($response);
  exit();
}

$conn->close();

