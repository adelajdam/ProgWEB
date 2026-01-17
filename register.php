<?php
require "config.php";
header("Content-Type: application/json"); // JSON për AJAX

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/vendor/PHPMailer/src/SMTP.php';

// Merr JSON nga fetch
$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$phone = trim($data['phone'] ?? '');

// VALIDIMI
$errors = [];
if (empty($name)) $errors[] = "Name cannot be empty";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";

if (!empty($errors)) {
    echo json_encode([
        "status" => "error",
        "message" => implode(", ", $errors)
    ]);
    exit;
}

// Kontrollo email
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Ky email ekziston tashmë"
    ]);
    exit;
}

// Fut user në DB
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (email, password, role, is_verified) VALUES (?, ?, 'user', 0)");
$stmt->bind_param("ss", $email, $hashedPassword);
$stmt->execute();
$user_id = $stmt->insert_id;

// Fut info personale
$stmt2 = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone) VALUES (?, ?, ?)");
$stmt2->bind_param("iss", $user_id, $name, $phone);
$stmt2->execute();

// Gjenero verification code
$verification_code = bin2hex(random_bytes(16));
$stmt3 = $conn->prepare("UPDATE users SET verification_code=? WHERE id=?");
$stmt3->bind_param("si", $verification_code, $user_id);
$stmt3->execute();

// Dërgo email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cosmico.noreply@gmail.com';
    $mail->Password = 'ydeobogolwmlmmmo';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('cosmico.noreply@gmail.com', 'Cosmico');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Verifikimi i llogarisë';
    $mail->Body = "
<div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
    <h2 style='color:#2c3e50;'>Përshëndetje $name!</h2>
    <p>Faleminderit që u regjistruat në <strong>Cosmico</strong>. Për të përfunduar procesin e regjistrimit dhe për të aktivizuar llogarinë tuaj, duhet të verifikoni email-in tuaj.</p>
    
    <p style='background-color:#f9f9f9; padding:15px; border-radius:5px; border:1px solid #ddd; text-align:center; margin:20px 0;'>
        <a href='http://localhost/ProgWEB/verify.php?code=$verification_code' 
           style='display:inline-block; padding:10px 20px; font-size:16px; color:#fff; background-color:#d97e8a; text-decoration:none; border-radius:5px;'>
           Verifiko Llogarinë Tani
        </a>
    </p>

    <p>Pas klikimit në link, llogaria juaj do të aktivizohet dhe ju mund të hyni në aplikacion. Nëse nuk keni bërë regjistrimin, mund të injoroni këtë email.</p>
    
    <p style='font-size:12px; color:#888; margin-top:20px;'>Ky është një email automatik, ju lutem mos e përgjigjeni. Për ndihmë, kontaktoni support@skincareapp.com</p>
</div>
";
    $mail->send();

    echo json_encode([
        "status" => "success",
        "message" => "Regjistrimi u krye! Kontrollo email-in për verifikim."
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Gabim në dërgimin e email-it: {$mail->ErrorInfo}"
    ]);
    exit;
}



