<?php
session_start();
/*
    This php is to register user into database, and login the user into the system.
    After registration, it will redirect to verify.php that ask user to verify their account.
    And it will call a function to send Email to user for verification. 

*/
require 'config/db.php';
require_once 'controllers/sendEmail.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
//error_reporting(0);

$username = "";
$email = "";
$role = 'u';
$errors = [];

//Problem: should set expiry for token, changing password


//REGISTER
extract($_POST);

if(isset($_POST['register-btn'])){
    if(empty($_POST["username"])){
        $errors['username'] = 'Username required';
    }
    if(empty($_POST["email"])){
        $errors['email'] = 'Email required';
    }
    if(empty($_POST["password1"])){
        $errors['password1'] = 'Password required';
    }
    if(isset($_POST['password1']) && $_POST['password1'] !== $_POST['password2']){
        $errors['password2'] = 'The two passwords do not match';
    }


$username = $_POST["username"];
$email = $_POST["email"];
$token = bin2hex(random_bytes(50));
$password1 = $_POST["password1"];

//Infinityfree does not suppport argon2, 000webhost is supported
$hash = password_hash($password1, PASSWORD_ARGON2I);

$verified="0";
$action = "register";

$check_username = $con->prepare("SELECT * FROM Users WHERE username = ?");
$check_email =  $con->prepare("SELECT * FROM Users WHERE email = ?");

$check_username->execute([$username]);
$row = $check_username->fetch(PDO::FETCH_ASSOC);

$check_email->execute([$email]);
$row1 = $check_email->fetch(PDO::FETCH_ASSOC);

    if($row > 0){
        $errors['username'] = "Username already exists";
    }
    if($row1 > 0){
        $errors['email'] = "Email already exists";
    }
    if(count($errors) === 0){
        
       try{
            $query = $con->prepare("INSERT INTO Users (username, email, password, token, verified, role)
            VALUES(:username, :email, :password, :token,:verified,:role)");
            $query->bindParam(':username',$username); 
            $query->bindParam(':email',$email);          
            $query->bindParam(':password',$hash);
            $query->bindParam(':token',$token);
            $query->bindParam(':verified',$verified);
            $query->bindParam(':role',$role);
            $query->execute();

            if($query){

                sendVerificationEmail($email, $token);
                $user_id = $con->lastInsertId();
                $_SESSION['id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['verified'] = false;
                $_SESSION['action'] = $action;
               
                header('location: verify.php');
                exit();
            }else{
                echo "Error";
            }

           
       }catch(PDOException $e){
           echo "Database error: Could not register user";
       }
            
            
        
       
    }

    

}
  
//LOGIN
if(isset($_POST['login-btn'])){
    if(empty($_POST['username'])){
        $errors['username'] = 'Username or email required';
    }
    if(empty($_POST['password'])){
        $errors['password'] = 'Password required';
    }
    $username = $_POST['username'];
    $password = $_POST['password'];

    $action = "login";

    if(count($errors) === 0){
        $query = $con->prepare("SELECT * FROM Users WHERE username= ? or email= ? ");
        $query->execute([$username, $password]);

        $user = $query->fetch();
        //for password
        $query1 = $con->prepare("SELECT password FROM Users WHERE username=? or email=? ");
        $query1->execute([$username,$password]);
        $row = $query1->fetch();
        $hash = $row[0];
        
            if(password_verify($password, $hash)){
                $_SESSION['id'] = $user[0];
                $_SESSION['username'] = $user[1];
                $_SESSION['email'] = $user[2];
                $_SESSION['verified'] = $user[5];
                $_SESSION['action'] = $action;
                if($_SESSION['verified']){
                    header('location: home.php');
                }else{
                    sendVerificationEmail($user[2], $user[4]);
                    header('location: verify.php');
                    
                }
                
            }else{
                $errors['login_fail'] = "Wrong username or email or password";
            }
    }
   $con = null;
}    


//ENTER EMAIL TO SENT PASSWORD RESET EMAIL

if(isset($_POST['reset-password-btn'])){
    $email = $_POST['email'];
    $query = $con->prepare("SELECT email FROM Users WHERE email=?");
    $query->execute([$email]);

    if(empty($email)){
        array_push($errors, "Your email is required");
    }else if($query->fetch() <= 0){
        array_push($errors, "No user exists on our system with the email");
    }

    $token = bin2hex(random_bytes(50));
    $action = "resetpassword";

    $_SESSION['token'] = $token;
    $_SESSION['email'] = $email;
    $_SESSION['action'] = $action;
    if(count($errors) == 0){
        $query = $con->prepare("UPDATE Users set token = ? WHERE email = ?");
        $query->execute([$token,$email]);
        $result = $query->fetch();

        sendPasswordResetEmail($email, $token);
        header('location: verify.php');


    }

}


//NEW PASSWORD, make sure is the same browser, because different browser different session
if(isset($_POST['new-password-btn'])){
    $new_pass1 = $_POST['new_pass1'];
    $new_pass2 = $_POST['new_pass2'];

    $token = $_SESSION['token'];
    if(empty($new_pass1) || empty($new_pass2)){
        array_push($errors, "Password required");
    }

    if($new_pass1 !== $new_pass2){
        array_push($errors, "Passwords do not match!");
    }
    if(count($errors) == 0){
        $hash = password_hash($new_pass1, PASSWORD_ARGON2I);

        $query = $con->prepare("SELECT email FROM Users WHERE token = ? LIMIT 1");
        $query->execute([$token]);
        $result = $query->fetch();
        $email = $result['email'];

        $sql = $con->prepare("UPDATE Users SET password= ? WHERE email = ?");
        $sql->execute([$hash, $email]);
        $result1 = $sql->fetch();

        $username = $_SESSION['username'];
        sendPasswordResetSuccessEmail($email, $username);
        header('location: index.php');
    }

}

?>