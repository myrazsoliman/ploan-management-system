<?php
require_once 'db.php'; // include your DB connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_id = $_POST['loan_id'];
    $payment_method = $_POST['payment_method'];
    $pay_amount = $_POST['pay_amount'];
    $payment_date = date("Y-m-d");

    // Check if a file was uploaded
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['proof_of_payment']['tmp_name'];
        $fileName = $_FILES['proof_of_payment']['name'];
        $fileSize = $_FILES['proof_of_payment']['size'];
        $fileType = $_FILES['proof_of_payment']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Generate unique filename
        $newFileName = uniqid() . '.' . $fileExtension;

        // Set upload path
        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        // Move file
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO payment (loan_id, payment_method, pay_amount, proof_of_payment, payment_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isdss", $loan_id, $payment_method, $pay_amount, $newFileName, $payment_date);
            if ($stmt->execute()) {
                header("Location: success_page.php");
                exit();
            } else {
                echo "Database error.";
            }
        } else {
            echo 'Error uploading file.';
        }
    } else {
        echo 'No file uploaded or upload error.';
    }
} else {
    echo 'Invalid request.';
}
?>