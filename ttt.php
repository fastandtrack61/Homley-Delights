<?php

// Include PHPMailer classes and autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$data=array();
if(!empty( $_POST['email']))
{
    $resetpassword=$_POST['email'];
    
$verificationCode=generateVerificationCode();
$data['vcode']=$verificationCode;

    echo json_encode($data);

    
    sendVerificationEmail($resetpassword,$verificationCode);

}

$email="";
// Function to send verification email
function sendVerificationEmail($email, $verificationCode) {
    $_SESSION['verification_code'] = $verificationCode;
    $mail = new PHPMailer(true); // Passing `true` enables exceptions

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set SMTPDebug to off for production
        $mail->isSMTP(); // Enable SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'josephjithin471@gmail.com'; // SMTP username
        $mail->Password = 'dmhj ctmw obzc knyi'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('Homely@gmail.com', 'Homley');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Email Verification';
        $mail->Body = "Your verification code is: $verificationCode";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Example usage
 // Email address to send verification email to
$verificationCode = generateVerificationCode(); // Generate verification code (you need to implement this function)
if (sendVerificationEmail($email, $verificationCode)) {
    echo 'Verification email sent successfully.';
}
// Function to generate random verification code
function generateVerificationCode($length = 6) {
    $characters = '0123456789';
    $verificationCode = '';
    for ($i = 0; $i < $length; $i++) {
        $verificationCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $verificationCode;
}

?>
