<?php
session_start();

require 'config/db.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $sql = $con->prepare("SELECT * FROM Users WHERE token = ? LIMIT 1");
    $sql->execute([$token]);
   

    if($sql->fetch() > 0){
        $user = $sql->fetch();
        try{
            $query = $con->prepare("UPDATE Users SET verified=1 WHERE token = ?");
            $query->execute([$token]);
            
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = true;
            $_SESSION['message'] = "Your email address has been verified successfully!";
            header('location: index.php');
            $_SESSION['type'] = $user['alert-success'];
            
            
            exit(0);

        }catch(PDOException $e){
            echo "Unable to verify!";
        }
        
    }else{
        echo "User not found";
    }   
    
}else{
    echo "No token provided!";
}


?>