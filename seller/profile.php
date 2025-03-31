<?php
session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];

if(empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}

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


$sql = "SELECT * FROM tbl_login l,tbl_registration r,tbl_images i where l.username='$email' and r.regid='$userid' and r.fk_loginid=l.login_id and i.fk_regid=r.regid";
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

           

           

            $sql = "UPDATE tbl_images set filename='$filename', filepath='$filepath' where fk_regid='$userid'";
            if ($conn->query($sql) === true) {
                echo  '<script>
                window.onload = function() {
                    swal("Success!", "Imge Updated Successfully", "success");
                }
            </script>';
              
               
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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Dashboard</title>
<link rel="stylesheet" href="stylesell.css">

<script src="../sweetalert.min.js"></script>
</head>
<body>
<nav>
        <div class="wrapper">
            <div class="logo1" ><a href="#">Homely</a></div>
         
           
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>
<div class="sidenav">
    <a href="overview.php" >Overview</a>
    <a href="./notification.php" >Notifications</a>
    <a href="#" onclick="toggleSubmenu('product-management')">Product Management</a>
    <div class="submenu" id="product-management">
    <a href="order_management.php" >Pre-Book Orders</a>
        <a href="add_product.php">Add Product</a>
        <a href="./normal_orders.php">Orders</a>
        <a href="./schduled.php">Schduled Deliveries</a>
    </div>
    <a href="profile.php"  class="active">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div style="margin-left: 250px; padding: 20px;">
    <!-- Content area where dashboard content will be displayed -->
    <h2>Welcome to Your Seller Dashboard</h2>
    <p>This is where you can view important metrics and manage your business.</p>
    <div class="form-container">
    <form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
        <input type="file" name="image" id="image" accept="image/*" style="display: none;">
        <img src="<?php echo $filepath ?>" alt="Default Image" id="imagePreview">
        <br>
        <input type="submit" value="Upload" name="upload" class="submit-btn" onclick="return validateImage()">
            <p id="errorText" style="color: red; display: none;">Please select an image.</p>
    </form>
</div>

<div id="user-details">
    <p><span>Name:</span> <?php echo $full_name; echo $userid;?></p>
    <p><span>Address:</span> <?php echo $street_address . " " . "<br>" .
            $city . " " . $state . " " . $postal . " " . $country; ?></p>
    <p><span>Email:</span> <?php echo $email; ?></p>
    <p><span>Phone:</span> <?php echo $phone; ?></p>
</div>






</div>

<script>
// JavaScript function to toggle submenu visibility
function toggleSubmenu(submenuId) {
    var submenu = document.getElementById(submenuId);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}

document.getElementById('image').addEventListener('change', function() {
        var input = this;
        var reader = new FileReader();
        var imagePreview = document.getElementById('imagePreview');
        var errorText = document.getElementById('errorText');

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
            document.getElementById('imageLabel').innerText = input.files[0].name; // Update label text
            errorText.style.display = 'none'; // Hide error text if image is selected
        } else {
            imagePreview.src = "default-image.jpg";
            document.getElementById('imageLabel').innerText = 'Select an image'; // Reset label text
            errorText.style.display = 'block'; // Show error text if no image is selected
        }
    });

    function validateImage() {
        var imageInput = document.getElementById('image');
        var errorText = document.getElementById('errorText');

        // Check if an image is selected
        if (imageInput.files.length === 0) {
            errorText.style.display = 'block';
            return false; // Prevent form submission
        } else {
            errorText.style.display = 'none';
            return true; // Allow form submission
        }
    }

    // Trigger file input when clicking on image preview
    document.getElementById('imagePreview').addEventListener('click', function() {
        document.getElementById('image').click();
    });

</script>

</body>
</html>
