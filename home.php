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
$query = "SELECT filepath FROM Files WHERE username = '$username'";
$result = mysqli_query($con, $query);

while($row = mysqli_fetch_array($result)){
    if(!file_exists($row['filepath'])){
		$path = $row['filepath'];
        $deleteRow = "DELETE FROM Files where filepath = '$path'";
        $con->query($deleteRow) or die ("Error: ".mysqli_error($con));
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
	$query1 = "SELECT filename FROM Files WHERE username = '$username' && filename = '$file'";
	$result1 = mysqli_query($con, $query1);

	if(mysqli_num_rows($result1) === 0){
		unlink($userFolder.$file);
	}
}


?>
<!DOCTYPE html>
<html>
<!-- The Application Page, only can enter after login and verification -->
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
<body>

	
	
		<!-- SELECT FILE MODAL -->
		<div id="uploadModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm" role="content">
				<div class="modal-content">
					<div class="modal-body">
					<a type="button" class="close-uploadModal" data-dismiss="modal">&times;</a>
						<!--form id="uploadForm" action="fileUpload.php" method="post" enctype="multipart/form-data" class="sm-form"-->
						<form id="uploadForm" method="post" action="" enctype="multipart/form-data" class="sm-form">
							<div class="form-row">
								<p class="mb-1">Select a file:</p>
								<div class="custom-file">
    								<input type="file" name="getFile" class="custom-file-input " id="getFile"
      									aria-describedby="inputGroupFileAddon01">
    								<label class="custom-file-label long" for="getFile" id="getFileName">Choose file</label>
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

	<div id="mySidebar" class="sidebar">
<!-- LEFT CONTENT (MENU TO SWITCH FROM OWN SPACE and SHARED FILE) -->
			<h3>catBox</h3>
			<ul class="list-unstyled components">
				<li>
					<a href="./index.php">Home</a>
				</li>
				<li>
					<a href="./home.php">myBox</a>
				</li>
				<li>
					<a onclick="displayShareBox()">shareBox</a>
				</li>
			</ul>
		
	</div>	


	<!--  MAIN CONTAINER -->
	
	<div id="main" class="container-outer">
		<!--  HEADER  -->
	
		<nav class="navbar navbar-expand-lg  fixed-top">
			<div class="container-fluid">
				<!-- SYSTEM NAME -->
				<button  type="button"  class="btn-menu" value="open" onclick="toggleNav(this)" >&#9776;</button>
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

	
	<!-- Scrollable DIV -->

		<div class="wrapper" id="myBox">
			
			
			<!-- MIDDLE CONTENT  -->
			<div class="middle ">
				<div class="container ml-3" id="mainContainer">
					<!-- FUNCTION BUTTONS -->
					<div class="row row-middle mb-1 ">
						<div class="btn-group" role="group">
							<a class="btn btn-box" id="addButton">Add</a>
							<a class="btn btn-box" id="delButton">Delete</a>
							<a class="btn btn-box" id="shareButton">Share</a>
							<a class="btn btn-box" id="downloadButton" href="">Download</a>
							<a class="btn btn-box" id="previewButton">Preview</a>
						</div>
					</div>
					


					<!-- FILE TITLE -->
					<div class="row row-middle m-0 p-0 ">
						<div class="col-3 pb-1">Name</div>
						<div class="col-3 pb-1">Created</div>
						<div class="col-3 pb-1">Type</div>
						<div class="col-3 pb-1">Size</div>
					</div>

				
					
					

					<div id="shareBoxMiddle" class="scrollable">
						
						<?php
							$username = $_SESSION['username'];
							$fetchInfo = "SELECT shared_users, id, username from Files";
							$result = mysqli_query($con, $fetchInfo);
							while($row = mysqli_fetch_row($result)){
								$listSharedUsers[] = array($row[0]);
								$listID[] = array($row[1]);
								$listUserName[] = array($row[2]); 
							}
							
			
							$foundPos = array();
							$foundFileID = array();
							
							for($i = 0; $i< count($listSharedUsers); $i++){
								$sharedUsers = $listSharedUsers[$i];
								$splitedUsers = explode(",", $sharedUsers[0]);
								

								for($j=0; $j<count($splitedUsers); $j++){
									if($splitedUsers[$j] == $username){
										array_push($foundPos, $i);
									
									}
								}

							}

							for($i=0; $i<count($foundPos); $i++){
								 $pos = $foundPos[$i];
								array_push($foundFileID, $listID[$pos]);
							}


							$i=0;
							
							while($i<count($foundFileID)){
								$ID = $foundFileID[$i][0];
								
								$fetchFile = "SELECT * from Files where id = '$ID'";
								$result = mysqli_query($con, $fetchFile);
								$num=1;
								$row = mysqli_fetch_array($result);
							
						?>
							<div class="row row-middle m-0 p-0 off-select" id="row_<?php echo $num;?>" onclick="getFileInfo(<?php echo $num; ?>, <?php echo $ID; ?>)">
								<div class="col-3 pb-1 long" ><?php echo $row['filename'] ?></div>
								<div class="col-3 pb-1 "><?php echo $row['createtime'] ?></div>
								<div class="col-3 pb-1 "><?php echo $row['filetype'] ?></div>
								<div class="col-3 pb-1"><?php echo $row['filesize'] ?></div>
							</div>
							<?php
								$i++;
								$num++; 
							}
						?>

							
					</div>

				</div>
			</div>
			
			

			<!-- RIGHT FILE INFORMATION -->
			<div class="right">
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
											<span id="name"></span>
										</div>
									</div>
									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Type</p>
										</div>
										<div class='col-7'>
											<span id="type"></span>
										</div>
									</div>

									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Size</p>
										</div>
										<div class='col-7'>
											<span id="size"></span>
										</div>
									</div>

									<div class='row m-0'>
										<div class='col-5 p-0'>
											<p>Created</p>
										</div>
										<div class='col-7'>
											<span id="timecreate"></span>
										</div>
									</div>

									<div class='row m-0' id="myBoxRight">
										<div class='col-5 p-0'>
											<p>Shared With</p>
										</div>
										<div class='col-7'>
											<span id="sharewith"></span>
										</div>
									</div>

									<div class='row m-0' id="shareBoxRight">
									<div class='col-5 p-0'>
											<p>Shared By</p>
										</div>
										<div class='col-7'>
											<span id="shareby"></span>
										</div>
									</div>

									
							</div>
						</div>
					</div>
				</div>

				
			</div>

		
		
		</div>
	</div>


	<!-- FOOTER -->

	<!--  jquery, popper.js, bootstrap script  -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>


	 <!-- custom javascript -->
	 <script src="script.js"></script>
	
</body>

</html>