<?php

session_start();
session_unset();
session_destroy();

// Fshij cookie për “remember me” nëse ekziston
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, "/");
}

header("Location: login_signup.php");
exit;

