<?php 
    include 'controllers/authController.php';
    $username = $_SESSION['username'];
    $action = $_POST['action'];
  
   
    //UPLOAD FILE
    if($action == "uploadFile"){

        $status = "";

        //UPLOAD
        if(isset($_FILES['getFile']['name'])){


            $defaultDir = "./file_dir/";
            $userFolder = $defaultDir.$username.'/';
            $fileExists = 0;
            $fileName = $_FILES['getFile']['name'];

            $query = "SELECT filename FROM Files WHERE filename = '$fileName' and username = '$username'";
            $result = $con->query($query) or die("Error: ". mysqli_error($con));

            while($row = mysqli_fetch_array($result)){
                if($row['filename'] == $fileName){
                    $fileExists = 1;
                }
            }

            //Check the file is existing in the database or not
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
                    exit();
                }
                else{
                    echo "Sorry! There was an error in uploading your file";
                }
                mysqli_close($con);
                $status = "no";
            }
            else{
                mysqli_close($con);
                $status = "exist";
                
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
    

?>
	
