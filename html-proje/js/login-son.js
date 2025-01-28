// Scroll ile header küçültme
window.addEventListener("scroll", function () {
  const header = document.querySelector("header");
  if (window.scrollY > 50) {
    header.classList.add("shrink");
  } else {
    header.classList.remove("shrink");
  }
});





// login-son.html için JavaScript kodu
document.getElementById('address-info-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Formun gönderilmesini durdur

  const form = event.target;
  const formData = new FormData(form);

  // Tüm alanların doldurulduğunu kontrol et
  for (let [name, value] of formData.entries()) {
    if (!value) {
      alert('Hata: Lütfen tüm alanları doldurun.');
      return;
    }
  }
  // Form verilerini AJAX ile gönder
  fetch('submit_address.php', {
    method: 'POST',
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Sunucu yanıtı geçersiz.');
      }
      return response.text(); // JSON yerine düz metin yanıtını al
    })
    .then(data => {
      console.log('Sunucu yanıtı alındı:', data); // Konsola yazdır
      if (data.includes('Hata:')) {
        alert(data); // Hata mesajını alert ile göster
      } else {
        window.location.href = 'sing.html'; // Başarılıysa giriş sayfasına yönlendir
      }
    })
    .catch(error => {
      console.error('Hata:', error);
      alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    });
});

