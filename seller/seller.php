<?php
$success="";
session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];

$email = $_SESSION['username'];
$userid = $_SESSION['userid'];
$regid = "";
$full_name = "";
$dob = "";
$gender = "";
$street_address = "";
$city = "";
$state = "";
$postal = "";
$country = "";
$phone = "";
$age = "";
$filepath="";


    $sql = "SELECT * FROM tbl_login l,tbl_registration r,tbl_images i where l.username='$email' and l.fk_regid=r.regid and i.fk_logid='$userid'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        
            $regid = $row['regid'];
            $full_name = $row['full_name'];
            $dob = $row['dob'];
            $gender = $row['gender'];
            $street_address = $row['street_address'];
            $city = $row['city'];
            $state = $row['state'];
            $postal = $row['postal'];
            $country = $row['country'];
            $phone = $row['phone'];
            $age = $row['age'];
            $filepath=$row['filepath'];
        }}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style1.css">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
<nav>
        <div class="wrapper">
            <div class="logo1" ><a href="#">Homely</a></div>
            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
           
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
    <!-- The sidebar -->
    <div class="sidebar">
      <label for="">DASHBOARD</label>  
  <a class="active" href="#home">Profile</a>
  <a href="#news">Order Histroy</a>
  <a href="#contact">Schedule</a>
  <a href="#about">About</a>
  

</div>

<!-- Page content -->
<div class="content">

                <div>
                    <form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
                        <input type="file" name="image" id="image" accept="image/*" style="display: none;">
                        <img src="<?php echo $filepath ?>" alt="Default Image" id="imagePreview" >
                        <br>
                        <input type="submit" value="Upload" name="upload">
                    </form>



                </div>
                <div id="d6">
    Name: <?php echo $full_name; echo $userid;?>
    <br>
    Address: <?php echo $street_address . " " . "<br>" .
                $city . " " . $state . " " . $postal . " " . $country;
            ?>
    <br>
    Email: <?php echo $email; ?>
    <br>
    Phone: <?php echo $phone; ?>
</div>

            </div>


</body>

<script>
        // Trigger file input when clicking on the default image
        document.getElementById('imagePreview').addEventListener('click', function() {
            document.getElementById('image').click();
        });

        // Display selected image filename on file input change
        document.getElementById('image').addEventListener('change', function() {
            var input = this;
            var reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('imagePreview').src = e.target.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            } else {
                document.getElementById('imagePreview').src = "default-image.jpg";
            }
        });
    </script>

</html>


<?php
    



if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (adjust the limit as needed)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert image information into the database
            $filename = basename($_FILES["image"]["name"]);
            $filepath = $target_file;

           

           

            $sql = "UPDATE tbl_images set filename='$filename', filepath='$filepath' where fk_logid='$userid'";
            if ($conn->query($sql) === true) {
                $success= "The file $filename has been uploaded and the record has been added to the database.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
               
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}


?>