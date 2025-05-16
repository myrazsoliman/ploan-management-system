<?php
// approve_loan.php

// Include database connection
include '../class.php'; // Make sure to create this file to handle your DB connection

// Check if the loan_id is set in the URL
if (isset($_GET['loan_id'])) {
    $loan_id = $_GET['loan_id'];

    // Validate the loan_id (ensure it's a number to prevent SQL injection)

    // Prepare and execute the update query
    $query = "UPDATE loan SET status = 'approved' WHERE loan_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $loan_id);

    if ($stmt->execute()) {
        echo "Loan approved successfully.";
    } else {
        echo "Error approving loan: " . $stmt->error;
    }
} else {
    echo "Invalid loan ID.";
}

// Redirect back to the previous page or loan list
header("Location: borrowers.php"); // Change this to your actual loan list page
exit;
?>