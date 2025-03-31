<?php

session_start();
require('../Connect_database.php');
$email = $_SESSION['username'];
$userid = $_SESSION['userid'];
if (empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}
$result = "";

if (isset($_POST['Add'])) {
    $product_name = $_POST["product_name"];
    // Check if the selected product name is "other"
    if ($product_name === "other") {
        // Use the value entered in the input box as the product name
        $product_name = $_POST["nproduct_name"];
    }
    $product_category = $_POST["product_category"];
    $product_stock = $_POST['product_stock1'];
    $pprice = $_POST['price'];
    $product_photo_name = $_FILES["product_photo"]["name"];
    $product_photo_temp = $_FILES["product_photo"]["tmp_name"];
    $product_photo_type = $_FILES["product_photo"]["type"];
    $product_photo_error = $_FILES["product_photo"]["error"];
    $product_photo_size = $_FILES["product_photo"]["size"];

    $allowed_types = array('image/jpeg', 'image/png');
    $max_file_size = 2 * 1024 * 1024;

    if ($product_photo_error === UPLOAD_ERR_OK) {
        $upload_dir = "../products-images/";
        $target_file = $upload_dir . basename($product_photo_name);

        // Check if the product already exists for the same user
        $check_product_sql = "SELECT * FROM tbl_products WHERE product_name = '$product_name' AND fk_regid = '$userid'";
        $check_product_result = mysqli_query($conn, $check_product_sql);
        if (mysqli_num_rows($check_product_result) > 0) {
            echo '<script>
                window.onload = function() {
                    swal({
                        title: "Error!",
                        text: "Product already exists.",
                        icon: "error"
                    }).then(function() {
                        document.querySelector(".add-product").style.display = "none";
                        document.querySelector(".update-product").style.display = "block";
                    });
                }
            </script>';
        } else {
            if (move_uploaded_file($product_photo_temp, $target_file)) {
                $sql = "INSERT INTO tbl_products(product_name,price,category_id,photo_path,stock,fk_regid) 
                        VALUES ('$product_name',$pprice, '$product_category', '$product_photo_name','$product_stock','$userid')";

                if (mysqli_query($conn, $sql)) {
                    echo '<script>
                    window.onload = function() {
                        swal({
                            title: "Success!",
                            text: "New Product Added Successfully.",
                            icon: "success"
                        })
                    }
                </script>';
                } else {
                    $_SESSION['res'] = "Error: " . mysqli_error($conn);
                }
            } else {
                echo  '<script>
                    window.onload = function() {
                        swal("Error!", "Sorry, there was an error uploading your file.", "error");
                    }
                </script>';
                $_SESSION['res'] = "";
            }
        }
    } else {
        $_SESSION['res'] = "Error: " . $product_photo_error;
    }
}

if (isset($_POST['stock'])) {
    echo '<script>                    document.getElementById("addproduct").style.display="none"
    </script>';
    $ps = $_POST['product_stock'];
    $pid = $_POST['product_id'];
    $po = $_POST['product_old'];
    $new_stock = $ps + $po; // Calculate the new stock value correctly

    $sql = "UPDATE tbl_products SET stock='$new_stock' WHERE product_id='$pid'";
    echo '<script>

document.querySelector(".update-product").style.display = "block";
</script>';
    if ($result = $conn->query($sql)) {
        echo  '<script>
            window.onload = function() {
                document.querySelector(".update-product").style.display = "block";

                swal({
                    title: "Sucess!",
                    text: "Product Stock Updated Successfully.",
                    icon: "success"
                }).then(function() {
                   
                    document.getElementById("addproduct").style.display="none"

                    document.querySelector(".update-product").style.display = "block";
                });
            }
        </script>';
    } else {
        echo '<script>
            window.onload = function() {
                swal({
                    title: "Error!",
                    text: "Failed to update product stock.",
                    icon: "error"
                });
            }
        </script>';
    }
}



