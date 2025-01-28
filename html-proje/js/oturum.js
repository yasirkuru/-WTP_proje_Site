document.addEventListener('DOMContentLoaded', function() {
  const authButtons = document.getElementById('auth-buttons');
  if (!authButtons) {
    console.error('auth-buttons element not found');
    return;
  }

  const userRole = localStorage.getItem('userRole');
  if (userRole) {

    authButtons.innerHTML = ''; // Mevcut butonları temizle

    // Oturumu kapat butonunu ekle
    const logoutButton = document.createElement('li');
    logoutButton.classList.add('button-item');
    logoutButton.innerHTML = '<a href="logout.php"><i class="fa fa-sign-out-alt"></i> Oturumu Kapat</a>';
    authButtons.appendChild(logoutButton);

    if (userRole === 'admin') {
      // Admin panel butonunu ekle
      const adminButton = document.createElement('li');
      adminButton.classList.add('button-item');
      adminButton.innerHTML = '<a href="admin.php"><i class="fa fa-user-shield"></i> Admin Paneli</a>';
      authButtons.appendChild(adminButton);
    } else if (userRole === 'user') {
      // Sepet butonunu ekle
      const cartButton = document.createElement('li');
      cartButton.classList.add('button-item');
      cartButton.innerHTML = '<a href="sepet.html"><i class="fa fa-shopping-cart"></i> Sepet</a>';
      authButtons.appendChild(cartButton);
    }
  } else {
    authButtons.innerHTML = `
      <li class="button-item"><a href="sing.html"><i class="fa fa-sign-in-alt"></i> Giriş</a></li>
      <li class="button-item"><a href="login-İlk.html"><i class="fa fa-user-plus"></i> Kayıt Ol</a></li>
    `;
  }
});


