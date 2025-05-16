<?php
declare(strict_types=1); // Enforce strict types for better code quality

// --- Session Security Configuration (Best to set these before session_start()) ---
ini_set('session.use_only_cookies', '1'); // Only use cookies for session IDs
ini_set('session.cookie_httponly', '1');  // Prevent client-side script access to session cookie
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0'); // Send cookie only over HTTPS if available
ini_set('session.cookie_samesite', 'Lax'); // Mitigate CSRF attacks. 'Strict' is more secure but can affect UX.
// Consider session_regenerate_id(true) on login, logout, and privilege changes.

session_start();

// --- Configuration Constants ---
define('UPLOAD_DIR_NAME', 'uploads/'); // Directory name
define('BASE_APP_PATH', dirname(__FILE__)); // Or an appropriate base path for your application
define('UPLOAD_DIR', BASE_APP_PATH . '/' . UPLOAD_DIR_NAME); // Full path to upload directory
define('DEFAULT_PROFILE_PIC', 'default.png'); // Ensure this image exists in UPLOAD_DIR
define('MAX_FILE_SIZE_BYTES', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_IMG_EXTENSIONS', ["jpg", "jpeg", "png", "gif"]);
define('ALLOWED_IMG_MIME_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// --- Database Connection ---
// Ensure this path is correct. Using require_once is safer.
require_once('../connect/connection.php');

// --- Utility Functions ---

/**
 * Handles critical errors by logging them and setting a flash message for the user.
 * @param string $log_message Message to log.
 * @param string $user_message User-friendly message.
 * @param string|null $redirect_url URL to redirect to. If null, script continues (page might show error).
 */
function handle_critical_error(string $log_message, string $user_message = "A critical error occurred. Please try again later.", ?string $redirect_url = 'profile.php'): void
{
    error_log("CRITICAL ERROR: " . $log_message);
    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => $user_message];
    if ($redirect_url) {
        header("Location: " . $redirect_url);
        exit;
    }
}

// Ensure database connection is established
if (!$conn || $conn->connect_error) {
    // For a user-facing page, use the critical error handler
    handle_critical_error(
        "Database connection failed: " . ($conn ? $conn->connect_error : 'Unknown error - $conn not initialized'),
        "We're having trouble connecting to our services. Please try again later. (Ref: DB_CONN_FAIL)"
    );
    // The function above will exit if redirect_url is set. If not, the script might continue to a broken state.
    // It's often better to ensure the redirect happens or the page renders a dedicated error template.
}

// --- Authentication & Authorization ---
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page
    exit;
}
$user_id = (int) $_SESSION['user_id']; // Cast to int for safety

// --- CSRF Token Generation (if not already set) ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// --- Flash Message Handling (for success/error messages from redirects) ---
$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // Clear after displaying
}

// --- Utility Functions (Continued) ---

/**
 * Get current profile picture filename from the database.
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @return string Filename of the profile picture (or default if not set/found)
 */
function getCurrentDbProfilePic(mysqli $conn, int $user_id): string
{
    $current_pic_filename = DEFAULT_PROFILE_PIC;
    $stmt = $conn->prepare("SELECT profile_pic FROM user WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $stmt->bind_result($db_pic);
            if ($stmt->fetch() && !empty($db_pic)) {
                // Check if the file actually exists before setting it, otherwise use default
                if (file_exists(UPLOAD_DIR . $db_pic)) {
                    $current_pic_filename = $db_pic;
                } else {
                    // File in DB record doesn't exist on disk (and it's not the default one we are already checking)
                    if ($db_pic !== DEFAULT_PROFILE_PIC) {
                        error_log("User {$user_id} DB profile pic '{$db_pic}' not found on disk. Using default.");
                    }
                }
            }
        } else {
            error_log("Failed to execute statement to get current profile pic for user {$user_id}: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Failed to prepare statement to get current profile pic for user {$user_id}: " . $conn->error);
    }
    return $current_pic_filename;
}

