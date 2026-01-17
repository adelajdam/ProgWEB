<?php
include 'config.php'; // Lidhja me DB

// Kontrollo nëse ka ID në URL
if(!isset($_GET['id'])){
    die("No product selected.");
}

$product_id = intval($_GET['id']);

// Merr produktin nga DB
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if($result->num_rows != 1){
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Për shembull: ndan description në paragrafë si “ingredients” opsionale
$ingredients = explode(",", $product['description']); // nese ke liste me koma
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> | Cosmico</title>
    <link rel="stylesheet" href="product-details/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
            /* Container për ikonat */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white; /* ngjyra bazë e ikonave */
            font-size: 1.5rem;
        }

        /* Stil për çdo ikonë */
        .nav-icons a {
            color: white; /* ngjyra bazë */
            text-decoration: none;
            transition: color 0.3s ease; /* ndryshimi i ngjyrës butësisht */
        }

        /* Hover efekt */
        .nav-icons a:hover {
            color: #c56a76; /* ngjyra kur hover */
        }

            .add-to-cart {
                background: #b85c68;
                color: white;
                border: none;
                padding: 14px 30px;
                border-radius: 14px;
                font-size: 16px;
                font-weight: 700;
                cursor: pointer;
                transition: 0.3s;
            }

            .add-to-cart:hover {
                background: #a04b56;
            }

    </style>

</head>
<body>
<div class="nav-icons">
    <a href="index.php" title="Home"><i class="fas fa-home"></i></a>
    <a href="favorites.php" title="Fav"><i class="fas fa-heart"></i></a>
    <a href="cart.php" title="Cart"><i class="fa-solid fa-cart-shopping"></i></a>
</div>


<div class="product-details">
    <img id="product-img" src="<?php echo htmlspecialchars($product['picture']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

    <div class="info">
        <h1 id="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p id="product-price" class="price"><?php echo htmlspecialchars($product['price']); ?> L</p>

        <p id="product-desc" class="description collapsed"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <button id="toggle-desc" class="see-more">See more</button>


        <!-- ACTIONS -->
        <div class="actions">

            <div class="quantity">
                <button id="minus">−</button>
                <span id="qty">1</span>
                <button id="plus">+</button>
            </div>

            <button class="add-to-cart"
                    data-id="<?= $product['id'] ?>"
                    data-name="<?= htmlspecialchars($product['name']) ?>"
                    data-price="<?= $product['price'] ?>"
                    data-img="<?= htmlspecialchars($product['img']) ?>"> <!-- sigurohu që këtu është 'img' -->
                <i class="fas fa-shopping-cart"></i> Add to Cart
            </button>




            <button class="far fa-heart favourite" id="fav"
                    data-id="<?= $product['id'] ?>"
                    data-name="<?= htmlspecialchars($product['name']) ?>"
                    data-price="<?= $product['price'] ?>"
                    data-img="<?= htmlspecialchars($product['picture']) ?>"></button>



        </div>
    </div>
</div>

<script>
    // Toggle description
    const desc = document.getElementById("product-desc");
    const btn = document.getElementById("toggle-desc");
    btn.addEventListener("click", () => {
        desc.classList.toggle("collapsed");
        btn.textContent = desc.classList.contains("collapsed") ? "See more" : "See less";
    });

    // Quantity buttons
    let qty = 1;
    const qtyDisplay = document.getElementById("qty");
    document.getElementById("minus").addEventListener("click", () => {
        if(qty > 1) qty--;
        qtyDisplay.textContent = qty;
    });
    document.getElementById("plus").addEventListener("click", () => {
        qty++;
        qtyDisplay.textContent = qty;
    });

    // Favourite button
    const favBtn = document.getElementById("fav");

    favBtn.addEventListener("click", (e) => {
        // e.preventDefault(); // nuk nevojitet sepse nuk ka form
        const product = {
            id: favBtn.getAttribute("data-id"),
            name: favBtn.getAttribute("data-name"),
            price: favBtn.getAttribute("data-price"),
            img: favBtn.getAttribute("data-img")
        };

        const formData = new FormData();
        formData.append("toggle_fav", 1);
        formData.append("product[id]", product.id);
        formData.append("product[name]", product.name);
        formData.append("product[price]", product.price);
        formData.append("product[img]", product.img);

        fetch("favorites.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if(data.status === "added"){
                    favBtn.classList.add("active"); // zemra e kuqe
                } else if(data.status === "removed"){
                    favBtn.classList.remove("active"); // zemra bosh
                }
            })
            .catch(err => console.error(err));
    });



</script>

</body>
</html>

