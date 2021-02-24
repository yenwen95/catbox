<?php
include 'controllers/authController.php';
$username = $_SESSION['username'];
$set = '0';
$query = $con->prepare("UPDATE Users SET vault_isopen = ? WHERE username = ?");
$query->execute([$set, $username]);


unset($_SESSION['id']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['verified']);
unset($_SESSION['action']);
header("location: index.php");
session_destroy();
?>