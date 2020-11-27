<?php
	 include 'controllers/authController.php';

	if(empty($_SESSION['id'])){
		header('location: index.php');
	}
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

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

	<!--  HEADER  -->
	
		<nav class="navbar">
			<!-- SYSTEM NAME -->
			<a href="./index.php"><img width="50px" height="40px" src="img/logo.png" alt="logo" /></a>
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
		</nav>
	
		<!-- SELECT FILE MODAL -->
		<div id="uploadModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm" role="content">
				<div class="modal-content">
					<div class="modal-body">
					<a type="button" class="close-uploadModal" data-dismiss="modal">&times;</a>
						<form action="fileUpload.php" method="post" enctype="multipart/form-data" class="sm-form">
							<div class="form-row">
								<p class="mb-1">Select a file:</p>
								<div class="custom-file">
    								<input type="file" name="getFile" class="custom-file-input" id="getFile"
      									aria-describedby="inputGroupFileAddon01">
    								<label class="custom-file-label" for="getFile" id="getFileName">Choose file</label>
  								</div>
							</div>
							<div class="form-row">
								<button type="submit" class="btn btn-box mt-2" name="upload-btn">Upload</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	



	<!--  MAIN CONTAINER -->
	
	<div class="container container-outer">
	<div class="wrapper">



		<!-- LEFT CONTENT  -->
		<div class="left">
			<div class="container ml-3 ">
				<!-- FUNCTION BUTTONS 
				<div class="row row-middle">
					<div class="btn-group" role="group">
						<button type="button" class="btn btn-box">Add</button>
						<button type="button" class="btn btn-box">Edit</button>
						<button type="button" class="btn btn-box">Delete</button>
						<button type="button" class="btn btn-box">Share</button>
						<button type="button" class="btn btn-box">Preview</button>
					</div>
				</div>
				-->
				<div class="row row-middle mb-1">
					<div class="btn-group" role="group">
						<a class="btn btn-box" id="addButton">Add</a>
						<a class="btn btn-box" id="delButton">Delete</a>
					</div>
				</div>

				<!-- FILE TITLE -->
				<div class="row row-middle m-0 p-0">
					<div class="col-3 pb-1">Name</div>
					<div class="col-3 pb-1">Created</div>
					<div class="col-3 pb-1">Type</div>
					<div class="col-3 pb-1">Size</div>
				</div>

				<!-- SHOW FILES -->
				<?php 
					$username = $_SESSION['username'];
					$fetchFile = "SELECT * from Files where username = '$username'";
					$result = mysqli_query($con, $fetchFile);
					$num=1;
					while($row = mysqli_fetch_array($result)){
				?>
					<div class="row row-middle m-0 p-0 off-select" id="row_<?php echo $num;?>" onclick="getFileInfo(<?php echo $num; ?>)">
						<div class="col-3 pb-1" id="file_<?php echo $num; ?>" value="<?php echo $row['filename'] ?>"><?php echo $row['filename'] ?></div>
						<div class="col-3 pb-1"><?php echo $row['createtime'] ?></div>
						<div class="col-3 pb-1"><?php echo $row['filetype'] ?></div>
						<div class="col-3 pb-1"><?php echo $row['filesize'] ?></div>
					</div>
					<?php
						$num++; 
					}
				?>

			</div>
		</div>
		
		

		<!-- RIGHT FILE INFORMATION -->
		<div class="right">
				<!-- FILE INFORMATION -->
				<div class="ml-2 mt-4 ">
					<!-- made this two more wider, take more space -->
					<div class="card col-12">
						<div class="card-header col-12">
							File Information
						</div>
						<div class="card-body col-12">
							<div class="container info-text" id="showFileInfo">
								<div class='row m-0'>
									<div class='col-5'>
										<p>Name</p>
									</div>
									<div class='col-7'>
										<span id="name"></span>
									</div>
								</div>
								<div class='row m-0'>
									<div class='col-5'>
										<p>Type</p>
									</div>
									<div class='col-7'>
										<span id="type"></span>
									</div>
								</div>

								<div class='row m-0'>
									<div class='col-5'>
										<p>Size</p>
									</div>
									<div class='col-7'>
										<span id="size"></span>
									</div>
								</div>

								<div class='row m-0'>
									<div class='col-5'>
										<p>Created</p>
									</div>
									<div class='col-7'>
										<span id="timecreate"></span>
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