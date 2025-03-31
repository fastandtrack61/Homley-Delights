<?php
require('ttt.php');
require('Connect_database.php');
session_start();
$ema="";
$email="";
if (isset($_POST['submit'])) {
    $state;
    // Capture inputs from the form
    $full_name = isset($_POST['Full_Name']) && !empty($_POST['Full_Name']) ? $_POST['Full_Name'] : null;
    $dob = isset($_POST['Dob']) && !empty($_POST['Dob']) ? $_POST['Dob'] : null;
    $gender = isset($_POST['fm']) ? $_POST['fm'] : null;
    $street_address = isset($_POST['Street_Address']) ? $_POST['Street_Address'] : null;
    $city = isset($_POST['district']) ? $_POST['district'] : null;
   $state=$_POST['place'];
    $postal = isset($_POST['Postal']) ? $_POST['Postal'] : null;
    $country = isset($_POST['Country']) ? $_POST['Country'] : null;
    $email = isset($_POST['Email']) ? $_POST['Email'] : null;
    $phone = isset($_POST['Phone']) ? $_POST['Phone'] : null;
    $password = isset($_POST['Password']) ? $_POST['Password'] : null;

    $birthYear = date('Y', strtotime($dob));
    $currentYear = date('Y');
    $age = $currentYear - $birthYear;
    $query = "SELECT * FROM tbl_googleusers WHERE email = '$email'";
    $result = $conn->query($query);
    if($result->num_rows==0)
    {
    // Use your original method without prepared statements
    $checkEmailQuery = "SELECT * FROM tbl_login WHERE username = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        $ema = "Email already exists. Please Login";

        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    Swal.fire({';
        echo '        title: "Error!",';
        echo '        text: "' . $ema . '",';
        echo '        icon: "error",';
        echo '        showCancelButton: false,';
        echo '        confirmButtonText: "OK"';
        echo '    }).then(function(result) {';
        echo '        if (result.isConfirmed) {';
        echo '            window.location.href = "login.php";'; // Redirect if confirmed
        echo '        }';
        echo '    });';
        echo '});';
        echo '</script>';
    } else {
        $insertLoginQuery = "INSERT INTO tbl_login (username, password, user_roles) VALUES ('$email', '$password', '0')";

        if ($conn->query($insertLoginQuery) === TRUE) {
            $insertedId = $conn->insert_id;

            // Insert into tbl_login
            $insertRegistrationQuery = "INSERT INTO tbl_registration (Full_Name, Dob, Gender, Street_Address, City, State, Postal, Country, Phone, age,fk_loginid) VALUES ('$full_name', '$dob', '$gender', '$street_address','$city', '$state', '$postal', '$country', '$phone', '$age','$insertedId')";

            $email = isset($_POST['Email']) ? $_POST['Email'] : null;
           
           
            if (sendVerificationEmail($email, $verificationCode)) {
                $_SESSION['verification_code'] = $verificationCode;

    $_SESSION['show_verification_modal'] = true;
    $_SESSION['limit']=1;
                echo 'Verification email sent successfully.';
            } else {
                echo 'Failed to send verification email.';
            }
           
           
           
            if ($conn->query($insertRegistrationQuery) === TRUE) {
                $insertedId = $conn->insert_id;
                $_SESSION['regid']= $insertedId;
                $img="INSERT INTO tbl_images(filename, filepath, fk_regid) VALUES('del1.jpg','uploads/del1.jpg',' $insertedId')";
                $conn->query($img);
                $insertOrUpdateQuery = "INSERT INTO tbl_verification (fk_regid, email_verify)
                VALUES (' $insertedId', '0')";
$conn->query($insertOrUpdateQuery);
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo '    Swal.fire({';
                echo '        title: "Registration Successful!",';
                echo '        text: "Email verification code has been sent to your email.",';
                echo '        icon: "success",';
                echo '        showConfirmButton: false';
                echo '    });';
                echo '    setTimeout(function() {';
                echo '        window.location.href = "verification.php";'; // Redirect after 2 seconds
                echo '    }, 1000);'; // 2 seconds delay
                echo '});';
                echo '</script>';
            } else {
                echo "Error inserting into tbl_login: " . $conn->error;
            }
        } else {
            echo "Error inserting into tbl_registration: " . $conn->error;
        }
    }} else 
    {
        $ema = "Email already exists. Please Login";

        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '    Swal.fire({';
        echo '        title: "Error!",';
        echo '        text: "' . $ema . '",';
        echo '        icon: "error",';
        echo '        showCancelButton: false,';
        echo '        confirmButtonText: "OK"';
        echo '    }).then(function(result) {';
        echo '        if (result.isConfirmed) {';
        echo '            window.location.href = "login.php";'; // Redirect if confirmed
        echo '        }';
        echo '    });';
        echo '});';
        echo '</script>';
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
    <script src="./sweetalert.js"></script>
    <script src="./sweetalert.min.js"></script>
</head>

<body id="signup-bg">
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
                <a href="./food-items.php" class="desktop-item">Food-Items</a>
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




    <div id="mov">
        <h1 id="tptext">Sign-up</h1>
        <form action="#" name="myform" method="post" id="signup-form" onsubmit="return validate1()">
            <div class="s1">

                <table>
                    <tr>
                        <td class="ft">Full Name </td>
                        <td class="ft">Birth Date</td>
                    </tr>

                    <tr>
                        <td><input type="text" class="In" id="Full_Name" name="Full_Name"></td>
                        <td><input type="date" class="In" id="Dob" name="Dob"></td>
                    </tr>
                    <tr>
                        <td id="error1"></td>
                        <td id="errorDob"></td>
                    </tr>

                    <tr></tr>
                    <tr>
                        <td colspan="2">
                            <font size="5px"> <b>Gender:</b></font> &nbsp;&nbsp; Male<input type="radio" name="fm" value="Male" checked> &nbsp;Female<input type="radio" name="fm" value="Female">
                    </tr>
                </table>
            </div>

            <div class="s1">
                <table>
                    <tr>
                        <td class="ft">Present Address</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                       
                    </tr>
                    <tr>
                        <td>
                        <textarea  name="Street_Address" id="Street_Address"  class="In" style="height: 51px;
    width: 228px; resize:none;" cols="30" rows="10"></textarea>

                        </td>
                    </tr>
                    <tr>
                        <td id="error2"></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td>State / Province</td>
                    </tr>
                    <tr>
                    <td>  <select id="districtSelect" name="district" onchange="updatePlaces1('districtSelect', 'placesSelect')" class="In1" >
        <option value="">Select District</option>
      </select></td>
                        <td> <select id="placesSelect"  name="place" class="In1">
        
        <option value="">Select Place</option>
      </select> 
     </td>
                    </tr>
                    <tr>
                        <td id="error4"></td>
                        <td id="error5"></td>
                    </tr>
                    <tr>
                        <td>Postal / Zip Code</td>
                        <td>Country</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="In" id="Postal" name="Postal"></td>
                        <td><input type="text" class="In" id="Country" name="Country"></td>
                    </tr>
                    <tr>
                        <td id="error6"></td>
                        <td id="error7"></td>
                    </tr>

                </table>
            </div>



            <div class="s1">
                <table>
                    <tr>
                        <td>Email</td>
                        <td>Phone</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="In" id="Email" name="Email"></td>
                        <td><input type="text" class="In" id="Phone" name="Phone"></td>
                    </tr>
                    <tr>
                        <td id="error8"></td>
                        <td id="error9"></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td>Re-type Password</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="In" id="Password" name="Password"></td>
                        <td><input type="text" class="In" id="Re_Password" name="Re_Password"></td>
                    </tr>
                    <tr>
                        <td id="error10"></td>
                        <td id="error11"></td>
                    </tr>
                </table>
            </div>
            <input type="submit" id="signup-button" value="submit" name="submit">



        </form>
        <div id="popup">
            <p>Registration successful!</p>
            <p>Redirecting to the new page...</p>
        </div>
    </div>
</body>
<script>
    var test = /^[A-Za-z]+(\s[A-Za-z]+)?$/;
    var test1 = /^[A-Za-z\s]+$/;
    var test2 = /^\d{6}$/;
    var test3 = /^[A-Za-z]+$/;
    var test4 = /^(?!([6-9])\1{9})[6-9]\d{9}$/;
        var test5 = /^[a-zA-Z0-9._%+-]+@gmail\.(com|in)$/;
        var test6 = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{6,16}$/;
    var Full_Name = document.getElementById('Full_Name');
    var Dob = document.getElementById('Dob');
    var Street_Address = document.getElementById('Street_Address')
    var City = document.getElementById('districtSelect')
    var State = document.getElementById('placesSelect')
    var Postal = document.getElementById('Postal')
    var Country = document.getElementById('Country')
    var Phone = document.getElementById('Phone')
    var Email = document.getElementById('Email')
    var Password = document.getElementById('Password')
    var Re_Password = document.getElementById('Re_Password')
    var errorDob = document.getElementById('errorDob');
    var error1 = document.getElementById('error1')
    var error2 = document.getElementById('error2')
    var error3 = document.getElementById('error3')
    var error4 = document.getElementById('error4')
    var error5 = document.getElementById('error5')
    var error6 = document.getElementById('error6')
    var error7 = document.getElementById('error7')
    var error8 = document.getElementById('error8')
    var error9 = document.getElementById('error9')
    var error10 = document.getElementById('error10')
    var error11 = document.getElementById('error11')






    document.addEventListener("DOMContentLoaded", function() {

        function isValidDate(dateString) {
    // Check if the date string is a valid date
    var regexDate = /^\d{4}-\d{2}-\d{2}$/;
    var enteredDate = new Date(dateString);
    var currentDate = new Date();
    
    if (!dateString.match(regexDate)) {
        // If the date string doesn't match the regex, return false
        return false;
    }
    
    // Get the year and month components of the entered date
    var enteredYear = enteredDate.getFullYear();
    var enteredMonth = enteredDate.getMonth();
    
    // Get the year and month components of the current date
    var currentYear = currentDate.getFullYear();
    var currentMonth = currentDate.getMonth();
    
    // Calculate the age difference in years
    var age = currentYear - enteredYear;
    
    // If the current month is before the entered month or in the same month but before the entered day,
    // decrease the age by 1 to account for the fact that the person hasn't had their birthday yet this year
    if (currentMonth < enteredMonth || (currentMonth === enteredMonth && currentDate.getDate() < enteredDate.getDate())) {
        age--;
    }
    
    // Check if age is greater than or equal to 10
    return age >= 10;
}
        Full_Name.addEventListener('blur', function() {
            if (Full_Name.value === "") {
                error1.style.color = "red"
                error1.innerHTML = "*Enter the Full Name";
                Full_Name.focus();



            } else if (!Full_Name.value.match(test)) {
                error1.style.color = "red"
                error1.innerHTML = "Full Name should contain only letters";
                Full_Name.focus();
            } else {
                error1.innerHTML = ""
                Full_Name.style.backgroundColor = "white"
            }
            // Your logic for blur event here
        });




        Dob.addEventListener('blur', function() {
            if (Dob.value === "") {
                errorDob.innerHTML = "*Enter the Birth Date";
                errorDob.style.color = "red"
                Dob.classList.add("invalid");
                Dob.focus();
            } else {
                // Check if the entered date is a valid date
                if (!isValidDate(Dob.value)) {
                    errorDob.innerHTML = "Enter a valid Birth Date";
                    errorDob.style.color = "red"
                    Dob.classList.add("invalid");
                    Dob.focus();
                } else {
                    errorDob.innerHTML = "";
                    Dob.classList.remove("invalid");
                }
            }
        });
        Street_Address.addEventListener('blur', function() {
            if (Street_Address.value === "") {
                error2.style.color = "red"
                error2.innerHTML = "*Enter the Street Address";
                Street_Address.classList.add("invalid");
                Street_Address.focus();
            } else if (!Street_Address.value.match(test1)) {
                error2.style.color = "red"
                Street_Address.classList.add("invalid");
                error2.innerHTML = "Street Address should contain only letters";
                Street_Address.focus();
            } else {
                error2.innerHTML = ""
                Street_Address.classList.remove("invalid")
            }


        })
        City.addEventListener('blur', function() {
            if (City.value === "") {
                error4.style.color = "red"
                error4.innerHTML = "*Enter the City";
                City.focus();
            } else if (!City.value.match(test1)) {
                error4.style.color = "red"
                City.classList.add("invalid");
                error4.innerHTML = " City should contain only letters";
                City.focus();
            } else {
                error4.innerHTML = ""
                City.classList.remove("invalid")
            }


        })


        State.addEventListener('blur', function() {
            if (State.value === "") {
                error5.style.color = "red"
                error5.innerHTML = "*Enter the State";
                State.focus();
            } else if (!State.value.match(test1)) {
                error5.style.color = "red"
                State.classList.add("invalid");
                error5.innerHTML = "State should contain only letters";
                State.focus();
            } else {
                error5.innerHTML = ""
                State.classList.remove("invalid")
            }


        })
        Postal.addEventListener('blur', function() {
            if (Postal.value === "") {
                error6.style.color = "red"
                error6.innerHTML = "*Enter the Zip Code";
                Postal.focus();
            } else if (!Postal.value.match(test2)) {
                error6.style.color = "red"
                Postal.classList.add("invalid");
                error6.innerHTML = "Zip Code should contain only Numbers";
                Postal.focus();
            } else {
                error6.innerHTML = ""
                Postal.classList.remove("invalid")
            }
        })



        Country.addEventListener('blur', function() {
            if (Country.value === "") {
                error7.style.color = "red"
                error7.innerHTML = "*Enter the Country";
                Country.focus();
            } else if (!Country.value.match(test3)) {
                error7.style.color = "red"
                Country.classList.add("invalid");
                error7.innerHTML = "Country should contain only letters";
                Country.focus();
            } else {
                error7.innerHTML = ""
                Country.classList.remove("invalid")
            }


        })
        Phone.addEventListener('blur', function() {
            if (Phone.value === "") {
                error9.style.color = "red"
                error9.innerHTML = "*Enter the Phone";
                Phone.focus();
            } else if (!Phone.value.match(test4)) {
                error9.style.color = "red"
                Phone.classList.add("invalid");
                error9.innerHTML = "Phone should contain only letters";
                Phone.focus();
            } else {
                error9.innerHTML = ""
                Phone.classList.remove("invalid")
            }


        })
        Email.addEventListener('blur', function() {
            error8.innerHTML = ""
            if (Email.value === "") {
                error8.style.color = "red"
                error8.innerHTML = "*Enter the Email";
                Email.focus();
            } else if (!Email.value.match(test5)) {
                error8.style.color = "red"
                Email.classList.add("invalid");
                error8.innerHTML = "Email-Format not Matching ";
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
                                    error8.style.color = "red"
                                    error8.innerHTML = "*Email already exists";
                                    Email.focus()
                                    break; // No need to continue checking once a match is found
                                }
                            }
                            if (!emailExists) {
                                error8.innerHTML = ""; // Clear error message if email doesn't exist

                            }

                        } else {
                            alert("error");
                        }
                    }
                };



            }



        })




        Password.addEventListener('blur', function() {
            if (Password.value === "") {
                error10.style.color = "red"
                error10.innerHTML = "*Enter the Password";
                Password.focus();
            } else if (!Password.value.match(test6)) {
                error10.style.color = "red"
                Password.classList.add("invalid");
                error10.innerHTML = "Password should contain <br>at least one alphabet,<br> one number, one special character,<br> and a length between 6 and 16 characters";
                Password.focus();
            } else {
                error10.innerHTML = ""
                Password.classList.remove("invalid")
            }


        })
        Re_Password.addEventListener('blur', function() {
            if (Re_Password.value === "") {
                error11.style.color = "red"
                error11.innerHTML = "*Enter the Re_Password";
                Re_Password.focus();
            } else if (Password.value != Re_Password.value) {
                error11.style.color = "red"
                Re_Password.classList.add("invalid");
                error11.innerHTML = "Password not matching";
                Re_Password.focus();
            } else {
                error11.innerHTML = ""
                Re_Password.classList.remove("invalid")
            }


        })


    });




    function validate1() {

        var Full_Name = document.myform.Full_Name.value;
        var Dob = document.myform.Dob.value
        var Street_Address = document.myform.Street_Address.value
        var City = document.getElementById('districtSelect').value
    var State = document.getElementById('placesSelect').value
        var Postal = document.myform.Postal.value
        var Country = document.myform.Country.value
        var Phone = document.myform.Phone.value
        var Email = document.myform.Email.value
        var Password = document.myform.Password.value
        var Re_Password = document.myform.Re_Password.value
        var isValid = true;
        var t1 = true;

        var t3 = true;
        var t4 = true;

        var t6 = true;

        var t8 = true;

        var t10 = true;

        var t12 = true;

        var t14 = true;

        var t16 = true;

        var t18 = true;

        var t20 = true;

        var t22 = true;
      

        if (Full_Name == null || Full_Name == "") {
            error1.style.color = "red"
            error1.innerHTML = "First Name can't be blank";
            t1 = false;
            document.getElementById('Full_Name').focus();

        }

        if (Dob == "" || Dob == null) {
            errorDob.style.color = "red";
            errorDob.innerHTML = "*Enter a valid Birth Date";
            document.getElementById('Dob').focus();
            t3 = false;
        }
        if (Street_Address == null || Street_Address == "") {
            error2.style.color = "red"
            error2.innerHTML = "Street_Address can't be blank"
            document.getElementById('Street_Address').focus();
            t4 = false;
        }

       

        if (City == null || City == "") {
            error4.style.color = "red"
            error4.innerHTML = "City can't be blank"
            document.getElementById('districtSelect').focus();
            t8 = false;
        }

        if (State == null || State == "") {
            error5.style.color = "red"
            error5.innerHTML = "State can't be blank"
            document.getElementById('placesSelect').focus();
            t10 = false;
        }

        if (Country == null || Country == "") {
            error7.style.color = "red"
            error7.innerHTML = "Country can't be blank"
            document.getElementById('Country').focus();
            t12 = false;
        }

        if (Postal == null || Postal == "") {
            error6.style.color = "red"
            error6.innerHTML = "Postal/Zip code can't be blank"
            document.getElementById('Postal').focus();
            t14 = false;
        }

        if (Phone == null || Phone == "") {
            error9.style.color = "red"
            error9.innerHTML = "*Enter the Phone";
            document.getElementById('Phone').focus();
            t16 = false;
        }

        if (Email == null || Email == "") {
            error8.style.color = "red"
            error8.innerHTML = "*Enter the Email"
            document.getElementById('Email').focus();
            t18 = false;
        }

        if (Password == null || Password == "") {
            error10.style.color = "red"
            error10.innerHTML = "Enter the Password"
            document.getElementById('Password').focus();
            t20 = false;
            return false
        }

        if (!Re_Password == null || Re_Password == "") {
            error11.style.color = "red"
            error11.innerHTML = "Re-type Password can't be blank";
            document.getElementById('Re_Password').focus();
            t22 = false
           return false
        }


        if (t1 == false  || t3 == false || t4 == false   || t6 == false   || t8 == false   || t10 == false   || t12 == false   || t14 == false  || t16 == false   || t18 == false   || t20 == false   || t22 == false) {
            return false;
        } else {
        
  
       var modal = document.getElementById("myModal");
       modal.style.display = "block";
       var closeButton = document.querySelector(".close")
       closeButton.addEventListener("click", function() {
           modal.style.display = "none";
       });
    
        } 
    }

    function updateDistrictsAndPlaces1() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', './dis.json', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                populateSelect1('districtSelect', 'placesSelect', data);
            } else {
                console.error('Error fetching data:', xhr.status);
            }
        }
    };
    xhr.send();
}

