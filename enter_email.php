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
        <!--Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300&family=Noto+Sans&display=swap" rel="stylesheet">

        <title>Reset Password</title>
    </head>
    <body class="body-bg">
    <nav class="navbar">
        <!-- SYSTEM NAME -->
        <a href="./index.php"><img width="50px" height="40px" src="img/logo.png" alt="logo" /></a>
        <p class="mr-auto mb-0">catBox</p>
    </nav>

        <div class="container register-container">
            <div class="register-header">
                <h4>Reset Password</h4>
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
                <form method="POST" action="enter_email.php">
                    <div class="form-row">
                        <label>Please enter your email address</label>
                        <input type="email" name="email"class="form-control" value="<?php echo $email; ?>" >
                    </div>
                    <div class="form-row">
                        <button type="submit" name="reset-password-btn" class="btn btn-box btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>


    </body>



</html>

