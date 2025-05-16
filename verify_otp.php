<?php
session_start();
include 'connect/connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $otp = $_POST['otp'];

    // Verify OTP
    $query = "SELECT * FROM user WHERE email = ? AND verification_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid, redirect to reset password page
        header('Location: reset_password.php');
        exit();
    } else {
        $errorMessage = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- Link to your CSS file -->
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Verify OTP</h2>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" name="otp" id="otp" required>
                </div>
                <button type="submit" class="btn">Verify OTP</button>
            </form>
        </div>
    </div>
</body>

</html>