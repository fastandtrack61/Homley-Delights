<!-- schedule_delivery.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Delivery</title>
    <!-- Include any necessary stylesheets or scripts -->
</head>
<body>
    <h1>Schedule Delivery</h1>
    <form id="deliveryForm">
        <!-- Delivery scheduling form fields -->
        <label for="fromDate">From Date:</label>
        <input type="date" id="fromDate" name="fromDate" min="<?php echo date('Y-m-d'); ?>" required><br><br>
        
        <label for="toDate">To Date:</label>
        <input type="date" id="toDate" name="toDate" min="<?php echo date('Y-m-d'); ?>" required><br><br>
        
        <button type="submit">Schedule Delivery</button>
    </form>
    
    <!-- Include any necessary scripts -->
    <script>
        document.getElementById('deliveryForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var fromDate = document.getElementById('fromDate').value;
            var toDate = document.getElementById('toDate').value;
            // Send delivery scheduling request to the server
            scheduleDelivery(fromDate, toDate);
        });

        function scheduleDelivery(fromDate, toDate) {
            // Send an AJAX request to the server to schedule the delivery
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'schedule_delivery_backend.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Delivery scheduled successfully
                        alert('Delivery scheduled successfully!');
                    } else {
                        // Error scheduling delivery
                        alert('Error scheduling delivery. Please try again.');
                    }
                }
            };
            // Send delivery dates as data
            xhr.send('fromDate=' + encodeURIComponent(fromDate) + '&toDate=' + encodeURIComponent(toDate));
        }
    </script>
</body>
</html>
