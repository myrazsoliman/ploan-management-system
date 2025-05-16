<?php
session_start();

require_once '../connect/connection.php';

// Prevent accessing the page after logout
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Prevent browser from caching the page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Assuming you have a database connection established here ($conn)
    $sql = "SELECT firstname, profile_pic FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $profile_pic);
    $stmt->fetch();
    $stmt->close();

    $_SESSION['firstname'] = $firstname;
    $_SESSION['profile_pic'] = $profile_pic; // Store the filename
}

// In your payment.php
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : 'User';
$profile_pic_path = isset($_SESSION['profile_pic']) && $_SESSION['profile_pic'] ? '../uploads/' . $_SESSION['profile_pic'] : '../images/admin_profile.svg';
?>