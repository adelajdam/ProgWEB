<?php
session_start();
if(!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];

if(isset($_POST['toggle_fav']) && isset($_POST['product'])){
    $product = $_POST['product'];

    // Kontrollo duplicime
    $exists = false;
    foreach($_SESSION['favorites'] as $fav){
        if($fav['id'] == $product['id']) $exists = true;
    }

    if(!$exists){
        $_SESSION['favorites'][] = $product;
        echo json_encode(['status'=>'added']);
    } else {
        $_SESSION['favorites'] = array_filter($_SESSION['favorites'], fn($fav)=>$fav['id'] != $product['id']);
        echo json_encode(['status'=>'removed']);
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="Favorites/style.css">
    <style>
        .nav-icons {
        display: flex;
        gap: 15px;
        align-items: center;
        }

        .nav-icons a {
        color: white;
        font-size: 1.5rem;
        text-decoration: none;
        transition: color 0.3s ease;
        }

        .nav-icons a:hover {
        color: #c56a76; /* ndryshon ngjyra kur hover */
    }
        .actions button[name="remove_fav"]{
            background: transparent;
            font-size: 14px;
            color: var(--pink-dark);
            padding: 0 8px;
            cursor: pointer;
            border: none;
            outline: none;
            box-shadow: none;
            appearance: none;
            -webkit-appearance: none;
        }

        .actions button[name="remove_fav"]:hover{
            color: #333333;
        }
    </style>
</head>
<body>

<header class="header">
    <h2>My Favorites</h2>
    <div class="nav-icons">
        <a href="index.php" class="icons" title="Home">
            <i class="fas fa-home"></i>
        </a>
        <a href="cart.php" class="icons" title="Cart">
            <i class="fas fa-shopping-cart"></i>
        </a>
    </div>


</header>

<section class="favorites-container">
    <?php if (empty($_SESSION['favorites'])): ?>
        <p>Ju nuk keni asnjë produkt në favorites.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['favorites'] as $product): ?>
            <div class="favorite-item">
                <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="content">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price"><?= htmlspecialchars($product['price']) ?> L</div>
                    <div class="actions">
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" name="remove_fav">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </form>
                        <button class="add-to-cart"
                                data-id="<?= $product['id'] ?>"
                                data-name="<?= htmlspecialchars($product['name']) ?>"
                                data-price="<?= $product['price'] ?>"
                                data-img="<?= htmlspecialchars($product['img']) ?>"> <!-- sigurohu që këtu është 'img' -->
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>



                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

</body>
</html>

