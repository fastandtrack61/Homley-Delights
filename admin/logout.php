<script src="../sweetalert.min.js"></script>
<?php
session_start();
session_destroy();

echo  '<script>
setTimeout(function() {
    swal("Success!", "Logout Successfully", "success").then(function() {
        window.location.href = "../index.html";
    });
}, 100); // 2000 milliseconds = 2 seconds
</script>';
?>