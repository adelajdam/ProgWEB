<?php
session_start();

// Inicializo cart nëse nuk ekziston
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Shto produkt në cart nga Product Details (POST Ajax)
if(isset($_POST['add_cart']) && isset($_POST['id'])){
    $product = [
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'img' => $_POST['img']
    ];

    $exists = false;
    foreach($_SESSION['cart'] as &$item){
        if($item['id'] == $product['id']){
            $item['qty'] += 1;
            $exists = true;
            break;
        }
    }

    if(!$exists){
        $product['qty'] = 1;
        $_SESSION['cart'][] = $product;
    }

    echo json_encode(['status'=>'added']);
    exit;
}


// Heq produkt nga cart
if (isset($_POST['remove_cart']) && isset($_POST['product_id'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($item) => $item['id'] != $_POST['product_id']);
    echo json_encode(['status' => 'removed']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link rel="stylesheet" href="Cart/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="header">
    <h2>Cart</h2>
    <div class="icons">
        <a href="index.php"><i class="fas fa-home"></i></a>
        <span id="cartCount"><?= count($_SESSION['cart']) ?></span>
    </div>
</div>

<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Cart-i është bosh.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['cart'] as $product): ?>
            <div class="cart-product" data-id="<?= $product['id'] ?>">
                <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price" data-price="<?= $product['price'] ?>"><?= $product['price'] ?> L</div>
                </div>
                <div class="actions">
                    <div class="quantity">
                        <button class="minus">-</button>
                        <span class="qty"><?= $product['qty'] ?></span>
                        <button class="plus">+</button>
                    </div>
                    <div class="remove">
                        <button class="remove-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="cart-summary">
        <h3>Total: <span id="total">0</span>L</h3>
        <button class="checkout-btn">Checkout</button>
    </div>
</div>

<script>
    // Quantity buttons
    const plusButtons = document.querySelectorAll('.plus');
    const minusButtons = document.querySelectorAll('.minus');
    const qtySpans = document.querySelectorAll('.qty');
    const cartCount = document.getElementById('cartCount');

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

    // Zgjidh të gjithë butonat add-to-cart/add-cart
    document.querySelectorAll('.add-to-cart, .add-cart').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const product = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                price: btn.dataset.price,
                img: btn.dataset.img
            };

            const formData = new FormData();
            formData.append('add_cart', 1);
            formData.append('id', product.id);
            formData.append('name', product.name);
            formData.append('price', product.price);
            formData.append('img', product.img);

            fetch('cart.php', {method:'POST', body:formData})
                .then(res=>res.json())
                .then(data=>{
                    if(data.status==='added'){
                        btn.textContent = "Added!";
                        btn.disabled = true;
                    }
                });
        });
    });


    // Initialize total
    updateTotal();

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

    // Remove product
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const productDiv = btn.closest('.cart-product');
            const productId = productDiv.dataset.id;

            // Ajax për heqje nga session
            const formData = new FormData();
            formData.append('remove_cart', 1);
            formData.append('product_id', productId);

            fetch('cart.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
                .then(data => {
                    if(data.status === 'removed'){
                        productDiv.remove();
                        updateTotal();
                    }
                });
        });
    });
</script>

</body>
</html>
