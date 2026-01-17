<?php
header("Content-Type: application/json");
session_start();

require_once "config.php";
require_once "auth_functions.php";

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember_me']);
$ip       = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if (!$email || !$password) {
    echo json_encode(["status"=>"error","message"=>"Të dhëna të pavlefshme"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, email, password, is_verified
     FROM users WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    logAttempt(null, "failed", $ip);
    echo json_encode(["status"=>"error","message"=>"Email nuk u gjet"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT attempts, blocked_until FROM login_attempts WHERE user_id = ?"
);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$attempt = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($attempt && $attempt['blocked_until'] && strtotime($attempt['blocked_until']) > time()) {
    echo json_encode(["status"=>"error","message"=>"Account i bllokuar përkohësisht"]);
    exit;
}

if (!password_verify($password, $user['password'])) {
    handleFailedAttempt($user['id']);
    logAttempt($user['id'], "failed", $ip);
    echo json_encode(["status"=>"error","message"=>"Password i gabuar"]);
    exit;
}

if ((int)$user['is_verified'] === 0) {
    echo json_encode(["status"=>"error","message"=>"Llogaria nuk është e verifikuar"]);
    exit;
}

resetAttempts($user['id']);
logAttempt($user['id'], "success", $ip);

$_SESSION['user_id'] = $user['id'];
$_SESSION['email']   = $user['email'];

if ($remember) {
    $token = bin2hex(random_bytes(32));
    $hash  = password_hash($token, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO remember_tokens (user_id, token_hash, expires_at)
         VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))"
    );
    $stmt->bind_param("is", $user['id'], $hash);
    $stmt->execute();
    $stmt->close();

    setcookie("remember_token", $token, time()+2592000, "/", "", true, true);
}

echo json_encode(["status"=>"success"]);
exit;
