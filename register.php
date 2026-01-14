<?php

require "config.php";
if(!$conn){
    die("DB not connected!");
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$errors = [];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');

// ---- VALIDIMI BACKEND ----
if (empty($name)) {
    $errors[] = "Name cannot be empty";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

// Nëse ka gabime
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    exit;
}

// ---- SIGURIA ----
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$stmt->store_result(); // ruan rezultatin për num_rows

if($stmt->num_rows > 0){
    echo "<p style='color:red;'>Ky email është përdorur tashmë</p>";
    exit;
}

// ---- FUT USER NË USERS ----
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashedPassword);
$stmt->execute();
$user_id = $stmt->insert_id;

// ---- GJENERO EMAIL VERIFICATION ----
$verification_code = bin2hex(random_bytes(16));

$stmt2 = $conn->prepare("UPDATE users SET verification_code=? WHERE id=?");
$stmt2->bind_param("si", $verification_code, $user_id);
$stmt2->execute();

// ---- FUT INFO PERSONAL NË user_profiles ----
$stmt3 = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone) VALUES (?, ?, ?)");
$stmt3->bind_param("iss", $user_id, $name, $phone);
$stmt3->execute();

// ---- DËRGO EMAIL (në localhost zakonisht nuk funksionon mail()) ----
$to = $email;
$subject = "Verifikimi i email-it për Skincare";
$message = "Për të verifikuar llogarinë tuaj klikoni linkun më poshtë:\n";
$message .= "http://localhost/verify.php?code=$verification_code";
$headers = "From: no-reply@skincare.com\r\n";

if(mail($to, $subject, $message, $headers)){
    echo "<p style='color:green;'>Regjistrimi u krye! Kontrolloni email-in për verifikim.</p>";
}else{
    echo "<p style='color:orange;'>Regjistrimi u krye! Por email-i nuk mund të dërgohet në localhost. Përdor PHPMailer për testim.</p>";
}

?>
