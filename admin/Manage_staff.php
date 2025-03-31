<?php
session_start();
require('../Connect_database.php');
require_once '../memo.php';
// Function to sanitize input to prevent SQL injection
if(empty($_SESSION['username']) && empty($_SESSION['userid'])) {
    header('Location:../login.php');
    exit; // Ensure that script execution stops after redirecting
}
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
    $sql = "UPDATE tbl_login AS l
    JOIN tbl_registration AS r ON r.fk_loginid = l.login_id
    SET l.status = 0
    WHERE r.regid = '$staff_id';
    ";
    if ($conn->query($sql) === TRUE) {
        echo  '<script>
        window.onload = function() {
            swal("Success!", "Customer Account Deactivated", "success");
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
    $sql = "UPDATE tbl_login AS l
    JOIN tbl_registration AS r ON r.fk_loginid = l.login_id
    SET l.status = 1
    WHERE r.regid = '$staff_id';
    ";
    if ($conn->query($sql) === TRUE) {
        echo  '<script>
        window.onload = function() {
            swal("Success!", "Customer Account Activated", "success");
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

  <div  id="user-details" class="profile-details">
        <div id="s12">
        <form id="searchForm" method="post" action="">
                <input type="text" id="searchInput" name="search" value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">
                <button type="submit">Search</button>

               all<input type="radio" name="lf" value="all" id="all">blocked <input type="radio" name="lf" value="blocked" id="blocked">
                       </form>
        </div>
        <table>
            <tr>
                <th>EID</th>
                <th>Full Name</th>
                <th>Gender</th>
              
                <th>DOB</th>
                <th>Address</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT * FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND user_roles='0' AND l.status='1' ";

            // Append search condition if keyword is provided
            if (!empty($_POST['search'])) {
                $search_keyword = mysqli_real_escape_string($conn, $_POST['search']);
                $sql .= " AND r.full_name LIKE '%$search_keyword%'";
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["lf"]) && $_POST['lf']=="blocked") {
                    $selectedOption = $_POST["lf"];
                    $sql = "SELECT * FROM tbl_registration r, tbl_login l WHERE r.fk_loginid=l.login_id AND l.user_roles='0' and l.status='0'";

                   
                } 
            }
            
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    $i=1;
                    while ($row = $result->fetch_assoc()) { ?>
                         <form action="#" method="post">
                            <tr>
                                <input type="hidden" name="staff_id" value="<?= $row['regid'] ?>">
                                <td><?php echo $i; ?> </td>
                                <td><?= $row['full_name'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['dob'] ?></td>
                                <td><?= $row['street_address'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td>
                                <?php 
                                $i=$i+1;
                                ?>
                                <button type="button" name="memo" class="memo-btn" data-email="<?=$row['username'] ?>">memo</button>

                                <?php
                                if ($row['status'] == 0): ?>
        <button type="submit" name="status1" onclick="return confirm('Are you sure you want to activate this staff member?')">Activate</button>
    <?php else: ?>
        <button type="submit" name="status" onclick="return confirm('Are you sure you want to deactivate this staff member?')">Deactivate</button>
    <?php endif; ?>
    
                                </td>
                            </tr>
                        </form>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Error executing query: " . $conn->error . "</td></tr>";
            }
            ?>
        </table>
        <div id="memoModal" class="custom-modal">
        <div class="custom-modal-content">
            <span class="custom-close" onclick=" return closeModal()">&times;</span>
            <form  method="post" action="#">
            <input type="text" id="memoEmailInput" class="memo-email-input" name="email" placeholder="Email Address" readonly>
            <textarea id="memoTextarea" class="memo-textarea" name="memo" placeholder="Type your memo here"></textarea>
<button type="submit" name="send" >Send</button>
            </form>
        </div>
    </div>
    </div>
    </div>
    <?php

if(isset($_POST['send'])) {
    $email= $_POST['email'];
    $memo=$_POST['memo'];
    
    $send_success = sendMemoEmail($email, $memo);
    
    if ($send_success) {
        echo  '<script>
                        window.onload = function() {
                            swal("Success!", "Email Notifcation Send Successfully", "success");
                        }
                    </script>';
    } else {
      
    }
    }
    ?>

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



        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        searchInput.addEventListener('input', function() {
            if (searchInput.value === '') {
                searchForm.submit();

                
            }
        });
    

        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        });


        document.querySelectorAll('.memo-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Toggle display of memo section
                let memoSection = this.parentElement.parentElement.nextElementSibling;
                memoSection.style.display = memoSection.style.display === 'none' ? 'table-row' : 'none';
            });
        });



        function openModal(email) {
        document.getElementById('memoModal').style.display = 'block';
        document.getElementById('memoEmailInput').value = email;
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('memoModal').style.display = 'none';
    }

    // Function to handle sending the memo (you can adjust this function according to your needs)
  

    // Attach event listeners to memo buttons to open the modal
    document.querySelectorAll('.memo-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Fetch the email address from the data attribute
            let email = this.getAttribute('data-email');
            openModal(email);
        });
    })


    </script>

</html>