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
        <form id="signupForm" method="POST" action="../register.php">
            <h1>Create Account</h1>

            <input type="text" id="signupName" name="name" placeholder="Name" required>
            <small id="nameError" style="color:#d97e8a; font-size:12px;"></small>

            <input type="email" id="signupEmail" name="email" placeholder="Email" required>
            <small id="emailError" style="color:#d97e8a; font-size:12px;"></small>

            <input type="password" id="signupPassword" name="password" placeholder="Password" required>
            <small id="passwordError" style="color:#d97e8a; font-size:12px;"></small>


            <button type="submit">Sign Up</button>
            <p id="generalError" style="color:#d97e8a; font-size:12px;"></p>
            <p id="successMsg" style="color:#2f5f5f; font-size: 13px;"></p>
        </form>
    </div>

    <!-- LOGIN -->
    <div class="form-container sign-in">
        <form id="loginForm" method="POST" action="../login.php">
            <h1>Log In</h1>

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="login-options">
                <a href="../forgot_password.php" class="forgot-password">
                    Forgot password?
                </a>


                <label class="remember-me">
                    <input type="checkbox" name="remember_me">
                    <span>Remember me</span>
                </label>

            </div>

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

