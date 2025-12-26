const plusButtons = document.querySelectorAll('.plus');
const minusButtons = document.querySelectorAll('.minus');
const qtySpans = document.querySelectorAll('.qty');
const cartCount = document.getElementById('cartCount');

//TODO quantity nuk duhet te kaloje stokun

plusButtons.forEach((btn, i) => {
    btn.addEventListener('click', () => {
        let qty = parseInt(qtySpans[i].textContent);
        qty++;
        qtySpans[i].textContent = qty;
        updateTotal();
    });
});

minusButtons.forEach((btn, i) => {
    btn.addEventListener('click', () => {
        let qty = parseInt(qtySpans[i].textContent);
        if(qty > 1) qty--;
        qtySpans[i].textContent = qty;
        updateTotal();
    });
});

// Funksioni për të hequr produktin
function removeProd(btn) {
    const product = btn.closest('.cart-product');
    product.remove();
    updateTotal();
}

// Kalkulimi i totalit
function updateTotal() {
    const products = document.querySelectorAll('.cart-product');
    let total = 0;
    products.forEach(prod => {
        const price = parseInt(prod.querySelector('.price').dataset.price);
        const qty = parseInt(prod.querySelector('.qty').textContent);
        total += price * qty;
    });
    document.getElementById('total').textContent = total;
    cartCount.textContent = products.length;
}

// Initialize total
updateTotal();