/**
 * Placeholder for image re-processing to enhance security.
 * Call this after a successful move_uploaded_file.
 * @param string $filepath Full path to the uploaded image.
 * @param string &$error_msg Reference to an error message string.
 * @return bool True on success, false on failure.
 */
function reprocessImage(string $filepath, string &$error_msg): bool
{
    // Example using GD (ensure GD extension is enabled)
    /*
    $image_info = getimagesize($filepath);
    $mime_type = $image_info['mime'] ?? '';

    $new_image = null;
    switch ($mime_type) {
        case 'image/jpeg':
            $new_image = imagecreatefromjpeg($filepath);
            break;
        case 'image/png':
            $new_image = imagecreatefrompng($filepath);
            imagesavealpha($new_image, true); // Preserve transparency
            break;
        case 'image/gif':
            $new_image = imagecreatefromgif($filepath);
            break;
        default:
            $error_msg = "Image re-processing failed: Unsupported image type.";
            error_log($error_msg . " Path: " . $filepath);
            return false;
    }

    if (!$new_image) {
        $error_msg = "Image re-processing failed: Could not create image from file.";
        error_log($error_msg . " Path: " . $filepath);
        return false;
    }

    // Overwrite the original file with the re-processed one
    // This helps strip potential malicious code or excessive metadata.
    $success = false;
    switch ($mime_type) {
        case 'image/jpeg':
            $success = imagejpeg($new_image, $filepath, 85); // Quality 0-100
            break;
        case 'image/png':
            $success = imagepng($new_image, $filepath, 7); // Compression 0-9
            break;
        case 'image/gif':
            $success = imagegif($new_image, $filepath);
            break;
    }
    imagedestroy($new_image);

    if (!$success) {
        $error_msg = "Image re-processing failed: Could not save re-processed image.";
        error_log($error_msg . " Path: " . $filepath);
        return false;
    }
    return true;
    */
    // For now, assume success if not implemented
    $error_msg = ""; // Clear any potential error message if not implemented
    return true;
}


/**
 * Handles the profile picture upload process.
 * @param array $file_input The $_FILES['profile_pic'] array.
 * @param int $user_id The ID of the user.
 * @param string $current_db_profile_pic The filename of the current profile picture in the DB.
 * @param string &$error_msg Reference to the error message string.
 * @return string|null The new filename if successful, null otherwise.
 */