if (isset($_POST['update_status'])) {
    $product_id = $_POST['product_id'];
    $product_status = $_POST['product_status'];

    $update_status_sql = "UPDATE tbl_products SET p_status = '$product_status' WHERE product_id = '$product_id'";
    if (mysqli_query($conn, $update_status_sql)) {
        echo '<script>
                window.onload = function() {
                    swal({
                        title: "Success!",
                        text: "Product status updated successfully.",
                        icon: "success"
                    }).then(function() {
                        // Optional: You can redirect the user to another page or refresh the current page
                        document.querySelector(".update-product").style.display = "block";
                    });
                }
            </script>';
    } else {
        echo '<script>
                window.onload = function() {
                    swal({
                        title: "Error!",
                        text: "Failed to update product status.",
                        icon: "error"
                    });
                }
            </script>';
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

    <style>
        /* Additional styling for the right-side div */
        .add-product {
            position: fixed;
            width: 800px;
            height: fit-content;
            background-color: #f8f9fa;
            padding: 20px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            /* Adding a shadow effect */
        }

        /* Style for the form input fields */
        .add-product label {
            display: block;
            margin-bottom: 10px;
        }

        .add-product input[type="text"],
        .add-product select,
        .add-product input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .add-product input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }



        .update-product {
            position: fixed;
            width: 1100px;
            height: 500px;
            background-color: #f8f9fa;
            padding: 20px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            /* Adding a shadow effect */
        }

        /* Style for the form input fields */
        .update-product label {
            display: block;
            margin-bottom: 10px;
        }

        .update-product input[type="text"],
        .update-product select,
        .update-product input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .update-product input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .dashboard-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 10px;
            border-radius: 5px;
        }

        /* Style for the buttons when hovered */
        .dashboard-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="../sweetalert.min.js"></script>

</head>

<body>

    <div class="sidenav">
        <a href="overview.php">Overview</a>
        <a href="./notification.php" >Notifications</a>
        <a href="#" class="active" onclick="toggleSubmenu('product-management')">Product Management</a>
        <div class="submenu" id="product-management">
        <a href="order_management.php" >Pre-Book Orders</a>
        <a href="add_product.php">Add Product</a>
        <a href="./normal_orders.php">Orders</a>
        <a href="./schduled.php">Schduled Deliveries</a>
        </div>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Content area where dashboard content will be displayed -->
    <div style="margin-left: 250px; padding: 20px;">
  
        <div style="margin-bottom: 20px;">
            <button id="showProduct" class="dashboard-button">Product</button>
            <button id="showStock" class="dashboard-button">Stock</button>
        </div>
        <div class="add-product" id="addproduct">
            <h3>Add a Product</h3>


            <form action="#add_product" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <label for="product_name">Item Name:</label>
                <select id="product_name" name="product_name" onchange="checkOtherOption(this)">
        <option value="">Select Item Name</option>
        <!-- Existing product names will be dynamically populated here -->
        <option value="other">Other</option>

    </select>
    
    <label for="new_product_name" id="other_label" style="display: none;">Enter Product Name:</label>