function populateSelect1(districtSelectId, placesSelectId, data) {
    const districtSelect = document.getElementById(districtSelectId);
    const placesSelect = document.getElementById(placesSelectId);

    // Clear previous options
    placesSelect.innerHTML = '<option value="">Select Place</option>';

    // Populating the districts
    const districtNames = new Set(); // Using Set to avoid repetition
    data.sort((a, b) => a.District.localeCompare(b.District));
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.District;
        option.textContent = item.District+',Kerala';
        if (!districtNames.has(item.District)) { // Check if district name already exists
            districtSelect.appendChild(option);
            districtNames.add(item.District); // Add district name to Set
        }
    });
}

function updatePlaces1(districtSelectId, placesSelectId) {
    const districtSelect = document.getElementById(districtSelectId);
    const placesSelect = document.getElementById(placesSelectId);
    const selectedDistrict = districtSelect.value;

    // Clear previous options
    placesSelect.innerHTML = '<option value="">Select Place</option>';

    const xhr = new XMLHttpRequest();
    xhr.open('GET', './dis.json', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const districtData = data.find(item => item.District === selectedDistrict);
                if (districtData) {
                    const places = districtData.Places;
                    const placeNames = new Set(); // Using Set to avoid repetition
                    places.forEach(place => {
                        if (!placeNames.has(place)) { // Check if place name already exists
                            const placeOption = document.createElement('option');
                            placeOption.value = place;
                            placeOption.textContent = place;
                            placesSelect.appendChild(placeOption);
                            placeNames.add(place); // Add place name to Set
                        }
                    });
                } else {
                    console.log('No places found for the selected district.');
                }
            } else {
                console.error('Error fetching data:', xhr.status);
            }
        }
    };
    xhr.send();
}

// Call function to populate districts and places initially
updateDistrictsAndPlaces1();







    // Rest of your JavaScript code
</script>

</html>