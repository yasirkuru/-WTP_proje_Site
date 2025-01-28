// Sayfa yüklendiğinde nav barın aşağıya kaymasını sağlayan fonksiyon
function showNavbar() {
  const navbar = document.querySelector('header');
  if (navbar) {
    navbar.classList.remove('show');
    setTimeout(() => {
      navbar.classList.add('show');
    }, 50); // Kısa bir gecikme ekleyerek animasyonun tetiklenmesini sağla
  }
}

// Tüm linklerin sayfa yüklemeden önce animasyonu tetiklemesini sağla
document.addEventListener('DOMContentLoaded', function() {
  showNavbar(); // İlk sayfa yüklemesinde nav bar animasyonunu göster

  document.querySelectorAll('a').forEach(function(link) {
    link.addEventListener('click', function(event) {
      const href = this.getAttribute('href');

      // "target=_blank" bağlantılarını atla
      if (this.target === '_blank') {
        return;
      }

      event.preventDefault();

      if (href.startsWith('#')) {
        // Sayfa içi bağlantılar için smooth scroll animasyonu
        const targetElement = document.querySelector(href);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop,
            behavior: 'smooth' // Smooth scroll animasyonu
          });
        }
        // Geçiş animasyonunu ekle
        showNavbar();
        return;
      }

      // Nav bar animasyonunu tetikle
      document.querySelector('body').classList.add('fade-out');
      showNavbar();

      setTimeout(function() {
        window.location.href = href;
      }, 700); // Geçiş animasyon süresi ile aynı olmalı
    });
  });
});
