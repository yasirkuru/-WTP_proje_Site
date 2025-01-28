<?php
session_start();
session_destroy();
?>
<script>
  // Oturum kapatıldığında, kullanıcı rolünü ve sepeti sıfırlıyoruz
  localStorage.removeItem('userRole');
  localStorage.removeItem('cart'); // Sepeti sıfırlama
  window.location.href = 'sing.html'; // Giriş sayfasına yönlendir
</script>

