<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];
    $result = $conn->query("SELECT user_id, token_hash FROM remember_tokens");

    while ($row = $result->fetch_assoc()) {
        if (password_verify($token, $row['token_hash'])) {
            $_SESSION['user_id'] = $row['user_id'];
            break;
        }
    }
}

