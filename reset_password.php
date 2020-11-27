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
        <link rel="stylesheet" href="style.css">
        
        <title>Reset Password</title>
    </head>
    <body class="body-color">
        <div class="container register-container">
            <div class="register-header">
                <h4>New Password</h4>
                    <?php if (count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <li>
                                    <?php echo $error; ?>
                                </li>
                            <?php endforeach;?>
                        </div>
                    <?php endif;?>
            </div>
            <div>
                <form method="post" action="reset_password.php">
                    <div class="form-row">
                        <label>Please enter your new password</label>
                        <input type="password" name="new_pass1" class="form-control">
                    </div>
                    <div class="form-row">
                        <label>Confirm your password</label>
                        <input type="password" name="new_pass2" class="form-control">
                    </div>
                    <div class="form-row">
                        <button type="submit" name="new-password-btn" class="btn btn-box btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>


    </body>
</html>