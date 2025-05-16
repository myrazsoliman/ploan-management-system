<?php
// submit_loan_application.php

date_default_timezone_set("Asia/Manila"); // Set your desired timezone
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session if not already started
}

// --- Database Connection (Replace with your actual connection logic) ---
// Example: include 'db_connection.php';
/*
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "db_lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log error and die gracefully for the user
    error_log("Database connection failed: " . $conn->connect_error);
    die("We are experiencing technical difficulties. Please try again later. Error: DBConnectFail");
}
*/
// --- End Database Connection Placeholder ---

$errors = [];
$formData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Retrieve and Sanitize Form Data ---
    // Personal Information
    $formData['firstName'] = trim($_POST['firstName'] ?? '');
    $formData['lastName'] = trim($_POST['lastName'] ?? '');
    $formData['middleName'] = trim($_POST['middleName'] ?? ''); // Optional
    $formData['dateOfBirth'] = trim($_POST['dateOfBirth'] ?? '');
    $formData['gender'] = trim($_POST['gender'] ?? '');
    $formData['civilStatus'] = trim($_POST['civilStatus'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['contactNumber'] = trim($_POST['contactNumber'] ?? ''); // Should be 10 digits starting with 9

    // Present Address
    $formData['presentRegionCode'] = trim($_POST['presentRegion'] ?? '');
    $formData['presentProvinceCode'] = trim($_POST['presentProvince'] ?? '');
    $formData['presentCityCode'] = trim($_POST['presentCity'] ?? '');
    $formData['presentBarangayCode'] = trim($_POST['presentBarangay'] ?? '');
    $formData['presentStreet'] = trim($_POST['presentStreet'] ?? '');
    $formData['presentAddressFull'] = trim($_POST['presentAddressFull'] ?? ''); // Assembled by JS

    // Permanent Address
    $sameAsPresent = isset($_POST['sameAsPresent']);
    if ($sameAsPresent) {
        $formData['permanentRegionCode'] = $formData['presentRegionCode'];
        $formData['permanentProvinceCode'] = $formData['presentProvinceCode'];
        $formData['permanentCityCode'] = $formData['presentCityCode'];
        $formData['permanentBarangayCode'] = $formData['presentBarangayCode'];
        $formData['permanentStreet'] = $formData['presentStreet'];
        $formData['permanentAddressFull'] = $formData['presentAddressFull'];
    } else {
        $formData['permanentRegionCode'] = trim($_POST['permanentRegion'] ?? '');
        $formData['permanentProvinceCode'] = trim($_POST['permanentProvince'] ?? '');
        $formData['permanentCityCode'] = trim($_POST['permanentCity'] ?? '');
        $formData['permanentBarangayCode'] = trim($_POST['permanentBarangay'] ?? '');
        $formData['permanentStreet'] = trim($_POST['permanentStreet'] ?? '');
        $formData['permanentAddressFull'] = trim($_POST['permanentAddressFull'] ?? ''); // Assembled by JS
    }

    // Employment Information
    $formData['employmentStatus'] = trim($_POST['employmentStatus'] ?? '');
    $formData['employerName'] = trim($_POST['employerName'] ?? '');
    $formData['jobTitle'] = trim($_POST['jobTitle'] ?? '');
    $formData['monthlyIncome'] = trim($_POST['monthlyIncome'] ?? '');
    $formData['employmentAddress'] = trim($_POST['employmentAddress'] ?? '');

    // Loan Details
    $formData['loanType'] = trim($_POST['loanType'] ?? '');
    $formData['loanAmount'] = trim($_POST['loanAmount'] ?? '');
    $formData['loanPurpose'] = trim($_POST['loanPurpose'] ?? '');
    $formData['loanTerm'] = trim($_POST['loanTerm'] ?? '');
    $formData['agreeTerms'] = isset($_POST['agreeTerms']);

    // User ID (if applicable)
    // $user_id = $_SESSION['user_id'] ?? null; // Example if user is logged in

    // --- Server-Side Validation ---

    // Personal Information
    if (empty($formData['firstName'])) {
        $errors[] = "First name is required.";
    }
    if (empty($formData['lastName'])) {
        $errors[] = "Last name is required.";
    }
    if (empty($formData['dateOfBirth'])) {
        $errors[] = "Date of birth is required.";
    } else {
        // Basic age validation (e.g., must be at least 18)
        $dob = new DateTime($formData['dateOfBirth']);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
        if ($age < 18) { // Adjust age limit as needed
            $errors[] = "Applicant must be at least 18 years old.";
        }
    }
    if (empty($formData['gender'])) {
        $errors[] = "Gender is required.";
    }
    if (empty($formData['civilStatus'])) {
        $errors[] = "Civil status is required.";
    }
    if (empty($formData['email'])) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    }
    if (empty($formData['contactNumber'])) {
        $errors[] = "Contact number is required.";
    } elseif (!preg_match("/^[9][0-9]{9}$/", $formData['contactNumber'])) {
        $errors[] = "Contact number must be 10 digits starting with 9 (e.g., 9123456789).";
    }

    // Present Address
    if (empty($formData['presentRegionCode'])) {
        $errors[] = "Present address: Region is required.";
    }
    if (empty($formData['presentProvinceCode'])) {
        $errors[] = "Present address: Province is required.";
    }
    if (empty($formData['presentCityCode'])) {
        $errors[] = "Present address: City/Municipality is required.";
    }
    if (empty($formData['presentBarangayCode'])) {
        $errors[] = "Present address: Barangay is required.";
    }
    if (empty($formData['presentStreet'])) {
        $errors[] = "Present address: Street and House/Unit No. are required.";
    }
    if (empty($formData['presentAddressFull'])) {
        $errors[] = "Full present address could not be determined. Please check your address entries.";
    }


    // Permanent Address (only if not same as present)
    if (!$sameAsPresent) {
        if (empty($formData['permanentRegionCode'])) {
            $errors[] = "Permanent address: Region is required.";
        }
        if (empty($formData['permanentProvinceCode'])) {
            $errors[] = "Permanent address: Province is required.";
        }
        if (empty($formData['permanentCityCode'])) {
            $errors[] = "Permanent address: City/Municipality is required.";
        }
        if (empty($formData['permanentBarangayCode'])) {
            $errors[] = "Permanent address: Barangay is required.";
        }
        if (empty($formData['permanentStreet'])) {
            $errors[] = "Permanent address: Street and House/Unit No. are required.";
        }
        if (empty($formData['permanentAddressFull'])) {
            $errors[] = "Full permanent address could not be determined. Please check your address entries.";
        }
    }

    // Employment Information
    if (empty($formData['employmentStatus'])) {
        $errors[] = "Employment status is required.";
    }
    if ($formData['employmentStatus'] == 'employed' || $formData['employmentStatus'] == 'self-employed') {
        if (empty($formData['employerName'])) {
            $errors[] = "Employer/Business name is required for your employment status.";
        }
        if (empty($formData['jobTitle'])) {
            $errors[] = "Job title/Position is required for your employment status.";
        }
        if (empty($formData['employmentAddress'])) {
            $errors[] = "Employer/Business address is required for your employment status.";
        }
    }
    if (empty($formData['monthlyIncome'])) {
        $errors[] = "Gross monthly income is required.";
    } elseif (!is_numeric($formData['monthlyIncome']) || $formData['monthlyIncome'] < 0) {
        $errors[] = "Gross monthly income must be a non-negative number.";
    }

    // Loan Details
    if (empty($formData['loanType'])) {
        $errors[] = "Loan type is required.";
    }
    if (empty($formData['loanAmount'])) {
        $errors[] = "Desired loan amount is required.";
    } elseif (!is_numeric($formData['loanAmount']) || $formData['loanAmount'] < 1000) { // Min amount from form
        $errors[] = "Desired loan amount must be a number and at least 1000.";
    }
    if (empty($formData['loanPurpose'])) {
        $errors[] = "Purpose of loan is required.";
    }
    if (empty($formData['loanTerm'])) {
        $errors[] = "Preferred loan term is required.";
    }
    if (!$formData['agreeTerms']) {
        $errors[] = "You must agree to the Terms and Conditions and Privacy Policy.";
    }

    // --- Process Data (If No Errors) ---
    if (empty($errors)) {
        // --- Database Insertion Placeholder ---
        // Ensure $conn is your mysqli connection object from db_connection.php
        if (isset($conn) && $conn) {
            $stmt = $conn->prepare("INSERT INTO loan_applications (
                user_id, -- if you have user sessions
                first_name, last_name, middle_name, date_of_birth, gender, civil_status, email, contact_number,
                present_region_code, present_province_code, present_city_code, present_barangay_code, present_street, present_address_full,
                permanent_region_code, permanent_province_code, permanent_city_code, permanent_barangay_code, permanent_street, permanent_address_full,
                employment_status, employer_name, job_title, monthly_income, employment_address,
                loan_type, loan_amount, loan_purpose, loan_term,
                application_date, status
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                NOW(), ?
            )");

            if ($stmt) {
                $default_status = "Pending"; // Initial status of the loan application
                // $user_id_to_insert = $user_id; // Use $user_id if available, otherwise null or a guest ID
                $user_id_to_insert = $_SESSION['user_id'] ?? null;


                $stmt->bind_param(
                    "isssssssssssssssssssssssdssssis", // Adjust 'i' for user_id if it's an integer
                    $user_id_to_insert,
                    $formData['firstName'],
                    $formData['lastName'],
                    $formData['middleName'],
                    $formData['dateOfBirth'],
                    $formData['gender'],
                    $formData['civilStatus'],
                    $formData['email'],
                    $formData['contactNumber'],
                    $formData['presentRegionCode'],
                    $formData['presentProvinceCode'],
                    $formData['presentCityCode'],
                    $formData['presentBarangayCode'],
                    $formData['presentStreet'],
                    $formData['presentAddressFull'],
                    $formData['permanentRegionCode'],
                    $formData['permanentProvinceCode'],
                    $formData['permanentCityCode'],
                    $formData['permanentBarangayCode'],
                    $formData['permanentStreet'],
                    $formData['permanentAddressFull'],
                    $formData['employmentStatus'],
                    $formData['employerName'],
                    $formData['jobTitle'],
                    $formData['monthlyIncome'],
                    $formData['employmentAddress'],
                    $formData['loanType'],
                    $formData['loanAmount'],
                    $formData['loanPurpose'],
                    $formData['loanTerm'],
                    $default_status
                );

                if ($stmt->execute()) {
                    // Success!
                    $_SESSION['success_message'] = "Loan application submitted successfully! We will review your application and get back to you.";
                    // Redirect to a success page or user's loan status page
                    // header("Location: loan_status.php"); // Example
                    // For now, just output success:
                    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Success</title></head><body>";
                    echo "<h1>Application Submitted!</h1>";
                    echo "<p>Your loan application has been submitted successfully. We will review it and contact you soon.</p>";
                    echo "<p><a href='submit_loan.php'>Apply for another loan</a> or <a href='userhomepage.php'>Go to Homepage</a></p>"; // Adjust links as needed
                    echo "</body></html>";
                    exit;
                } else {
                    $errors[] = "Database error: Failed to submit application. " . $stmt->error;
                    // Log the detailed error for admin review:
                    error_log("Loan Application DB Error: " . $stmt->error . " - Data: " . json_encode($formData));
                }
                $stmt->close();
            } else {
                $errors[] = "Database error: Failed to prepare statement. " . $conn->error;
                error_log("Loan Application DB Prepare Error: " . $conn->error);
            }
            // $conn->close(); // Usually closed at the end of the script or by db_connection.php
        } else {
            $errors[] = "Database connection is not available. Application cannot be saved.";
            // For demonstration if DB is not set up, simulate success or show what would be saved.
            // Remove this block in production if DB connection is mandatory.
            echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Debug - Submitted Data (No DB)</title></head><body>";
            echo "<h1>Debug: Loan Application Data (No Database Connection)</h1>";
            echo "<p>This is a simulation. In a real scenario, this data would be saved to a database.</p>";
            echo "<pre>" . htmlspecialchars(print_r($formData, true)) . "</pre>";
            echo "<p><a href='submit_loan.php'>Go Back</a></p>";
            echo "</body></html>";
            exit;
        }
        // --- End Database Insertion Placeholder ---

    }

    // If there are errors, display them
    if (!empty($errors)) {
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Error</title></head><body>";
        echo "<h1>Application Submission Failed</h1>";
        echo "<p>Please correct the following errors:</p><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='javascript:history.back()'>Go Back and Correct</a></p>";
        // Optional: Store form data in session to repopulate the form
        $_SESSION['loan_form_data_errors'] = $formData;
        $_SESSION['loan_form_errors'] = $errors;
        // header("Location: submit_loan.php"); // Redirecting back might be better UX
        // exit;
        echo "</body></html>";
        exit;
    }

} else {
    // Not a POST request, redirect to form or show error
    header("Location: submit_loan.php"); // Redirect to the loan form page
    exit;
}

?>