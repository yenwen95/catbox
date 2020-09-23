<?php include 'controllers/authController.php'?>

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
	
    <title>Verification</title>
</head>
<body class="body-bg">
    <div class="container">
        <div class="row">
            
            <?php if(!$_SESSION['verified']): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    You need to verify you email address! Sign into your email account 
                    and click on the verification link we just emailed you at 
                     <strong><?php echo $_SESSION['email']; ?></strong>
                </div>
            <?php else: ?>
                <a class="btn btn-lg btn-primary btn-block" href="home.html">I'm verified!!!</a>
            <?php endif;?>

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
