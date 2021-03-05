<?php

ob_start("minifier"); 
function minifier($code) { 
    $search = array( 
          
        // Remove whitespaces after tags 
        '/\>[^\S ]+/s', 
          
        // Remove whitespaces before tags 
        '/[^\S ]+\</s', 
          
        // Remove multiple whitespace sequences 
        '/(\s)+/s', 
          
        // Removes comments 
        '/<!--(.|\s)*?-->/'
    ); 
    $replace = array('>', '<', '\\1'); 
    $code = preg_replace($search, $replace, $code); 
    return $code; 
} 


	include 'controllers/authController.php';

	if(empty($_SESSION['id'])){
		header('location: index.php');
	}
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

    ?>

<!DOCTYPE html>
<html>
<!-- The Application Page, only can enter after login and verification -->
<head>
    <meta charset = "utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie-edge">
	
	<!--  bootstrap, font awesome  -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./css/commonHomeStyle.css">
	

	<!--Google font-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300&family=Noto+Sans&display=swap" rel="stylesheet">


	<script src="https://kit.fontawesome.com/b48b20acd1.js"></script>
    <title>CATBOX Profile</title>
</head>
<body class="body-color">
	
	<nav class="navbar navbar-expand-lg fixed-top">
			<div class="container-fluid">
				<!-- SYSTEM NAME -->
				<button  type="button" id="openSidebar">&#9776;</button>
				<img src="./img/logo.png" alt="logo" width="50px" height="40px" />
				
				<p class="mr-auto mb-0">CATBOX@<?php echo $_SESSION['username'] ?></p>
				
				<!-- SEARCH BAR
				<div class="search-container">
					<input type="text" placeholder="Search..">
					<button type="submit"><i class="fa fa-search"></i></button>
				</div>
				-->
				<!-- LOGIN BUTTON -->
				<a class="btn btn-box" href="logout.php">Logout</a>
			</div>
	</nav>

    <nav id="mySidebar" class="sidebar">
		<!-- LEFT CONTENT (MENU TO SWITCH FROM OWN SPACE and SHARED FILE) -->
		<div id="closeSidebar" class="mb-5">
			<i class="fas fa-arrow-left"></i>
		</div>
		<ul class="list-unstyled components">
			<li>
				<a href="./index.php" class="d-flex align-self-end">
					<svg  class="icon-sidebar" viewBox="0 0 527 527" xmlns="http://www.w3.org/2000/svg">
						<path d="m498.195312 222.695312c-.011718-.011718-.023437-.023437-.035156-.035156l-208.855468-208.847656c-8.902344-8.90625-20.738282-13.8125-33.328126-13.8125-12.589843 
								0-24.425781 4.902344-33.332031 13.808594l-208.746093 208.742187c-.070313.070313-.140626.144531-.210938.214844-18.28125 18.386719-18.25 48.21875.089844 66.558594 
								8.378906 8.382812 19.445312 13.238281 31.277344 13.746093.480468.046876.964843.070313 1.453124.070313h8.324219v153.699219c0 30.414062 24.746094 55.160156 55.167969 
								55.160156h81.710938c8.28125 0 15-6.714844 15-15v-120.5c0-13.878906 11.289062-25.167969 25.167968-25.167969h48.195313c13.878906 0 25.167969 11.289063 25.167969 25.167969v120.5c0 
								8.285156 6.714843 15 15 15h81.710937c30.421875 0 55.167969-24.746094 55.167969-55.160156v-153.699219h7.71875c12.585937 0 24.421875-4.902344 33.332031-13.808594 18.359375-18.371093 
								18.367187-48.253906.023437-66.636719zm0 0"/>
					</svg>
					<span class="pl-3">Main Page</span>
				</a>
			</li>
			<li>
				<a href="./home.php" class="d-flex align-self-end">
					<svg  class="icon-sidebar" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 510.218 510.218"  xml:space="preserve">
						<g>
							<g>
								<path d="M497.587,188.634c-10.155-11.989-24.981-18.859-40.704-18.859H280.777c-11.541,0-23.104,2.731-33.387,7.893
									l-58.325,29.141c-7.36,3.691-15.616,5.632-23.851,5.632H53.363c-16.064,0-31.104,7.125-41.28,19.563
									C1.907,244.442-2.103,260.592,1.054,276.336l29.141,144.512c2.795,15.189,16.043,26.261,31.509,26.261h384.619
									c15.893,0,29.205-11.413,31.595-26.987L509.47,231.92C512.094,216.41,507.742,200.645,497.587,188.634z"/>
							</g>
						</g>
						<g>
							<g>
								<path d="M436.318,105.776H258.334c-8.427,0-16.661-3.413-22.635-9.365l-17.685-17.664c-10.048-10.091-23.445-15.637-37.696-15.637
									H94.985c-29.397,0-53.333,23.936-53.333,53.333v75.84c3.499-0.704,7.061-1.173,10.709-1.173h112.853
									c8.235,0,16.491-1.941,23.851-5.632l58.304-29.141c10.304-5.163,21.867-7.893,33.408-7.893h176.789
									c3.648,0,7.232,0.448,10.752,1.173v-11.84C468.318,120.133,453.961,105.776,436.318,105.776z"/>
							</g>
						</g>

					</svg>
					<span class="pl-3">My Files</span>
				</a>
			</li>
		</ul>
		
	</nav>	

    <div id="main">
    </div>

    <div class="overlay"></div>
	<div class="d-md-none overlayMobile"></div>
	<div id="overlayLoading" class="overlayLoading"></div>
	<div id="loading" class="load loadingContainer">
		<div class="load top-left"></div>
		<div class="load top-right"></div>
		<div class="load bottom-left"></div>
		<div class="load bottom-right"></div>
	</div>

    	<!--  jquery, popper.js, bootstrap script  -->
	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	 <script src="./js/userScript.js"></script>


     	
</body>

</html>

<?php 
ob_end_flush(); 
?>