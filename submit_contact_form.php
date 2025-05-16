<style>
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .close-alert {
        float: right;
        font-size: 1.5rem;
        line-height: 1;
        color: inherit;
        text-decoration: none;
    }
</style>

<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
include 'vendor/autoload.php';

// Function to easily create an alert (optional, but keeps code DRY)
function create_alert($type, $strong_message, $message, $is_dismissible = true)
{
    $dismiss_button = $is_dismissible ? '<button type="button" class="close-alert" onclick="this.parentElement.style.display=\'none\';" aria-label="Close">&times;</button>' : '';
    return "<div class='alert alert-{$type}' role='alert'>
              <strong>{$strong_message}</strong> <span class='alert-message'>{$message}</span>
              {$dismiss_button}
            </div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["contactName"]));
    $email_from = filter_var(trim($_POST["contactEmail"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["contactPhone"]));
    $message_body = trim($_POST["contactMessage"]);
    $recipient = "ploansystem@gmail.com"; // Your recipient email

    if (empty($name) || empty($message_body) || !filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        // Output styled error message
        echo create_alert('warning', 'Validation Error!', 'Oops! There was a problem. Please complete the form and try again.');
        exit;
    }
    try {
        $mail = new PHPMailer(true);
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output for troubleshooting
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ploansystem@gmail.com'; // Your Gmail address
        $mail->Password = 'jxpq cdkt irbi lmbr'; // Your Gmail App Password - IMPORTANT: Keep this secure!
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Or PHPMailer::ENCRYPTION_SMTPS for port 465
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('ploansystem@gmail.com', 'Ploan System Contact Form'); // "From" address. Can be your Gmail.
        $mail->addAddress($recipient); // Add a recipient
        $mail->addReplyTo($email_from, $name); // Set reply-to to the user's email

        // Content
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = "New Contact Form Submission from $name";
        $mail->Body = "Name: $name\n";
        $mail->Body .= "Email: $email_from\n";
        if (!empty($phone)) {
            $mail->Body .= "Phone: $phone\n";
        }
        $mail->Body .= "\nMessage:\n$message_body\n";

        $mail->send();
        http_response_code(200);
        // Output styled success message
        echo create_alert('success', 'Thank You!', 'Your message has been sent successfully.');

    } catch (Exception $e) {
        http_response_code(500);
        // Output styled error message
        // Be cautious about displaying $mail->ErrorInfo directly to users in production
        // For better security, you might log the detailed error and show a generic message.
        $user_message = "Oops! Message could not be sent due to a server issue. Please try again later.";
        // error_log("Mailer Error: {$mail->ErrorInfo}"); // Log the detailed error for admins
        echo create_alert('danger', 'Server Error!', $user_message);
        // If you must show Mailer Error (for debugging, not recommended for live):
        // echo create_alert('danger', 'Mailer Error!', "Message could not be sent. Details: {$mail->ErrorInfo}");
    }
} else {
    http_response_code(403);
    // Output styled error message
    echo create_alert('danger', 'Access Denied!', 'There was a problem with your submission. Please use the form correctly.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <center><a href="homepage.php" class="btn btn-primary" color="maroon">Go to Homepage</a></center>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<body>

</body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        // Automatically hide alerts after 5 seconds
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);
    });

    function closeAlert(element) {
        $(element).parent().fadeOut('slow');
    }

    function showAlert(type, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button></div>';
        $('#alertContainer').html(alertHtml);
    }

    function clearForm() {
        $('#contactForm')[0].reset();
    }

    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent the default form submission

        // Perform your AJAX request here
        $.ajax({
            type: 'POST',
            url: 'submit_contact_form.php', // The URL to your PHP script
            data: $('#contactForm').serialize(),
            success: function (response) {
                // Show success alert
                showAlert('success', 'Your message has been sent successfully.');
                clearForm();
            },
            error: function (xhr, status, error) {
                // Show error alert
                showAlert('danger', 'There was a problem sending your message. Please try again later.');
            }
        });
    }

    $(document).ready(function () {
        $('#contactForm').on('submit', handleFormSubmit);
    });

</script>

</head>

</html>