<?php
//This is send the verification email with user email and the token
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
    require_once './vendor/autoload.php';
    require_once 'config/constants.php';
    

 function sendVerificationEmail($userEmail, $token){
        $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL;
            $mail->Password = PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
                );
            $mail->From = EMAIL;
            $mail->FromName = "CATBOX";
            $mail->addAddress($userEmail);

            $mail->isHTML(true);
            $mail->Subject= 'CATBOX: Verify your email';
            $mail->Body = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>CATBOX Verification Email</title>
                    <style>
                        .wrapper{
                            padding: 20px;
                            font-size: 1.3em;
                        }
                        a {
                            background: #592f80;
                            text-decoration: none;
                            padding: 8px 15px;
                            border-radius: 5px;
                            color: #E7E8EC;
                        }
                        .btn-box{
                            border: solid;
                            border-color: #074B78;
                            background-color: #074B78;
                            color: #E7E8EC; 

                        }
                        .btn-box:hover{
                            background-color: #0969aa;
                            color: #E7E8EC; 
                            border-color: #0969aa;
                        }
                    </style>
                </head>
                <body>
                        <div class="wrapper">
                            <p>Thank you for signing up on our site. Please click on the link below to verify your account: </p>
                           
                            <!--a class="btn-box" href="https://catboxtest.000webhostapp.com/verify_email.php?token=' . $token . '">Verify Email!</a -->
                            <a class="btn-box" href="http://localhost/verify_email.php?token=' . $token . '">Verify Email</a>  
                        </div>
                </body>
            </html>
            
            ';
            $mail->send();

}

       
function sendPasswordResetEmail($userEmail, $token){
    $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL;
            $mail->Password = PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
                );
            $mail->From = EMAIL;
            $mail->FromName = "CATBOX";
            $mail->addAddress($userEmail);

            $mail->isHTML(true);
            $mail->Subject= 'CATBOX: Reset Your Password';
            $mail->Body = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>CATBOX Password Reset</title>
                    <style>
                        .wrapper{
                            padding: 20px;
                            font-size: 1.3em;
                        }
                        a {
                            text-decoration: none;
                            padding: 8px 15px;
                            border-radius: 5px;
                            color: #E7E8EC; 
                        }
                        .btn-box{
	                        border: solid;
                        	border-color: #074B78;
                        	background-color: #074B78;
	                        color: #E7E8EC; 
	
                        }
                        .btn-box:hover{
	                        background-color: #0969aa;
	                        color: #E7E8EC; 
	                        border-color: #0969aa;
                        }
                    </style>
                </head>
                <body>
                        <div class="wrapper">
                            <p>Please click on the link below to change your password:  </p>
                            <!-- <a class="btn-box" href="https://catboxtest.000webhostapp.com/reset_password.php?token=' . $token . '">Reset Password</a> -->
                            <a class="btn-box" href="http://localhost/reset_password.php?token=' . $token . '">Reset Password</a>  
                        </div>
                </body>
            </html>
            
            ';
            $mail->send();
}

function  sendPasswordResetSuccessEmail($userEmail, $username) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL;
    $mail->Password = PASSWORD;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
        );
    $mail->From = EMAIL;
    $mail->FromName = "CATBOX";
    $mail->addAddress($userEmail);

    $mail->isHTML(true);
    $mail->Subject= 'CATBOX password change';
    $mail->Body = '<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>CATBOX password change</title>
            <style>
                .wrapper{
                    padding: 20px;
                    font-size: 1.3em;
                }  
            </style>
        </head>
        <body>
                <div class="wrapper">
                    <p>This is a confirmation email that the password of your CATBOX account ' . $username . ' has just been changed. </p>
                </div>
        </body>
    </html>
    
    ';
    $mail->send();

}

function sendOTPNumberEmail($userEmail, $otp){
    $mail = new PHPMailer(true);

    $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL;
            $mail->Password = PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
                );
            $mail->From = EMAIL;
            $mail->FromName = "CATBOX";
            $mail->addAddress($userEmail);

            $mail->isHTML(true);
            $mail->Subject= 'CATBOX Vault OTP';
            $mail->Body = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>CATBOX Vault OTP</title>
                    <style>
                        .wrapper{
                            padding: 20px;
                            font-size: 1.3em;
                        }  
                    </style>
                </head>
                <body>
                        <div class="wrapper">
                            This is your OTP Number: <strong>' .$otp. '</strong>
                        </div>
                </body>
            </html>
            
            ';
            $mail->send();

}
  
?>