<input type="text" id="new_product_name" name="nproduct_name" style="display: none;">

    <br>
                <label for="product_category">Item Category:</label>
                <select id="product_category" name="product_category">
                    <option value="1">Breakfast Items</option>
                    <option value="2">HomeHarvest Lunch</option>
                    <option value="3">Evening Eatables</option>
                </select><br><br>
                <label for="item_price">Item Price</label>
                <input type="text" name="price" id="pprice">

                <label for="product_stock">Item Stock:</label>
                <div>
                    <button type="button" onclick="decrementStock()">-</button>
                    <input type="number" id="product_stock" name="product_stock1" min="1" max="25" value="1">
                    <button type="button" onclick="incrementStock()">+</button>

                </div>
                <label for="product_photo">Item Photo:</label>
                <input type="file" id="product_photo" name="product_photo"><br><br>
                <input type="submit" name="Add" value="Add">
            </form>
        </div>


        <div class="update-product" id=updateproduct style="display: none;overflow: auto;">
            <h3>Stock</h3>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Photo</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    // Assuming you have fetched product data from your database and stored it in $products variable
                    $sql1 = "SELECT * FROM tbl_products p,tbl_categories c Where p.fk_regid='$userid' AND p.category_id=c.category_id";
                    if ($result = $conn->query($sql1)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                    ?>
                                <form action="#" method="post">

                                    <tr>

                                        <td> <?php echo $row['product_name']; ?></td>
                                        <td><?php echo  $row['category_name']; ?></td>
                                        <td><img src="../products-images/<?php echo $row['photo_path'] ?>" width='100' height='100'></td>
                                        <td><?php echo $row['stock']; ?>




                                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                            <input type="hidden" name="product_old" value="<?php echo $row['stock']; ?>">
                                            <input type="number" name="product_stock" min="0" max="25" value="1" style="text-align:center;">
                                            <button type="submit" name="stock">Update</button>



                                        </td>
                                        <td>
                                            <form action="#" method="post">
                                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                                <select name="product_status">
                                                    <option value="1" <?php echo ($row['p_status'] == 1) ? 'selected' : ''; ?>>Available</option>
                                                    <option value="0" <?php echo ($row['p_status'] == 0) ? 'selected' : ''; ?>>Unavailable</option>
                                                </select>
                                                <button type="submit" name="update_status">Update</button>
                                            </form>


                                            </select>
                                        </td>
                                    </tr>
                                </form>
                    <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
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




        function validateForm() {
            var productName = document.getElementById("product_name").value;
            var productCategory = document.getElementById("product_category").value;
            var productPhoto = document.getElementById("product_photo").value;
            var pprice = document.getElementById('pprice').value
            if (pprice == "") {
                alert("enter the price");
                return false

            }
            if (pprice < 1 || pprice > 1000) {
                alert("Please enter the price in the range 1 to 1000");
                return false;
            }

            if (productName === "" || productCategory === "" || productPhoto === "") {
                alert("All fields are required.");
                return false;
            }

            // Check file extension
            var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (!allowedExtensions.exec(productPhoto)) {
                alert("Only JPG, JPEG, and PNG file types are allowed.");
                return false;
            }

            // Check file size
            var maxSize = 2 * 1024 * 1024; // 2 MB
            if (document.getElementById("product_photo").files[0].size > maxSize) {
                alert("File size exceeds the limit of 2 MB.");
                return false;
            }

            return true;
        }



        function incrementStock() {
            var stockInput = document.getElementById("product_stock");
            var currentValue = parseInt(stockInput.value);
            if (currentValue < 25) {
                stockInput.value = currentValue + 1;
            }
        }

        function decrementStock() {
            var stockInput = document.getElementById("product_stock");
            var currentValue = parseInt(stockInput.value);
            if (currentValue > 0) {
                stockInput.value = currentValue - 1;
            }
        }


        document.getElementById('showProduct').addEventListener('click', function() {
            document.getElementById('addproduct').style.display = 'block'
            document.getElementById('updateproduct').style.display = 'none'


        })


        document.getElementById('showStock').addEventListener('click', function() {


            document.getElementById('addproduct').style.display = 'none'
            document.getElementById('updateproduct').style.display = 'block'


        })


        function fetchProductNames() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetchname.php');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var productNames = JSON.parse(xhr.responseText);
                var select = document.getElementById('product_name');
                select.innerHTML = '';
                var selectOption = document.createElement('option');
                selectOption.value = ""; // Set value as empty
                selectOption.textContent = "Select Item Name"; // Set display text
                select.appendChild(selectOption);
                
                // Create and append "Other" option
              
                productNames.forEach(function(product) {
                    var option = document.createElement('option');
                    option.value = product;
                    option.textContent = product;
                    select.appendChild(option);
                });


                var otherOption = document.createElement('option');
                otherOption.value = "other"; // Set value as "other"
                otherOption.textContent = "Other"; // Set display text
                select.appendChild(otherOption);
            }
        }
    }
}



        window.addEventListener('DOMContentLoaded', fetchProductNames);
        function checkOtherOption(select) {
    var otherOption = document.getElementById('new_product_name');
    var otherLabel = document.getElementById('other_label');
    if (select.value === 'other') {
        otherOption.style.display = 'inline-block';
        otherLabel.style.display = 'inline-block';
        otherOption.required = true; // Mark the field as required
    } else {
        otherOption.style.display = 'none';
        otherLabel.style.display = 'none';
        otherOption.required = false; // Remove the required attribute
    }
}


    </script>

</body>

</html>