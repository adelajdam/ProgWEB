<?php
include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cosmico – Products</title>
    <link rel="stylesheet" href="Products/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Cosmico</div>
    <nav>
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#blog">Blog</a>
        <a href="#contact">Contact</a>
    </nav>
    <div class="nav-icons">
        <a href="favorites.php"><i class="fas fa-heart"></i></a>
        <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="profile.php"><i class="fa-solid fa-user"></i></a>
    </div>
</header>

<section class="hero" id="home">
    <img src="images/skincare2.jpg" class="hero-bg">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Beauty is the<br>illumination of<br>your soul</h1>
            <p>Natural skincare products for glowing skin.</p>
            <a href="#products" class="btn">Shop Now</a>
        </div>
    </div>
</section>

<section class="categories" id="categories">
    <div class="category-card">
        <div class="category-img">
            <img src="images/cleanser.jpg" alt="Cleansers">
        </div>
        <h3>Cleansers</h3>
    </div>

    <div class="category-card">
        <div class="category-img">
            <img src="images/moisturizer.jpg" alt="Moisturizers">
        </div>
        <h3>Moisturizers</h3>
    </div>

    <div class="category-card">
        <div class="category-img">
            <img src="images/serum.jpg" alt="Serums">
        </div>
        <h3>Serums</h3>
    </div>

    <div class="category-card">
        <div class="category-img">
            <img src="https://i.pinimg.com/736x/5f/5a/9d/5f5a9d8af93e4bd1f853a68a49bac625.jpg" alt="Masks">
        </div>
        <h3>Masks</h3>
    </div>

    <div class="category-card">
        <div class="category-img">
            <img src="https://tse4.mm.bing.net/th/id/OIP.7ru5yRGeTw7aSzaBe4KkRQHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Sun Care">
        </div>
        <h3>Sun Care</h3>
    </div>
</section>



</section>

<section class="products-section" id="products">
    <h2>Best Sellers</h2>
    <div class="products-grid">
        <?php
        $sql = "SELECT * FROM products ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($product = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<a href="product_details.php?id='.$product['id'].'">';
                echo '<img src="'.$product['picture'].'" alt="'.$product['name'].'">';
                echo '<h3>'.$product['name'].'</h3>';
                echo '<p class="price">'.$product['price'].' L</p>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>
</section>

<!-- ABOUT -->
<section class="about-section" id="about">
    <div class="about-content">
        <div class="about-text">
            <h2>About Cosmico</h2>
            <p>
Cosmico is a modern skincare brand inspired by
                natural beauty and self-care rituals.
Our products are designed to nourish, protect,
                and illuminate your skin every day.
            </p>
            <p>
We believe skincare is more than a routine —
                it’s a moment of calm, confidence, and care.
            </p>
        </div>

        <div class="about-video">
            <video autoplay muted loop playsinline>
                <source src="images/skincare-video.mp4" type="video/mp4">
    Your browser does not support the video tag.
            </video>
        </div>
    </div>
</section>


<!-- BLOG -->
<section class="blog-section" id="blog">
    <h2>Our Blog</h2>
    <p class="blog-subtitle">
Discover skincare tips, routines and ingredient guides written by experts.
    </p>

    <div class="blog-grid">
        <div class="blog-card">
            <img src="https://skinsense.sg/wp-content/uploads/2024/06/d3ba69901e8eafc78b86e848bd41f5da.jpg" alt="Skincare Routine">
            <div class="blog-content">
                <span class="blog-category">Routine</span>
                <h3>How to Build a Simple Skincare Routine</h3>
                <p>
Learn the essential steps for healthy,
                              glowing skin without overcomplicating your routine.
                </p>
                <a href="../blog/blog-routine.html">Read More →</a>

            </div>
        </div>

        <div class="blog-card">
            <img src="https://tse1.mm.bing.net/th/id/OIP.sPYRIMjJH0tR87lMWr0nJAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Serum Ingredients">
            <div class="blog-content">
                <span class="blog-category">Ingredients</span>
                <h3>Best Ingredients for Sensitive Skin</h3>
                <p>
Discover calming and nourishing ingredients
                    that help protect sensitive skin.
                </p>
                <a href="../blog/blog-ingredients.html">Read More →</a>

            </div>
        </div>

        <div class="blog-card">
            <img src="https://eswbeauty.com/cdn/shop/files/mayafraserphoto--04.jpg?v=1744114891&width=2820" alt="Face Masks">
            <div class="blog-content">
                <span class="blog-category">Masks</span>
                <h3>Why Face Masks Are Essential for Your Skin</h3>
                <p>
Face masks help hydrate, repair and
refresh your skin when it needs extra care.
                </p>
                <a href="../blog/blog-masks.html">Read More →</a>

            </div>
        </div>
    </div>
</section>


<!<!-- CONTACT -->
<section class="contact-section" id="contact">
    <div class="contact-container">

        <!-- INFO -->
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p>
We'd love to hear from you. Whether you have
                a question about our products or just want to say hi,
                feel free to reach out.
            </p>

            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <span>info@cosmico.com</span>
            </div>

            <div class="contact-item">
                <i class="fa-brands fa-instagram"></i>
                <span>@cosmico</span>
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <span>Tirana, Albania</span>
            </div>
        </div>

        <!-- FORM -->
        <div class="contact-form">
            <form>
                <input type="text" placeholder="Your Name">
                <input type="email" placeholder="Your Email">
                <textarea placeholder="Your Message"></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>

    </div>
</section>


<!-- FOOTER -->
<footer class="footer">
    <p>
        © 2026 <span>Cosmico</span>. All rights reserved.
    </p>
</footer>



<!-- JS -->
<script src="script.js"></script>
</body>
</html>

