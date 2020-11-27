<?php include 'controllers/authController.php' ?>

<!DOCTYPE html>
<html>
<!-- The Main Page, before login, this page can login and click button to redirect to register.php
     Login function will use authController.php  -->
<head>
    <meta charset = "utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie-edge">
	<!--  bootstrap, font awesome  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!--  custom css  -->
	<link rel="stylesheet" href="style.css">
	
    <title>home page</title>
</head>
<body class="body-color">

	<!--  HEADER  -->
	
		<nav class="navbar">
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

        

        <!-- CONTENT -->
        
         

        <div class="container">
              
            <!-- DISPLAY ERROR -->
            <div class="row m-0 mt-1 d-flex justify-content-center">
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

            <!-- DISPLAY MESSAGE FROM VERIFICATION -->
            <div class="row m-0 mt-1 d-flex justify-content-center">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success">
                        <?php
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            unset($_SESSION['type']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
                     <!-- background images -->
            <div class="row image-holder mb-0 ">
               
                <img class="bg-img1 img-responsive" src="./img/paws.png" alt="paws" />
                <img class="bg-img2 img-responsive" src="./img/paws.png" alt="paws" />
                
                <div class="img-div">
                    <img class="bg-img3 img-responsive" src="./img/laptop.png" alt="laptop" />
                    <img class="bg-img4 img-responsive" src="./img/phone.png" alt="phone" />
                </div>
               
          </div>
            
            <!-- BUTTONS -->
            <div class="row">  
                <div class="container mt-n3">    
                <div class="row mt-0 mb-1">
                    <p class="mx-auto  text-color">Secure and store your files in catBox</p>
                </div> 
                <div class="row m-0 d-flex justify-content-center">
                    
                    <span class="col-5 col-sm-4 ">
                        <a href="home.php"  class="btn btn-lg btn-block btn-design1 text-size">Open your box</a>
                    </span>
                    <span class="col-5 col-sm-4">
                        <a href="register.php" class="btn btn-lg btn-block btn-design2 text-size">Try it now!</a>
                    </span>
  
                </div>
                </div>
            </div>   
           

            <!-- CONTENT ONE -->
            <!-- Background paws -->
         
        
            <!-- FOOTER -->
        </div> 
                 

     
	<!-- jquery, popper.js, bootstrap script -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <!-- custom javascript -->
    <script src="script.js"></script>

</body>

</html>