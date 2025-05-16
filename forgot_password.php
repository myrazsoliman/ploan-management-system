<?php
date_default_timezone_set("Etc/GMT+8");
session_start(); // Start the session to use session variables
// Include PHPMailer library
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect/connection.php'; // Include your database connection file

    $email = $_POST['email'];

    // Generate 6-character OTP
    $characters = '0123456789';
    $otp = substr(str_shuffle($characters), 0, 6);
    $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes')); // OTP valid for 1 hour

    // Check if the email exists
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Save the OTP in the database
        $query = "UPDATE user SET verification_code = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $otp, $email);
        $stmt->execute();

        // Send OTP via email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ploansystem@gmail.com'; // Your Gmail
            $mail->Password = 'jxpq cdkt irbi lmbr'; // App password (secure this)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('dummy@gmail.com', 'P-Loan');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code for Password Reset';
            $mail->Body = "Your OTP code is: <strong>$otp</strong><br>This code is valid for 1 hour.";
            $mail->AltBody = "Your OTP code is: $otp\nThis code is valid for 1 hour.";

            $mail->send();
            $successMessage = "OTP sent to your email. Please check your inbox.";
            $_SESSION['success_message'] = $successMessage;
            header("Location: verify-otp.php?email=" . urlencode($email));
            exit; // Ensure the script stops here after redirect
        } catch (Exception $e) {
            $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $errorMessage = "Email not found.";
    }
    $stmt->close();
    $conn->close();
} else {
    $successMessage = '';
    $errorMessage = '';
}

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- Link to your CSS file -->
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
        text-align: center;

    }

    .container {
        width: 50vh;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container {
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    input[type="email"] {
        width: 95%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn {
        background-color: #800000;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .success-message {
        color: green;
        margin-bottom: 15px;
    }

    .error-message {
        color: red;
        margin-bottom: 15px;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo $successMessage; ?></div>
            <?php elseif (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn">Send OTP</button>
            </form>
            <p><a href="index.php">Back to Login</a></p>
        </div>
    </div>
</body>

</html>