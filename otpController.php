<?php
    include 'controllers/authController.php';
    require_once 'controllers/sendEmail.php';


    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];

    //create otp
    //call send email function
    //store otp in database

    if(isset($_POST['action'])){
        $action = $_POST['action'];

        if($action == "checkVault"){
            $status = "";
            $fetchStatus = $con->prepare("SELECT vault_isopen FROM Users WHERE username = ?");
            $fetchStatus->execute([$username]);

            $row = $fetchStatus->fetch();
            $status = $row[0];

            echo json_encode($status);


        }

        if($action == "sendOTP"){
           
            //create otp number
            $random = rand(100000, 999999);
            $otp = strval($random);

            //update database
            $setOTP = $con->prepare("UPDATE Users SET otp_pass = ? WHERE username = ?");
       
            $setOTP->execute([$otp, $username]);

            //send email
            $status ="sent";
            sendOTPNumberEmail($email, $otp);
            echo json_encode($status);

        }

        if($action == "submitOTP"){

            $otpPass = $_POST['otpPass'];
            //check otp used how many times

            if("" == trim($otpPass)){
                $status = "1"; //empty otp
            }else{
                $query = $con->prepare("SELECT otp_timestamp, otp_valid FROM Users WHERE username = ? && otp_pass = ?");
                $query->execute([$username, $otpPass]);
                $row = $query->fetch();

                if($row == ""){
                    $status = '2'; //wrong otp 
                }else{
                    $validTime = strtotime($row['otp_timestamp']) + 900;

                    if((strtotime("now")>$validTime)){
                        $status = '3';  //expired
                    }else{

                        if($row['otp_valid'] == '0'){
                            $status = '4'; //otp has been used
                        }else{
                            $status = "5";  //success
                            $vaultopen = "1";
                            $otpValid = "0";
                            $query2 = $con->prepare("UPDATE Users SET vault_isopen = ?, otp_valid = ? WHERE username = ?");
                            $query2->execute([$vaultopen, $otpValid, $username]);
                        
                        }
                        
                    }

                }

            }
            echo json_encode($status); 
        }
        


    }

?>