<?php
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

if(!file_exists($userFolder)){ 
    mkdir($userFolder, 0777, true);
}

//CHECKING FILES CONSISTENCY IN BOTH DATABASE AND SERVER
//  CONDITION 1: record in database but not in server, delete record in db
$query = $con->prepare("SELECT filepath FROM Files WHERE username = ?");
$query->execute([$username]);


while($row = $query->fetch()){
    if(!file_exists($row['filepath'])){
		$path = $row['filepath'];
		$deleteRow = $con->prepare("DELETE FROM Files where filepath = ?");
		$deleteRow->execute($path);

		
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

	
	<!--  custom css  -->
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./css/homeStyle.css">
	
	<script src="https://kit.fontawesome.com/b48b20acd1.js"></script>
    <title>myBox</title>
</head>
<body>

	<nav class="navbar navbar-expand-lg fixed-top">
			<div class="container-fluid">
				<!-- SYSTEM NAME -->
				<button  type="button" id="openSidebar">&#9776;</button>
				<img src="./img/logo.png" alt="logo" width="50px" height="40px" />
				<!--
				<p class="mr-auto mb-0">myBox@1171101541</p>
				-->
				<p class="mr-auto mb-0">myBox@<?php echo $_SESSION['username'] ?></p>

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
	
	<!-- SELECT FILE MODAL -->
	<div id="uploadModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm" role="content">
			<div class="modal-content">
				<div class="modal-body">
				<a type="button" class="close-homeModal" data-dismiss="modal">&times;</a>
					<!--form id="uploadForm" action="fileUpload.php" method="post" enctype="multipart/form-data" class="sm-form"-->
					<form id="uploadForm" method="post" action="" enctype="multipart/form-data" class="sm-form">
						<div class="form-row">
							<p class="mb-1">Select a file:</p>
							<div class="custom-file">
								<input type="file" name="getFile" class="custom-file-input col-12" id="getFile"
									aria-describedby="inputGroupFileAddon01">
								<label class="custom-file-label long col-12" for="getFile" id="getFileName">Choose file</label>
							</div>
						</div>
						<div class="form-row">
							<button id="uploadButton" type="button" value="Upload" class="btn btn-box mt-2">Upload</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- DOWNLOAD and SHARE MODAL -->
		<div id="shareModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm" role="content">
				<div class="modal-content">
					<div class="modal-body">
						<a type="button" class="close-uploadModal" data-dismiss="modal">&times;</a>
						<div class="container" id="shareDiv">
							<div class="row">
								<p class="mb-1">Shared with: </p>
								<input type="text" id="checkUser" class="form-control" />
							</div>
							<div class="row">
								<button class="btn btn-box btn-sm" id="share-btn">Share</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- PREVIEW MODAL -->
	<div id="previewModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md" role="content">
			<div class="modal-content">
				<div class="modal-body">
					<a type="button" class="close-uploadModal" data-dismiss="modal">&times;</a>
					<div class="container" >
					    <img id="previewImg" src="" width="100%" height="100%"/>
						<iframe id="previewFrame" src="" width="100%" height="350px"></iframe>
					</div>
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
				<a href="./index.php" class="">Home</a>
			</li>
			<li>
				<a id="gotoMyBox" class="boxes">myBox</a>
			</li>
			<li>
				<a id="gotoShareBox" class="boxes">shareBox</a>
			</li>
		</ul>
		
	</nav>	

	


	<!--  MAIN CONTAINER -->
	
	<div id="main">
	
	<!-- Scrollable DIV -->

		<div class="wrapper" id="myBox">
			
			
			<!-- MIDDLE CONTENT  -->
			<div class="middle ">
				<div class="container ml-3" id="mainContainer">
					<!-- FUNCTION BUTTONS -->
					<div class="row ">
						<div class="btn-group" role="group">
							<a class="btn btn-box " id="addButton">Add</a>
							<a class="btn btn-box d-none d-sm-block" id="delButton">Delete</a>
							<a class="btn btn-box d-none d-sm-block" id="shareButton">Share</a>
							<a class="btn btn-box d-none d-sm-block" id="downloadButton" href="">Download</a>
							<a class="btn btn-box d-none d-sm-block" id="previewButton">Preview</a>
						</div>
					</div>
					


					<!-- FILE TITLE -->
					<div id="sortFile" class="row row-middle m-0 p-0">
						<div id="sortByName" class="col-12 col-sm-4 pb-1 sortClass" >Name <i id="nameArrow" ></i></div>
						<div id="sortByTime" class="d-none d-sm-block col-sm-3 sortClass">Created <i id="timeArrow"></i></div>
						<div id="sortByType" class="d-none d-sm-block col-sm-3 sortClass">Type <i id="typeArrow" ></i></div>
						<div id="sortBySize" class="d-none d-sm-block col-sm-2  sortClass">Size <i id="sizeArrow"></i></div>
					</div>

				</div>
			</div>
			
			

			<!-- RIGHT FILE INFORMATION --laptop view -->
			<div class="d-none d-sm-block mt-5 right">
				<!-- FILE INFORMATION -->
				<div class="ml-2 mt-4 ">
					<!-- made this two more wider, take more space -->
					<div class="card col-12 ml-2">
						<div class="card-header col-12">
							File Information
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
										<p>Created</p>
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
			</div>

			<!-- RIGHT FILE INFORMATION --mobile view -->
			<div class="d-block d-sm-none fileInfoMobileClose" id="fileInfoMobile">
				<!-- FILE INFORMATION -->
				<div class="container m-3 mr-4">
					<div class='row m-0 mb-3 border-bottom'>
						<span class="h4">File Information</span>
					</div>
					<div class='row m-0'>
						<div class='col-3 p-0'>
							<p>Name</p>
						</div>
						<div class='col-9'>
							<span class="name"></span>
						</div>
					</div>
					<div class='row m-0'>
						<div class='col-3 p-0'>
							<p>Type</p>
						</div>
						<div class='col-7'>
							<span class="type"></span>
						</div>
					</div>

					<div class='row m-0'>
						<div class='col-3 p-0'>
							<p>Size</p>
						</div>
						<div class='col-7'>
							<span class="size"></span>
						</div>
					</div>

					<div class='row m-0'>
						<div class='col-3 p-0'>
							<p>Created</p>
						</div>
						<div class='col-7'>
							<span class="timecreate"></span>
						</div>
					</div>

					<div class='row m-0' id="myBoxRightMobile">
						<div class='col-3 p-0'>
							<p>Shared With</p>
						</div>
						<div class='col-7'>
							<span class="sharewith"></span>
						</div>
					</div>

					<div class='row m-0' id="shareBoxRightMobile">
						<div class='col-3 p-0'>
							<p>Shared By</p>
						</div>
						<div class='col-7'>
							<span class="shareby"></span>
						</div>
					</div>
					<div class='d-grid gap-3 mt-3 mr-4'>
						<a class="btn btn-box btn-block" id="delButtonMobile">Delete</a>
						<a class="btn btn-box btn-block" id="shareButtonMobile">Share</a>
						<a class="btn btn-box btn-block" id="downloadButtonMobile"  href="">Download</a>
						<a class="btn btn-box btn-block" id="previewButtonMobile">Preview</a>
					</div>


				</div>
			</div>

		
	
		
		</div>
	</div>

	<div class="overlay"></div>
	<div class=" d-sm-none overlayMobile"></div>
	<!-- FOOTER -->
	
	<!--  jquery, popper.js, bootstrap script  -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	

	 <!-- custom javascript -->
	 <script src="./js/homeScript.js"></script>
	
</body>

</html>