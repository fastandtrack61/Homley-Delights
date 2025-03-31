<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download PDF with jsPDF</title>
    <!-- Include jsPDF library -->
    <script src="jspdf.min.js"></script>
</head>
<body>
    <button id="downloadBtn">Download PDF</button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const downloadBtn = document.getElementById('downloadBtn');
            downloadBtn.addEventListener('click', function() {
                // Get the data attributes from the button
                const productAmount = 'jithin';
                const quantity = 1;
                const productName = 'none';
                const price = 21;
                const totalAmount = price * quantity;

                // Create a new jsPDF instance
                const doc = new jsPDF();

                // Set font styles
                doc.setFont("helvetica");

                // Define colors
                const primaryColor = "#007bff"; // Primary color (blue)
                const secondaryColor = "#343a40"; // Secondary color (dark gray)
                const lightColor = "#f8f9fa"; // Light color (light gray)

                // Add company name
                const companyName = 'Homley Delights';
                doc.setTextColor(primaryColor);
                doc.setFontSize(30);
                doc.text(companyName, 20, 30);

                // Add customer details (replace PHP variables with hardcoded values)
                const customerName = 'John Doe';
                const phoneNumber = '123-456-7890';
                doc.setFontSize(14);
                doc.setTextColor(secondaryColor);
                doc.text('Customer Name:', 20, 50);
                doc.setTextColor(primaryColor);
                doc.text(customerName, 70, 50);

                doc.setTextColor(secondaryColor);
                doc.text('Phone Number:', 20, 65);
                doc.setTextColor(primaryColor);
                doc.text(phoneNumber, 70, 65);

                // Add invoice title
                doc.setFontSize(24);
                doc.setTextColor(secondaryColor);
                doc.text('Invoice', 20, 90);

                // Add horizontal line
                doc.setLineWidth(0.5);
                doc.setDrawColor(secondaryColor);
                doc.line(20, 95, 190, 95);

                // Add order details
                doc.setFontSize(14);
                doc.setTextColor(secondaryColor);
                doc.text('Product Name:', 20, 110);
                doc.setTextColor(primaryColor);
                doc.text(productName, 70, 110);

                doc.setTextColor(secondaryColor);
                doc.text('Quantity:', 20, 125);
                doc.setTextColor(primaryColor);
                doc.text(quantity.toString(), 70, 125);

                doc.setTextColor(secondaryColor);
                doc.text('Price per unit:', 20, 140);
                doc.setTextColor(primaryColor);
                doc.text('$' + price.toFixed(2), 70, 140);

                doc.setTextColor(secondaryColor);
                doc.text('Total Amount:', 20, 155);
                doc.setTextColor(primaryColor);
                doc.text('$' + totalAmount.toFixed(2), 70, 155);

                // Add signature section
                doc.setTextColor(secondaryColor);
                doc.setFontSize(18);
                doc.text('Authorized Signature', 20, 190);

                // Save the PDF
                doc.save('invoice.pdf');
            });
        });
    </script>
</body>
</html>
