<?php
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
    $mail->Username = 'cosmico.noreply@gmail.com';
    $mail->Password = 'ydeobogolwmlmmmo';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('cosmico.noreply@gmail.com', 'Skincare App');
    $mail->addAddress('bajameh6@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Test PHPMailer';
    $mail->Body = 'Nëse e lexon këtë, PHPMailer funksionon ✅';

    $mail->send();
    echo "Email u dërgua me sukses!";
} catch (Exception $e) {
    echo "Gabim: {$mail->ErrorInfo}";
}
