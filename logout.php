<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['verified']);
header("location: index.php");
session_destroy();
?>