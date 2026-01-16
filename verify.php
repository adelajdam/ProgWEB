<?php
require "config.php";

if (!isset($_GET['code'])) {
    die("Kod mungon");
}

$code = $_GET['code'];

$stmt = $conn->prepare("SELECT id FROM users WHERE verification_code=?");
$stmt->bind_param("s", $code);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Kod i pavlefshëm");
}

$user = $res->fetch_assoc();

$stmt = $conn->prepare("
    UPDATE users
    SET is_verified=1, verification_code=NULL
    WHERE id=?
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();

echo "Llogaria u verifikua me sukses ✅";
