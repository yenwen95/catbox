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
	
    <title>Registration</title>
</head>
<body class="body-bg">
    <nav class="navbar">
        <!-- SYSTEM NAME -->
        <a class="navbar-brand" href="index.php">catBox</a>
        


        <!-- LOGIN BUTTON -->
        <a id="loginButton" class="btn btn-box" >Login</a>
    </nav>

    <!-- REGISTER FORM -->
    
    <div class="container register-container">
        <div class="register-header">
            <h4>Please Provide Your Information: </h4>
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
        <div >    
            <form method="POST" action="register.php">
                <div class="form-row"> 
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" placeholder="Enter your username">
                </div>
                <div class="form-row">
                    <label for="Name">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" placeholder="Enter your name">
                </div>
                <div class="form-row">
                    <label for="exampleInputEmail">Email address</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" placeholder="Enter your email">
                </div>        
                <div class="form-row">
                    <label for="examplePassword">Password</label>
                    <input type="password" class="form-control" name="password1" placeholder="Enter your password">
                </div>
                <div class="form-row">
                    <label for=exampleConfirmPassword">Confirm Your Password</label>
                    <input type="password" class="form-control" name="password2" placeholder="Enter your password">
                </div>
                <div class="form-row">
                    <button type="submit" class="btn btn-box btn-sm ml-1" name="register-btn">Submit</button>
                </div>
            </form>
        </div> 
    </div>
 
  




	<!-- jquery, popper.js, bootstrap script -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <!-- custom javascript -->
    <script src="script.js"></script>
    

</body>

</html>