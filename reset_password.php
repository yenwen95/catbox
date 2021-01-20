<?php include 'controllers/authController.php' ?>

<!DOCTYPE html>
<html>
<head>
        <meta charset = "utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie-edge">
	    <!--  bootstrap, font awesome  -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	    <!--  custom css  -->
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/registerStyle.css" type="text/css" media="all">
        <!--Google font-->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300&family=Noto+Sans&display=swap" rel="stylesheet">

        <title>Reset Password</title>
    </head>
    <body class="body-color">
        <div class="main-container wrapper pt-0 pb-0">
            <h1 class="text-center">Set Your New Password</h1>
            <?php if (count($errors) > 0): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <li>
                            <?php echo $error; ?>
                        </li>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
           
            <div class="register-outer">
                <div class="register-inner p-1">
                    <form method="post" action="reset_password.php">
                        <div class="form-row">
                            <label>Please enter your new password</label>
                            <input type="password" name="new_pass1" class="text col-sm-12 p-2" required>
                        </div>
                        <div class="form-row">
                            <label>Confirm your password</label>
                            <input type="password" name="new_pass2" class="text col-sm-12 p-2" required>
                        </div>
                        <div class="form-row">
                            <button type="submit" name="new-password-btn" class="btn btn-reg">Submit</button>
                        </div>
                    </form>
                </div>
            </div>


            <ul class="bubbles list-unstyled">
                <li></li>
                <li></li>  
                <li></li>  
                <li></li>  
                <li></li>  
                <li></li>  
                <li></li>  
                <li></li>  
                <li></li>  
                <li></li>       
                <li></li>      
            </ul>

        </div>


    </body>
</html>