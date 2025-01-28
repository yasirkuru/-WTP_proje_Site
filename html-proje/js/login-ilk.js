// Scroll ile header küçültme
window.addEventListener("scroll", function () {
  const header = document.querySelector("header");
  if (window.scrollY > 50) {
    header.classList.add("shrink");
  } else {
    header.classList.remove("shrink");
  }
});



// login-ilk.html için JavaScript kodu
document.getElementById('personal-info-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Formun gönderilmesini durdur

  const form = event.target;
  const formData = new FormData(form);

  fetch('submit.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert(data.message); // Hata mesajını alert ile göster
      } else {
        window.location.href = 'login-son.html';
      }
    })
    .catch(error => {
      console.error('Hata:', error);
      alert('Bir hata oluştu. Lütfen tekrar deneyin.');
    });
});






