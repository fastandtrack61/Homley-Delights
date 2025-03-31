<?php
session_start();
require('../Connect_database.php');
$userid = $_SESSION['userid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['from'] == "update") {
        // Check if product_id and quantity are set in the POST request
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            // Sanitize inputs to prevent SQL injection
            $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
            $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
            // Update the quantity of the product in the cart
            $sql = "UPDATE tbl_cart SET quantity ='$quantity' WHERE product_id = '$product_id' AND fk_regid = '$userid'";
            if ($conn->query($sql)) {
                // If update is successful, return a success message
                echo json_encode(array("status" => "success", "message" => "Quantity updated successfully."));
            } else {
                // If there's an error, return an error message
                echo json_encode(array("status" => "error", "message" => "Error updating quantity: " . $conn->error));
            }
        } else {
            // If product_id or quantity is not set, return an error message
            echo json_encode(array("status" => "error", "message" => "Missing parameters."));
        }
    } elseif ($_POST['from'] == "remove") {
        $product_id = $_POST['product_id'];
        $sql = "DELETE FROM tbl_cart WHERE product_id = '$product_id' AND fk_regid = '$userid'";
        if ($conn->query($sql)) {
            // If deletion is successful, prepare response
            $sql = "SELECT COUNT(*) AS cart_count FROM tbl_cart WHERE fk_regid='$userid'";
            if ($result = $conn->query($sql)) {
                $row = $result->fetch_assoc();
                $count = $row['cart_count'];

                
                echo json_encode(array("status" => "success", "message" => "Product removed from cart.", "count" => $count));
            } else {
                echo json_encode(array("status" => "error", "message" => "Error getting cart count."));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Error removing product from cart: " . $conn->error));
        }
    }
    
    elseif ($_POST['from'] == "saveforlater") {
        // Check if product_id and quantity are set in the POST request
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            // Sanitize inputs to prevent SQL injection
            $productId = mysqli_real_escape_string($conn, $_POST['product_id']);
            $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
            
            // Insert the item into the save for later list
            $sql_insert = "INSERT INTO tbl_save_for_later (user_id, product_id, quantity) VALUES ('$userid', '$productId', '$quantity')";
            if ($conn->query($sql_insert) === TRUE) {
                // Item saved successfully, now remove from cart
                $sql_delete = "DELETE FROM tbl_cart WHERE product_id = '$productId' AND fk_regid = '$userid'";
                if ($conn->query($sql_delete) === TRUE) {
                    // Get cart count
                    $sql_count = "SELECT COUNT(*) AS cart_count FROM tbl_cart WHERE fk_regid='$userid'";
                    $result = $conn->query($sql_count);
                    if ($result) {
                        $row = $result->fetch_assoc();
                        $count = $row['cart_count'];
                        // Fetch saved items for later and their prices
                        $sql_saved_items = "SELECT sf.product_id, sf.quantity, p.photo_path, p.product_name, p.price, p.stock 
                                            FROM tbl_save_for_later sf 
                                            JOIN tbl_products p ON sf.product_id = p.product_id 
                                            WHERE sf.user_id = '$userid'";
                        $result_saved_items = $conn->query($sql_saved_items);
                        if ($result_saved_items->num_rows > 0) {
                            $saved_items = array();
                            while ($row = $result_saved_items->fetch_assoc()) {
                                $saved_items[] = array(
                                    "product_id" => $row['product_id'],
                                    "quantity" => $row['quantity'],
                                    "photo_path" => $row['photo_path'],
                                    "product_name" => $row['product_name'],
                                    "price" => $row['price'],
                                    "stock" => $row['stock']
                                );
                            }
                            // Return the saved items for later and their prices along with the cart count
                            echo json_encode(array("status" => "success", "message" => "Product removed from cart.", "count" => $count, "saved_items" => $saved_items));
                        } else {
                            echo json_encode(array("status" => "error", "message" => "No saved items for later found."));
                        }
                    } else {
                        echo json_encode(array("status" => "error", "message" => "Error getting cart count: " . $conn->error));
                    }
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error removing product from cart: " . $conn->error));
                }
            } else {
                echo json_encode(array("status" => "error", "message" => "Error saving product for later: " . $conn->error));
            }
            exit;
        } else {
            echo json_encode(array("status" => "error", "message" => "Missing parameters."));
        }
    }
    

    elseif ($_POST['from'] == "remove_from_save") {
        // Check if product_id is set in the POST request
        if (isset($_POST['product_id'])) {
          
            // Sanitize product_id to prevent SQL injection
            $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
         
            // Delete the item from the save for later list
            $sql = "DELETE FROM tbl_save_for_later WHERE product_id = '$product_id' AND user_id = '$userid'";
            if ($conn->query($sql)) {
                // If deletion is successful, return a success message
                echo json_encode(array("status" => "success", "message" => "Product removed from save for later list."));
            } else {
                // If there's an error, return an error message
                echo json_encode(array("status" => "error", "message" => "Error removing product from save for later list: " . $conn->error));
            }
        } else {
            // If product_id is not set, return an error message
            echo json_encode(array("status" => "error", "message" => "Product ID not provided."));
        }
    }

    elseif ($_POST['from'] == "move_to_cart") {
        // Check if product_id is set in the POST request
        if (isset($_POST['product_id'])) {
            // Sanitize input to prevent SQL injection
            $productId = mysqli_real_escape_string($conn, $_POST['product_id']);
            
            // Fetch the quantity of the product from the save for later table
            $sql_fetch_quantity = "SELECT quantity FROM tbl_save_for_later WHERE product_id = '$productId' AND user_id = '$userid'";
            $result_fetch_quantity = $conn->query($sql_fetch_quantity);
            if ($result_fetch_quantity && $result_fetch_quantity->num_rows > 0) {
                $row_quantity = $result_fetch_quantity->fetch_assoc();
                $quantity = $row_quantity['quantity'];
            } else {
                // Quantity not found, set default quantity to 1
                $quantity = 1;
            }
            
            // Insert the item into the cart
            $sql_insert = "INSERT INTO tbl_cart (fk_regid, product_id, quantity) VALUES ('$userid', '$productId', '$quantity')";
            if ($conn->query($sql_insert) === TRUE) {
                // Get cart count
                $sql_count = "SELECT COUNT(*) AS cart_count FROM tbl_cart WHERE fk_regid='$userid'";
                $result_count = $conn->query($sql_count);
                if ($result_count) {
                    $row_count = $result_count->fetch_assoc();
                    $count = $row_count['cart_count'];
                } else {
                    $count = 0;
                }
                
                // Fetch item details including image
                $sql_item = "SELECT p.product_id, p.product_name, p.price, p.stock, p.photo_path
                             FROM tbl_products AS p
                             WHERE p.product_id = '$productId'";
                $result_item = $conn->query($sql_item);
                if ($result_item->num_rows > 0) {
                    $row_item = $result_item->fetch_assoc();
                    $item_details = array(
                        "product_id" => $row_item['product_id'],
                        "product_name" => $row_item['product_name'],
                        "price" => $row_item['price'],
                        "quantity" => $quantity,
                        "photo_path" => $row_item['photo_path'] // Include product image path
                    );
                } else {
                    $item_details = null;
                }
                
                // Item moved successfully, now remove from save for later
                $sql_delete = "DELETE FROM tbl_save_for_later WHERE product_id = '$productId' AND user_id = '$userid'";
                if ($conn->query($sql_delete) === TRUE) {
                    echo json_encode(array("status" => "success", "message" => "Product moved to cart successfully.", "count" => $count, "item_details" => $item_details));
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error removing product from save for later: " . $conn->error));
                }
            } else {
                echo json_encode(array("status" => "error", "message" => "Error moving product to cart: " . $conn->error));
            }
            exit;
        } else {
            echo json_encode(array("status" => "error", "message" => "Missing product_id parameter."));
        }
    }
    


     
    else {
        echo json_encode(array("status" => "error", "message" => "Invalid action."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}
?>
