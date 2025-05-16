<?php
// filepath: c:\xampp\htdocs\ploan management system\forgot_password.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ploansystem@gmail.com';
    $mail->Password = 'jxpq cdkt irbi lmbr';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('no-reply@ploan.com', 'Ploan Management');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";

    $mail->send();
    echo "A password reset link has been sent to your email.";
} catch (Exception $e) {
    echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
}