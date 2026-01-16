<?php
require "config.php"; // lidhja me DB

if(!$conn){
    die("DB nuk është lidhur!");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /ProgWEB/Login/login_signup.php");
    exit;
}

// ---- Merr të dhënat nga form ----
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');

// ---- VALIDIMI BACKEND ----
$errors = [];

if (empty($name)) {
    $errors[] = "Name cannot be empty";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}
if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    exit;
}

// ---- Siguria ----
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ---- Kontrollo nëse email ekziston ----
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    // Email ekziston, ridrejto te Sign Up me name dhe password mbetëse, email bosh
    header("Location: /ProgWEB/Login/login_signup.php?signup_error=email_exists"
        . "&name=" . urlencode($name)
        . "&password=" . urlencode($password)
    );
    exit;
}

// ---- Fut user në users me rol default 'user' dhe is_verified = 0 ----
$stmt = $conn->prepare("INSERT INTO users (email, password, role, is_verified) VALUES (?, ?, 'user', 0)");
$stmt->bind_param("ss", $email, $hashedPassword);
$stmt->execute();
$user_id = $stmt->insert_id;

// ---- Gjenero kod verifikimi ----
$verification_code = bin2hex(random_bytes(16));

$stmt2 = $conn->prepare("UPDATE users SET verification_code=? WHERE id=?");
$stmt2->bind_param("si", $verification_code, $user_id);
$stmt2->execute();

// ---- Fut info personale në user_profiles ----
$stmt3 = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone) VALUES (?, ?, ?)");
$stmt3->bind_param("iss", $user_id, $name, $phone);
$stmt3->execute();

// ---- Dërgo email me PHPMailer ----
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/vendor/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cosmico.noreply@gmail.com'; // email kompanie
    $mail->Password = 'ydeobogolwmlmmmo'; // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('cosmico.noreply@gmail.com', 'Skincare App');
    $mail->addAddress($email); // email i përdoruesit

    $mail->isHTML(true);
    $mail->Subject = 'Verifikimi i llogarisë';
    $mail->Body = "
        Përshëndetje $name,<br><br>
        Ju lutem klikoni linkun më poshtë për të verifikuar llogarinë tuaj:<br><br>
        <a href='http://localhost/ProgWEB/verify.php?code=$verification_code'>Verifiko llogarinë</a><br><br>
        Nëse nuk keni krijuar këtë llogari, thjesht injorojeni këtë email.<br><br>
        Faleminderit!
    ";

    $mail->send();

    // ---- Ridrejto tek login_signup me mesazh suksesi ----
    header("Location: /ProgWEB/Login/login_signup.php?signup_success=1");
    exit;

} catch (Exception $e) {
    echo "Gabim në dërgimin e email-it: {$mail->ErrorInfo}";
}
