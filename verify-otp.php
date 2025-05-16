<?php
date_default_timezone_set('Asia/Manila'); // Adjust according to your timezone

session_start();
require_once 'connect/connection.php'; // Include your database connection file

$email = $_GET['email'];

// Check if the form is submitted
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $otp = $_POST['otp'];

        // Validate the OTP
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND verification_code = ?");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // OTP is valid, proceed with password reset
            $_SESSION['success_message'] = "OTP verified successfully! You can now reset your password.";
            header("Location: reset_password.php?email=" . urlencode($email));
            exit;
        } else {
            // OTP is invalid
            $errorMessage = "Invalid OTP. Please try again.";
        }
        break;
    default:
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome for icons -->
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

    input[type="text"] {
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
            <h2>Enter OTP</h2>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="email"
                    value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">

                <div class="form-group">
                    <input type="text" name="otp" id="otp" placeholder="Enter your 6-digit OTP" required>
                </div>
                <button type="submit" class="btn">Verify OTP</button>
            </form>
        </div>
    </div>
</body>

</html>