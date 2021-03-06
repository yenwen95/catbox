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

	//Check user folder exist or not
	$defaultDir = "./file_dir/";
	$username = $_SESSION['username'];
	$userFolder = $defaultDir.$username.'/';
	$userRecoveryFolder = $userFolder.'recyclebin/';
	$userVaultFolder = $userFolder.'vault/';

	if(!file_exists($userFolder)){ 
		mkdir($userFolder, 0755, true);
		
	}

	if(!file_exists($userRecoveryFolder)){ 
		mkdir($userRecoveryFolder, 0755, true);
		
	}
	if(!file_exists($userVaultFolder)){ 
		mkdir($userVaultFolder, 0755, true);
		
	}

	/*CHECKING FILES CONSISTENCY IN BOTH DATABASE AND SERVER
	//  CONDITION 1: record in database but not in server, delete record in db
	$query = $con->prepare("SELECT filepath FROM Files WHERE username = ?");
	$query->execute([$username]);


	while($row = $query->fetch()){
		if(!file_exists($row['filepath'])){
			$path = $row['filepath'];
			$deleteRow = $con->prepare("DELETE FROM Files where filepath = ?");
			$deleteRow->execute([$path]);

			
		}
	}

	//  CONDITION 2: record in server but not in database, remove file from server
	$files = array();
	$folder = $defaultDir.$username;
	foreach(scandir($folder) as $file){
		if($file !== '.' && $file !== '..'){
			$files[] = $file;
		}
	}
		
	foreach($files as $file){
		$query1 = $con->prepare("SELECT filename FROM Files WHERE username = ? && filename = ?");
		$query1->execute([$username, $file]);

		if($query1->fetch() === 0){
			unlink($userFolder.$file);
		}
	}
	*/


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
	<link rel="stylesheet" href="./css/homeStyle.css">
	

	<!--Google font-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300&family=Noto+Sans&display=swap" rel="stylesheet">


	<script src="https://kit.fontawesome.com/b48b20acd1.js"></script>
    <title>CATBOX User</title>
