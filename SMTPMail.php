<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

class SMTPMail
{
    public function sendMail($Recipient, $Subject, $Body)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.gmail.com';
            $mail->Username = 'ploansystem@gmail.com';
            $mail->Password = 'jxpq cdkt irbi lmbr';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;


            // Send Email
            $mail->setFrom('ploansystem@gmail.com', 'P-Loan');
            $mail->addAddress($Recipient);

            $mail->isHTML(true);
            $mail->Subject = $Subject;
            $mail->Body = $Body;

            $mail->send();
            return true;
        } catch (Exception $error) {
            return 'Email sending failed: ' . $error->getMessage();
        }
    }
}
