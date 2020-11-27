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
$name = "";
$email = "";
$role = 'u';
$errors = [];




//REGISTER
extract($_POST);

if(isset($_POST['register-btn'])){
    if(empty($_POST["username"])){
        $errors['username'] = 'Username required';
    }
    if(empty($_POST["name"])){
        $errors['name'] = 'Name required';
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
$name = $_POST["name"];
$email = $_POST["email"];
$token = bin2hex(random_bytes(50));
$password1 = $_POST["password1"];
$action = "register";

$check_username = "SELECT * FROM Users WHERE username = '$username'";
$check_email =  "SELECT * FROM Users WHERE email = '$email'";

$result = mysqli_query($con, $check_username);
$row = mysqli_fetch_array($result);

$result1 = mysqli_query($con,$check_email);
$row1 = mysqli_fetch_array($result1);

    if($row > 0){
        $errors['username'] = "Username already exists";
    }
    if($row1 > 0){
        $errors['email'] = "Email already exists";
    }
    if(count($errors) === 0){
        
        $query = "INSERT INTO Users (`username`, `name`, `email`, `password`, `token`, `verified`, `role`)
                    VALUES('$username','$name', '$email', '$password1', '$token','0','$role')";
        $result3 = mysqli_query($con, $query);
        if($result3){
            
            sendVerificationEmail($email, $token);
            $user_id = mysqli_insert_id($con);
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = false;
            $_SESSION['action'] = $action;
           
            header('location: verify.php');
            exit();
        }
        else{
           
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
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $action = "login";

    if(count($errors) === 0){
        $query = "SELECT * FROM Users WHERE username='$username' or email='$username'";
        $result = mysqli_query($con, $query);
        if(!$result){
            echo "Could not execute query";
        }
        
        $user = mysqli_fetch_row($result);
        //for password
        $query1 = "SELECT password FROM Users WHERE username='$username' or email='$username' ";
        $result1 = mysqli_query($con, $query1);
        $row = mysqli_fetch_array($result1);
        
            if( "$password" == "$row[0]"){
                $_SESSION['id'] = $user[0];
                $_SESSION['username'] = $user[1];
                $_SESSION['name'] = $user[2];
                $_SESSION['email'] = $user[3];
                $_SESSION['verified'] = $user[6];
                $_SESSION['action'] = $action;
                if($_SESSION['verified']){
                    header('location: home.php');
                }else{
                    sendVerificationEmail($user[3], $user[5]);
                    header('location: verify.php');
                    
                }
                
            }else{
                $errors['login_fail'] = "Wrong username or email or password";
            }
    }
    mysqli_close($con);
}    


//ENTER EMAIL TO SENT PASSWORD RESET EMAIL

if(isset($_POST['reset-password-btn'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $query = "SELECT email FROM Users WHERE email='$email'";
    $result = mysqli_query($con, $query);

    if(empty($email)){
        array_push($errors, "Your email is required");
    }else if(mysqli_num_rows($result) <= 0){
        array_push($errors, "No user exists on our system with the email");
    }

    $token = bin2hex(random_bytes(50));
    $action = "resetpassword";

    $_SESSION['token'] = $token;
    $_SESSION['email'] = $email;
    $_SESSION['action'] = $action;
    if(count($errors) == 0){
        $sql = "UPDATE Users set token = '$token' WHERE email = '$email'";
        $result = mysqli_query($con, $sql);

        sendPasswordResetEmail($email, $token);
        header('location: verify.php');


    }

}


//NEW PASSWORD, make sure is the same browser, because different browser different session
if(isset($_POST['new-password-btn'])){
    $new_pass1 = mysqli_real_escape_string($con, $_POST['new_pass1']);
    $new_pass2 = mysqli_real_escape_string($con, $_POST['new_pass2']);

    $token = $_SESSION['token'];
    if(empty($new_pass1) || empty($new_pass2)){
        array_push($errors, "Password required");
    }

    if($new_pass1 !== $new_pass2){
        array_push($errors, "Passwords do not match!");
    }
    if(count($errors) == 0){
      
        $query = "SELECT email FROM Users WHERE token = '$token' LIMIT 1";
        $result = mysqli_query($con, $query);
        $email = mysqli_fetch_assoc($result)['email'];

        $sql = "UPDATE Users SET password='$new_pass1' WHERE email = '$email'";
        $result1 = mysqli_query($con, $sql);

        $username = $_SESSION['username'];
        sendPasswordResetSuccessEmail($email, $username);
        header('location: index.php');
    }

}

?>