</head>
<body>
	
	<nav class="navbar navbar-expand-lg fixed-top">
			<div class="container-fluid">
				<!-- SYSTEM NAME -->
				<button  type="button" id="openSidebar">&#9776;</button>
				<img src="./img/logo.png" alt="logo" width="50px" height="40px" />
				
				<p class="mr-auto mb-0">CATBOX<span  id="boxName"></span>@<?php echo $_SESSION['username'] ?></p>
				
				<!-- LOGIN BUTTON -->
				<div class="d-none d-md-block col-md-2">
					<a id="logoutButton"class="btn btn-block btn-box" href="logout.php">
						<span class="pr-1">Logout</span>
						<svg class="icon-log" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 491.213 491.213" xml:space="preserve">
							<g>
								<polygon points="146.213,3.107 146.213,175.607 176.213,175.607 176.213,33.107 461.213,33.107 461.213,458.107 176.213,458.107 
									176.213,315.607 146.213,315.607 146.213,488.107 491.213,488.107 491.213,3.107 	"/>
								<polygon points="318.713,260.607 318.713,230.607 57.427,230.607 91.82,196.213 70.607,175 0,245.607 70.607,316.213 91.82,295 
									57.426,260.607 	"/>
							</g>
						</svg>
					</a>
				</div>
			</div>
	</nav>
	



	<!-- SELECT FILE MODAL -->
	<div id="uploadModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm" role="content">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row m-0 mb-2">
						<div class="alert message1 m-0 p-1 "> </div>
						<a type="button" class="close-homeModal p-2 ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
					</div>
					<div class="modal-inner">
						<!--form id="uploadForm" action="fileUpload.php" method="post" enctype="multipart/form-data" class="sm-form"-->
						<form id="uploadForm" method="post" action="" enctype="multipart/form-data" class="sm-form">
							<div class="form-row">
								<p class="mb-1 ">Select a file:<span style="font-size: 0.7em;">(Maximum up to 10MB)</span></p>
								<div class="custom-file">
									<input type="file" name="getFile" class="custom-file-input col-12" id="getFile"
										aria-describedby="inputGroupFileAddon01">
									<label class="custom-file-label long col-12" for="getFile" id="getFileName">Choose file</label>
								</div>
							</div>
							<div class="form-row mb-0">
								<button id="uploadButton" type="button" value="Upload" class="btn btn-box btn-block mt-2">UPLOAD</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<!-- vault MODAL -->
	<div id="vaultModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm" role="content">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row m-0 mb-2">
						<div class="alert  message4 p-1 m-0"> </div>
						<a type="button" class="close-homeModal p-2 ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
						
					</div>
					<div class="container modal-inner">
						<p class="mb-1">You need to get a one time password from your email to open the vault!</p>
						<div class="row">
							<input type="text" id="otpPass" class="form-control" placeholder="Enter OTP here" />
						</div>
						<div class="row mb-2">
							<button id="getOTP-btn" class="btn btn-design1 shadow-none btn-block">GET NEW OTP</button>
						</div>
						<div class="row mt-0 mb-0">
							<button id="submitOTP-btn" class="btn btn-design2 shadow-none btn-block">SUBMIT</button>
						</div>		
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- message modal -->
	<div id="messageModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm" role="content">
			<div class="modal-content">
				<div class="modal-body">
					<div class="alert alert-danger p-1 m-0">Vault will be closed if there is no activity for 20 minutes. Get a new OTP to open the vault.</div>
				</div>
			</div>
		</div>
	</div>


	<nav id="mySidebar" class="sidebar">
		<!-- LEFT CONTENT (MENU TO SWITCH FROM OWN SPACE and SHARED FILE) -->
		<div id="closeSidebar" class="mb-5">
			<i class="fas fa-arrow-left"></i>
		</div>
		<ul class="list-unstyled components">
			
			<li>
				<a id="gotoMyBox" class="d-flex align-self-end">
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
			<li>
				<a id="gotoShareBox" class="d-flex align-self-end">
					<svg class="icon-sidebar" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 268.733 268.733" style="enable-background:new 0 0 268.733 268.733;" xml:space="preserve">
						<g>
							<path d="M0,141.467v36.37c0,5.69,4.613,10.304,10.304,10.304h33.425v-39.175c0-13.63,3.309-26.493,9.135-37.864
								c-12.09-0.718-23.009-5.835-31.187-13.77C8.495,107.539,0,123.506,0,141.467z"/>
							<path d="M78.616,81.218c-5.225-8.579-8.236-18.646-8.236-29.403c0-6.551,1.13-12.839,3.183-18.697
								c-5.172-3.171-11.254-5.001-17.765-5.001c-18.8,0-34.041,15.239-34.041,34.04c0,18.8,15.241,34.041,34.041,34.041
								c2.589,0,5.107-0.299,7.531-0.847C67.813,90.029,72.951,85.282,78.616,81.218z"/>
							<path d="M171.078,150.335c5.518,0,10.918,1.226,15.834,3.515l8.482-6.204c-0.432-22.684-11.904-42.655-29.279-54.77
								c-10.175,9.679-23.919,15.639-39.037,15.639c-15.118,0-28.862-5.96-39.038-15.638c-17.712,12.35-29.312,32.86-29.312,56.091v44.552
								c0,6.971,5.651,12.622,12.622,12.622h66.796c-2.988-5.393-4.696-11.589-4.696-18.178
								C133.45,167.214,150.33,150.335,171.078,150.335z"/>
							<circle cx="127.078" cy="51.815" r="41.698"/>
							<path d="M247.104,215.36c-3.436,0-6.672,0.822-9.558,2.248l-40.529-29.645l40.234-29.431c2.957,1.518,6.301,2.391,9.852,2.391
								c11.279,0,20.53-8.636,21.529-19.652c1.163-12.944-9.064-23.603-21.529-23.603c-11.944,0-21.628,9.683-21.628,21.628
								c0,0.99,0.09,1.957,0.219,2.911l-40.359,29.521c-3.96-3.473-9.025-5.393-14.258-5.393c-11.944,0-21.628,9.683-21.628,21.628
								c0,11.944,9.684,21.628,21.628,21.628c5.273,0,10.329-1.941,14.258-5.394l40.408,29.557c-0.159,1.058-0.268,2.132-0.268,3.234
								c0,11.944,9.684,21.628,21.628,21.628c11.912,0,21.629-9.655,21.629-21.628C268.733,225.079,259.078,215.36,247.104,215.36z"/>
						</g>
					</svg>
					<span class="pl-3">Shared Files</span>
				</a>
			</li>
			<li>
				<a id="gotoVault"  class="d-flex align-self-end">
					<svg class="icon-sidebar" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 175.833 175.833"  xml:space="preserve">
						<path d="M157.417,0h-139c-7.72,0-14,6.28-14,14v139c0,7.383,5.746,13.45,13,13.965v3.869c0,2.761,2.239,5,5,5h14.167
								c2.761,0,5-2.239,5-5V167h91.167v3.833c0,2.761,2.239,5,5,5h14.167c2.761,0,5-2.239,5-5V167h0.5c7.72,0,14-6.28,14-14V14
								C171.417,6.28,165.136,0,157.417,0z M156.917,139.25c0,7.306-5.944,13.25-13.25,13.25h-113c-7.306,0-13.25-5.944-13.25-13.25V26.75
								c0-7.306,5.944-13.25,13.25-13.25h113c7.306,0,13.25,5.944,13.25,13.25V139.25z M70.461,83.25c0,6.341-5.159,11.5-11.5,11.5
								s-11.5-5.159-11.5-11.5s5.159-11.5,11.5-11.5S70.461,76.909,70.461,83.25z M143.667,23.5h-113c-1.792,0-3.25,1.458-3.25,3.25v112.5
								c0,1.792,1.458,3.25,3.25,3.25h113c1.792,0,3.25-1.458,3.25-3.25V26.75C146.917,24.958,145.459,23.5,143.667,23.5z M58.961,104.75
								c-11.855,0-21.5-9.645-21.5-21.5s9.645-21.5,21.5-21.5s21.5,9.645,21.5,21.5S70.816,104.75,58.961,104.75z M98.596,104.833h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S101.357,104.833,98.596,104.833z M98.596,88.5h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S101.357,88.5,98.596,88.5z M98.596,72.167h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S101.357,72.167,98.596,72.167z M115.596,104.833h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S118.357,104.833,115.596,104.833z M115.596,88.5h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S118.357,88.5,115.596,88.5z M115.596,72.167h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S118.357,72.167,115.596,72.167z M132.596,104.833h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S135.357,104.833,132.596,104.833z M132.596,88.5h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S135.357,88.5,132.596,88.5z M132.596,72.167h-2.429
								c-2.761,0-5-2.239-5-5s2.239-5,5-5h2.429c2.761,0,5,2.239,5,5S135.357,72.167,132.596,72.167z"/>



					</svg>
					<span class="pl-3">Vault</span>
				</a>
			</li>
			<li>
				<a id="gotoRecycleBin" class="d-flex align-self-end">
					<svg class="icon-sidebar"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
						<g>
							<g>
								<path d="M99.534,96.697l12.126,389.173C112.122,500.703,124.107,512,138.948,512h234.106c14.839,0,26.826-11.297,27.287-26.131
									l12.126-389.172H99.534z M224.985,336.879c-0.485,6.515-5.921,11.478-12.35,11.478c-0.31,0-0.62-0.011-0.932-0.035l-39.996-2.975
									c-5.601-0.415-10.815-2.891-14.681-6.97c-6.595-6.957-8.063-17.353-3.651-25.867l16.968-32.733l-1.871,0.832
									c-6.254,2.781-13.583-0.032-16.365-6.289c-2.783-6.256,0.032-13.582,6.289-16.365l34.297-15.256
									c3.439-1.531,7.387-1.414,10.733,0.315c3.345,1.731,5.721,4.886,6.46,8.578l7.368,36.806c1.344,6.713-3.009,13.245-9.722,14.59
									c-0.821,0.164-1.64,0.243-2.447,0.243c-5.787,0-10.963-4.074-12.143-9.966l-0.464-2.318l-15.513,29.925l36.575,2.72
									C220.369,324.105,225.493,330.05,224.985,336.879z M222.343,216.834c-5.571-3.981-6.859-11.724-2.879-17.294l23.318-32.632
									c3.265-4.57,8.087-7.738,13.579-8.925c9.375-2.021,19.02,2.122,24.002,10.316l19.096,31.401l0.248-1.93
									c0.872-6.792,7.092-11.593,13.874-10.719c6.791,0.872,11.59,7.084,10.719,13.874l-4.779,37.231
									c-0.48,3.735-2.629,7.049-5.845,9.009c-1.967,1.199-4.203,1.81-6.451,1.81c-1.427,0-2.86-0.247-4.234-0.746l-35.278-12.821
									c-6.435-2.338-9.756-9.452-7.417-15.886c2.338-6.435,9.457-9.755,15.886-7.417l2.317,0.842l-17.538-28.832l-21.323,29.841
									C235.656,219.526,227.913,220.813,222.343,216.834z M359.925,329.587c-3.123,9.067-11.663,15.173-21.252,15.194l-36.866,0.083
									l1.602,1.273c5.359,4.262,6.249,12.06,1.989,17.419c-2.447,3.078-6.062,4.682-9.711,4.682c-2.705,0-5.426-0.88-7.707-2.694
									l-29.38-23.362c-2.947-2.343-4.67-5.898-4.681-9.665c-0.011-3.765,1.688-7.332,4.62-9.693l29.234-23.547
									c5.332-4.296,13.137-3.455,17.432,1.878c4.296,5.332,3.455,13.136-1.878,17.432l-1.842,1.484l33.708-0.076l-14.492-33.692
									c-2.706-6.29,0.2-13.582,6.489-16.287c6.29-2.708,13.582,0.2,16.287,6.489l15.848,36.843
									C361.539,318.506,361.755,324.275,359.925,329.587z"/>
							</g>
						</g>
						<g>
							<g>
								<path d="M407.667,23.555h-87.859c0.415-1.24,0.657-3.221,0.657-4.896C320.465,8.389,312.139,0,301.869,0h-91.739
									c-10.27,0-18.596,8.389-18.596,18.659c0,1.675,0.242,3.656,0.657,4.896h-87.859c-18.022,0-33.049,14.536-33.049,32.557v15.791
									h369.433V56.112C440.717,38.09,425.689,23.555,407.667,23.555z"/>
							</g>
						</g>
					</svg>
					<span class="pl-3">Recycle Bin</span>
				</a>
			</li>
			<!--li>
				<a href="./user.php" class="d-flex align-self-end">
					<svg class="icon-sidebar" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 45.532 45.532" xml:space="preserve">
						<g>
							<path d="M22.766,0.001C10.194,0.001,0,10.193,0,22.766s10.193,22.765,22.766,22.765c12.574,0,22.766-10.192,22.766-22.765
								S35.34,0.001,22.766,0.001z M22.766,6.808c4.16,0,7.531,3.372,7.531,7.53c0,4.159-3.371,7.53-7.531,7.53
								c-4.158,0-7.529-3.371-7.529-7.53C15.237,10.18,18.608,6.808,22.766,6.808z M22.761,39.579c-4.149,0-7.949-1.511-10.88-4.012
								c-0.714-0.609-1.126-1.502-1.126-2.439c0-4.217,3.413-7.592,7.631-7.592h8.762c4.219,0,7.619,3.375,7.619,7.592
								c0,0.938-0.41,1.829-1.125,2.438C30.712,38.068,26.911,39.579,22.761,39.579z"/>
						</g>
					</svg>
					<span class="pl-3">User Profile</span>
				</a>
			</li -->
			<li class="d-block d-md-none">
				<a id="logoutButton" class="d-flex align-self-end" href="logout.php">
					<svg class="icon-sidebar" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 491.213 491.213" xml:space="preserve">
						<g>
							<polygon points="146.213,3.107 146.213,175.607 176.213,175.607 176.213,33.107 461.213,33.107 461.213,458.107 176.213,458.107 
								176.213,315.607 146.213,315.607 146.213,488.107 491.213,488.107 491.213,3.107 	"/>
							<polygon points="318.713,260.607 318.713,230.607 57.427,230.607 91.82,196.213 70.607,175 0,245.607 70.607,316.213 91.82,295 
								57.426,260.607 	"/>
						</g>
					</svg>
					<span class="pl-3">Logout</span>
				</a>
			</li>
		</ul>
		<div></div>
		
	</nav>	


	<!--  MAIN CONTAINER -->
	<div id="main">
	
		<!-- Scrollable DIV -->
		<div class=" container-fluid m-0 mr-1 mt-n3 p-0 w-100" id="myBox">
			
			<div class="row mb-0">
				<!-- MIDDLE CONTENT  -->
				<div class="middle col-12 col-md-8 m-0 pr-0">
					<div class="container-md" id="mainContainer">


						<!-- PREVIEW MODAL -->
						<div id="previewModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-md" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<a type="button" class="close-homeModal float-right" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
									
										<div class="container" >
											<img id="previewImg" src="" width="100%" height="100%"/>
											<video id="previewVideo" width="100%" height="100%" controls>
												Your browser does not support the video tag.
											</video>
											<audio id="previewAudio" width="100%" height="100%" controls>
												Your browser does not support the video tag.
											</audio>
											<iframe id="previewFrame" src="" width="100%" height="350px"></iframe>
										</div>
									
									</div>
								</div>
							</div>
						</div>

						<!--  SHARE MODAL -->
						<div id="shareModal" class="modal fade"  role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row m-0 mb-2">
											<div class="alert message2 p-1 m-0"></div>
											<a type="button" class="close-homeModal p-2 ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
										</div>
										<div class="container modal-inner" id="shareDiv">
											<div class="row mt-0 mb-0">
												Filename: <strong><p id="tobeShared" class="name"></p></strong>
											</div>
											<div class="row mt-0">
												<p class="mb-1">Share with (Username): </p>
												<input type="text" id="checkUser" class="form-control" />
											</div>
											<div class="row mt-0 mb-0">
												<button class="btn btn-box btn-block" id="share-btn">SHARE</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- MOVE TO RECYCLE BIN MODAL -->
						<div id="recyclebinModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row m-0">
											<a type="button" class="close-homeModal ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
										</div>
										<div class="container modal-inner">
												<div class="d-block row mt-0 mb-0">
													Filename: <strong><p id="tobeMoved" class="name"></p></strong>
													<p class="m-0">Are you sure you want to delete this?<br><br></p>
												</div>
										
											<div class="row mt-0 mb-0">
												<button class="btn btn-box btn-block" id="movetobin-btn">YES</button>
												<button class="btn btn-secondary btn-block"  data-dismiss="modal" >NO</button>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- DELETE PERMANENTLY CONFIRMATION MODAL -->
						<div id="deleteModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row m-0">
											<a type="button" class="close-homeModal ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
										</div>
										<div class="container modal-inner">
												<div class="d-block row mt-0 mb-0">
													Filename: <strong><p id="tobeDeleted" class="name"></p></strong>
													<p class="m-0">Are you sure you want to <strong>permanently</strong> delete this?<br><br></p>
												</div>
										
											<div class="row mt-0 mb-0">
												<button class="btn btn-box btn-block" id="delete-btn">YES</button>
												<button class="btn btn-secondary btn-block"  data-dismiss="modal" >NO</button>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- EMPTY BIN CONFIRMATION MODAL -->
						<div id="emptybinModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row m-0">
											<a type="button" class="close-homeModal ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
										</div>
										<div class="container modal-inner">
												<div class="d-block row mt-0 mb-0">
													<p class="m-0">All items in the bin will be <strong>deleted forever</strong> and you won't be able to restore back. Continue to delete them?<br><br></p>
												</div>
										
											<div class="row mt-0 mb-0">
												<button class="btn btn-box btn-block" id="emptybin-btn">YES</button>
												<button class="btn btn-secondary btn-block"  data-dismiss="modal" >NO</button>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- ADD TO VAULT CONFIRMATION MODAL-->
						<div id="addtovaultModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row m-0 mb-2">
											<div class="alert alert-success message5 p-1 m-0">Successfully added!</div>
											<a type="button" class="close-homeModal p-2 ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
										</div>
										<div class="container modal-inner">
												<div class="d-block row mt-0 mb-0">
													Filename: <strong><p id="tobeAddedToVault" class="name"></p></strong>
													<p class="m-0">Add this file into vault?<br><br></p>
												</div>
										
											<div class="row mt-0 mb-0">
												<button class="btn btn-box btn-block" id="addtovault-btn">YES</button>
												<button class="btn btn-secondary btn-block"  data-dismiss="modal" >NO</button>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- REMOVE FROM VAULT CONFIRMATION MODAL -->
						<div id="removefromvaultModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row m-0">
											<a type="button" class="close-homeModal ml-auto" data-dismiss="modal"><i class="far fa-times-circle"></i></a>
										</div>
										<div class="container modal-inner">
												<div class="d-block row mt-0 mb-0">
													Filename: <strong><p id="tobeRemoved" class="name"></p></strong>
													<p class="m-0">Remove this file from vault?<br><br></p>
												</div>
										
											<div class="row mt-0 mb-0">
												<button class="btn btn-box btn-block" id="removefromvault-btn">YES</button>
												<button class="btn btn-secondary btn-block"  data-dismiss="modal" >NO</button>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- FUNCTION BUTTONS -->
						<div class="row" id="functionButtons">
							<div  id="buttonrow" class="container m-0 w-50 d-flex justify-content-start justify-content-md-between">
								<a class="btn button btn-function rounded-circle d-flex justify-content-center p-1 pl-2 pt-2  " id="addButton" data-toggle="tooltip" data-placement="bottom" title="Add File">
									<svg class="icon-function" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="-45 0 530 530">
										<g id="surface1">
										<path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.972656 347.664062 119.410156 345.851562 117.382812 L 237.21875 3.308594 
											C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.195312 0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 
											440.71875 57.195312 440.71875 L 193.148438 440.71875 C 218.863281 483.402344 265.605469 512 318.851562 512 C 399.738281 512 465.792969 446.265625 
											465.792969 365.273438 C 465.902344 294.523438 415.105469 235.40625 348.945312 221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 
											265.710938 114.179688 C 251.625 114.179688 240.101562 102.550781 240.101562 88.464844 Z M 57.195312 419.375 C 37.242188 419.375 21.34375 403.367188 
											21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.195312 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 
											218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 L 327.601562 135.523438 L 327.601562 218.863281 C 324.402344 218.757812 321.839844 
											218.4375 319.066406 218.4375 C 281.824219 218.4375 247.570312 232.738281 221.746094 255.148438 L 86.222656 255.148438 C 80.351562 255.148438 
											75.550781 259.949219 75.550781 265.816406 C 75.550781 271.6875 80.351562 276.488281 86.222656 276.488281 L 201.898438 276.488281 C 194.320312 
											287.160156 188.023438 297.832031 183.117188 309.570312 L 86.222656 309.570312 C 80.351562 309.570312 75.550781 314.371094 75.550781 320.242188 C 
											75.550781 326.109375 80.351562 330.914062 86.222656 330.914062 L 176.179688 330.914062 C 173.511719 341.585938 172.125 353.429688 172.125 365.273438 
											C 172.125 384.480469 175.859375 403.476562 182.582031 419.484375 L 57.195312 419.484375 Z M 318.960938 490.765625 C 249.8125 490.765625 193.574219 
											434.527344 193.574219 365.378906 C 193.574219 296.230469 249.703125 239.992188 318.960938 239.992188 C 388.214844 239.992188 444.34375 296.230469 
											444.34375 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625 " 
											/>
										<path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 C 204.992188 206.484375 
											200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.351562 201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 
											218.222656 80.351562 223.027344 86.222656 223.027344 Z M 86.222656 223.027344 " 
											/>
										<path d="M 362.390625 355.347656 L 329.738281 355.347656 L 329.738281 322.160156 C 329.738281 316.292969 324.933594 311.488281 319.066406 311.488281 
											C 313.195312 311.488281 308.394531 316.292969 308.394531 322.160156 L 308.394531 355.347656 L 275.207031 355.347656 C 269.335938 355.347656 264.535156 
											360.152344 264.535156 366.019531 C 264.535156 371.890625 269.335938 376.691406 275.207031 376.691406 L 308.394531 376.691406 L 308.394531 409.34375 C 
											308.394531 415.214844 313.195312 420.015625 319.066406 420.015625 C 324.933594 420.015625 329.738281 415.214844 329.738281 409.34375 L 329.738281 
											376.691406 L 362.390625 376.691406 C 368.261719 376.691406 373.0625 371.890625 373.0625 366.019531 C 373.0625 360.152344 368.261719 355.347656 362.390625 
											355.347656 Z M 362.390625 355.347656 "  />
										</g>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pl-2 pt-2" id="removeButton" data-toggle="tooltip" data-placement="bottom" title="Remove File">
									<svg class="icon-function" viewBox="-45 0 530 530" xmlns="http://www.w3.org/2000/svg">
										<path d="m232.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 
												10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/>
										<path d="m114.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 
												10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/>
										<path d="m28.398438 127.121094v246.378906c0 14.5625 5.339843 28.238281 14.667968 38.050781 9.285156 9.839844 
												22.207032 15.425781 35.730469 15.449219h189.203125c13.527344-.023438 26.449219-5.609375 35.730469-15.449219 
												9.328125-9.8125 14.667969-23.488281 14.667969-38.050781v-246.378906c18.542968-4.921875 30.558593-22.835938 
												28.078124-41.863282-2.484374-19.023437-18.691406-33.253906-37.878906-33.257812h-51.199218v-12.5c.058593-10.511719-4.097657-20.605469-11.539063-28.03125-7.441406-7.421875-17.550781-11.5546875-28.0625-11.46875h-88.796875c-10.511719-.0859375-20.621094 
												4.046875-28.0625 11.46875-7.441406 7.425781-11.597656 17.519531-11.539062 28.03125v12.5h-51.199219c-19.1875.003906-35.394531 
												14.234375-37.878907 33.257812-2.480468 19.027344 9.535157 36.941407 28.078126 41.863282zm239.601562 279.878906h-189.203125c-17.097656 
												0-30.398437-14.6875-30.398437-33.5v-245.5h250v245.5c0 18.8125-13.300782 33.5-30.398438 33.5zm-158.601562-367.5c-.066407-5.207031 
												1.980468-10.21875 5.675781-13.894531 3.691406-3.675781 8.714843-5.695313 13.925781-5.605469h88.796875c5.210937-.089844 
												10.234375 1.929688 13.925781 5.605469 3.695313 3.671875 5.742188 8.6875 5.675782 13.894531v12.5h-128zm-71.199219 32.5h270.398437c9.941406 
												0 18 8.058594 18 18s-8.058594 18-18 18h-270.398437c-9.941407 0-18-8.058594-18-18s8.058593-18 18-18zm0 0"/>
										<path d="m173.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 
												10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pl-2 pt-2 " id="shareButton" data-toggle="tooltip" data-placement="bottom" title="Share File">
									<svg class="icon-function" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="-45 0 530 530">
										<g id="surface1">
											<path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.972656 347.664062 119.410156 
													345.851562 117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.195312 
													0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.195312 
													440.71875 L 193.148438 440.71875 C 218.863281 483.402344 265.605469 512 318.851562 512 C 399.738281 512 
													465.792969 446.265625 465.792969 365.273438 C 465.902344 294.523438 415.105469 235.40625 348.945312 
													221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 
													240.101562 102.550781 240.101562 88.464844 Z M 57.195312 419.375 C 37.242188 419.375 21.34375 403.367188 
													21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.195312 21.34375 L 
													218.757812 21.34375 L 218.757812 88.464844 C 218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 
													L 327.601562 135.523438 L 327.601562 218.863281 C 324.402344 218.757812 321.839844 218.4375 319.066406 218.4375 
													C 281.824219 218.4375 247.570312 232.738281 221.746094 255.148438 L 86.222656 255.148438 C 80.351562 255.148438 
													75.550781 259.949219 75.550781 265.816406 C 75.550781 271.6875 80.351562 276.488281 86.222656 276.488281 
													L 201.898438 276.488281 C 194.320312 287.160156 188.023438 297.832031 183.117188 309.570312 L 86.222656 309.570312 
													C 80.351562 309.570312 75.550781 314.371094 75.550781 320.242188 C 75.550781 326.109375 80.351562 330.914062 
													86.222656 330.914062 L 176.179688 330.914062 C 173.511719 341.585938 172.125 353.429688 172.125 365.273438 C 
													172.125 384.480469 175.859375 403.476562 182.582031 419.484375 L 57.195312 419.484375 Z M 318.960938 490.765625 
													C 249.8125 490.765625 193.574219 434.527344 193.574219 365.378906 C 193.574219 296.230469 249.703125 239.992188 
													318.960938 239.992188 C 388.214844 239.992188 444.34375 296.230469 444.34375 365.378906 C 444.34375 434.527344 
													388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625 "/>
											<path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 
													212.355469 C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.351562 
													201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.351562 223.027344 86.222656 223.027344 
													Z M 86.222656 223.027344 " />
											<path d="M 327.28125 346.8125 L 310.207031 346.8125 C 276.808594 346.8125 249.703125 373.597656 249.703125 406.996094 L 
													249.703125 456.296875 C 249.703125 462.167969 254.503906 467.394531 260.375 467.394531 L 377.117188 467.394531 C 
													382.984375 467.394531 387.363281 462.273438 387.363281 456.296875 L 387.363281 406.996094 C 387.363281 373.597656 
													360.683594 346.8125 327.28125 346.8125 Z M 366.019531 446.050781 L 271.046875 446.050781 L 271.046875 406.996094 C 
													271.046875 385.441406 288.652344 368.152344 310.210938 368.152344 L 327.285156 368.152344 C 348.839844 368.152344 
													366.019531 385.335938 366.019531 406.996094 Z M 366.019531 446.050781 "/>
											<path d="M 318.746094 344.890625 C 343.289062 344.890625 363.246094 324.933594 363.246094 300.390625 C 363.246094 275.847656 
													343.289062 255.894531 318.746094 255.894531 C 294.203125 255.894531 274.246094 275.847656 274.246094 300.390625 C 
													274.246094 324.933594 294.203125 344.890625 318.746094 344.890625 Z M 318.746094 277.234375 C 331.550781 277.234375 
													341.902344 287.585938 341.902344 300.390625 C 341.902344 313.199219 331.550781 323.550781 318.746094 323.550781 C 
													305.941406 323.550781 295.589844 313.199219 295.589844 300.390625 C 295.589844 287.585938 306.046875 277.234375 
													318.746094 277.234375 Z M 318.746094 277.234375 "/>
										</g>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pl-2 pt-2" id="downloadButton" data-toggle="tooltip" data-placement="bottom" title="Download File">
									<svg class="icon-function" xmlns="http://www.w3.org/2000/svg"  version="1.1" viewBox="-45 0 530 530">
										<g>
											<path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.972656 347.664062 119.410156 345.851562 
													117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.195312 0 C 25.398438 
													0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.195312 440.71875 L 193.148438 
													440.71875 C 218.863281 483.402344 265.605469 512 318.851562 512 C 399.738281 512 465.792969 446.265625 465.792969 
													365.273438 C 465.902344 294.523438 415.105469 235.40625 348.945312 221.640625 Z M 240.101562 37.457031 L 
													312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 240.101562 102.550781 240.101562 88.464844 
													Z M 57.195312 419.375 C 37.242188 419.375 21.34375 403.367188 21.34375 383.414062 L 21.34375 57.730469 C 21.34375 
													37.667969 37.242188 21.34375 57.195312 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 218.757812 
													114.394531 239.78125 135.523438 265.710938 135.523438 L 327.601562 135.523438 L 327.601562 218.863281 C 324.402344 
													218.757812 321.839844 218.4375 319.066406 218.4375 C 281.824219 218.4375 247.570312 232.738281 221.746094 
													255.148438 L 86.222656 255.148438 C 80.351562 255.148438 75.550781 259.949219 75.550781 265.816406 C 75.550781 
													271.6875 80.351562 276.488281 86.222656 276.488281 L 201.898438 276.488281 C 194.320312 287.160156 188.023438 
													297.832031 183.117188 309.570312 L 86.222656 309.570312 C 80.351562 309.570312 75.550781 314.371094 75.550781 
													320.242188 C 75.550781 326.109375 80.351562 330.914062 86.222656 330.914062 L 176.179688 330.914062 C 173.511719 
													341.585938 172.125 353.429688 172.125 365.273438 C 172.125 384.480469 175.859375 403.476562 182.582031 419.484375 
													L 57.195312 419.484375 Z M 318.960938 490.765625 C 249.8125 490.765625 193.574219 434.527344 193.574219 365.378906 
													C 193.574219 296.230469 249.703125 239.992188 318.960938 239.992188 C 388.214844 239.992188 444.34375 296.230469 
													444.34375 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625 "/>
											<path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 
													C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.351562 201.683594 
													75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.351562 223.027344 86.222656 223.027344 Z M 
													86.222656 223.027344 " />
											<path d="M 373.59375 363.136719 L 329.738281 410.410156 L 329.738281 293.882812 C 329.738281 288.011719 324.933594 283.210938 
													319.066406 283.210938 C 313.195312 283.210938 308.394531 288.011719 308.394531 293.882812 L 308.394531 410.410156 L 
													264.214844 363.136719 C 260.160156 358.871094 253.332031 358.550781 249.0625 362.605469 C 244.792969 366.660156 
													244.472656 373.382812 248.53125 377.652344 L 310.957031 444.773438 C 312.984375 446.90625 315.757812 448.1875 
													318.746094 448.1875 C 321.734375 448.1875 324.507812 446.90625 326.535156 444.773438 L 389.070312 377.652344 C 393.125 
													373.382812 392.910156 366.554688 388.640625 362.605469 C 384.265625 358.550781 377.652344 358.871094 373.59375 
													363.136719 Z M 373.59375 363.136719 "/>
										</g>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pt-2 pr-2" id="previewButton" data-toggle="tooltip" data-placement="bottom" title="Preview  File">
								
									<svg class="icon-function" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-45 0 550 550" xml:space="preserve">
										<g>
											<g>
												<path d="M510.096,249.937c-4.032-5.867-100.928-143.275-254.101-143.275C124.56,106.662,7.44,243.281,2.512,249.105
													c-3.349,3.968-3.349,9.792,0,13.781C7.44,268.71,124.56,405.329,255.995,405.329S504.549,268.71,509.477,262.886
													C512.571,259.217,512.848,253.905,510.096,249.937z M255.995,383.996c-105.365,0-205.547-100.48-230.997-128
													c25.408-27.541,125.483-128,230.997-128c123.285,0,210.304,100.331,231.552,127.424
													C463.013,282.065,362.256,383.996,255.995,383.996z"/>
											</g>
										</g>
										<g>
										<g>
											<path d="M255.995,170.662c-47.061,0-85.333,38.272-85.333,85.333s38.272,85.333,85.333,85.333s85.333-38.272,85.333-85.333
												S303.056,170.662,255.995,170.662z M255.995,319.996c-35.285,0-64-28.715-64-64s28.715-64,64-64s64,28.715,64,64
												S291.28,319.996,255.995,319.996z"/>
										</g>

									</svg>

								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pr-2  " id="addToVaultButton"  data-toggle="tooltip" data-placement="bottom" title="Save To Vault">
									<svg  class="icon-function" version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-45 0 550 550" xml:space="preserve">
										<g>
											<g>
												<path d="M548.904,35.373v54.474h-27.03v-1.572c0-10.013-8.146-18.158-18.158-18.158H164.771c-10.01,0-18.158,8.146-18.158,18.158
													v11.945c-2.069,0.94-4.079,2.021-5.914,3.402c-11.904,8.987-16.385,24.956-10.9,38.804l15.082,38.192h-27.73V35.373H548.904z
													M521.874,378.031v21.953c0,10.013-8.146,18.158-18.158,18.158H164.771c-10.01,0-18.158-8.146-18.158-18.158v-11.869
													c-2.01-0.939-3.945-2.021-5.769-3.369c-12.055-9.078-16.547-25.073-11.015-38.969l15.061-38.143h-27.733V513.53h112.978
													l8.473-46.399h188.834l8.464,46.399h112.979V371.293h-15.971C530.231,374.733,526.354,377.216,521.874,378.031z M534.789,275.87
													c2.068,0,5.071-4.185,7.441-12.114l-7.459-24.84l-7.442,24.787C529.7,271.667,532.702,275.87,534.789,275.87z M545.877,234.504
													c0-9.963-0.981-18.152-2.395-24.568l-7.134,23.72l7.317,24.364C544.96,251.76,545.877,243.97,545.877,234.504z M523.688,234.504
													c0,9.437,0.904,17.203,2.205,23.454l7.294-24.302l-7.105-23.658C524.664,216.408,523.688,224.57,523.688,234.504z
													M541.982,204.374c-2.329-7.382-5.201-11.228-7.193-11.228c-2.01,0-4.889,3.869-7.223,11.284l7.193,23.98L541.982,204.374z
													M503.491,97.714c-0.083-10.021-8.233-18.158-18.275-18.158h-307.31c-9.859,0-17.859,7.87-18.217,17.656
													c4.079-0.119,8.183,0.396,12.031,1.685v-1.029c0-3.422,2.778-6.206,6.18-6.206h307.303c3.369,0,6.077,2.707,6.171,6.053h-33.266
													v16.645h-18.453v242.104h18.453v10.593h33.29v26.604c0,3.422-2.761,6.206-6.183,6.206h-307.31c-3.408,0-6.18-2.784-6.18-6.206
													v-4.191c-3.523,1.229-7.232,1.886-10.973,1.886c-0.387,0-0.757-0.077-1.138-0.089v2.395c0,10.107,8.201,18.312,18.285,18.312
													h307.303c10.084,0,18.288-8.204,18.288-18.312v-26.604h1.005v4.237l44.39-16.951v-92.081c-2.802,11.476-7.53,19.662-14.114,19.662
													c-11.267,0-17.153-23.85-17.153-47.413c0-23.563,5.887-47.416,17.153-47.416c6.584,0,11.312,8.183,14.114,19.659v-92.084
													l-44.378-16.955H503.491z M407.683,167.514H263.907l15.75,12.105h128.026V167.514z M407.683,251.344h-66.266
													c-0.98,4.362-2.872,8.467-5.438,12.105h71.703V251.344z M227.036,349.099h180.647v-12.105H242.785L227.036,349.099z
													M150.893,354.176c-1.005,2.53-0.186,5.414,1.986,7.046c1.09,0.815,2.355,1.218,3.632,1.218c1.3,0,2.601-0.414,3.686-1.254
													L308.4,247.25c1.502-1.149,2.364-2.923,2.364-4.799s-0.862-3.656-2.364-4.799L160.197,123.73c-2.148-1.664-5.157-1.679-7.317-0.05
													c-2.172,1.641-2.991,4.528-1.986,7.058l29.802,75.433H6.041C2.695,206.17,0,208.89,0,212.223v60.456
													c0,3.345,2.695,6.052,6.041,6.052h174.654L150.893,354.176z"/>
											</g>
										</g>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pr-2 " id="removeFromVaultButton"  data-toggle="tooltip" data-placement="bottom" title="Remove From Vault">
									<svg version="1.1" class="icon-function" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-45 0 550 550" xml:space="preserve">
										<g>
											<g>
												<path d="M467,46H341.154l-46.437-27.862c-2.191-1.315-4.874-2.121-7.645-2.134c-0.025,0-0.048-0.004-0.073-0.004h-60
													c-8.284,0-15,6.716-15,15v15H45C20.187,46,0,66.187,0,91v300c0,19.555,12.541,36.228,30,42.42V481c0,8.284,6.716,15,15,15h60
													c8.284,0,15-6.716,15-15v-45h92v15c0,8.284,6.716,15,15,15h60h0.001c2.782,0,5.494-0.807,7.716-2.138L341.154,436H392v45
													c0,8.284,6.716,15,15,15h60c8.284,0,15-6.716,15-15v-47.58c17.459-6.192,30-22.865,30-42.42V91C512,66.187,491.813,46,467,46z
													M90,466H60v-30h30V466z M212,346H90V136h122V346z M212,106H75c-8.284,0-15,6.716-15,15v240c0,8.284,6.716,15,15,15h137v30
													c-19.158,0-150.148,0-167,0c-8.271,0-15-6.729-15-15V91c0-8.271,6.729-15,15-15h167V106z M272,436h-30c0-3.888,0-385.912,0-390h30
													V436z M302,424.508V57.493l120,72v223.015L302,424.508z M452,466h-30v-30h30V466z M482,391c0,8.271-6.729,15-15,15
													c-7.553,0-68.176,0-75.845,0l53.563-32.138C449.235,371.151,452,366.269,452,361V121c0-5.269-2.765-10.151-7.283-12.862
													L391.155,76H467c8.271,0,15,6.729,15,15V391z"/>
											</g>
										</g>
										<g>
											<g>
												<path d="M347,166c-8.284,0-15,6.716-15,15s6.716,15,15,15c8.271,0,15,6.729,15,15v60c0,8.271-6.729,15-15,15
													c-8.284,0-15,6.716-15,15s6.716,15,15,15c24.813,0,45-20.187,45-45v-60C392,186.187,371.813,166,347,166z"/>
											</g>
										</g>

									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-flex justify-content-center p-2 " id="closeVaultButton"  data-toggle="tooltip" data-placement="bottom" title="Close Vault">
									<svg class="icon-function" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" xml:space="preserve">
										<g>
											<path d="M42,26A12,12,0,1,0,30,38,12.013,12.013,0,0,0,42,26Zm-6.166,8.1-1.468-2.543-1.732,1,1.47,2.547a9.9,9.9,0,0,1-3.1.84V33H29v2.949a9.9,9.9,0,0,1-3.1-.84l1.47-2.547-1.732-1L24.166,
													34.1A10.07,10.07,0,0,1,21.9,31.834l2.542-1.468-1-1.732L20.891,30.1a9.9,9.9,0,0,1-.84-3.1H23V25H20.051a9.9,9.9,0,0,1,.84-3.1l2.546,1.47,1-1.732L21.9,20.166A10.07,10.07,0,0,1,24.166,
													17.9l1.468,2.543,1.732-1L25.9,16.891a9.9,9.9,0,0,1,3.1-.84V19h2V16.051a9.9,9.9,0,0,1,3.1.84l-1.47,2.547,1.732,1L35.834,17.9A10.07,10.07,0,0,1,38.1,20.166l-2.543,1.468,1,1.732,2.547-1.47a9.9,
													9.9,0,0,1,.84,3.1H37v2h2.949a9.9,9.9,0,0,1-.84,3.1l-2.547-1.47-1,1.732L38.1,31.834A10.07,10.07,0,0,1,35.834,34.1Z"/>
											<path d="M30,23a3,3,0,1,0,3,3A3,3,0,0,0,30,23Zm0,4a1,1,0,1,1,1-1A1,1,0,0,1,30,27Z"/>
											<path d="M34,42V40H20a1,1,0,0,0-1,1v4a1,1,0,0,0,1,1H34V44H21V42Z"/>
											<path d="M59,40H58V17H56V31.356A8.993,8.993,0,0,0,40,37v3H39a3,3,0,0,0-3,3v5H13a1,1,0,0,1-1-1V44h3a1,1,0,0,0,1-1V39a1,1,0,0,0-1-1H12V22h3a1,1,0,0,0,1-1V17a1,1,0,0,0-1-1H12V13a1,1,0,0,1,1-1H37V10H13a3,
													3,0,0,0-3,3v3H7a1,1,0,0,0-1,1v4a1,1,0,0,0,1,1h3V38H7a1,1,0,0,0-1,1v4a1,1,0,0,0,1,1h3v3a3,3,0,0,0,3,3H36v6H5a1,1,0,0,1-1-1V5A1,1,0,0,1,5,4H55a1,1,0,0,1,1,1V6h2V5a3,3,0,0,0-3-3H5A3,3,0,0,0,2,5V55a3,
													3,0,0,0,3,3H8v3a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V58H36v1a3,3,0,0,0,3,3H59a3,3,0,0,0,3-3V43A3,3,0,0,0,59,40ZM8,18h6v2H8ZM8,40h6v2H8Zm8,20H10V58h6ZM42,37a7,7,0,0,1,14,0v3H54V37a5,5,0,0,0-10,0v3H42Zm10,
													3H46V37a3,3,0,0,1,6,0Zm8,19a1,1,0,0,1-1,1H39a1,1,0,0,1-1-1V43a1,1,0,0,1,1-1H59a1,1,0,0,1,1,1Z"/>
											<path d="M49,44a5,5,0,0,0-1,9.9V58h2V53.9A5,5,0,0,0,49,44Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,49,52Z"/>
											<path d="M59.606,10.392a15,15,0,0,0-21.212,0l1.412,1.417a13,13,0,0,1,18.388,0Z"/>
											<path d="M56.777,13.223a10.994,10.994,0,0,0-15.554,0l1.414,1.414a9,9,0,0,1,12.726,0Z"/>
											<path d="M53.95,16.056a6.987,6.987,0,0,0-9.9,0l1.42,1.408a4.988,4.988,0,0,1,7.06,0Z"/>
											<path d="M46,21a3,3,0,1,0,3-3A3,3,0,0,0,46,21Zm4,0a1,1,0,1,1-1-1A1,1,0,0,1,50,21Z"/>
										</g>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-flex justify-content-center p-1 pr-2 pt-2" id="emptybinButton" data-toggle="tooltip" data-placement="bottom" title="Empty Bin">
									<svg class="icon-function" viewBox="-45 0 530 530"  xmlns="http://www.w3.org/2000/svg">
										<g>
											<path d="m415.157 86.566-93.9 26.865c-.104.034-.208.068-.312.104-10.041 3.493-16.788 12.982-16.788 23.614v27.223h-92.79l106.051-106.05c5.858-5.858 5.858-15.355
													0-21.213l-26.872-26.873c-13.647-13.648-35.853-13.648-49.5 0l-27.631 27.631-14.144-14.144c-15.596-15.597-40.974-15.597-56.572 0l-55.159 55.159c-15.596 
													15.597-15.596 40.975 0 56.572l14.144 14.144-27.631 27.631c-13.647 13.647-13.647 35.853 0 49.5l26.872 26.872c5.857 5.858 15.356 5.859 21.214 0l38.022-38.022v231.417c0
													35.843 29.161 65.004 65.004 65.004h158.012c35.843 0 65.004-29.161 65.004-65.004 0-10.566 0-331.798 0-336.751.001-17.08-16.816-29.171-33.024-23.679zm-306.403 13.529 
													55.158-55.158c3.899-3.899 10.244-3.9 14.145 0l14.143 14.144-69.302 69.302-14.144-14.143c-3.899-3.9-3.899-10.245 0-14.145zm2.779 121.685-16.266-16.265c-1.95-1.95-1.95-5.123
													0-7.073 10.495-10.495 161.616-161.615 166.993-166.992 1.951-1.95 5.122-1.95 7.073 0l16.265 16.266zm306.648 225.216c0 19.301-15.703 35.003-35.003 35.003h-158.012c-19.301 
													0-35.003-15.703-35.003-35.003v-252.622h228.018zm0-281.816-84.022-.807v-23.583l84.022-23.67z"/>
											<path d="m237.431 442.995c8.284 0 15-6.716 15-15v-179.617c0-8.284-6.716-15-15-15s-15 6.716-15 15v179.616c-.001 8.285 6.715 15.001 15 15.001z"/>
											<path d="m304.172 442.995c8.284 0 15-6.716 15-15v-179.617c0-8.284-6.716-15-15-15s-15 6.716-15 15v179.616c-.001 8.285 6.715 15.001 15 15.001z"/>
											<path d="m370.912 442.995c8.284 0 15-6.716 15-15v-179.617c0-8.284-6.716-15-15-15s-15 6.716-15 15v179.616c0 8.285 6.716 15.001 15 15.001z"/>
										</g>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pl-2 pt-2" id="delButton" data-toggle="tooltip" data-placement="bottom" title="Delete File">
									<svg class="icon-function" viewBox="-45 0 530 530" xmlns="http://www.w3.org/2000/svg">
										<path d="m232.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 
												10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/>
										<path d="m114.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 
												10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/>
										<path d="m28.398438 127.121094v246.378906c0 14.5625 5.339843 28.238281 14.667968 38.050781 9.285156 9.839844 
												22.207032 15.425781 35.730469 15.449219h189.203125c13.527344-.023438 26.449219-5.609375 35.730469-15.449219 
												9.328125-9.8125 14.667969-23.488281 14.667969-38.050781v-246.378906c18.542968-4.921875 30.558593-22.835938 
												28.078124-41.863282-2.484374-19.023437-18.691406-33.253906-37.878906-33.257812h-51.199218v-12.5c.058593-10.511719-4.097657-20.605469-11.539063-28.03125-7.441406-7.421875-17.550781-11.5546875-28.0625-11.46875h-88.796875c-10.511719-.0859375-20.621094 
												4.046875-28.0625 11.46875-7.441406 7.425781-11.597656 17.519531-11.539062 28.03125v12.5h-51.199219c-19.1875.003906-35.394531 
												14.234375-37.878907 33.257812-2.480468 19.027344 9.535157 36.941407 28.078126 41.863282zm239.601562 279.878906h-189.203125c-17.097656 
												0-30.398437-14.6875-30.398437-33.5v-245.5h250v245.5c0 18.8125-13.300782 33.5-30.398438 33.5zm-158.601562-367.5c-.066407-5.207031 
												1.980468-10.21875 5.675781-13.894531 3.691406-3.675781 8.714843-5.695313 13.925781-5.605469h88.796875c5.210937-.089844 
												10.234375 1.929688 13.925781 5.605469 3.695313 3.671875 5.742188 8.6875 5.675782 13.894531v12.5h-128zm-71.199219 32.5h270.398437c9.941406 
												0 18 8.058594 18 18s-8.058594 18-18 18h-270.398437c-9.941407 0-18-8.058594-18-18s8.058593-18 18-18zm0 0"/>
										<path d="m173.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 
												10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/>
									</svg>
								</a>
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pt-2" id="restoreButton" data-toggle="tooltip" data-placement="bottom" title="Restore File">
								
									<svg class="icon-function" version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"	viewBox="-45 0 530 530" xml:space="preserve">
										<g>
											<g>
												<g>
													<path d="M256,32C149.973,32,64,117.973,64,224H0l85.333,85.333L170.667,224h-64c0-82.453,66.88-149.333,149.333-149.333
														S405.333,141.547,405.333,224S338.453,373.333,256,373.333c-32.32,0-62.08-10.347-86.613-27.84L139.2,376.107
														C171.627,401.067,212.053,416,256,416c106.027,0,192-85.973,192-192S362.027,32,256,32z"/>
													<path d="M298.667,224c0-23.573-19.093-42.667-42.667-42.667S213.333,200.427,213.333,224s19.093,42.667,42.667,42.667
														S298.667,247.573,298.667,224z"/>
												</g>
											</g>
										</g>
									</svg>
								</a>
								
							</div>
							<div class="alert alert-danger m-0 ml-3 mt-1 message3">No file is selected!</div>
						</div>

						
						<!-- FILE TITLE -->
						<div id="sortFile" class="row row-middle m-0 p-0">
							<div id="sortByName" class="col-12 col-md-4 pb-1 pt-1 sortClass" >Name <i id="nameArrow" ></i></div>
							<div id="sortByTime" class="d-none d-md-block col-md-3  pb-1 pt-1 sortClass"><span class="m-0" id="labelSort">Created At </span><i id="timeArrow"></i></div>
							<div id="sortByType" class="d-none d-md-block col-md-3  pb-1 pt-1 sortClass">Type <i id="typeArrow" ></i></div>
							<div id="sortBySize" class="d-none d-md-block col-md-2  pb-1 pt-1 sortClass">Size <i id="sizeArrow"></i></div>
						</div>

						<!-- RIGHT FILE INFORMATION --mobile view -->
						<div class="d-block d-md-none fileInfoMobileClose" id="fileInfoMobile">
							<!-- FILE INFORMATION -->
							<div class="container m-3">
								<div class='row m-0 mb-3 border-bottom'>
									<span class="h4">File Information</span>
								</div>
								<div class='row m-0'>
									<div class='col-3 p-0'>
										<p>Name</p>
									</div>
									<div class='col-8'>
										<span class="name"></span>
									</div>
								</div>
								<div class='row m-0'>
									<div class='col-3 p-0'>
										<p>Type</p>
									</div>
									<div class='col-8'>
										<span class="type"></span>
									</div>
								</div>

								<div class='row m-0'>
									<div class='col-3 p-0'>
										<p>Size</p>
									</div>
									<div class='col-8'>
										<span class="size"></span>
									</div>
								</div>

								<div class='row m-0'>
									<div class='col-3 p-0'>
										<p>Created At</p>
									</div>
									<div class='col-8'>
										<span class="timecreate"></span>
									</div>
								</div>

								<div class='row m-0' id="myBoxRightMobile">
									<div class='col-3 p-0'>
										<p>Shared With</p>
									</div>
									<div class='col-8'>
										<span class="sharewith"></span>
									</div>
								</div>

								<div class='row m-0' id="shareBoxRightMobile">
									<div class='col-3 p-0'>
										<p>Shared By</p>
									</div>
									<div class='col-9'>
										<span class="shareby"></span>
									</div>
								</div>

								<!-- Mobile Function button -->
								<div class='d-grid gap-3 mt-3 mr-4'>
									<a class="btn btn-box btn-block" id="removeButtonMobile">Delete</a>
									<a class="btn btn-box btn-block" id="shareButtonMobile">Share</a>
									<a class="btn btn-box btn-block" id="downloadButtonMobile"  href="">Download</a>
									<a class="btn btn-box btn-block" id="previewButtonMobile">Preview</a>
									<a class="btn btn-box btn-block" id="addToVaultButtonMobile">Save To Vault</a>
									<a class="btn btn-box btn-block" id="delButtonMobile">Delete</a>
									<a class="btn btn-box btn-block" id="restoreButtonMobile">Restore</a>
								</div>


							</div>
						</div>

					</div>
				</div>
				
				

				<!-- RIGHT FILE INFORMATION --laptop view -->
				<div class="d-none d-md-block pt-4 right col-md-4 m-0">
					<!-- FILE INFORMATION -->
					<div class="ml-2  ">
						<div class="m-0">
						
								<svg class="catAboveFileInfo" viewBox="0 0 512 512"  xmlns="http://www.w3.org/2000/svg" >
									<path d="m104.015 140.386a5 5 0 0 1 6.014 4.136c.147.969 3.421 24.066-11.207 52.683-10.6 20.744 13.363 
											79.88 23.492 100.916.048.1.093.2.134.3.339.841 8.323 20.935 11.524 50.7 2.959 27.524 2.148 
											68.78-18.52 109.027a15.654 15.654 0 0 1 -11.074 8.185c-10.463 1.9-18.3 5.389-20.439 9.107a4.737 
											4.737 0 0 0 -.174 4.348 8.49 8.49 0 0 0 7.434 5.47c14.24 1.066 24.77-1.654 31.092-4.125a12.623 
											12.623 0 0 0 7.435-8.171c7.457-25.356 26.485-85.982 42.065-101.249a39.952 39.952 0 0 0 
											11.571-35.388 5 5 0 0 1 9.733-2.3 49.71 49.71 0 0 1 -12.358 42.813 27.015 27.015 0 0 0 9.367 
											13.245 63.218 63.218 0 0 1 2.187-25.492c11.129-36.8 53.407-51.027 55.2-51.615a5 5 0 0 1 3.111 
											9.5c-.385.127-39.18 13.257-48.757 45.06-6.529 21.683 1.709 47.4 24.484 76.426l20.435 
											18.606a5.134 5.134 0 0 1 1.663 4.709 5.012 5.012 0 0 1 -5.339 4.071c-11.882-.988-43.678-.048-48.8 
											15.8a4.071 4.071 0 0 0 .318 3.38 3.811 3.811 0 0 0 3.158 1.836c62.615 1.78 100.729.836 115.882-1.14a12.324 
											12.324 0 0 0 8.719-5.558c8.049-12.379 21.26-11.764 43.218-10.744l3.09.143c33.462 1.52 75.242-6.561 
											84.3-53.731 4.683-24.385-2.734-44.388-18.058-72.771-4.508-8.349-8.853-15.305-13.054-22.032-12.891-20.642-23.074-36.946-19.834-68.714 
											2.069-20.281 16.316-26.4 27.9-28.238-8.87-3.655-17.6-4.073-24.177-.8-10.219 5.087-15.441 18.9-15.105 39.933.588 
											36.686 15.727 65.36 27.891 88.4 5.533 10.48 10.311 19.53 13.012 27.885 4.718 14.6.513 29.612-11.247 40.17a45.573 
											45.573 0 0 1 -48.973 7.833c-14.5-6.481-25.286-12.684-25.738-12.945a5 5 0 0 1 5-8.661c.079.045 5.887 3.384 14.518 
											7.635 24.213-173.873-107.546-209.562-134.508-214.99-1-.2-2.178-.448-3.4-.761-48.315-12.361-56.292-54.632-54.475-87.917 
											2.935-53.787-34.874-61.128-36.485-61.414a5.023 5.023 0 0 1 -4.134-5.292c.709-9.7-13.018-22.143-23.65-29.981a63.5 
											63.5 0 0 1 -3.129 14.626 47.683 47.683 0 0 1 13.294 20.088 5.132 5.132 0 0 1 -2.683 6.357 5 5 0 0 1 
											-6.742-3.007c-3.465-10.42-11.396-18.719-23.588-24.668a72.57 72.57 0 0 0 -9.269-3.736c1.2 3.3 2.569 7.223 3.875 
											11.312 6.474 20.273 5.561 26.287 3.814 29.5a84.76 84.76 0 0 1 -5.248 7.894c-4.988 6.986-11.818 16.56-12.232 
											24.86-.334 6.7-3.212 10.742-5.525 14-3.046 4.284-5.059 7.114-1.47 16.921 4.789 13.079 34.447 9.759 44.462 7.565z"/>
									</svg>
						
						</div>
						
						<div class="card card-shadow col-12 ml-2 ">
							<div class="card-header col-12">
								<b>File Information</b>
							</div>
							<div class="card-body col-12">
								<div class="container info-text" id="showFileInfo">
									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Name</p>
										</div>
										<div class='col-7'>
											<span class="name"></span>
										</div>
									</div>
									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Type</p>
										</div>
										<div class='col-7'>
											<span class="type"></span>
										</div>
									</div>

									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Size</p>
										</div>
										<div class='col-7'>
											<span class="size"></span>
										</div>
									</div>

									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Created At</p>
										</div>
										<div class='col-7'>
											<span class="timecreate"></span>
										</div>
									</div>

									<div class='row m-0' id="myBoxRight">
										<div class='col-5 p-0'>
											<p>Shared With</p>
										</div>
										<div class='col-7'>
											<span class="sharewith"></span>
										</div>
									</div>

									<div class='row m-0' id="shareBoxRight">
									<div class='col-5 p-0'>
											<p>Shared By</p>
										</div>
										<div class='col-7'>
											<span class="shareby"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class= "mt-1">
						<div class="d-flex justify-content-end">
							<svg class="catUnderFileInfo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<g>
									<path d="M161.854,386.878,190.92,352H165.082A93.061,93.061,0,0,1,128,381.2v8.5L164.434,448h29.132l-32.35-51.76A8,8,0,0,1,161.854,386.878Z"/>
									<path d="M77.833,216.331a64.487,64.487,0,0,1,12.582-12.086A64,64,0,0,1,112,80h64a8,8,0,1,0,0-16H112A80,80,0,0,0,77.833,216.331Z"/>
									<path d="M379.2,295.6l40-30a8,8,0,0,1,6.369-1.445l35.8,7.161,17.477-17.477L472.41,234.53A8.027,8.027,0,0,1,472,232V211.313l-13.657-13.656A8,8,
											0,0,1,456,192v-1.373a46.293,46.293,0,0,0-8.48-26.833l-3.594,3.594a46.549,46.549,0,0,0-8.736,12.119l-4.035,8.07a8,8,0,0,1-5.586,4.268l-80,
											16A8.026,8.026,0,0,1,344,208H128a48.054,48.054,0,0,0-48,48v80.333h-.021a7.987,7.987,0,0,1-3.541,6.323L32,372.281V448H56V392a8,8,0,0,1,
											5.8-7.692l50.462-14.418A77.173,77.173,0,0,0,168,296h16a92.25,92.25,0,0,1-9.144,40h135.6L296.51,298.809l14.98-5.618,23.677,63.138L396.281,
											448h30.325l-49.65-92.207A8,8,0,0,1,376,352V302A8,8,0,0,1,379.2,295.6ZM440,216h16v16H440Z"/>
									<path d="M277.416,352,264,385.541V448h24V392a8,8,0,0,1,1.861-5.13L317.244,354.1l-.788-2.1Z"/>
								</g>
							</svg>

						</div>	
					</div>

				</div>

			
		
	
			</div>
		</div>
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
	 <script src="./js/homeScript.js"></script>


	
</body>

</html>

<?php 
ob_end_flush(); 
?>