<?php
session_start();
require('Connect_database.php');

$error = "";




if(isset($_POST['submit'])){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    
    $sql="SELECT * FROM tbl_login l, tbl_registration r WHERE l.username='$username' AND l.password='$password' AND l.login_id=r.fk_loginid";
    $result=$conn->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if($row['status'] == 0) {
            echo '<script>
                window.onload = function() {
                    swal({
                        title: "Error!",
                        text: "Account Is Blocked. Contact Admin.",
                        icon: "error"
                    })
                }
            </script>';
        } else {
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = $row['regid'];
            
            switch($row['user_roles']) {
                case 0:
                    header('Location: ./user/profile.php');
                    exit;
                case 1:
                    header('Location: ./seller/overview.php');
                    exit;
                case 2:
                    header('Location: ./delivery_person/delivery_person.php');
                    exit;
                case 3:
                    header('Location: ./admin/overview.php');
                    exit;
                default:
                    $error = "Invalid user role";
            }
        }
    } else {
        $error = "Username or password is incorrect";
    }
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login</title>
    <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style1.css">
        <script src="./sweetalert.min.js"></script>
       
</head>
<body>
<nav>
        <div class="wrapper">
            <div class="logo"><a href="#">Homely</a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li>
                    <a href="#" class="desktop-item">Food-Items</a>
                    <input type="checkbox" id="showDrop">
                    <label for="showDrop" class="mobile-item">Dropdown Menu</label>
                    <ul class="drop-menu">
                    <li><a href="./breakfast.php">Breakfast Items</a></li>
                        <li><a href="./lunch.php">HomeHarvest Lunch</a></li>
                        <li><a href="./evening.php">Evening Eatables</a></li>
                    </ul>
                </li>

                <li><a href="login.php"><button id="but">Sign-In</button></a></li>
                <li><a href="become_a_seller.php"><button id="but">Become a Seller</button></a></li>


            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>

    
        <div class="container">
        

        <form action="#" method="post" name="myform1" onsubmit="return validate2()" >
        <!-- Inside your HTML form -->

    <div class="div1">
        <div>
           <h1 style="text-align:center;">Homely</h1>
        </div>
        <?php if ($error): ?>
    <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
        <div>
            <input type="text" placeholder="Enter the Username" class="Up" id="user" name="user"  ><br>
            
            <input type="password" placeholder="Enter the password" class="Up" id="password" name="pass" ><br>
           
            <a href="forgot_password.php">
                <h1 id="text1">forgot password?</h1>
            </a><br>
            <input type="submit" value="submit" id="subut" name="submit">
            <div id="align">
                <table>
                    <tr>
                        <td class="line">
                            <hr>
                        </td>
                        <td>or</td>
                        <td class="line">
                            <hr>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <div id="f1">
                
                <?php require('./sigin_with_google.php');?>

                

            </div>
            <br>

            <h1 id="tex"> Need an account? &nbsp;<a id="text1" href="signup.php">SIGN UP</a></h1>
        </div>
      
    </div>

</form>

    </div>
</body>
<script>
var user = document.getElementById('user');
var password = document.getElementById('password');
var e1 = document.getElementById('err1');

user.addEventListener('focusout', function() {
    // Handle focusout event here
    if (user.value === "") {
        e1.innerHTML = 'Username required';
    } else {
        e1.innerHTML = "";
    }
});

password.addEventListener('focusin', function() {
    // Handle focusin event here
    if (user.value === "") {
        e1.innerHTML = 'Username required';
    } else {
        e1.innerHTML = "";
    }
});

password.addEventListener('focusout', function() {
    // Handle focusout event here 
    if (user.value !== "" && password.value === "") {
        e1.innerHTML = 'Password required';
    } else {
        e1.innerHTML = "";
    }
});



    
    function validate2() {
            var user = document.myform1.user.value;
            var password = document.myform1.password.value;
            var e1 = document.getElementById('err1');

            if (user == "" && password=="") {
                e1.innerHTML = "Enter The Username and password";
                return false;
            } else if (password=="") {
                e1.innerHTML = "Please Enter The Password"; // Corrected the error message
                return false;
            }

            return true;
        }





// Show the modal when the page loads, if needed
 var modal = document.getElementById('verificationModal');
    
        modal.style.display = "block";
   //cancel button functionality
var cancelButton = document.querySelector('.cancelbtn');
cancelButton.addEventListener('click', function() {
    <?php 
    // Reset verification attempts when modal is closed
    unset($_SESSION['verification_attempts']);
    $_SESSION['show_verification_modal'] = false;
    ?>
    // Close the modal
    var modal = document.getElementById('verificationModal');
    modal.style.display = "none";
});

// When the user clicks anywhere outside of the modal, close it









        
    </script>
</html>

