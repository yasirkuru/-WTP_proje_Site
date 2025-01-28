<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kayitlar";

// Veritabanına bağlan
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
  die("Bağlantı hatası: " . $conn->connect_error);
}

