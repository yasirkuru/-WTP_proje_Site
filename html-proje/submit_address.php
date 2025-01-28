<?php
global $conn;
include('db.php'); // Veritabanı bağlantısını dahil et

// Bağlantıyı kontrol et
if ($conn->connect_error) {
  die("Bağlantı hatası: " . $conn->connect_error);
}

// Form verilerini al
$il = $_POST['il'];
$ilce = $_POST['ilce'];
$mahalle = $_POST['mahalle'];
$sokak = $_POST['sokak'];
$binaNo = $_POST['binaNo'];
$kapiNo = $_POST['kapiNo'];
$apartmanAdi = $_POST['apartmanAdi'];
$adresTarif = $_POST['adresTarif'];

// Veritabanına ekle
$sql = "INSERT INTO adres (il, ilce, mahalle, sokak, binaNo, kapiNo, apartmanAdi, adresTarif)
VALUES ('$il', '$ilce', '$mahalle', '$sokak', '$binaNo', '$kapiNo', '$apartmanAdi', '$adresTarif')";

if ($conn->query($sql) === TRUE) {
  // Başarılı kayıt, giriş sayfasına yönlendir
  header("Location: sing.html");
  exit();
} else {
  die("Hata: " . $sql . "<br>" . $conn->error);
}

$conn->close();

