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
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./css/homeStyle.css">
	

	<!--Google font-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300&family=Noto+Sans&display=swap" rel="stylesheet">


	<script src="https://kit.fontawesome.com/b48b20acd1.js"></script>
    <title>myBox</title>
</head>
<body>

	<nav class="navbar navbar-expand-lg fixed-top">
			<div class="container-fluid">
				<!-- SYSTEM NAME -->
				<button  type="button" id="openSidebar">&#9776;</button>
				<img src="./img/logo.png" alt="logo" width="50px" height="40px" />
				
				<p class="mr-auto mb-0"><span id="boxName"></span><?php echo $_SESSION['username'] ?></p>

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
	
<!--Problem: create function to let user delete their account -->


	<!-- SELECT FILE MODAL -->
	<div id="uploadModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm" role="content">
			<div class="modal-content">
				<div class="modal-body">
					<div class="row m-0 mb-2">
						<a type="button" class="close-homeModal p-2 " data-dismiss="modal">&times;</a>
						<div class="alert alert-danger message1 p-1 m-0"> File exists! </div>
					</div>
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
							<button id="uploadButton" type="button" value="Upload" class="btn btn-box btn-block mt-2">Upload</button>
						</div>
					</form>
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
			<li>
				<a id="gotoRecycleBin" class="boxes">Recycle Bin</a>
			</li>
		</ul>
		
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
										<a type="button" class="close-uploadModal" data-dismiss="modal">&times;</a>
										<div class="container" >
											<img id="previewImg" src="" width="100%" height="100%"/>
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
											<a type="button" class="close-homeModal p-2 " data-dismiss="modal">&times;</a>
											<div class="alert alert-danger message2 p-1 m-0"> User does not exist! </div>
										</div>
										<div class="container" id="shareDiv">
											<div class="d-block d-md-none row">
												Filename: <p id="tobeShared" class="name"></p>
											</div>
											<div class="row">
												<p class="mb-1">Shared with: </p>
												<input type="text" id="checkUser" class="form-control" />
											</div>
											<div class="row">
												<button class="btn btn-box btn-block" id="share-btn">Share</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- DELETE CONFIRMATION MODAL -->
						<div id="deleteModal" class="modal fade" role="dialog">
							<div class="modal-dialog modal-sm" role="content">
								<div class="modal-content">
									<div class="modal-body">
										<a type="button" class="close-uploadModal" data-dismiss="modal">&times;</a>
										<div class="container">
												<div class="d-block row">
													<p class="m-0">Are you sure you want to delete this file?<br><br>
													Filename: </p><strong><p id="tobeDeleted" class="name"></p></strong>
												</div>
										
											<div class="row">
												<button class="btn btn-box btn-sm ml-auto" id="delete-btn">Yes</button>
												<button class="btn btn-secondary btn-sm ml-1"  data-dismiss="modal" >No</button>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- FUNCTION BUTTONS -->
						<div class="row" id="functionButtons">
							<div  class="container m-0 w-50 d-flex justify-content-between">
								<a class="btn button btn-function rounded-circle d-flex justify-content-center p-1 pl-2 pt-2" id="addButton" data-toggle="tooltip" data-placement="bottom" title="Add File">
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
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pl-2 pt-2" id="shareButton" data-toggle="tooltip" data-placement="bottom" title="Share File">
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
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pl-2 pt-2" id="downloadButton" href="" data-toggle="tooltip" data-placement="bottom" title="Download File">
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
								<a class="btn button btn-function rounded-circle d-none d-md-flex justify-content-center p-1 pt-2 " id="previewButton" data-toggle="tooltip" data-placement="bottom" title="Preview  File">
								
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
								
							</div>
							<div class="alert alert-danger m-0 ml-3 mt-1 message3">No file is selected!</div>
						</div>
						


						<!-- FILE TITLE -->
						<div id="sortFile" class="row row-middle m-0 p-0">
							<div id="sortByName" class="col-12 col-md-4 pb-1 pt-1 sortClass" >Name <i id="nameArrow" ></i></div>
							<div id="sortByTime" class="d-none d-md-block col-md-3  pb-1 pt-1 sortClass">Created <i id="timeArrow"></i></div>
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
									<div class='col-8'>
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
				
				

				<!-- RIGHT FILE INFORMATION --laptop view -->
				<div class="d-none d-md-block pt-4 right col-md-4 m-0">
					<!-- FILE INFORMATION -->
					<div class="ml-2 mt-4 ">
						<!-- made this two more wider, take more space -->
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
				</div>

			
		
	
			</div>
		</div>
	</div>

	<div class="overlay"></div>
	<div class="d-md-none overlayMobile"></div>
	<!-- FOOTER -->
	
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