function handleProfilePictureUpload(array $file_input, int $user_id, string $current_db_profile_pic, string &$error_msg): ?string
{
    if (isset($file_input['name']) && $file_input['error'] === UPLOAD_ERR_OK) {
        $img_name = $file_input['name'];
        $img_tmp = $file_input['tmp_name'];
        $img_size = $file_input['size'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        if (!in_array($img_ext, ALLOWED_IMG_EXTENSIONS)) {
            $error_msg = "Invalid file type. Only " . implode(', ', ALLOWED_IMG_EXTENSIONS) . " are allowed.";
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $img_tmp);
        finfo_close($finfo);
        if (!in_array($mime_type, ALLOWED_IMG_MIME_TYPES)) {
            $error_msg = "Invalid file content. Only " . implode(', ', ALLOWED_IMG_EXTENSIONS) . " images are allowed.";
            return null;
        }

        if ($img_size > MAX_FILE_SIZE_BYTES) {
            $error_msg = "File is too large. Maximum size is " . (MAX_FILE_SIZE_BYTES / 1024 / 1024) . "MB.";
            return null;
        }

        if (!is_dir(UPLOAD_DIR)) {
            if (!mkdir(UPLOAD_DIR, 0755, true)) { // 0755: rwxr-xr-x
                $error_msg = "Failed to create upload directory. Please check server permissions.";
                error_log("Failed to create directory: " . UPLOAD_DIR . " for user " . $user_id);
                return null;
            }
        }
        // IMPORTANT: Ensure UPLOAD_DIR is not web-accessible for script execution.
        // Use .htaccess in UPLOAD_DIR:
        // <FilesMatch "\.(php|phtml|php3|php4|php5|php7|phps)$">
        //     Order Deny,Allow
        //     Deny from all
        // </FilesMatch>
        // Or better, serve images via a script or ensure the web server only serves specific image types.


        $new_filename = "profile_" . $user_id . "_" . uniqid('', true) . "." . $img_ext;
        $img_upload_path = UPLOAD_DIR . $new_filename;

        if (move_uploaded_file($img_tmp, $img_upload_path)) {
            // Attempt to re-process the image for security
            $reprocess_error = '';
            if (!reprocessImage($img_upload_path, $reprocess_error)) {
                @unlink($img_upload_path); // Delete the potentially unsafe uploaded file
                $error_msg = "Image processing failed: " . $reprocess_error;
                error_log("Image re-processing failed for user {$user_id}, file {$new_filename}: {$reprocess_error}");
                return null;
            }

            // Remove old profile picture (if not default and different from new one)
            if ($current_db_profile_pic !== DEFAULT_PROFILE_PIC && $current_db_profile_pic !== $new_filename) {
                $old_pic_path = UPLOAD_DIR . $current_db_profile_pic;
                if (file_exists($old_pic_path)) {
                    if (!@unlink($old_pic_path)) {
                        error_log("Could not delete old profile picture {$old_pic_path} for user {$user_id}. Check permissions.");
                    }
                }
            }
            return $new_filename;
        } else {
            $error_msg = "Failed to move uploaded file. Check server permissions or path correctness.";
            error_log("Failed to move uploaded file to: " . $img_upload_path . " for user " . $user_id);
            return null;
        }
    } elseif (isset($file_input['error']) && $file_input['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the server's maximum file size limit.",
            UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the form's maximum file size limit.",
            UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder on the server.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk on the server.",
            UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
        ];
        $error_code = $file_input['error'];
        $error_msg = $upload_errors[$error_code] ?? "An unknown error occurred during file upload.";
        error_log("File upload error for user {$user_id}: code {$error_code} - {$error_msg}");
        return null;
    }
    return null; // No new file uploaded or an error occurred
}


// --- Handle Profile Update (POST Request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $page_error_message = null; // Use this for errors displayed on the page

    // 1. CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !hash_equals($csrf_token, $_POST['csrf_token'])) {
        error_log("CSRF token mismatch for user_id: {$user_id}");
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate for next attempt
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Invalid request. Please try submitting the form again. (CSRF Fail)'];
        header("Location: profile.php"); // Redirect back to the form
        exit;
    }

    // 2. Sanitize and Validate Inputs
    $firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING) ?: '');
    $lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING) ?: '');
    $email = filter_var(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?: ''), FILTER_SANITIZE_EMAIL);
    $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING) ?: ''); // Basic sanitize, regex for validation

    // 3. Basic Server-Side Validation
    if (empty($firstname) || empty($lastname) || empty($email)) {
        $page_error_message = "First name, last name, and email are required.";
    } elseif (mb_strlen($firstname) < 2 || mb_strlen($firstname) > 50) {
        $page_error_message = "First name must be between 2 and 50 characters.";
    } elseif (mb_strlen($lastname) < 2 || mb_strlen($lastname) > 50) {
        $page_error_message = "Last name must be between 2 and 50 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $page_error_message = "Invalid email address format.";
    } elseif (!empty($phone) && !preg_match('/^[+]?[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/', $phone)) {
        $page_error_message = "Invalid phone number format.";
    } elseif (mb_strlen($phone) > 20) {
        $page_error_message = "Phone number is too long (max 20 characters).";
    }

    // Fetch current email to check if it's being changed for uniqueness validation
    $current_email_stmt = $conn->prepare("SELECT email FROM user WHERE user_id = ?");
    $current_db_email = '';
    if ($current_email_stmt) {
        $current_email_stmt->bind_param("i", $user_id);
        $current_email_stmt->execute();
        $current_email_stmt->bind_result($current_db_email);
        $current_email_stmt->fetch();
        $current_email_stmt->close();
    }

    // Email Uniqueness Check (if email is being changed and no other errors yet)
    if (!$page_error_message && strtolower($email) !== strtolower($current_db_email)) {
        $stmt_check_email = $conn->prepare("SELECT user_id FROM user WHERE email = ? AND user_id != ?");
        if ($stmt_check_email) {
            $stmt_check_email->bind_param("si", $email, $user_id);
            $stmt_check_email->execute();
            $stmt_check_email->store_result();
            if ($stmt_check_email->num_rows > 0) {
                $page_error_message = "This email address is already in use by another account.";
            }
            $stmt_check_email->close();
        } else {
            $page_error_message = "Error checking email uniqueness. Please try again.";
            error_log("Failed to prepare email uniqueness check for user {$user_id}: " . $conn->error);
        }
    }

    if (!$page_error_message) {
        $current_db_profile_pic = getCurrentDbProfilePic($conn, $user_id);
        $upload_error_message = ''; // Passed by reference
        $new_profile_pic_filename = handleProfilePictureUpload(
            $_FILES['profile_pic'] ?? [],
            $user_id,
            $current_db_profile_pic,
            $upload_error_message
        );

        if ($upload_error_message) {
            $page_error_message = $upload_error_message; // Prioritize upload error message
        }

        if (!$page_error_message) { // Proceed to DB update if no prior errors and no upload errors
            $sql = "";
            $params = [];
            $types = "";

            if ($new_profile_pic_filename) {
                $sql = "UPDATE user SET firstname=?, lastname=?, email=?, phone=?, profile_pic=? WHERE user_id=?";
                $params = [$firstname, $lastname, $email, $phone, $new_profile_pic_filename, $user_id];
                $types = "sssssi";
            } else {
                $sql = "UPDATE user SET firstname=?, lastname=?, email=?, phone=? WHERE user_id=?";
                $params = [$firstname, $lastname, $email, $phone, $user_id];
                $types = "ssssi";
            }

            $stmt_update = $conn->prepare($sql);
            if ($stmt_update) {
                $stmt_update->bind_param($types, ...$params);
                if ($stmt_update->execute()) {
                    $_SESSION['username'] = $firstname; // Update session username

                    $pic_for_session = $new_profile_pic_filename ?: $current_db_profile_pic;
                    // Ensure UPLOAD_DIR_NAME is used for web paths, not the full server path UPLOAD_DIR
                    $_SESSION['profile_pic_path'] = UPLOAD_DIR_NAME . (file_exists(UPLOAD_DIR . $pic_for_session) ? $pic_for_session : DEFAULT_PROFILE_PIC);

                    $stmt_update->close();
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate CSRF token
                    $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Profile updated successfully!'];
                    header("Location: profile.php");
                    exit();
                } else {
                    $page_error_message = "Failed to update profile. Please try again.";
                    error_log("DB execute error for user {$user_id} during profile update: " . $stmt_update->error);
                }
                if (isset($stmt_update) && $stmt_update instanceof mysqli_stmt) {
                    $stmt_update->close();
                }
            } else {
                $page_error_message = "Database error (prepare failed). Please try again.";
                error_log("DB prepare error for profile update for user {$user_id}: " . $conn->error);
            }
        }
    }
    // If there was a page error, set it to flash message to be displayed after potential redirect or on page reload
    if ($page_error_message) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => $page_error_message];
    }
}

