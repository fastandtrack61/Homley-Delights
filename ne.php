<?php
$conn = new mysqli("localhost", "root", "", "homely");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $gender = $_POST['gender'];

  

    $targetDir = "imge/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
      $image = $targetFile;
  
      if ($gender === 'male') {
          $sql = "INSERT INTO male_services (name, description, price, image) VALUES (?, ?, ?, ?)";
      } else {
          $sql = "INSERT INTO female_services (name, description, price, image) VALUES (?, ?, ?, ?)";
      }
  
      $stmt = $conn->prepare($sql);
  
      if ($stmt) {
          $stmt->bind_param("ssds", $name, $description, $price, $image);
  
          if ($stmt->execute()) {
              // Success message with SweetAlert
              echo '<script>
                  alert("New service added successfully!");
                  window.location = "add_service.php"; // Redirect to dashboard or any other page
                  </script>';
          } else {
              echo "Error executing the statement: " . $stmt->error;
          }
  
          $stmt->close();
      } else {
          echo "Error preparing the statement: " . $conn->error;
      }
  } else {
      echo "Sorry, there was an error uploading your file.";
  }
  
} else {
    echo "Form not submitted.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admindash.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        body {
            background-color: #fce9da;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: rgb(151, 105, 78);
            color: #fff;
            border-radius: 10px 10px 0 0;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: rgb(151, 105, 78);
            border-color: #333;
        }

        .btn-primary:hover {
            background-color: #555;
            border-color: #555;
        }

        .sidebar {
            width: 250px;
            height: 100%;
            background-color: rgb(151, 105, 78);
            position: fixed;
            left: 0;
            top: 0;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .menu {
            list-style-type: none;
            padding: 0;
        }

        .menu li {
            padding: 10px;
            text-align: center;
        }

        .menu li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .menu li.active {
            background-color: #111;
        }

        .main--content {
            margin-left: 250px;
            padding: 20px;
        }

        .header--wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header--title span {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .header--title h2 {
            font-size: 24px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li><a href="admin_dashboard - (1)"><i class="fas fa-user"></i>
                    <span>Customer Details</span></a>
            </li>
            <li><a href="admin_review.php"><i class="fas fa-chart-bar"></i>
                    <span>Reviews</span></a>
            </li>
            <li><a href="admin_dashboard - (3).php"><i class="fas fa-tachometer-alt"></i>
                    <span>Manage Staff</span></a>
            </li>
            <li><a href="admin_accepted.php"><i class="fas fa-question-circle"></i>
                    <span>Appointment Management</span></a>
            </li>
            <li><a href="admin_dashboard - (5).php"><i class="fas fa-cog"></i>
                    <span>View Staff</span></a>
            </li>
            <li class="active"><a href="add_service.php"><i class="fas fa-cog"></i>
                    <span>Manage Services</span></a>
            </li>
        </ul>
    </div>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Admin</span>
                <h2>Dashboard</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search">
                </div>
                <img src="logo.png">
                <div style="color:white;">
                    <a href="logout.php" style="text-decoration:none; color: white;">
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Add New Service</h5>
                        </div>
                        <div class="card-body">
                            <form id="addServiceForm" action="#" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="name">Service Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback" id="nameError"></div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                        cols="50" required></textarea>
                                    <div class="invalid-feedback" id="descriptionError"></div>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01"
                                        required>
                                    <div class="invalid-feedback" id="priceError"></div>
                                </div>
                                <div class="form-group">
                                    <label>Gender:</label>
                                    <div>
                                        <input type="radio" id="male" name="gender" value="male" required>
                                        <label for="male">Male</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="female" name="gender" value="female" required>
                                        <label for="female">Female</label>
                                    </div>
                                    <div class="invalid-feedback" id="genderError"></div>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image:</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                        required>
                                    <div class="invalid-feedback" id="imageError"></div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Custom JavaScript for form validation -->
    <script>
        $(document).ready(function () {
            $('#addServiceForm').submit(function (event) {
                var isValid = true;

                // Validate Service Name
                if ($('#name').val().trim() === '') {
                    $('#name').addClass('is-invalid');
                    $('#nameError').text('Service Name is required.');
                    isValid = false;
                } else {
                    $('#name').removeClass('is-invalid');
                    $('#nameError').text('');
                }

                // Validate Description
                if ($('#description').val().trim() === '') {
                    $('#description').addClass('is-invalid');
                    $('#descriptionError').text('Description is required.');
                    isValid = false;
                } else {
                    $('#description').removeClass('is-invalid');
                    $('#descriptionError').text('');
                }

                // Validate Price
                if ($('#price').val().trim() === '') {
                    $('#price').addClass('is-invalid');
                    $('#priceError').text('Price is required.');
                    isValid = false;
                } else {
                    $('#price').removeClass('is-invalid');
                    $('#priceError').text('');
                }

                // Validate Gender
                if (!$('#male').is(':checked') && !$('#female').is(':checked')) {
                    $('#genderError').text('Please select a gender.');
                    isValid = false;
                } else {
                    $('#genderError').text('');
                }

                // Validate Image
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                var imageInput = $('#image');
                var image = imageInput.val();
                if (image === '') {
                    $('#image').addClass('is-invalid');
                    $('#imageError').text('Please choose an image.');
                    isValid = false;
                } else if (!allowedExtensions.exec(image)) {
                    $('#image').addClass('is-invalid');
                    $('#imageError').text('Please choose a valid image file (JPEG/JPG/PNG/GIF).');
                    isValid = false;
                } else {
                    $('#image').removeClass('is-invalid');
                    $('#imageError').text('');
                }

                // Prevent form submission if validation fails
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>