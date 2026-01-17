<?php
session_start(); // fillon session
require "config.php";

if (!isset($_GET['code'])) {
    die("Kod mungon");
}

$code = $_GET['code'];

// Kontrollo kodin në DB
$stmt = $conn->prepare("SELECT id FROM users WHERE verification_code=? AND is_verified=0");
$stmt->bind_param("s", $code);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Kod i pavlefshëm ose llogaria është tashmë e verifikuar.");
}

$user = $res->fetch_assoc();

// Verifiko llogarinë
$stmt = $conn->prepare("UPDATE users SET is_verified=1, verification_code=NULL WHERE id=?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();

// Bëj login automatik
$_SESSION['user_id'] = $user['id'];

// Ridrejto te profili
header("Location: profile.php");
exit;
?>
