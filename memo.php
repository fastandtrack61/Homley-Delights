<?php
// Load PHPMailer Autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Function to send memo email using SMTP (PHPMailer)
function sendMemoEmail($email, $memoMessage) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // SMTP Configuration
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set SMTPDebug to off for production
    $mail->isSMTP(); // Enable SMTP
    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'josephjithin471@gmail.com'; // SMTP username
    $mail->Password = 'dmhj ctmw obzc knyi'; // Your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587; // Your SMTP port

    // Email content
    $mail->setFrom('Homley@support.com', 'Homley'); // Sender's email address and name
    $mail->addAddress($email); // Recipient's email address
    $mail->Subject = 'Memo Notification';
    $mail->Body = $memoMessage;

    // Send the email
    if ($mail->send()) {
        
        return true; // Email sent successfully
    } else {
        return false; // Failed to send email
    }
}
?>