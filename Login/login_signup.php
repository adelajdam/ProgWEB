<?php
require "../config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login / Sign Up</title>
</head>
<body>

<div class="container <?php echo isset($_GET['signup_error']) ? 'active' : ''; ?>" id="container">

    <!-- SIGN UP -->
    <div class="form-container sign-up">
        <form id="signupForm" method="POST" action="/ProgWEB/register.php">
            <h1>Create Account</h1>

            <input type="text" name="name" placeholder="Name" required
                   value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">

            <input type="email" name="email" placeholder="Email" required
                   value="">

            <?php if(isset($_GET['signup_error']) && $_GET['signup_error'] === 'email_exists'): ?>
                <p style="color:red; font-size:12px; margin-top:5px;">Ky email është përdorur tashmë</p>
            <?php endif; ?>

            <input type="password" name="password" placeholder="Password"
                   value="<?php echo htmlspecialchars($_GET['password'] ?? ''); ?>">

            <?php if(isset($_GET['signup_success'])): ?>
                <p style="color:green;">Regjistrimi u krye! Kontrollo email-in për verifikim ✅</p>
            <?php endif; ?>

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <!-- LOGIN -->
    <div class="form-container sign-in">
        <form id="loginForm" method="POST" action="/ProgWEB/login.php">
            <h1>Log In</h1>

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <?php if(isset($_GET['login_error'])): ?>
                <p style="color:red;">Email ose password i gabuar</p>
            <?php endif; ?>

            <?php if(isset($_GET['verify_success'])): ?>
                <p style="color:green;">Llogaria u verifikua me sukses! Tani mund të bëni login ✅</p>
            <?php endif; ?>

            <?php if(isset($_GET['verify_error'])): ?>
                <p style="color:red;">Gabim gjatë verifikimit të llogarisë</p>
            <?php endif; ?>

            <button type="submit">Log In</button>
        </form>
    </div>

    <!-- TOGGLE PANEL -->
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome Back!</h1>
                <p>Enter your personal details to use all of site features</p>
                <button class="hidden" id="login">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello, Friend!</h1>
                <p>Register with your personal details to use all of site features</p>
                <button class="hidden" id="register">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>

