<?php
session_start();
session_destroy(); // Oturumu sonlandir
header("Location: login.php");
exit;
?>


