<?php
require('../Connect_database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Div Overlay Example</title>
    <script src="../jquery/jquery-3.7.1.min.js"></script>
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
            z-index: 9999; /* Ensures the overlay appears on top of other elements */
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
    </style>
</head>
<body>
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
        $sql="SELECT * FROM tbl_products p,tbl_login l,tbl_registration r where p.fk_logid=l.login_id and l.fk_regid=r.regid";
        if($result=$conn->query($sql)) {
            if($result->num_rows > 0) {
                $i=1;
                while($row=$result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td class="product-name" data-product-id="<?php echo $row['fk_logid'] ?>"><?php echo $row['product_name']?></td>
                        <td><img src="../products-images/<?php echo $row['photo_path']?>" style="width:100px; height: 60px;"></td>
                        <td><?php echo $row['stock']?></td>
                        <td><?php echo $row['full_name']?></td>
                        <td><?php echo $row['p_status']?></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
        }
        ?>
        </tbody>
    </table>

    <div class="overlay" id="overlay">
        <div class="overlay-content" id="details">
            This is the overlay content. Click outside to close.
        </div>
    </div>

    <script>
        var overlay = document.getElementById('overlay');
        var productNames = document.querySelectorAll('.product-name');

        productNames.forEach(function(productName) {
            productName.addEventListener('click', function() {
                var productId = this.getAttribute('data-product-id');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch.php');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("fk_logid=" + productId);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var data = JSON.parse(xhr.responseText);
                            var show = "Seller Name: " + data[0].full_name + "<br>DOB: " + data[0].dob;
                            document.getElementById('details').innerHTML = show;
                            overlay.style.display = 'block';
                        } else {
                            alert("Error occurred while processing your request.");
                        }
                    }
                }
            });
        });

        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                overlay.style.display = 'none';
            }
        });
    </script>
</body>
</html>
