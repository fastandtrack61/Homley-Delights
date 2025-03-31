<?php
session_start();
require_once("Connect_database.php");

// Check if data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data sent from JavaScript
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if data is decoded properly
    if ($data !== null) {
        // Extract data from JSON
        
        $oauth_provider = 'google'; 
        $oauth_uid  = !empty($data['sub']) ? $data['sub'] : null;
        $first_name = !empty($data['given_name']) ? $data['given_name'] : null;
        $last_name  = !empty($data['family_name']) ? $data['family_name'] : null;
        $email      = !empty($data['email']) ? $data['email'] : null;
        $picture    = !empty($data['picture']) ? $data['picture'] : null;

        // Check if essential data is present
        if ($oauth_uid !== null && $first_name !== null && $last_name !== null && $email !== null) {
            $_SESSION['userid']= $data['sub'];
            $_SESSION['username']= $email;
            $_SESSION['photo_path']=$picture;
            $query = "SELECT * FROM tbl_login l, tbl_registration r WHERE l.username='$email' AND l.login_id=r.fk_loginid";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {

                $row=$result->fetch_assoc();

                $_SESSION['userid'] = $row['regid'];// Use email as userid
                $_SESSION['username'] = $email; // Use email as username or update according to your system
                
                $userRole = $row['user_roles']; // Assuming the column name for user role is 'user_role'

                $output = [ 
                    'status' => 'error',
                    'msg' => 'exist',
                    'userRole' => $userRole // Include user role in the response
                ]; 
                echo json_encode($output); 
            } else {
                // User does not exist in tbl_login, check if they exist in tbl_googleusers
                $query = "SELECT * FROM tbl_googleusers WHERE email = '$email'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    // User exists in tbl_googleusers, update their information
                    $query = "UPDATE tbl_googleusers SET first_name = '$first_name', last_name = '$last_name', email = '$email', picture = '$picture', modified = NOW() WHERE oauth_provider = '$oauth_provider' AND oauth_uid = '$oauth_uid'"; 
                    if ($conn->query($query)) {

                        
                        $output = [ 
                            'status' => 'success',
                            'msg' => 'Login successfully!',
                        ]; 
                        echo json_encode($output); 
                    } else {
                        $output = [ 
                            'status' => 'error',
                            'msg' => 'Error updating user information in Google users table!',
                        ]; 
                        echo json_encode($output); 
                    }
                } else {
                    // User does not exist in tbl_googleusers or tbl_login, insert them into tbl_googleusers
                    

                    $query = "INSERT INTO tbl_googleusers (oauth_provider, oauth_uid, first_name, last_name, email, picture, created, modified) VALUES ('$oauth_provider','$oauth_uid','$first_name','$last_name','$email','$picture', NOW(), NOW())";
                    if ($conn->query($query)) {

                        $full_name = $first_name . ' ' . $last_name;

                        // Insert into tbl_login with user role 0
                        $query_login = "INSERT INTO tbl_login (username, user_roles) VALUES ('$email', '0')";
                        $conn->query($query_login);
                    
                        // Get the auto-generated ID from tbl_login for use in tbl_registration
                        $login_id = $conn->insert_id;
                    
                        // Insert into tbl_registration with full name
                        $query_registration = "INSERT INTO tbl_registration (fk_loginid, full_name) VALUES ('$login_id', '$full_name')";
                        $conn->query($query_registration);
                        $registration_id = $conn->insert_id;
                        $_SESSION['userid'] =$registration_id ;// Use email as userid
                        $file_path = $picture; // Update with the actual file path
                        $file_name = $picture;
                        $query_images = "INSERT INTO tbl_images (fk_regid, filepath, filename) VALUES ('$registration_id', '$file_path', '$file_name')";
                        $conn->query($query_images);
    
                        
                        $output = [ 
                            'status' => 'success',
                            'msg' => 'New user inserted into Google users table!',
                        ]; 
                        echo json_encode($output); 
                    } else {
                        $output = [ 
                            'status' => 'error',
                            'msg' => 'Error inserting new user into Google users table!',
                        ]; 
                        echo json_encode($output); 
                    }
                }
            }
        } else {
            // Error decoding JSON
            $output = [ 
                'status' => 'error', 
                'msg' => 'Error decoding JSON data!', 
            ]; 
            echo json_encode($output); 
        }
    } else {
        // Invalid request method
        $output = [ 
            'status' => 'error', 
            'msg' => 'Invalid request method!', 
        ]; 
        echo json_encode($output); 
    }
}
?>
