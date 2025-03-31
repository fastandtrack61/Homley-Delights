<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Delivery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="date"],
        button {
            width: calc(100% - 40px); /* Adjust for button padding */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        #message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Schedule Delivery</h1>
    <button onclick="openModal()">Schedule Delivery</button>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form id="deliveryForm">
                <!-- Delivery scheduling form fields -->
                <label for="fromDate">From Date:</label>
                <input type="date" id="fromDate" name="fromDate" min="<?php echo date('Y-m-d'); ?>" required><br><br>
                
                <label for="toDate">To Date:</label>
                <input type="date" id="toDate" name="toDate" min="<?php echo date('Y-m-d'); ?>" required><br><br>
                
                <button type="submit">Schedule Delivery</button>
            </form>
        </div>
    </div>

    <!-- Include any necessary scripts -->
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the button that opens the modal
        var btn = document.querySelector('button');

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Perform validation before submitting the form
        function validateDates() {
            var fromDate = document.getElementById('fromDate').value;
            var toDate = document.getElementById('toDate').value;
            var currentDate = new Date().toISOString().slice(0, 10);

            if (fromDate < currentDate) {
                alert('From Date cannot be before the current date.');
                document.getElementById('fromDate').value = currentDate;
            }

            if (toDate < fromDate) {
                alert('To Date must be greater than or equal to From Date.');
                document.getElementById('toDate').value = fromDate;
            }
        }

        // When the modal form is submitted
        document.getElementById('deliveryForm').addEventListener('submit', function(event) {
            event.preventDefault();
            validateDates();
            if (document.getElementById('toDate').value >= document.getElementById('fromDate').value) {
                // Submit form data
                var formData = new FormData(this);
                // AJAX request to handle form submission
                scheduleDelivery(formData);
            }
        });

        // Close modal function
        function closeModal() {
            modal.style.display = "none";
        }

        // Open modal function
        function openModal() {
            modal.style.display = "block";
        }

        // Function to handle delivery scheduling
        function scheduleDelivery(formData) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'schedule_status.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Delivery scheduled successfully!');
                        closeModal(); // Close modal after successful scheduling
                    } else {
                        alert('Error scheduling delivery. Please try again.');
                    }
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>
