<?php include 'controllers/authController.php' ?>
<!DOCTYPE html>
<html>
<!-- Registration page, after registration will enter verification page which is verify.php -->
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


    <title>Registration</title>
</head>
<body class="body-color">

    <!-- NAV BAR-->
    <nav class="navbar navbar-expand-lg  fixed-top">
        <!-- SYSTEM NAME -->
        <a href="./index.php"><img width="50px" height="40px" src="img/logo.png" alt="logo" /></a>
        <p class="mr-auto mb-0">catBox</p>

        <!-- LOGIN BUTTON -->
        <a id="loginButton" class="btn btn-box" >Login</a>
    </nav>




    <!-- LOGIN MODAL -->
    <div id="loginModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md" role="content">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Login</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    
                    <form method="post" action="index.php">
                    
                        <div class="form-row">
                            <label>Username or Email address</label>
                            <input type="text" name="username" class="form-control" id="exampleInputEmail" value="<?php echo $username; ?>">
                        </div>  
                        <div class="form-row">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" id="exampleUsername">
                        </div>
                        <div class="form-row">
                            <a href="register.php" >Not Yet Register?</a>
                            <a href="enter_email.php" class="ml-auto">Forgot Password?</a>
                        </div>
                        <div class="form-row">
                            <button type="button" class="btn btn-secondary btn-sm ml-auto" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-box btn-sm ml-1" name="login-btn">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

       
           

    <!-- REGISTER FORM -->
    
    <div class="main-container wrapper">
        <h1 class="text-center">Create Your catBox Account </h1>
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
            <div class="register-inner">        
                <form method="POST" action="register.php">
                    <div class="form-row"> 
                        <label for="username">Username</label>
                        <input type="text" class="text col-12 p-2" name="username" value="<?php echo $username; ?>" placeholder="Enter your username" required>
                    </div>
                    <div class="form-row">
                        <label for="exampleInputEmail">Email address</label>
                        <input type="email" class="text col-12 p-2" name="email" value="<?php echo $email; ?>" placeholder="Enter your email" required>
                    </div>        
                    <div class="form-row">
                        <label for="examplePassword">Password</label>
                        <input type="password" class="text col-12 p-2" name="password1" placeholder="Enter your password" required>
                    </div>
                    <div class="form-row">
                        <label for="exampleConfirmPassword">Confirm Your Password</label>
                        <input type="password" class="text col-12 p-2" name="password2" placeholder="Enter your password" required>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-reg" name="register-btn">SIGNUP</button>
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
 
  




	<!-- jquery, popper.js, bootstrap script -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <!-- custom javascript -->
    <script src="./js/script.js"></script>
    

</body>

</html>