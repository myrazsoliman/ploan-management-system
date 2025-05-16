<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connect/connection.php';
session_start();

if (isset($_POST['verify_code'])) {
    $otp = $_POST['otp'];
    $email = $_GET['email']; // Get the email from the URL
    

    // Check if the OTP and email match in the database
    $query = "SELECT * FROM `user` WHERE verification_code = '$otp'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // OTP is valid, update the user's status to 'active'
        $update_query = "UPDATE `user` SET status = 'active', otp_expiry = NULL WHERE email = '$email'";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Your account has been successfully verified!';
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Failed to update account status. Please try again.';
        }
    } else {
        // OTP is invalid or expired
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid or expired OTP. Please try again.';

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <title>Verification Code</title>
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
</head>

<body>
    <div class="card">
        <div class="card-header">
            Enter Verification Code
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<p style='color: red;'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            }
            ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="otp">Verification Code:</label>
                    <input type="text" name="otp" id="otp" class="form-control" required>
                </div>
                <button type="submit" name="verify_code" class="btn btn-primary">Verify</button>
            </form>
        </div>
    </div>
</body>

</html>