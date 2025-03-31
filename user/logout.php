<?php
session_start();
session_destroy();
header('Location:../index.html');
exit; // Ensure that script execution stops after redirecting
?>
