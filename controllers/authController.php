
<?php
require_once 'sendEmail.php';
error_reporting(0);

 
session_start();
$username = "";
$name = "";
$email = "";
$role = 'u';
$errors = [];



$con = mysqli_connect("localhost","root", "", "catbox");

if(mysqli_connect_errno()){
    exit('Failed to connect to database: ' . mysqli_connect_error());
}

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

$check_username = "SELECT * FROM users WHERE username = '$username'";
$check_email =  "SELECT * FROM users WHERE email = '$email'";

$result1 = mysqli_query($con, $check_username);
$result2 = mysqli_query($con, $check_email);

    if(mysqli_num_rows($result1)>0){
        $errors['username'] = "Username already exists";
    }
    if(mysqli_num_rows($result2)>0){
        $errors['email'] = "Email already exists";
    }
    if(count($errors) === 0){
        
        $query = "INSERT INTO users (username, name, email, password, token,verified, role)
                    VALUES('$username','$name', '$email', '$password1', '$token','','$role')";
        
        if($result = mysqli_query($con, $query)){
            $user_id = $query->insert_id;

            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = false;
            header('location: verify.php');
        }else{
            echo "Database error: Could not register user";
        }
    }

    sendVerificationEmail($email, $token);

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

    if(count($errors) === 0){
        $query = "SELECT * FROM users WHERE username='$username' or email='$username'";
        $result = mysqli_query($con, $query);
        $user = mysqli_fetch_assoc($result);
        //for password
        $query1 = "SELECT password FROM users WHERE username='$username' or email='$username' ";
        $result1 = mysqli_query($con, $query1);
        $row = mysqli_fetch_array($result1);

            if("$password" == "$row[0]"){
                
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['verified'] = $user['verified'];

                if($_SESSION['verified']){
                    header('location: home.php');
                }else{
                    header('location: verify.php');
                }
                
            }else{
                $errors['login_fail'] = "Wrong username or email or password";
            }


        
    }

}    


?>

