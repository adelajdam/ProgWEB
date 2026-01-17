<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$showSuccess = false;

require_once __DIR__ . "/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/vendor/PHPMailer/src/SMTP.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);

    /* =============================
       KONTROLLO NËSE USER EKZISTON
       ============================= */
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {

        /* =============================
           KRIJO TOKEN RESET
           ============================= */
        $token = bin2hex(random_bytes(32));

        $stmt = $conn->prepare("
            INSERT INTO password_resets (user_id, reset_token, expires_at)
            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))
        ");
        $stmt->bind_param("is", $user['id'], $token);
        $stmt->execute();
        $stmt->close();

        // 🔗 RESET LINK
        $resetLink = "http://localhost/ProgWEB/reset_password.php?token=" . $token;


        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cosmico.noreply@gmail.com';
        $mail->Password = 'ydeobogolwmlmmmo';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('cosmico.noreply@gmail.com', 'Cosmico');
        $mail->addAddress($email);
        $mail->Subject = 'Reset password';
        $mail->Body = "Click here: $resetLink";
        $mail->send();

        $showSuccess = true;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f6f6f6, #ececec);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot-container {
            background: #fff;
            padding: 35px;
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .forgot-container h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .forgot-container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .forgot-container input[type="email"] {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .forgot-container input[type="email"]:focus {
            outline: none;
            border-color: #d97e8a;
        }

        .forgot-container button {
            width: 100%;
            padding: 12px;
            background: #d97e8a;
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .forgot-container button:hover {
            background: #c56a76;
        }

        .success-message {
            margin-top: 12px;
            font-size: 14px;
            color: #2e7d32;
            font-weight: 600;
        }

    </style>
</head>
<body>

<div class="forgot-container">
    <h2>Forgot Password</h2>
    <p>Enter your email to reset your password</p>

    <form method="POST">
        <input type="email" name="email" placeholder="Email address" required>

        <button type="submit">Send reset link</button>

        <?php if ($showSuccess): ?>
            <p class="success-message">
                Email për reset password u dërgua.
            </p>

            <script>
                setTimeout(() => {
                    window.location.href = "/ProgWEB/Login/login_signup.php";
                }, 3000);
            </script>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
