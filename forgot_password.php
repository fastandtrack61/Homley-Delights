<?php
session_start();
require('./Connect_database.php');

if(isset($_POST['newpass']))
{
    $email=$_POST['email'];
    $password=$_POST['confirm-password'];
    $sql="UPDATE tbl_login set password='$password' where username='$email'";
    if($conn->query($sql))
    {
        session_destroy();
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    Swal.fire({';
        echo '        title: "Success!",'; // Updated title
        echo '        text: "Your password has been successfully reset. Please Login.",';
        echo '        icon: "success",';
        echo '        showConfirmButton: false';
        echo '    });';
        echo '    setTimeout(function() {';
        echo '        window.location.href = "login.php";'; // Redirect after 2 seconds
        echo '    }, 4000);'; // 2 seconds delay
        echo '});';
        echo '</script>';
        
        
    }
    else
    {
        echo "error";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="./sweetalert.js"></script>
 
</head>
<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 400px;
    margin: 100px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.container h2 {
    margin-bottom: 20px;
    color: #333;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    
}

.input-group input {
    width: 60%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #4caf50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

.link {
    text-align: center;
}

.link a {
    color: #4caf50;
}

.error-message {
    color: red;
    margin-top: 10px;
}
#otp-section {
    display: none; /* Initially hide the OTP section */
}

.resend-btn {
    margin-top: 10px;
}

</style>
<body>
    <div class="container">
        <div id="hide">
        <form action="forgot_password.php" method="post">
            <h2>Forgot Password</h2>
            <p>Please enter your email address to reset your password.</p>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label id="error"></label>
            </div>
            <button type="button" id="reset">Reset Password</button>
            <p class="link"><a href="login.php">Back to Login</a></p>
            <?php
            // Display error message if any
            if(isset($_GET['error'])) {
                echo '<p class="error-message">' . $_GET['error'] . '</p>';
            }
            ?>
       
        </div>

        <div id="otp-section">
            <h2>Enter OTP</h2>
            <div class="input-group">
                <label for="otp">OTP</label>
                <input type="text" id="otp" name="otp" required>
                <label id="error3"></label>
            </div>
            <div >
            <button type="button" id="submit-otp">Submit OTP</button>
            <button type="button" class="resend-btn" id="resend-otp" disabled>Resend OTP<div id="timer"></div></button>
          </div>  
        </div>


        <div id="new-password-section" style="display: none;">
            <h2>Set New Password</h2>
            <div class="input-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" required>
                <label id="error1"></label>
            </div>
            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <label id="error2"></label>
            </div>
            <button type="submit" id="submit-new-password" name="newpass">Submit New Password</button>
        </div>
        </form>
    </div>
    
</body>
<script>
var flag = 0;
var emailExists = false;
var timer;

var Email = document.getElementById('email');
var error8 = document.getElementById('error');

Email.addEventListener('input', function() {
    error8.innerHTML = "";
    if (Email.value === "") {
        error8.style.color = "red";
        error8.innerHTML = "*Enter the Email";
        Email.focus();
    } else {
        Email.classList.remove("invalid");
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "check.php");
        xhr.send();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);
                    for (var i = 0; i < data.length; i++) {
                        if (Email.value === data[i].username) {
                            emailExists = true;
                            error8.style.color = "green";
                            error8.innerHTML = "&#10004 Email verified";
                            flag = 1;
                            break;
                        }
                    }
                    if (!emailExists) {
                        error8.style.color = "red";
                        error8.innerHTML = "&#10060 Email Not Registered";
                    }
                } else {
                    alert("error");
                }
            }
        };
    }
});

var data;

var reset = document.getElementById('reset');
reset.addEventListener('click', function() {
    if (flag == 1) {
        
        document.getElementById("hide").style.display = "none";

        var xhr = new XMLHttpRequest();
   
   xhr.open('POST', 'ttt.php');
   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   var email = document.getElementById('email').value;
   xhr.send("email=" + email);

   xhr.onreadystatechange = function() {
       if (xhr.readyState == 4 && xhr.status == 200) {
           data = JSON.parse(xhr.responseText);
          
       }
   };

    
        document.getElementById("otp-section").style.display = "block";

        startTimer();
    } else {
        document.getElementById("otp-section").style.display = "none";
    }
});

function startTimer() {
    var timeLeft = 60;
    timer = setInterval(function() {
        document.getElementById("timer").innerText = timeLeft + " s";
        timeLeft--;
        if (timeLeft < 0) {
            clearInterval(timer);
            document.getElementById("timer").innerText = "";
            document.getElementById("resend-otp").disabled = false; 
        }
    }, 1000);
}

document.getElementById("resend-otp").addEventListener("click", function() {
    this.disabled = true; 
    startTimer();
    var email = document.getElementById('email').value;
    document.getElementById('otp').value="";
    document.getElementById('error3').innerHTML="";
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ttt.php');
   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

 
   xhr.send("email=" + email);

   xhr.onreadystatechange = function() {
       if (xhr.readyState == 4 && xhr.status == 200) {
           data = JSON.parse(xhr.responseText);
          
          
       }
   };

  
});


   


document.getElementById('submit-otp').addEventListener('click', function() {
  var otp=document.getElementById('otp').value
    if (otp ==data['vcode'] ) {
        document.getElementById("otp-section").style.display = "none";
document.getElementById('new-password-section').style.display="block";
    }
    else
    {
        document.getElementById('error3').style.color="red"
       document.getElementById('error3').innerHTML="Entered OTP Is Wrong";
    }
});
var new_password=document.getElementById('new-password')
var confirm_password=document.getElementById('confirm-password')
var error1=document.getElementById('error1')
var error2=document.getElementById('error2')
var test6 = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{6,16}$/;

document.getElementById('new-password').addEventListener('blur',function(){

    if (new_password.value === "") {
                error1.style.color = "red"
                error1.innerHTML = "*Enter the Password";
              
            } else if (!new_password.value.match(test6)) {
                error1.style.color = "red"
                error1.innerHTML = "Password should contain <br>at least one alphabet,<br> one number, one special character,<br> and a length between 6 and 16 characters";;
              
            } else {
                error1.innerHTML = ""
                
            }

})


document.getElementById('confirm-password').addEventListener('blur',function(){

    if (confirm_password.value === "") {
                error2.style.color = "red"
                error2.innerHTML = "*Enter the Re_Password";
               
            } else if (confirm_password.value != new_password.value) {
                error2.style.color = "red"
                error2.innerHTML = "Password not matching";
               
            } else {
                error2.innerHTML = ""
                
            }

})
</script>
</html>
