<?php 
    include 'controllers/authController.php';
    $username = $_SESSION['username'];

   
    //DISPLAY FILE LIST
    if(isset($_GET['displayFile'])){
        $displayFile = $_GET['displayFile'];

        if($displayFile == "displayFileList"){
            echo '<div id="myBoxMiddle" class="scrollable" >';
						
            $fetchFile = "SELECT * from Files where username = '$username'";
            $result = mysqli_query($con, $fetchFile);
            $num=1;
            $x = "";
            while($row = mysqli_fetch_array($result)){
                

                echo '<div class="row row-middle m-0 p-0 off-select " id="row_'.$num.'" onclick="getFileInfo('.$num.', '.$x.')">';
                echo '<div class="col-3 pb-1 long" id="file_'.$num.'" value="'.$row['filename'].'">'.$row['filename'].'</div>';
                echo '<div class="col-3 pb-1">'.$row['createtime'].'</div>';
                $shortType = "";            
                $type = $row['filetype'];
                if($type == "text/plain"){
                    $shortType = "text";
                    echo '<div class="col-3 pb-1 long">'.$shortType.'</div>';
                }elseif($type == "application/pdf"){
                    $shortType = "pdf";
                    echo '<div class="col-3 pb-1 long">'.$shortType.'</div>';
                }elseif($type == "application/msword" || $type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
                    $shortType = "word";
                    echo '<div class="col-3 pb-1 long">'.$shortType.'</div>';
                }elseif($type == "application/vnd.openxmlformats-officedocument.presentationml.presentation"){
                    $shortType = "powerpoint";
                    echo '<div class="col-3 pb-1 long">'.$shortType.'</div>';
                }elseif($type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
                    $shortType = "excel";
                    echo '<div class="col-3 pb-1 long">'.$shortType.'</div>';
                }
                else{
                    echo '<div class="col-3 pb-1 long">'.$row['filetype'].'</div>';
                }
               
                echo '<div class="col-3 pb-1">'.$row['filesize'].'</div>';
                echo '</div>';
                                
                $num++; 
            }
                    
                            
            echo '</div>';
        }

        if($displayFile == "displayShareFileList"){
            echo '<div id="shareBoxMiddle" class="scrollable">';
						
					
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
							
	
						echo	'<div class="row row-middle m-0 p-0 off-select" id="row_'.$num.'" onclick="getFileInfo('.$num.', '.$ID.')">';
						echo	'<div class="col-3 pb-1 long" id="file_'.$num.'">'.$row['filename'].'</div>';
						echo	'<div class="col-3 pb-1 ">'.$row['createtime'].'</div>';
						echo    '<div class="col-3 pb-1 long">'.$row['filetype'].'</div>';
						echo    '<div class="col-3 pb-1">'.$row['filesize'].'</div>';
						echo 	'</div>';
							
								$i++;
								$num++; 
							}
							
					echo '</div>';

        }
     


    }

