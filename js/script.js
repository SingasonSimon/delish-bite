const hamburgerButton = document.querySelector('.hamburger-menu');
const navLinks = document.querySelector('.nav-links');

hamburgerButton.addEventListener('click', ( ) => {
    navLinks.classList.toggle('active');
});

// --- Shopping Cart Logic ---

let cart = [];


const addToCartButtons = document.querySelectorAll('.add-to-cart-btn'); 
const cartItemsContainer = document.querySelector('#cart-items');
const cartTotalPriceEl = document.querySelector('#cart-total-price');

addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        const card = button.closest('.menu-card');
        const itemName = card.querySelector('.food-name').innerText;
        const itemPriceString = card.querySelector('.food-price').innerText;

        const itemPrice = parseInt(itemPriceString.replace('KSh ', ''));

        const cartItem = {
            name: itemName,
            price: itemPrice
        };
        cart.push(cartItem);

        updateCartDisplay();
    });
});


function updateCartDisplay() {
    cartItemsContainer.innerHTML = '';
    let totalPrice = 0;
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<li class="cart-empty-msg">Your cart is empty.</li>';
        cartTotalPriceEl.innerText = 0;
        return;
    }

    cart.forEach(item => {
        const li = document.createElement('li');
        li.classList.add('cart-item');
        li.innerText = `${item.name} - KSh ${item.price}`;
        cartItemsContainer.appendChild(li);
        totalPrice += item.price;
    });

    cartTotalPriceEl.innerText = totalPrice;
}