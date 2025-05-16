<?php
require_once 'class.php';
session_start();

// Prevent logged-in users from accessing the login page
if (isset($_SESSION['user_id'])) {
    // Redirect to the appropriate page if already logged in
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
        header("Location: admin/dashboard.php");
        exit();
    } elseif (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) {
        header("Location: user/dashboard.php");
        exit();
    } else {
        header("Location: logout.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $db = new db_class();
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['message'] = "Username and password are required!";
        header("Location: index.php");
        exit();
    }

    // Authenticate user
    $get_id = $db->login($username, $password);

    if ($get_id && $get_id['count'] > 0) {
        session_regenerate_id(true); // Prevent session fixation attacks
        $_SESSION['user_id'] = $get_id['user_id'];
        unset($_SESSION['message']);

        // Fetch user role
        $conn = $db->conn;
        $sql = "SELECT role_id FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $get_id['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['role_id'] = $user['role_id'];

                // Redirect based on role
                if ($user['role_id'] == 1) {
                    $_SESSION['message'] = "Welcome Admin!";
                    header("Location: admin/dashboard.php");
                    exit();
                } elseif ($user['role_id'] == 2) {
                    $_SESSION['message'] = "Welcome User!";
                    header("Location: user/dashboard.php");
                    exit();
                } else {
                    $_SESSION['message'] = "Invalid Role! Contact support.";
                }
            } else {
                $_SESSION['message'] = "User role not found.";
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Database error: " . $conn->error;
        }
    } else {
        $_SESSION['message'] = "Invalid Username or Password";
    }

    header("Location: index.php");
    exit();
}
?>