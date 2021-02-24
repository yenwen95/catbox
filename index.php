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


include 'controllers/authController.php' 


?>

<!DOCTYPE html>
<html>
<!-- The Main Page, before login, this page can login and click button to redirect to register.php
     Login function will use authController.php  -->
<head>
    <meta charset = "utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie-edge">

	<!--  bootstrap, font awesome  -->
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/indexStyle.css">
  

<!--Google font-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300&family=Noto+Sans&display=swap" rel="stylesheet">

<script src="https://kit.fontawesome.com/b48b20acd1.js"></script>
    <title>catBox</title>
</head>
<body class="body-color">

<!--  

    IDEA

    SHARE
    -cannot share files which are shared by other users

    Password protected file
    -a vault that needs password to open
    -vault password stores in user table [ isvaultActivate(1/0), vaultPass(random chars)]
    -inside can store files, can set pin
    -if protect ur own file, u can share ur own file
    -if protect shared file, u cannot share the file
    -if it is ur own file, store pin in file table 
     [isPinSet(1/0), pinPass(first pin is ur own pin, 
                            second onwards are pin set by other users if u share the file to other user,
                            so when u get a shared and protected file, system will prompt u to set the pin,
                            system will need to know the shared_users orders from db) ]



  -->




	<!--  HEADER  -->
	
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
                        <button  type="button" class="close" data-dismiss="modal"><i class="far fa-times-circle"></i></button>
                    </div>
                    <div class="modal-body">
                        
                        <form method="post" action="index.php">
                        
                            <div class="form-row mt-1">
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
                            <div class="form-row mb-0">
                                <button type="submit" class="btn btn-box btn-sm ml-auto" name="login-btn">LOG IN</button>
                                <button type="button" class="btn btn-secondary btn-sm ml-1" data-dismiss="modal">CANCEL</button>
                               
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- CONTENT -->
        
         

        <div class="main container mx-auto">
              
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
            <div class="row image-holder mx-auto">
               
                <div class="img-div mx-auto">
                    <img class="bg-img3 img-responsive" src="./img/laptop.png" alt="laptop" />
                    <img class="bg-img4 img-responsive" src="./img/phone.png" alt="phone" />
                </div>
               
          </div>
            
            <!-- BUTTONS -->
            <div class="row mt-0">  
                <div class="container">    
                <div class="row mt-0 mb-1">
                    <p class="mx-auto  index-banner"><b>Secure and store your files in catBox</b></p>
                </div> 
                <div class="row m-0 mb-2 d-flex justify-content-center">
                    
                    <span class="col-12 mb-3 col-md-4 ">
                        <a href="home.php"  class="btn btn-lg btn-block btn-design1 index-btn">Open your box</a>
                    </span>
                    <span class="col-12 col-md-4">
                        <a href="register.php" class="btn btn-lg btn-block btn-design2 index-btn">Register now!</a>
                    </span>
  
                </div>
                </div>
            </div>   
           

            <!-- CONTENT ONE -->
            <!-- Background paws -->
        
            <div class="paw paw-print-1">
                <svg version="1.0" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1280 1232" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,1232.000000) scale(0.100000,-0.100000)"  stroke="none">
                        <path d="M8425 12296 c-714 -172 -1276 -774 -1548 -1656 -128 -415 -184 -930
                        -142 -1305 82 -741 432 -1322 951 -1583 176 -88 352 -134 520 -134 134 0 252
                        24 387 78 814 327 1377 1162 1499 2224 21 187 16 551 -11 770 -40 326 -87 500
                        -196 725 -171 353 -411 614 -705 764 -178 92 -325 130 -525 137 -113 3 -143 1
                        -230 -20z"/>
                        <path d="M3822 12255 c-408 -74 -780 -384 -1006 -839 -212 -426 -270 -1025
                        -160 -1654 140 -802 549 -1478 1131 -1868 128 -86 340 -191 484 -239 102 -34
                        128 -38 241 -43 150 -6 257 8 388 50 203 65 369 167 525 323 309 309 501 774
                        546 1323 18 226 -2 562 -51 825 -175 952 -690 1700 -1380 2003 -269 118 -506
                        157 -718 119z"/>
                        <path d="M11403 8606 c-661 -109 -1244 -566 -1604 -1260 -171 -329 -261 -613
                        -326 -1026 -23 -149 -27 -205 -27 -375 0 -208 12 -311 55 -479 115 -451 386
                        -818 723 -981 184 -89 352 -122 541 -105 459 40 868 247 1232 623 401 415 677
                        989 767 1592 23 151 39 446 32 567 -37 623 -371 1169 -836 1369 -180 77 -378
                        104 -557 75z"/>
                        <path d="M1051 8344 c-435 -73 -801 -377 -936 -779 -48 -142 -88 -345 -105
                        -530 -62 -671 173 -1424 618 -1985 98 -123 291 -317 414 -413 284 -225 627
                        -386 947 -447 85 -16 286 -14 367 4 359 78 662 328 834 689 106 222 160 505
                        160 838 0 205 -10 317 -46 507 -137 732 -582 1417 -1182 1820 -164 111 -397
                        229 -524 266 -159 46 -380 58 -547 30z"/>
                        <path d="M6240 5905 c-348 -39 -692 -132 -1031 -281 -518 -229 -1018 -610
                        -1341 -1024 -697 -894 -1181 -1862 -1378 -2755 -12 -55 -33 -138 -46 -185 -67
                        -243 -56 -519 31 -760 140 -388 443 -689 818 -815 224 -75 559 -74 932 4 243
                        50 425 108 941 299 718 267 917 318 1400 363 216 20 255 21 404 10 381 -27
                        682 -110 1260 -346 512 -209 798 -322 870 -342 720 -204 1435 47 1680 590 107
                        239 119 628 29 997 -88 359 -349 942 -599 1335 -704 1108 -1211 1729 -1790
                        2197 -549 442 -1005 650 -1571 713 -159 18 -447 18 -609 0z"/>
                    </g>
                </svg>
               
            </div>

            <div class="paw paw-print-2">
                 <svg version="1.0" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1280 1232" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,1232.000000) scale(0.100000,-0.100000)"  stroke="none">
                        <path d="M8425 12296 c-714 -172 -1276 -774 -1548 -1656 -128 -415 -184 -930
                        -142 -1305 82 -741 432 -1322 951 -1583 176 -88 352 -134 520 -134 134 0 252
                        24 387 78 814 327 1377 1162 1499 2224 21 187 16 551 -11 770 -40 326 -87 500
                        -196 725 -171 353 -411 614 -705 764 -178 92 -325 130 -525 137 -113 3 -143 1
                        -230 -20z"/>
                        <path d="M3822 12255 c-408 -74 -780 -384 -1006 -839 -212 -426 -270 -1025
                        -160 -1654 140 -802 549 -1478 1131 -1868 128 -86 340 -191 484 -239 102 -34
                        128 -38 241 -43 150 -6 257 8 388 50 203 65 369 167 525 323 309 309 501 774
                        546 1323 18 226 -2 562 -51 825 -175 952 -690 1700 -1380 2003 -269 118 -506
                        157 -718 119z"/>
                        <path d="M11403 8606 c-661 -109 -1244 -566 -1604 -1260 -171 -329 -261 -613
                        -326 -1026 -23 -149 -27 -205 -27 -375 0 -208 12 -311 55 -479 115 -451 386
                        -818 723 -981 184 -89 352 -122 541 -105 459 40 868 247 1232 623 401 415 677
                        989 767 1592 23 151 39 446 32 567 -37 623 -371 1169 -836 1369 -180 77 -378
                        104 -557 75z"/>
                        <path d="M1051 8344 c-435 -73 -801 -377 -936 -779 -48 -142 -88 -345 -105
                        -530 -62 -671 173 -1424 618 -1985 98 -123 291 -317 414 -413 284 -225 627
                        -386 947 -447 85 -16 286 -14 367 4 359 78 662 328 834 689 106 222 160 505
                        160 838 0 205 -10 317 -46 507 -137 732 -582 1417 -1182 1820 -164 111 -397
                        229 -524 266 -159 46 -380 58 -547 30z"/>
                        <path d="M6240 5905 c-348 -39 -692 -132 -1031 -281 -518 -229 -1018 -610
                        -1341 -1024 -697 -894 -1181 -1862 -1378 -2755 -12 -55 -33 -138 -46 -185 -67
                        -243 -56 -519 31 -760 140 -388 443 -689 818 -815 224 -75 559 -74 932 4 243
                        50 425 108 941 299 718 267 917 318 1400 363 216 20 255 21 404 10 381 -27
                        682 -110 1260 -346 512 -209 798 -322 870 -342 720 -204 1435 47 1680 590 107
                        239 119 628 29 997 -88 359 -349 942 -599 1335 -704 1108 -1211 1729 -1790
                        2197 -549 442 -1005 650 -1571 713 -159 18 -447 18 -609 0z"/>
                    </g>
                </svg>
            </div>
            <div class="paw paw-print-3">
                 <svg version="1.0" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1280 1232" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,1232.000000) scale(0.100000,-0.100000)"  stroke="none">
                        <path d="M8425 12296 c-714 -172 -1276 -774 -1548 -1656 -128 -415 -184 -930
                        -142 -1305 82 -741 432 -1322 951 -1583 176 -88 352 -134 520 -134 134 0 252
                        24 387 78 814 327 1377 1162 1499 2224 21 187 16 551 -11 770 -40 326 -87 500
                        -196 725 -171 353 -411 614 -705 764 -178 92 -325 130 -525 137 -113 3 -143 1
                        -230 -20z"/>
                        <path d="M3822 12255 c-408 -74 -780 -384 -1006 -839 -212 -426 -270 -1025
                        -160 -1654 140 -802 549 -1478 1131 -1868 128 -86 340 -191 484 -239 102 -34
                        128 -38 241 -43 150 -6 257 8 388 50 203 65 369 167 525 323 309 309 501 774
                        546 1323 18 226 -2 562 -51 825 -175 952 -690 1700 -1380 2003 -269 118 -506
                        157 -718 119z"/>
                        <path d="M11403 8606 c-661 -109 -1244 -566 -1604 -1260 -171 -329 -261 -613
                        -326 -1026 -23 -149 -27 -205 -27 -375 0 -208 12 -311 55 -479 115 -451 386
                        -818 723 -981 184 -89 352 -122 541 -105 459 40 868 247 1232 623 401 415 677
                        989 767 1592 23 151 39 446 32 567 -37 623 -371 1169 -836 1369 -180 77 -378
                        104 -557 75z"/>
                        <path d="M1051 8344 c-435 -73 -801 -377 -936 -779 -48 -142 -88 -345 -105
                        -530 -62 -671 173 -1424 618 -1985 98 -123 291 -317 414 -413 284 -225 627
                        -386 947 -447 85 -16 286 -14 367 4 359 78 662 328 834 689 106 222 160 505
                        160 838 0 205 -10 317 -46 507 -137 732 -582 1417 -1182 1820 -164 111 -397
                        229 -524 266 -159 46 -380 58 -547 30z"/>
                        <path d="M6240 5905 c-348 -39 -692 -132 -1031 -281 -518 -229 -1018 -610
                        -1341 -1024 -697 -894 -1181 -1862 -1378 -2755 -12 -55 -33 -138 -46 -185 -67
                        -243 -56 -519 31 -760 140 -388 443 -689 818 -815 224 -75 559 -74 932 4 243
                        50 425 108 941 299 718 267 917 318 1400 363 216 20 255 21 404 10 381 -27
                        682 -110 1260 -346 512 -209 798 -322 870 -342 720 -204 1435 47 1680 590 107
                        239 119 628 29 997 -88 359 -349 942 -599 1335 -704 1108 -1211 1729 -1790
                        2197 -549 442 -1005 650 -1571 713 -159 18 -447 18 -609 0z"/>
                    </g>
                </svg>
            </div>
            <div class="paw paw-print-4">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1280 1232" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,1232.000000) scale(0.100000,-0.100000)"  stroke="none">
                        <path d="M8425 12296 c-714 -172 -1276 -774 -1548 -1656 -128 -415 -184 -930
                        -142 -1305 82 -741 432 -1322 951 -1583 176 -88 352 -134 520 -134 134 0 252
                        24 387 78 814 327 1377 1162 1499 2224 21 187 16 551 -11 770 -40 326 -87 500
                        -196 725 -171 353 -411 614 -705 764 -178 92 -325 130 -525 137 -113 3 -143 1
                        -230 -20z"/>
                        <path d="M3822 12255 c-408 -74 -780 -384 -1006 -839 -212 -426 -270 -1025
                        -160 -1654 140 -802 549 -1478 1131 -1868 128 -86 340 -191 484 -239 102 -34
                        128 -38 241 -43 150 -6 257 8 388 50 203 65 369 167 525 323 309 309 501 774
                        546 1323 18 226 -2 562 -51 825 -175 952 -690 1700 -1380 2003 -269 118 -506
                        157 -718 119z"/>
                        <path d="M11403 8606 c-661 -109 -1244 -566 -1604 -1260 -171 -329 -261 -613
                        -326 -1026 -23 -149 -27 -205 -27 -375 0 -208 12 -311 55 -479 115 -451 386
                        -818 723 -981 184 -89 352 -122 541 -105 459 40 868 247 1232 623 401 415 677
                        989 767 1592 23 151 39 446 32 567 -37 623 -371 1169 -836 1369 -180 77 -378
                        104 -557 75z"/>
                        <path d="M1051 8344 c-435 -73 -801 -377 -936 -779 -48 -142 -88 -345 -105
                        -530 -62 -671 173 -1424 618 -1985 98 -123 291 -317 414 -413 284 -225 627
                        -386 947 -447 85 -16 286 -14 367 4 359 78 662 328 834 689 106 222 160 505
                        160 838 0 205 -10 317 -46 507 -137 732 -582 1417 -1182 1820 -164 111 -397
                        229 -524 266 -159 46 -380 58 -547 30z"/>
                        <path d="M6240 5905 c-348 -39 -692 -132 -1031 -281 -518 -229 -1018 -610
                        -1341 -1024 -697 -894 -1181 -1862 -1378 -2755 -12 -55 -33 -138 -46 -185 -67
                        -243 -56 -519 31 -760 140 -388 443 -689 818 -815 224 -75 559 -74 932 4 243
                        50 425 108 941 299 718 267 917 318 1400 363 216 20 255 21 404 10 381 -27
                        682 -110 1260 -346 512 -209 798 -322 870 -342 720 -204 1435 47 1680 590 107
                        239 119 628 29 997 -88 359 -349 942 -599 1335 -704 1108 -1211 1729 -1790
                        2197 -549 442 -1005 650 -1571 713 -159 18 -447 18 -609 0z"/>
                    </g>
                </svg>
            </div>
            <div class="paw paw-print-5">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1280 1232" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,1232.000000) scale(0.100000,-0.100000)"  stroke="none">
                        <path d="M8425 12296 c-714 -172 -1276 -774 -1548 -1656 -128 -415 -184 -930
                        -142 -1305 82 -741 432 -1322 951 -1583 176 -88 352 -134 520 -134 134 0 252
                        24 387 78 814 327 1377 1162 1499 2224 21 187 16 551 -11 770 -40 326 -87 500
                        -196 725 -171 353 -411 614 -705 764 -178 92 -325 130 -525 137 -113 3 -143 1
                        -230 -20z"/>
                        <path d="M3822 12255 c-408 -74 -780 -384 -1006 -839 -212 -426 -270 -1025
                        -160 -1654 140 -802 549 -1478 1131 -1868 128 -86 340 -191 484 -239 102 -34
                        128 -38 241 -43 150 -6 257 8 388 50 203 65 369 167 525 323 309 309 501 774
                        546 1323 18 226 -2 562 -51 825 -175 952 -690 1700 -1380 2003 -269 118 -506
                        157 -718 119z"/>
                        <path d="M11403 8606 c-661 -109 -1244 -566 -1604 -1260 -171 -329 -261 -613
                        -326 -1026 -23 -149 -27 -205 -27 -375 0 -208 12 -311 55 -479 115 -451 386
                        -818 723 -981 184 -89 352 -122 541 -105 459 40 868 247 1232 623 401 415 677
                        989 767 1592 23 151 39 446 32 567 -37 623 -371 1169 -836 1369 -180 77 -378
                        104 -557 75z"/>
                        <path d="M1051 8344 c-435 -73 -801 -377 -936 -779 -48 -142 -88 -345 -105
                        -530 -62 -671 173 -1424 618 -1985 98 -123 291 -317 414 -413 284 -225 627
                        -386 947 -447 85 -16 286 -14 367 4 359 78 662 328 834 689 106 222 160 505
                        160 838 0 205 -10 317 -46 507 -137 732 -582 1417 -1182 1820 -164 111 -397
                        229 -524 266 -159 46 -380 58 -547 30z"/>
                        <path d="M6240 5905 c-348 -39 -692 -132 -1031 -281 -518 -229 -1018 -610
                        -1341 -1024 -697 -894 -1181 -1862 -1378 -2755 -12 -55 -33 -138 -46 -185 -67
                        -243 -56 -519 31 -760 140 -388 443 -689 818 -815 224 -75 559 -74 932 4 243
                        50 425 108 941 299 718 267 917 318 1400 363 216 20 255 21 404 10 381 -27
                        682 -110 1260 -346 512 -209 798 -322 870 -342 720 -204 1435 47 1680 590 107
                        239 119 628 29 997 -88 359 -349 942 -599 1335 -704 1108 -1211 1729 -1790
                        2197 -549 442 -1005 650 -1571 713 -159 18 -447 18 -609 0z"/>
                    </g>
                </svg>
            </div>
            <div class="paw paw-print-6">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1280 1232" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,1232.000000) scale(0.100000,-0.100000)"  stroke="none">
                        <path d="M8425 12296 c-714 -172 -1276 -774 -1548 -1656 -128 -415 -184 -930
                        -142 -1305 82 -741 432 -1322 951 -1583 176 -88 352 -134 520 -134 134 0 252
                        24 387 78 814 327 1377 1162 1499 2224 21 187 16 551 -11 770 -40 326 -87 500
                        -196 725 -171 353 -411 614 -705 764 -178 92 -325 130 -525 137 -113 3 -143 1
                        -230 -20z"/>
                        <path d="M3822 12255 c-408 -74 -780 -384 -1006 -839 -212 -426 -270 -1025
                        -160 -1654 140 -802 549 -1478 1131 -1868 128 -86 340 -191 484 -239 102 -34
                        128 -38 241 -43 150 -6 257 8 388 50 203 65 369 167 525 323 309 309 501 774
                        546 1323 18 226 -2 562 -51 825 -175 952 -690 1700 -1380 2003 -269 118 -506
                        157 -718 119z"/>
                        <path d="M11403 8606 c-661 -109 -1244 -566 -1604 -1260 -171 -329 -261 -613
                        -326 -1026 -23 -149 -27 -205 -27 -375 0 -208 12 -311 55 -479 115 -451 386
                        -818 723 -981 184 -89 352 -122 541 -105 459 40 868 247 1232 623 401 415 677
                        989 767 1592 23 151 39 446 32 567 -37 623 -371 1169 -836 1369 -180 77 -378
                        104 -557 75z"/>
                        <path d="M1051 8344 c-435 -73 -801 -377 -936 -779 -48 -142 -88 -345 -105
                        -530 -62 -671 173 -1424 618 -1985 98 -123 291 -317 414 -413 284 -225 627
                        -386 947 -447 85 -16 286 -14 367 4 359 78 662 328 834 689 106 222 160 505
                        160 838 0 205 -10 317 -46 507 -137 732 -582 1417 -1182 1820 -164 111 -397
                        229 -524 266 -159 46 -380 58 -547 30z"/>
                        <path d="M6240 5905 c-348 -39 -692 -132 -1031 -281 -518 -229 -1018 -610
                        -1341 -1024 -697 -894 -1181 -1862 -1378 -2755 -12 -55 -33 -138 -46 -185 -67
                        -243 -56 -519 31 -760 140 -388 443 -689 818 -815 224 -75 559 -74 932 4 243
                        50 425 108 941 299 718 267 917 318 1400 363 216 20 255 21 404 10 381 -27
                        682 -110 1260 -346 512 -209 798 -322 870 -342 720 -204 1435 47 1680 590 107
                        239 119 628 29 997 -88 359 -349 942 -599 1335 -704 1108 -1211 1729 -1790
                        2197 -549 442 -1005 650 -1571 713 -159 18 -447 18 -609 0z"/>
                    </g>
                </svg>
            </div-->

       
           
        </div> 
                 

     
	<!-- jquery, popper.js, bootstrap script -->
  
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
 

</body>

</html>

<?php 
ob_end_flush(); 
?>