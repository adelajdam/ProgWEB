<?php
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $stmt = $pdo->query("SELECT * FROM remember_tokens");
    foreach ($stmt as $row) {
        if (password_verify($token, $row['token_hash'])) {
            $_SESSION['user_id'] = $row['user_id'];
        }
    }
}
