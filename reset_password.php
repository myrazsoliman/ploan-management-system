<?php
session_start();
include 'connect/connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_GET['email'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Update the password in the database
    $query = "UPDATE user SET password = ?, verification_code = NULL WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $newPassword, $email);
    $stmt->execute();

    // Clear session and redirect to login
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles/style.css"> <!-- Link to your CSS file -->
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Reset Password</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>

</html>