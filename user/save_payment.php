<?php
date_default_timezone_set("Etc/GMT+8");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../class.php'; // Database class

$db = new db_class();

// --- Authentication ---
$user_id = $_SESSION['user_id'] ?? null; // Borrower's ID

if (!$user_id) {
    $_SESSION['payment_error_message'] = "Authentication required. Please log in.";
    header('Location: payment.php'); // Redirect back to payment page
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_payment'])) {

    // --- Sanitize and Retrieve POST Data ---
    $loan_id = filter_input(INPUT_POST, 'loan_id', FILTER_VALIDATE_INT);
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
    $pay_amount_str = filter_input(INPUT_POST, 'pay_amount', FILTER_SANITIZE_STRING); // Sanitize as string first
    $reference_no = filter_input(INPUT_POST, 'reference_no', FILTER_SANITIZE_STRING);
    $other_details = filter_input(INPUT_POST, 'other_details', FILTER_SANITIZE_STRING);

    // --- Validate Inputs ---
    $errors = [];

    if (empty($loan_id)) {
        $errors[] = "Please select the loan you are paying for.";
    }
    if (empty($payment_method)) {
        $errors[] = "Payment method is missing."; // Should be auto-set from form
    }
    if (empty($pay_amount_str) || !is_numeric($pay_amount_str) || (float) $pay_amount_str <= 0) {
        $errors[] = "Invalid payment amount. Please enter a positive number.";
    } else {
        $pay_amount = (float) $pay_amount_str; // Convert to float after validation
    }
    if (empty($reference_no)) {
        $errors[] = "GCash Reference Number is required.";
    } elseif (!preg_match('/^\d{13}$/', $reference_no)) {
        $errors[] = "GCash Reference Number must be 13 digits.";
    }

    // --- File Upload Handling for Proof of Payment ---
    $proof_of_payment_filename = null;
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['proof_of_payment'];
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_file_size = 5 * 1024 * 1024; // 5MB

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_mime_types)) {
            $errors[] = "Invalid file type for proof of payment. Only JPG, JPEG, and PNG are allowed.";
        }
        // Validate file size
        if ($file['size'] > $max_file_size) {
            $errors[] = "Proof of payment file is too large. Maximum size is 5MB.";
        }

        if (empty($errors)) { // Proceed with file move if no prior validation errors
            $upload_dir = __DIR__ . '/../uploads/proofs/'; // Server path
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) { // Create directory if it doesn't exist
                    $errors[] = "Failed to create upload directory for proofs. Please contact support.";
                    error_log("Failed to create directory: " . $upload_dir);
                }
            }

            if (is_dir($upload_dir) && is_writable($upload_dir)) {
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                // Sanitize original filename before using parts of it, or just generate a fully unique name
                $safe_original_name_part = preg_replace("/[^a-zA-Z0-9._-]/", "", pathinfo($file['name'], PATHINFO_FILENAME));
                $proof_of_payment_filename = "proof_" . $user_id . "_" . $loan_id . "_" . time() . "_" . uniqid() . "." . $file_extension;
                $upload_path = $upload_dir . $proof_of_payment_filename;

                if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $errors[] = "Failed to upload proof of payment. Please try again.";
                    error_log("Failed to move uploaded file to: " . $upload_path);
                    $proof_of_payment_filename = null; // Reset filename if move failed
                }
            } else {
                $errors[] = "Upload directory for proofs is not writable or does not exist. Please contact support.";
                error_log("Upload directory not writable or missing: " . $upload_dir);
            }
        }
    } elseif (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] != UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => "File exceeds server's max upload size.",
            UPLOAD_ERR_FORM_SIZE => "File exceeds form's max upload size.",
            UPLOAD_ERR_PARTIAL => "File was only partially uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder on server.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
        ];
        $error_code = $_FILES['proof_of_payment']['error'];
        $errors[] = "Proof of payment upload error: " . ($upload_errors[$error_code] ?? "Unknown error");
    } else {
        $errors[] = "Proof of payment is required."; // If no file was uploaded at all
    }


    // --- If no errors, proceed to save to database ---
    if (empty($errors)) {
        // Verify that the selected loan_id belongs to the logged-in user and is active
        $verify_loan_stmt = $db->conn->prepare("SELECT status FROM loan WHERE loan_id = ? AND borrower_id = ?");
        if ($verify_loan_stmt) {
            $verify_loan_stmt->bind_param("ii", $loan_id, $user_id);
            $verify_loan_stmt->execute();
            $verify_loan_result = $verify_loan_stmt->get_result();
            if ($verify_loan_result->num_rows == 1) {
                $loan_data = $verify_loan_result->fetch_assoc();
                // Check if loan status allows payment (e.g., 1=Approved, 2=Released)
                if (!in_array($loan_data['status'], [1, 2])) {
                    $errors[] = "The selected loan is not currently eligible for payments.";
                }
            } else {
                $errors[] = "Invalid loan selected or loan does not belong to you.";
            }
            $verify_loan_stmt->close();
        } else {
            $errors[] = "Failed to verify loan details. Please try again.";
            error_log("Failed to prepare loan verification statement: " . $db->conn->error);
        }

        if (empty($errors)) { // Final check before DB insert
            $db->conn->begin_transaction(); // Start transaction

            try {
                // Insert into payment table
                // Note: 'payee' might be the borrower's name or a generic system identifier.
                // 'penalty' and 'overdue' might be calculated here or by a separate process. For now, setting to 0.
                $payment_date = date("Y-m-d H:i:s");
                $payee_name = $_SESSION['firstname'] . " " . ($_SESSION['lastname'] ?? ''); // Example payee name

                $insert_stmt = $db->conn->prepare("
                    INSERT INTO payment (loan_id, payee, payment_method, pay_amount, penalty, overdue, proof_of_payment, payment_date, reference_no, other_details)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                if ($insert_stmt) {
                    $default_penalty = 0.00;
                    $default_overdue = 0; // Or calculate this based on due dates

                    $insert_stmt->bind_param(
                        "issddissss",
                        $loan_id,
                        $payee_name,
                        $payment_method,
                        $pay_amount,
                        $default_penalty,
                        $default_overdue,
                        $proof_of_payment_filename,
                        $payment_date,
                        $reference_no,
                        $other_details
                    );

                    if ($insert_stmt->execute()) {
                        // (Optional but Recommended) Update loan balance or status
                        // This logic can be complex: calculate remaining balance, check if fully paid.
                        // For example:
                        // $update_loan_stmt = $db->conn->prepare("UPDATE loan SET balance = balance - ? WHERE loan_id = ?");
                        // $update_loan_stmt->bind_param("di", $pay_amount, $loan_id);
                        // $update_loan_stmt->execute();
                        // $update_loan_stmt->close();
                        // Add logic to check if loan is fully paid and update status if so.

                        $db->conn->commit(); // Commit transaction
                        $_SESSION['payment_success_message'] = "Payment of &#8369;" . number_format($pay_amount, 2) . " submitted successfully! Your payment is being processed.";
                    } else {
                        throw new Exception("Failed to record payment: " . $insert_stmt->error);
                    }
                    $insert_stmt->close();
                } else {
                    throw new Exception("Failed to prepare payment statement: " . $db->conn->error);
                }

            } catch (Exception $e) {
                $db->conn->rollback(); // Rollback transaction on error
                $errors[] = "An error occurred while saving your payment: " . $e->getMessage();
                error_log("Payment save error for user {$user_id}, loan {$loan_id}: " . $e->getMessage());
                // If file was uploaded but DB failed, consider deleting the orphaned file
                if ($proof_of_payment_filename && file_exists($upload_dir . $proof_of_payment_filename)) {
                    @unlink($upload_dir . $proof_of_payment_filename);
                }
            }
        }
    }

    // --- Handle Errors or Success ---
    if (!empty($errors)) {
        $_SESSION['payment_error_message'] = implode("<br>", array_map('htmlspecialchars', $errors));
    }

    header('Location: payment.php'); // Redirect back to the payment page
    exit;

} else {
    // Not a POST request or form not submitted correctly
    $_SESSION['payment_error_message'] = "Invalid request method.";
    header('Location: payment.php');
    exit;
}
?>
