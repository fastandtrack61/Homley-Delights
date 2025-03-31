<?php
session_start();
require('../Connect_database.php');
require_once '../memo.php';
if(empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}
// Function to sanitize input to prevent SQL injection
function sanitizeInput($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

$search_keyword = isset($_POST['search']
) ? sanitizeInput($_POST['search']) : '';
if(isset($_POST['status']) && isset($_POST['staff_id'])) {
    $staff_id = sanitizeInput($_POST['staff_id']);
    // Perform status update
    $sql = "UPDATE tbl_login SET status = 0 WHERE fk_regid = '$staff_id'";
    if ($conn->query($sql) === TRUE) {

        echo  '<script>
        window.onload = function() {
            swal("Success!", "Seller Account Deactivated", "success");
        }
    </script>';

    } else {
        // Error in status update
        // You may want to redirect the user or display an error message
    }
}
if(isset($_POST['status1']) && isset($_POST['staff_id'])) {
    $staff_id = sanitizeInput($_POST['staff_id']);
    // Perform status update
    $sql = "UPDATE tbl_login SET status = 1 WHERE fk_regid = '$staff_id'";
    if ($conn->query($sql) === TRUE) {
        echo  '<script>
        window.onload = function() {
            swal("Success!", "Seller Account Activated", "success");
        }
    </script>';
    } else {
        // Error in status update
        // You may want to redirect the user or display an error message
    }
}

// Construct SQL query based on selected radio button option




    // No need to add condition for 'all', as it shows all users (default behavior)




?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <link rel="stylesheet" href="../style2.css">
  <script src="../sweetalert.min.js"></script>
   <style>
   h1 {
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            /* Ensures the overlay appears on top of other elements */
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .seller-details {
    display: flex;
    align-items: center;
}

.seller-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
}

.seller-info {
    font-size: 16px;
    line-height: 1.6;
}

.seller-name {
    font-weight: bold;
    margin-bottom: 5px;
}

.seller-email,
.seller-dob,
.seller-address,
.seller-phone,
.seller-age {
    margin-bottom: 5px;
}
#pos {
    cursor: pointer;
    transition: transform .5s ease, font-size .6s ease; 
}

#pos:hover {
    transform: scale(1.2);
    font-size: 1.2em;
}


   </style>
</head>

<body>
    <!-- The sidebar -->
    <div class="sidebar">
        <label for="">DASHBOARD</label>  
        <a href="overview.php">Overview</a>

        
         <a href="Manage_staff.php">Manage Users</a>
        <div id="overviewSubmenu" class="submenu" style="display: none;">
        <a href="Manage_staff.php">List Users</a>
        
    </div>
        <a href="Manage_seller.php">Manage Sellers</a>
        <div id="overviewSubmenu1" class="submenu" style="display: none;">
        <a href="Manage_seller.php">Sellers</a>
        <a href="List_products.php">Products</a>
        
    </div>

        <a href="logout.php">Logout</a>
    </div>

    <div style="margin-left: 250px; padding: 20px;" >
  
    <div class="overlay" id="overlay">
        <div class="overlay-content" id="details">
            This is the overlay content. Click outside to close.
        </div>
    </div>

  <div  id="user-details" class="profile-details">
    

<div class="table-wrapper">
    <table class="fl-table">
        <thead>
           
             
            
        <tr>
            <th>Sl.No</th>
            <th>Product Name</th>
            <th>Image</th>
            <th>Stock</th>
            <th>Seller Name</th>
            <th>Product Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql="SELECT * FROM tbl_products p,tbl_login l,tbl_registration r where p.fk_regid=r.regid and r.fk_loginid=l.login_id";
            if($result=$conn->query($sql))
            {
                if($result->num_rows>0)
                {
                    $i=1;
                    while($row=$result->fetch_assoc())
                    {
                        ?>
        <tr>
            <td><?php echo $i ?></td>
            <td id="pos" class="showOverlay" fk_logid="<?php echo $row['regid'] ?>"><?php echo $row['product_name'] ?></td>
            <td><img src="../products-images/<?php echo $row['photo_path']?>" style="width:100px; height: 60px;"> </td>
            <td><?php echo $row['stock']?></td>
            <td><?php echo $row['full_name']?></td>
            <td><?php if( $row['p_status']==1){ echo "In Stock";}
            else{echo "Out Of Stock";}
            ?></td>
        </tr>
        <?php
        $i++;
            }
                }
            }
            ?>
        <tbody>
        
    </table>
</div>




    </div>
    </div>
    

</body>
<script>
document.querySelector('.sidebar a[href="Manage_staff.php"]').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        var submenu = document.getElementById('overviewSubmenu');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
            submenu.style.maxHeight = submenu.scrollHeight + 'px'; // Expand the submenu height
        } else {
            submenu.style.maxHeight = '0'; // Collapse the submenu height
            setTimeout(function() {
                submenu.style.display = 'none';
            }, 500); // Wait for the transition to complete before hiding the submenu
        }
    });
    document.querySelector('.sidebar a[href="Manage_seller.php"]').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        var submenu = document.getElementById('overviewSubmenu1');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
            submenu.style.maxHeight = submenu.scrollHeight + 'px'; // Expand the submenu height
        } else {
            submenu.style.maxHeight = '0'; // Collapse the submenu height
            setTimeout(function() {
                submenu.style.display = 'none';
            }, 500); // Wait for the transition to complete before hiding the submenu
        }
    });



    var overlay = document.getElementById('overlay');
        var details = document.querySelectorAll('.showOverlay')
        details.forEach(function(pname) {


            pname.addEventListener('click', function() {
                var fk_id = this.getAttribute('fk_logid');

                var xhr = new XMLHttpRequest()
                xhr.open('POST', 'fetch.php')
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                xhr.send("fk_logid=" + fk_id)
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                        
                           var data = JSON.parse(xhr.responseText);
                         
                            var show = `
    <div class="seller-details">
        <img src="../seller/uploads/${data[0].filename}" class="seller-avatar">
        <div class="seller-info">
            <p class="seller-name">Seller Name: ${data[0].full_name}</p>
            <p class="seller-email">Email: ${data[0].username}</p>
            <p class="seller-dob">DOB: ${data[0].dob}</p>
            <p class="seller-address">Address: ${data[0].street_address} ${data[0].city}, ${data[0].state} ${data[0].postal}, ${data[0].country}</p>
            <p class="seller-phone">Phone No: ${data[0].phone}</p>
            <p class="seller-age">Age: ${data[0].age}</p>
        </div>
    </div>`;

                            document.getElementById('details').innerHTML = show
                            overlay.style.display = 'block';
                        } else {
                            alert("error")
                        }
                    }
                }
            });
        })
        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                overlay.style.display = 'none';
            }
        });
    </script>

</html>