if(isset($_POST['action'])){
    $action = $_POST['action'];

    //UPLOAD FILE
    if($action == "uploadFile"){

        $status = "no";

        //UPLOAD
        if(isset($_FILES['getFile']['name'])){


            $defaultDir = "./file_dir/";
            $userFolder = $defaultDir.$username.'/';
            $fileExists = 0;
            $fileName = $_FILES['getFile']['name'];

            //Check the file is existing in the database or not
            $query = "SELECT filename FROM Files WHERE filename = '$fileName' and username = '$username'";
            $result = $con->query($query) or die("Error: ". mysqli_error($con));

            while($row = mysqli_fetch_array($result)){
                if($row['filename'] == $fileName){
                    $fileExists = 1;
                    $status = "exist";
                }
            }

           
            if($fileExists == 0){
            // $targetDir = "file_dir/";
                $filePath = $userFolder.$fileName;
                $fileTempName = $_FILES['getFile']['tmp_name'];
                $fileSize = $_FILES['getFile']['size'];
                $fileType = $_FILES['getFile']['type'];
                //$username = $_SESSION['username'];
                $result = move_uploaded_file($fileTempName, $filePath);

                if($result){
                    $query = "INSERT INTO Files(filename, filetype, filesize, filepath, createtime, username) VALUES ('$fileName', '$fileType', '$fileSize', '$filePath', curdate(), (SELECT username from Users where username = '$username'))";
                    $con->query($query) or die ("Error: ".mysqli_error($con));
                }
                else{
                    echo "Sorry! There was an error in uploading your file";
                }
                mysqli_close($con);
           
            }
            else{
                mysqli_close($con);
               
                
            }
        }
        echo json_encode($status);

    }


    //SHOW FILE INFO
    if($action == "showFileInfoMyBox"){
        $filename = $_POST['file'];
        $fetchInfo = "SELECT * FROM Files WHERE filename = '".$filename."' && username = '$username'";
        $result = mysqli_query($con, $fetchInfo);
        $return_arr = array();

        while($row = mysqli_fetch_array($result)){
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $row['filetype'];
            $return_arr['filesize'] = $row['filesize'];
            $return_arr['createtime'] = $row['createtime'];
            $return_arr['shared_users'] = $row['shared_users'];
            $return_arr['action'] = $action;
        }
        echo json_encode($return_arr);
    }

    if($action == "showFileInfoShareBox"){
        $fileID = $_POST['file'];
        $fetchInfo = "SELECT * FROM Files WHERE id = '$fileID'";
        $result = mysqli_query($con, $fetchInfo);
        $return_arr = array();

        while($row = mysqli_fetch_array($result)){
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $row['filetype'];
            $return_arr['filesize'] = $row['filesize'];
            $return_arr['createtime'] = $row['createtime'];
            $return_arr['username'] = $row['username'];
            $return_arr['action'] = $action;
        }
        echo json_encode($return_arr);
    }

    if($action == "deleteFile"){
        $filename = $_POST['filename'];
        $status = "";
        
        //delete filepath from database
        $query = "DELETE FROM Files WHERE filename = '".$filename."' && username = '".$username."'";
        $result = mysqli_query($con, $query);
        //delete the file in server
        $defaultDir = "./file_dir/";
        $userFolder = $defaultDir.$username.'/';
        unlink($userFolder.$filename);

        if(($result) && (!(file_exists($userFolder.$filename)))){
            $status = "success";
        }else{
            $status = "fail";
        }
        echo json_encode($status);
    }

    if($action == "shareFile"){
        $filename = $_POST['filename'];
        $isUserExist = "yes";
        $checkUser = $_POST['checkUser'];
        $query = "SELECT username FROM Users WHERE username = '".$checkUser."'";
        $result = mysqli_query($con, $query);

        //If user does not exists return back
        if(mysqli_num_rows($result) == 0){
            $isUserExist = "no";
        }else{

            //If user exists
            //select shareuser col for ori value
            $query1 = "SELECT shared_users FROM Files WHERE filename = '".$filename."' && username = '".$username."'";
            $result1 = mysqli_query($con, $query1);
            $row = mysqli_fetch_array($result1);

            //CHECK THE USER IS ALREADY SHARED OR NOT [UNSOLVED]

            //If shared_users is empty
            if(($row['shared_users'] == '0') || ($row['shared_users'] == NULL) ){
                $new = $checkUser.',';
                $query2 = "UPDATE Files SET shared_users = '".$new."' WHERE filename = '".$filename."' && username = '".$username."'";
                mysqli_query($con, $query2);
              
            }else{
                //store original value
                $original = $row['shared_users'];
                //add new share user with original value
                $new = $original.$checkUser.',';
                //update shareuser col in db for selected file
                $query2 = "UPDATE Files SET shared_users = '".$new."' WHERE filename = '".$filename."' && username = '".$username."'";
                mysqli_query($con, $query2);
                
            }
        }
        echo json_encode($isUserExist);
    }

    //IF USER WANT TO UNSHARE THE FILE
    
    if($action == "previewFile"){
        $return_arr = array();
      $filename = $_POST['file'];
      $path = $username.'/'.$filename;

      $file = './file_dir/'.$path;
      $info = pathinfo($file);

      $return_arr['path'] = $path;
      $return_arr['filetype'] = $info["extension"];

      echo json_encode($return_arr);
  }
  
  if($action == "previewShareFile"){
      $return_arr = array();
      $fileID = $_POST['file'];
      $fetchInfo = "SELECT filepath FROM Files WHERE id = '$fileID'";
      $result = mysqli_query($con, $fetchInfo);
      while($row = mysqli_fetch_array($result)){
          $file = $row['filepath'];
          $info = pathinfo($file);
          $path = substr($row['filepath'], 11);
          $return_arr['path'] = $path;
          $return_arr['filetype'] = $info["extension"];
      }


      echo json_encode($return_arr);
  }
    
}

?>
	
