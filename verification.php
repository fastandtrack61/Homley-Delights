

<?php
session_start();
$regid=$_SESSION['regid'];
echo $regid;
require('./Connect_database.php');

// Initialize attempt count if not already set
if (!isset($_SESSION['attempt_count'])) {
    $_SESSION['attempt_count'] = 0;
}

// Check if verification code is submitted
if (isset($_POST['submit'])) {
    // Increment attempt count
    $_SESSION['attempt_count']++;

    // Check if attempt count exceeds 3
    if ($_SESSION['attempt_count'] > 3) {
        // Redirect to login page if attempt count exceeds 3
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Retrieve verification code from the form
    $verification_code = isset($_POST['verification_code']) ? $_POST['verification_code'] : '';

    // Check if the verification code matches the one stored in the session
    if ($verification_code === $_SESSION['verification_code']) {
        // Reset attempt count
        $_SESSION['attempt_count'] = 0;
        $updateVerificationQuery = "UPDATE tbl_verification SET email_verify = '1' WHERE fk_regid = '$regid'";
        if ($conn->query($updateVerificationQuery) === TRUE) {
            // Verification successful, display success message using SweetAlert
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo '    Swal.fire({';
            echo '        title: "Verification Successful!",';
            echo '        text: "Your email has been successfully verified.",';
            echo '        icon: "success",';
            echo '        showConfirmButton: false';
            echo '    });';
            echo '    setTimeout(function() {';
            echo '        window.location.href = "login.php";'; // Redirect after 2 seconds
            echo '    }, 2000);'; // 3 seconds delay
            echo '});';
            echo '</script>';
           
            session_unset();
            session_destroy();

        } else {
            echo "Error updating verification status: " . $conn->error;
        }

        // Verification successful, redirect to success page or perform further actions
       
    } else {
        // Verification failed, display error message
        $error = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
        }

        .container {
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            text-align: center;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
    <script src="./sweetalert.js"></script>
    <script src="./sweetalert.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Enter Verification Code</h2>
        <?php if (isset($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>
        <form method="post">
            <input type="text" name="verification_code" placeholder="Verification Code" required>
            <button type="submit" name="submit">Verify</button>
        </form>
        <p>Attempts remaining: <?php echo 3 - $_SESSION['attempt_count']; ?></p>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
