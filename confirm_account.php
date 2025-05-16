<?php
require_once 'connect/connection.php';

if (isset($_GET['email'])) { // Use $_GET for URL parameters
	$email = mysqli_real_escape_string($conn, $_GET['email']); // Sanitize input to prevent SQL injection

	// Check if the email exists in the database
	$result = mysqli_query($conn, "SELECT * FROM `user` WHERE `email` = '$email'") or die(mysqli_error($conn));
	if (mysqli_num_rows($result) > 0) {
		// Update the status to 'Verified'
		mysqli_query($conn, "UPDATE `user` SET `status` = 'Verified' WHERE `email` = '$email'") or die(mysqli_error($conn));
		header('location:index.php'); // Redirect to the index page
		exit;
	} else {
		// Email not found, handle the error
		echo "Error: Email not found.";
	}
} else {
	echo "Error: No email provided.";
}