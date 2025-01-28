
function addToCart(name, price, image) {
  const userRole = localStorage.getItem('userRole');
  if (userRole !== 'admin') {
    alert('Lütfen giriş yapınız.');
    return;
  }

  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let product = {
    name: name,
    price: price,
    image: image,
    quantity: 1
  };
  cart.push(product);
  localStorage.setItem('cart', JSON.stringify(cart));
  alert('Ürün sepete eklendi!');
}

window.onload = function() {
  if (!localStorage.getItem('user')) {  // Eğer kullanıcı oturumu açmamışsa
    let buttons = document.querySelectorAll(".btn,.btn2");
    buttons.forEach(button => {
      button.disabled = true;
    });
  }
};
