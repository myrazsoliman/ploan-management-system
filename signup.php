<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'connect/connection.php';
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $verification_code = bin2hex(random_bytes(16)); // Generate a random verification code
    $status = 'pending';
    $role_id = 2; // Assuming 2 is the role ID for users

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+15 minutes")); // OTP valid for 15 minutes

    // Insert user data into the database
    $query = "INSERT INTO `user` (username, password, role_id, firstname, lastname, email, status, reset_token, verification_code, otp_expiry) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisssssss", $username, $password, $role_id, $firstname, $lastname, $email, $status, $verification_code, $otp, $otp_expiry);

    if (!$stmt->execute()) {
        die("Database Error: " . $stmt->error);
    }

    // Email content
    $message = "Hello $firstname $lastname! <br>"
        . "Your One-Time Password (OTP) for account verification is: <b>$otp</b><br>"
        . "This OTP is valid for 15 minutes.<br><br>"
        . "If you did not request this, please ignore this email.";

    // Load composer's autoloader
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ploansystem@gmail.com';
        $mail->Password = 'jxpq cdkt irbi lmbr'; // Your email password
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        // Send Email
        $mail->setFrom('ploansystem@gmail.com', 'P-Loan');
        $mail->addAddress($Recipient);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Account Verification OTP";
        $mail->Body = $message;

        $mail->send();

        // Redirect to OTP verification page
        header("Location: otp_verification.php?email=" . urlencode($email));
        exit;
    } catch (Exception $e) {
        // Log error and redirect to an error page
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        $_SESSION['result'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        $_SESSION['status'] = 'error';
        header("Location: error_page.php");
        exit;
    }
}
?>