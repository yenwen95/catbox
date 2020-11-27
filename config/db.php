<?php
require 'constants.php';

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(mysqli_connect_errno()){
    exit('Failed to connect to database: ' . mysqli_connect_error());
}

?>