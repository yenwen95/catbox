<?php
session_destroy();
unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['username']);
unset($_SESSION['email']);
header("location: index.php");

?>