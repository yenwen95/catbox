<?php
session_start();

require 'config/db.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $sql = "SELECT * FROM Users WHERE token = '$token' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $query = "UPDATE Users SET verified=1 WHERE token = '$token'";

        if(mysqli_query($con, $query)){
            
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = true;
            $_SESSION['message'] = "Your email address has been verified successfully!";
            header('location: index.php');
            $_SESSION['type'] = $user['alert-success'];
            
            
            exit(0);
        }
    }else{
        echo "User not found!";
    }
}else{
    echo "No token provided!";
}


?>
