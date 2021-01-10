<?php 
    include 'controllers/authController.php';
    $username = $_SESSION['username'];



    //Problem: add codes to close databse

   function getFileType($type){
        $shortType = "";
        if($type == "txt"){
            $shortType = "Text";
        }elseif($type == "pdf"){
            $shortType = "PDF";
        }elseif($type == "doc" || $type == "docx"){
            $shortType = "Word";
        }elseif($type == "xls" || $type == "xlsx"){
            $shortType = "Excel";
        }elseif($type == "ppt" || $type == "pptx"){
            $shortType = "Powerpoint";
        }elseif($type == "js"){
            $shortType = "Javascript";
        }elseif($type == "php"){
            $shortType = "PHP";
        }elseif($type == "css"){
            $shortType = "Style Sheet";
        }elseif($type == "html"){
            $shortType = "HTML";
        }elseif($type == "jpg" || $type == "png" || $type == "jpeg" || $type == "jpe" || $type == "jif" || $type == "jfif" || $type == "jfi" || $type == "gif"){
            $shortType = "Image";
        }elseif($type == "exe"){
            $shortType = "Application";
        }elseif($type == "java"){
            $shortType = "Java";
        }elseif($type == "class"){
            $shortType = "Class";
        }elseif($type == "cpp" || $type == "o"){
            $shortType = "Binary File";
        }
        else{
            $shortType = "Unknown Type";
        }

        return $shortType;

   }

   
    function formatSize($bytes){
        if($bytes >= 1073741824){
            $bytes = number_format($bytes/1073741824, 2).' GB';
        }elseif($bytes >= 1048576){
            $bytes = number_format($bytes/1048576, 2).' MB';
        }elseif($bytes >= 1024){
            $bytes = number_format($bytes/1024, 2).' KB';
        }elseif($bytes > 1){
            $bytes = $bytes.' bytes';
        }elseif($bytes == 1){
            $bytes = $bytes.' byte';
        }else{
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    //DISPLAY FILE LIST
    if(isset($_GET['displayFile'])){
        $displayFile = $_GET['displayFile'];
        $sortType = $_GET['sortType'];
     

        if($sortType == "sortByDefault"){
            $sortQuery = "ORDER BY filetype, filename ASC";  //DEFAULT
        }elseif($sortType == "sortByNameASC"){
            $sortQuery = "ORDER BY filename ASC";
        }elseif($sortType == "sortByNameDESC"){
            $sortQuery = "ORDER BY filename DESC";
        }elseif($sortType == "sortByTimeASC"){
            $sortQuery = "ORDER BY createtime ASC";
        }elseif($sortType == "sortByTimeDESC"){
            $sortQuery = "ORDER BY createtime DESC";
        }elseif($sortType == "sortByTypeASC"){
            $sortQuery = "ORDER BY filetype ASC";
        }elseif($sortType == "sortByTypeDESC"){
            $sortQuery = "ORDER BY filetype DESC";
        }elseif($sortType == "sortBySizeASC"){
            $sortQuery = "ORDER BY actualsize ASC";
        }elseif($sortType == "sortBySizeDESC"){
            $sortQuery = "ORDER BY actualsize DESC";
        }

        if($displayFile == "displayFileList"){
            echo '<div id="myBoxMiddle" class="scrollable" >';
			$fetchFile = $con->prepare("SELECT * from Files where username = ? ".$sortQuery); //DEFAULT			
            $fetchFile->execute([$username]);
          
            $num=1;
            $x = "";

            while($row = $fetchFile->fetch()){
                $type = $row['filetype'];
                $fileType = getFileType($type);
                echo '<div class="row row-middle m-0 p-0 off-select" id="row_'.$num.'" onclick="getFileInfo('.$num.', '.$x.')">';
                echo '<div class="col-12 col-sm-4 p-0 pb-2 pt-2 pt-sm-1 pb-sm-1 d-flex d-sm-block"><div class="long mr-1 col-7 col-sm-12 d-sm-block" id="file_'.$num.'">'.$row['filename'].'</div><p class="m-0 pt-1 mr-2 small d-sm-none">Created at: '.$row['createtime'].'</p></div>';
                echo '<div class="d-none d-sm-block col-sm-3 pb-1 pt-1">'.$row['createtime'].'</div>';           
                echo '<div class="d-none d-sm-block col-sm-3 pb-1 pt-1">'.$fileType.'</div>';
                echo '<div class="d-none d-sm-block col-sm-2 pb-1 pt-1">'.$row['filesize'].'</div>';
                echo '</div>';
                                
                $num++; 
            }
                    
                            
            echo '</div>';
        }

        if($displayFile == "displayShareFileList"){
           
            echo '<div id="shareBoxMiddle" class="scrollable">';
	
            $fetchInfo = $con->prepare("SELECT shared_users, id, username from Files");
            $fetchInfo->execute();

            while($row = $fetchInfo->fetch()){
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
            $data=array();
            while($i<count($foundFileID)){
                $ID = $foundFileID[$i][0];
                //different sort method for sharefile, because it displays one by one
                $fetchFile = $con->prepare("SELECT * from Files where id = ?");
                $fetchFile->execute([$ID]);

                $row = $fetchFile->fetch();
                $data[] = $row;

                $i++;
               
            }
            
            function sortByNameDESC($a, $b){
                return strcasecmp($b['filename'], $a['filename']);
            }
            function sortByNameASC($a, $b){
                return strcasecmp($a['filename'], $b['filename']);
            }
            function sortByTypeDESC($a, $b){
                return $a['filetype'] < $b['filetype'];
            }
            function sortByTypeASC($a, $b){
                return $a['filetype'] > $b['filetype'];
            }
            function sortBySizeDESC($a, $b){
                return $a['actualsize'] < $b['actualsize'];
            }
            function sortBySizeASC($a, $b){
                return $a['actualsize'] > $b['actualsize'];
            }
            function sortByTimeASC($a, $b){
                 $t1 = strtotime($a['createtime']);
                 $t2 = strtotime($b['createtime']);
                return $t1 > $t2;
            }
            function sortByTimeDESC($a, $b){
                $t1 = strtotime($a['createtime']);
                $t2 = strtotime($b['createtime']);
               return $t1 < $t2;
           }




            function sortByDefault($a, $b){
                return [$a['filetype'], $a['filename']] <=> [$b['filetype'],$b['filename']];
            }

          //files to be sorted
            $sorted = $data;  
           //sort file at here
            usort($sorted, $sortType);

            //to display sorted files
            $num =1;
            foreach($sorted as $s){
                //different sort method for sharefile, because it displays one by one
            
                $type = $s['filetype'];
                $fileType = getFileType($type);

                echo	'<div class="row row-middle m-0 p-0 off-select" id="row_'.$num.'" onclick="getFileInfo('.$num.', '.$s['id'].')">';
                echo	'<div class="col-12 col-sm-4 p-0 pb-2 pt-2 pt-sm-1 pb-sm-1 d-flex d-sm-block"><div class="long mr-1 col-7 col-sm-12 d-sm-block" id="file_'.$num.'">'.$s['filename'].'</div><p class="m-0 pt-1 mr-2 small d-sm-none">Created at: '.$s['createtime'].'</p></div>';
                echo	'<div class="d-none d-sm-block col-sm-3 pb-1 pt-1">'.$s['createtime'].'</div>';
                echo    '<div class="d-none d-sm-block col-sm-3 pb-1 pt-1">'.$fileType.'</div>';
                echo    '<div class="d-none d-sm-block col-sm-2 pb-1 pt-1">'.$s['filesize'].'</div>';
                echo 	'</div>';
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
            $query = $con->prepare("SELECT filename FROM Files WHERE filename = ? && username = ?");
            $query->execute([$fileName, $username]);
            
            while($row = $query->fetch()){
                if($row['filename'] == $fileName){
                    $fileExists = 1;
                    $status = "exist";
                }
            }

           
            if($fileExists == 0){
            // $targetDir = "file_dir/";
                $filePath = $userFolder.$fileName;
                $fileTempName = $_FILES['getFile']['tmp_name'];
                $actualSize = $_FILES['getFile']['size'];
                $fileSize = formatSize($actualSize);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

                //$username = $_SESSION['username'];
                $result = move_uploaded_file($fileTempName, $filePath);

                if($result){
                    $query = $con->prepare("INSERT INTO Files(filename, filetype, filesize, actualsize, filepath, createtime, username)
                         VALUES (:filename, :filetype, :filesize, :actualsize, :filepath, curdate(), (SELECT username from Users where username = :username))");
                    $query->bindParam(':filename', $fileName);
                    $query->bindParam(':filetype', $fileType);
                    $query->bindParam(':filesize', $fileSize);
                    $query->bindParam(':actualsize', $actualSize);
                    $query->bindParam(':filepath', $filePath);
                    $query->bindParam(':username', $username);
                    $query->execute();

 
                }
                else{
                    echo "Sorry! There was an error in uploading your file";
                }
              $con = null;
           
            }
            else{
                $con = null;
               
                
            }
        }
        echo json_encode($status);

    }

  

    //SHOW FILE INFO
    if($action == "showFileInfoMyBox"){
        $filename = $_POST['file'];
        $fetchInfo = $con->prepare("SELECT filename, filetype, filesize, createtime, shared_users FROM Files WHERE filename = ? && username = ?");
        $fetchInfo->execute([$filename, $username]);
        
        $return_arr = array();

        while($row = $fetchInfo->fetch()){
            $type = $row['filetype'];
            $fileType = getFileType($type);
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $fileType;
            $return_arr['filesize'] = $row['filesize'];
            $return_arr['createtime'] = $row['createtime'];
            $return_arr['shared_users'] = $row['shared_users'];
            $return_arr['action'] = $action;
        }
        echo json_encode($return_arr);
    }

    if($action == "showFileInfoShareBox"){
        $fileID = $_POST['file'];
        $fetchInfo = $con->prepare("SELECT filename, filetype, filesize, createtime, username FROM Files WHERE id = ?");
        $fetchInfo->execute([$fileID]);

        $return_arr = array();

        while($row = $fetchInfo->fetch()){
            $type = $row['filetype'];
            $fileType = getFileType($type);
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $fileType;
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
        $query = $con->prepare("DELETE FROM Files WHERE filename = ? && username = ?");
        $query->execute([$filename, $username]);

        //delete the file in server
        $defaultDir = "./file_dir/";
        $userFolder = $defaultDir.$username.'/';
        unlink($userFolder.$filename);

        if(($query) && (!(file_exists($userFolder.$filename)))){
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
        $query = $con->prepare("SELECT username FROM Users WHERE username = ?");
        $query->execute([$checkUser]);
 

        //If user does not exists return back
        if($query->fetch() == 0){
            $isUserExist = "no";
        }else{

            //If user exists
            //select shareuser col for ori value
            $query1 = $con->prepare("SELECT shared_users FROM Files WHERE filename = ? && username = ?");
            $query1->execute([$filename, $username]);
           
            $row = $query1->fetch();

            //CHECK THE USER IS ALREADY SHARED OR NOT [UNSOLVED]

            //If shared_users is empty
            if(($row['shared_users'] == '0') || ($row['shared_users'] == NULL) ){
                $new = $checkUser.',';
            }else{
                //store original value
                $original = $row['shared_users'];
                //add new share user with original value
                $new = $original.$checkUser.',';
            }
                $query2 = $con->prepare("UPDATE Files SET shared_users = ? WHERE filename = ? && username = ?");
                $query2->execute([$new, $filename, $username]);
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
      $fetchInfo = $con->prepare("SELECT filepath FROM Files WHERE id = ?");
      $fetchInfo->execute([$fileID]);
  
      while($row = $fetchInfo->fetch()){
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
	