// --- Fetch Current Data for the Form (always done after potential POST) ---
$db_firstname = '';
$db_lastname = '';
$db_email = '';
$db_phone = '';
$db_profile_pic_filename = DEFAULT_PROFILE_PIC; // Start with default

$stmt_fetch = $conn->prepare("SELECT firstname, lastname, email, phone, profile_pic FROM user WHERE user_id = ?");
if ($stmt_fetch) {
    $stmt_fetch->bind_param("i", $user_id);
    if ($stmt_fetch->execute()) {
        $result = $stmt_fetch->get_result();
        if ($user_data = $result->fetch_assoc()) {
            $db_firstname = $user_data['firstname'];
            $db_lastname = $user_data['lastname'];
            $db_email = $user_data['email'];
            $db_phone = $user_data['phone'] ?? ''; // Handle possible null phone
            if (!empty($user_data['profile_pic']) && file_exists(UPLOAD_DIR . $user_data['profile_pic'])) {
                $db_profile_pic_filename = $user_data['profile_pic'];
            } elseif (!empty($user_data['profile_pic']) && $user_data['profile_pic'] !== DEFAULT_PROFILE_PIC) {
                error_log("User {$user_id} has invalid profile_pic '{$user_data['profile_pic']}' in DB or file missing. Serving default.");
            }
        } else {
            error_log("User with ID {$user_id} not found in database, but session active. Logging out.");
            session_unset();
            session_destroy();
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Your session is invalid. Please log in again. (Ref: DATA_SYNC_ERR)'];
            header('Location: index.php');
            exit;
        }
    } else {
        error_log("Failed to execute statement to fetch user data for user {$user_id}: " . $stmt_fetch->error);
        // Use existing flash message if set from POST, otherwise set a new one.
        if (!isset($_SESSION['flash_message'])) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error fetching your profile data. Please try refreshing.'];
        }
    }
    $stmt_fetch->close();
} else {
    error_log("Failed to prepare statement to fetch user data for user {$user_id}: " . $conn->error);
    if (!isset($_SESSION['flash_message'])) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'A critical error occurred while fetching your data. Please try again. (Ref: FETCH_PREP_FAIL)'];
    }
}

