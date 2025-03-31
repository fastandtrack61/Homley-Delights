<?php
// Include TCPDF library
require_once('../tcpdf/tcpdf.php');
// Include database connection
require('../Connect_database.php');

// Check if the order ID is provided in the request
if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    echo $orderId;
    // Fetch order details from the database based on the order ID
    $sql = "SELECT * FROM tbl_orders WHERE order_id = '$orderId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Company Name');
        $pdf->SetTitle('Invoice');
        $pdf->SetSubject('Order Invoice');
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 12);
        
        // Add content to the PDF
        $content = '
        <h1>Invoice</h1>
        <p>Order ID: ' . $row['order_id'] . '</p>
        <p>Customer Name: ' . $row['customer_name'] . '</p>
        <table border="1">
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>';

        // Prepare SQL query to fetch order items from the database
        $sql_items = "SELECT * FROM order_items WHERE order_id = '$orderId'";
        $result_items = $conn->query($sql_items);

        // Add order items to the table
        while ($item = $result_items->fetch_assoc()) {
            $content .= '
            <tr>
                <td>' . $item['item_id'] . '</td>
                <td>' . $item['item_name'] . '</td>
                <td>' . $item['quantity'] . '</td>
                <td>$' . $item['price'] . '</td>
                <td>$' . ($item['quantity'] * $item['price']) . '</td>
            </tr>';
        }

        // Add total row
        $content .= '
            <tr>
                <td colspan="4" align="right"><b>Total</b></td>
                <td>$' . $row['total'] . '</td>
            </tr>
        </table>';
        
        // Output the content to the PDF
        $pdf->writeHTML($content, true, false, true, false, '');
        
        // Close and output PDF document
        $pdf->Output('invoice1.pdf', 'D');
        exit; // Exit after downloading the PDF
    } else {
        // Handle the case where order details are not found
        http_response_code(404);
        echo "Order details not found";
        exit;
    }
} else {
    // Handle the case where order ID is not provided in the request
    http_response_code(400);
    echo "Order ID is required";
    exit;
}
?>
