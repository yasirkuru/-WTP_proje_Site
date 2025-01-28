

document.getElementById('sing-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Formun gönderilmesini durdur

  const form = event.target;
  const formData = new FormData(form);

  fetch('sing.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.text()) // Yanıtı düz metin olarak al
    .then(data => {
      alert(data); // Yanıtı alert ile göster
      if (data.includes("Giriş başarılı")) {
        // Oturum bilgisini localStorage'e kaydet
        if (data.includes("Admin")) {
          localStorage.setItem('userRole', 'admin');
        } else if (data.includes("Kullanıcı")) {
          localStorage.setItem('userRole', 'user');
        }
        window.location.href = 'main.html'; // Ana sayfaya yönlendir
      }
    })
    .catch(error => {
      console.error('Hata:', error);
      alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    });
});