// Determine profile picture path for display
$profile_pic_web_path = UPLOAD_DIR_NAME . $db_profile_pic_filename; // Web accessible path
$profile_pic_disk_path = UPLOAD_DIR . $db_profile_pic_filename;   // Server disk path for file_exists check
$cache_buster_value = time(); // Default cache buster

if (file_exists($profile_pic_disk_path)) {
    $cache_buster_value = filemtime($profile_pic_disk_path);
} else {
    // Fallback to default if specific pic not found
    $profile_pic_web_path = UPLOAD_DIR_NAME . DEFAULT_PROFILE_PIC;
    $default_pic_disk_path = UPLOAD_DIR . DEFAULT_PROFILE_PIC;
    if (file_exists($default_pic_disk_path)) {
        $cache_buster_value = filemtime($default_pic_disk_path);
    } else {
        // If default is also missing, log an error. The img src will be broken.
        error_log("CRITICAL: Default profile picture " . DEFAULT_PROFILE_PIC . " is missing from " . UPLOAD_DIR);
        // $profile_pic_web_path will point to a non-existent default, browser will show alt text.
    }
}

// Re-fetch flash message in case it was set during data fetching logic
if (isset($_SESSION['flash_message']) && !$flash_message) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-maroon: #800000;
            --content-bg: #f8f9fa;
            /* Slightly lighter background */
            --card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.07);
            /* Softer shadow */
            --input-border-color: #ced4da;
            --input-focus-border-color: var(--primary-maroon);
            --input-focus-box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.2);
            /* Slightly softer focus */
            --danger-text: #dc3545;
        }

        body {
            background-color: var(--content-bg);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            /* Added for smoother rendering */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        #wrapper {
            /* Assumes sidebar.php might use this structure */
            display: flex;
            width: 100%;
        }

        #content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        #main-content {
            padding: 1.5rem;
            /* Consistent padding */
        }

        .profile-card {
            width: 100%;
            position: relative;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem 2.5rem;
            /* Existing padding */
            border-radius: 0.8rem;
            /* Slightly larger radius */
            box-shadow: var(--card-shadow);
            border: none;
            /* Remove default border, rely on shadow */
        }

        .profile-card-header {
            color: var(--primary-maroon);
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 1.8rem;
            /* Slightly smaller */
            font-weight: 600;
        }

        .profile-pic-section {
            /* Improvement: Added a dedicated section for better structure */
            text-align: center;
            margin-bottom: 1.5rem;
            /* Space below pic area */
        }

        .profile-pic-container {
            position: relative;
            width: 150px;
            /* Slightly smaller */
            height: 150px;
            margin: 0 auto 0.5rem;
            /* Less margin below pic itself */
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e9ecef;
            cursor: pointer;
            display: inline-block;
            /* Allows text-align center */
            transition: border-color 0.2s ease;
            /* Smooth transition */
        }

        .profile-pic-container:hover,
        .profile-pic-container:focus-within {
            border-color: var(--primary-maroon);
            /* Highlight border on hover/focus */
            outline: none;
            /* Remove default outline, use border instead */
        }

        .profile-pic-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            /* Remove potential small gap */
        }

        .profile-pic-upload-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.65);
            /* Slightly darker overlay */
            color: white;
            text-align: center;
            padding: 12px 0;
            /* More padding */
            font-size: 0.85rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5em;
            /* Improvement: Prevent text/icon from stealing clicks */
            pointer-events: none;
        }

        .profile-pic-container:hover .profile-pic-upload-label,
        .profile-pic-container:focus-within .profile-pic-upload-label {
            opacity: 1;
        }

        #profile_pic_input {
            /* Visually hidden file input - unchanged */
            border: 0;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
            white-space: nowrap;
        }

        .file-input-help {
            /* Improvement: Combined help texts */
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .file-error-help {
            /* Improvement: Style for dedicated file error message */
            min-height: 1.2em;
            /* Reserve space */
            font-size: 0.875em;
            color: var(--danger-text);
            margin-top: 0.25rem;
            font-weight: 500;
            /* Make error more prominent */
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.6rem;
            color: #343a40;
        }

        .form-control {
            border-radius: 0.35rem;
            padding: 0.8rem 1rem;
            border: 1px solid var(--input-border-color);
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            /* Smooth focus */
        }

        .form-control::placeholder {
            color: #6c757d;
            opacity: 0.8;
            /* Slightly fainter placeholder */
        }

        .form-control:focus {
            border-color: var(--input-focus-border-color);
            box-shadow: var(--input-focus-box-shadow);
        }

        /* Ensure validation messages are visible */
        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: var(--danger-text);
        }

        .was-validated .form-control:invalid:focus,
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        .was-validated .form-control:valid,
        .form-control.is-valid {
            border-color: var(--input-focus-border-color);
        }

        .invalid-feedback,
        .valid-feedback {
            font-size: 0.875em;
            display: block;
            /* Ensure feedback always takes space if needed */
            width: 100%;
            margin-top: 0.25rem;
        }

        .btn-maroon {
            background-color: var(--primary-maroon);
            border-color: var(--primary-maroon);
            color: white;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 0.35rem;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-maroon:hover:not(:disabled) {
            background-color: #600000;
            border-color: #500000;
            color: white;
        }

        .btn-maroon:focus {
            box-shadow: var(--input-focus-box-shadow);
            background-color: #600000;
            /* Maintain hover color on focus */
            border-color: #500000;
        }

        .btn-maroon:disabled {
            background-color: #a05252;
            border-color: #a05252;
            cursor: not-allowed;
            opacity: 0.75;
            /* Slightly more visible disabled state */
        }

        .btn-spinner {
            margin-right: 0.5rem;
        }

        /* Ensure alert close button is easily clickable */
        .alert .btn-close {
            padding: 1rem;
            /* Larger click target */
            outline: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .profile-card {
                margin: 1rem;
                padding: 1.5rem;
            }

            .profile-card-header {
                font-size: 1.6rem;
                margin-bottom: 2rem;
            }

            /* No need for extra margin override if using Bootstrap's mb-3 correctly */
        }
    </style>
</head>

<body class="antialiased">
    <?php
    // --- Sidebar Inclusion (same as before) ---
    if (file_exists('sidebar.php')) {
        include('sidebar.php');
    } else if (file_exists('../sidebar.php')) {
        include('../sidebar.php');
    }
    ?>

    <div id="content-wrapper">
        <main id="main-content" class="container-fluid mt-0">
            <div class="profile-card">
                <h2 class="profile-card-header">Edit Profile</h2>

                <?php if ($flash_message): ?>
                    <div class="alert alert-<?= htmlspecialchars($flash_message['type']) ?> alert-dismissible fade show"
                        role="alert">
                        <?= htmlspecialchars($flash_message['text']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" id="profileForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="profile-pic-section">
                        <label for="profile_pic_input" class="profile-pic-container" tabindex="0"
                            aria-label="Change profile picture (Current: <?= htmlspecialchars(basename($profile_pic_web_path)) ?>)">
                            <img src="<?= htmlspecialchars($profile_pic_web_path) ?>?v=<?= $cache_buster_value ?>"
                                id="profilePicPreview" class="profile-pic-preview" alt="Current Profile Picture">
                            <span class="profile-pic-upload-label">
                                <i class="fas fa-camera" aria-hidden="true"></i> Change Photo
                            </span>
                        </label>
                        <input type="file" name="profile_pic" id="profile_pic_input" accept=".jpg, .jpeg, .png, .gif"
                            aria-describedby="fileHelp fileErrorHelp">
                        <div id="fileErrorHelp" class="file-error-help" aria-live="polite"></div>
                        <div id="fileHelp" class="file-input-help">
                            Max size: <?= MAX_FILE_SIZE_BYTES / 1024 / 1024 ?>MB. Allowed: JPG, PNG, GIF.
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="firstname" class="form-label">First Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                value="<?= htmlspecialchars($db_firstname) ?>" required minlength="2" maxlength="50"
                                aria-describedby="firstname-feedback">
                            <div id="firstname-feedback" class="invalid-feedback">Please enter your first name (2-50
                                characters).</div>
                        </div>
                        <div class="col-md-6">
                            <label for="lastname" class="form-label">Last Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                value="<?= htmlspecialchars($db_lastname) ?>" required minlength="2" maxlength="50"
                                aria-describedby="lastname-feedback">
                            <div id="lastname-feedback" class="invalid-feedback">Please enter your last name (2-50
                                characters).</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($db_email) ?>" required maxlength="100"
                            aria-describedby="email-feedback">
                        <div id="email-feedback" class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= htmlspecialchars($db_phone) ?>"
                            pattern="^[+]?[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$" maxlength="20"
                            placeholder="e.g., 09123456789" aria-describedby="phone-feedback">
                        <div id="phone-feedback" class="invalid-feedback">Please enter a valid phone number (max 20
                            characters, numbers and common symbols like +, -, (, ) allowed).</div>
                    </div>

                    <button type="submit" id="updateProfileBtn" class="btn btn-maroon w-100 mt-3">
                        <span class="spinner-border spinner-border-sm btn-spinner d-none" role="status"
                            aria-hidden="true"></span>
                        <span class="btn-text">Update Profile</span>
                    </button>
                </form>
            </div>
        </main>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profilePicInput = document.getElementById('profile_pic_input');
            const profilePicPreview = document.getElementById('profilePicPreview');
            const fileErrorHelp = document.getElementById('fileErrorHelp');
            const profileForm = document.getElementById('profileForm');
            const updateProfileBtn = document.getElementById('updateProfileBtn');
            const btnSpinner = updateProfileBtn.querySelector('.btn-spinner');
            const btnText = updateProfileBtn.querySelector('.btn-text');
            const picContainerLabel = document.querySelector('.profile-pic-container'); // Get the label

            const MAX_FILE_SIZE_JS = <?= MAX_FILE_SIZE_BYTES ?>;
            // Improvement: Case-insensitive check in JS is slightly safer
            const ALLOWED_EXTENSIONS_JS = ['jpeg', 'jpg', 'png', 'gif'];

            function validateFile(file) {
                fileErrorHelp.textContent = ''; // Clear previous error
                fileErrorHelp.classList.remove('is-invalid'); // Pertains more to inputs, but good practice
                picContainerLabel?.classList.remove('is-invalid'); // Remove potential invalid visual state

                if (!file) return true; // No file selected is valid

                const fileName = file.name.toLowerCase();
                const fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

                if (!ALLOWED_EXTENSIONS_JS.includes(fileExtension)) {
                    fileErrorHelp.textContent = 'Invalid file type. Please select a JPG, JPEG, PNG, or GIF image.';
                    // Optionally add visual indication to the container
                    picContainerLabel?.classList.add('is-invalid'); // Needs corresponding CSS if desired
                    return false;
                }

                if (file.size > MAX_FILE_SIZE_JS) {
                    fileErrorHelp.textContent = `File is too large. Maximum size is ${MAX_FILE_SIZE_JS / 1024 / 1024}MB.`;
                    picContainerLabel?.classList.add('is-invalid');
                    return false;
                }

                return true;
            }

            if (profilePicInput && profilePicPreview && fileErrorHelp) {
                profilePicInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (validateFile(file)) {
                        if (file) {
                            // Improvement: Use try-catch for FileReader
                            try {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    profilePicPreview.src = e.target.result;
                                }
                                reader.onerror = function () {
                                    console.error("FileReader error.");
                                    fileErrorHelp.textContent = 'Could not read file preview.';
                                }
                                reader.readAsDataURL(file);
                            } catch (err) {
                                console.error("Error initializing FileReader:", err);
                                fileErrorHelp.textContent = 'Could not initialize file preview.';
                            }
                        }
                        // Optional: If user selects a valid file then selects 'cancel',
                        // you might want to reset the preview to the original server image.
                        // Requires storing the original path initially.
                    } else {
                        // If validation fails, clear the input value
                        profilePicInput.value = '';
                        // Optional: Reset preview to original image if validation fails after showing a temp preview
                        // profilePicPreview.src = originalImagePath; // Need to store original path in JS
                    }
                });
            }

            // Accessibility: Allow profile pic container (label) to trigger file input
            if (picContainerLabel && profilePicInput) {
                picContainerLabel.addEventListener('click', () => profilePicInput.click());
                picContainerLabel.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault(); // Prevent default space scroll
                        profilePicInput.click();
                    }
                });
            }

            if (profileForm && updateProfileBtn) {
                profileForm.addEventListener('submit', function (event) {
                    // 1. Validate the file input again on submit
                    const file = profilePicInput.files[0];
                    const isFileValid = validateFile(file); // Uses the helper

                    // 2. Check standard form validity
                    const isFormValid = profileForm.checkValidity();

                    if (!isFormValid || !isFileValid) {
                        event.preventDefault();
                        event.stopPropagation();

                        // Focus the first invalid field OR the file input if it's the issue
                        const firstInvalidField = profileForm.querySelector(':invalid');
                        if (!isFileValid) {
                            profilePicInput.focus(); // Focus hidden input (browser might focus label)
                            picContainerLabel?.focus(); // Explicitly focus the visible label
                        } else if (firstInvalidField) {
                            firstInvalidField.focus();
                        }

                    } else {
                        // Form is client-side valid
                        updateProfileBtn.disabled = true;
                        if (btnSpinner) btnSpinner.classList.remove('d-none');
                        if (btnText) btnText.textContent = 'Updating...';
                        // Form submits naturally
                    }

                    // Always add 'was-validated' after checks to show feedback
                    profileForm.classList.add('was-validated');
                });
            }

            // Auto-dismiss flash alerts (same robust logic as before)
            const allAlerts = document.querySelectorAll('.alert-dismissible');
            allAlerts.forEach(function (alertEl) {
                let timeoutDuration = alertEl.classList.contains('alert-success') ? 5000 : 8000; // 5s success, 8s others

                setTimeout(() => {
                    // Use try-catch for robustness
                    try {
                        const alertInstance = bootstrap.Alert.getInstance(alertEl);
                        if (alertInstance) {
                            alertInstance.close();
                        } else if (alertEl.parentNode) {
                            // Fallback: try creating instance or remove manually
                            new bootstrap.Alert(alertEl).close();
                        }
                    } catch (e) {
                        console.error("Error closing alert:", e);
                        // Final fallback if bootstrap fails completely
                        if (alertEl.parentNode) {
                            alertEl.parentNode.removeChild(alertEl);
                        }
                    }
                }, timeoutDuration);
            });
        });
    </script>
</body>

</html>
<?php
// --- Close DB connection (same as before) ---
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>