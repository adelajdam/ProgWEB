<?php
require "config.php";
session_start();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember_me']);
$ip = $_SERVER['REMOTE_ADDR'];

if (!$email || !$password) {
    echo json_encode(["status"=>"error","message"=>"Invalid input"]);
    exit;
}

/* --- USER --- */
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    logAttempt(null, "failed");
    echo json_encode(["status"=>"error","message"=>"Invalid credentials"]);
    exit;
}

/* --- LOGIN ATTEMPTS --- */
$attempt = $pdo->prepare("SELECT * FROM login_attempts WHERE user_id=?");
$attempt->execute([$user['id']]);
$attempt = $attempt->fetch();

if ($attempt && $attempt['blocked_until'] && strtotime($attempt['blocked_until']) > time()) {
    echo json_encode(["status"=>"error","message"=>"Account locked. Try later"]);
    exit;
}

/* --- PASSWORD --- */
if (!password_verify($password, $user['password'])) {
    handleFailedAttempt($user['id']);
    logAttempt($user['id'], "failed");
    echo json_encode(["status"=>"error","message"=>"Invalid credentials"]);
    exit;
}

/* --- VERIFICATION --- */
if ($user['verification_code'] !== null) {
    echo json_encode(["status"=>"error","message"=>"Account not verified"]);
    exit;
}

/* --- SUCCESS --- */
resetAttempts($user['id']);
logAttempt($user['id'], "success");

$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];

/* --- REMEMBER ME --- */
if ($remember) {
    $token = bin2hex(random_bytes(32));
    $hash = password_hash($token, PASSWORD_DEFAULT);

    $pdo->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) 
        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))")
        ->execute([$user['id'], $hash]);

    setcookie("remember_token", $token, time()+2592000, "/", "", true, true);
}

echo json_encode(["status"=>"success"]);
