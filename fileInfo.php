<?php 
    include 'controllers/authController.php';
    $username = $_SESSION['username'];
    $action = $_POST['action'];
  
   
    //SHOW FILE INFO
    if($action == "showFileInfoMyBox"){
        $filename = $_POST['filename'];
        $fetchInfo = "SELECT * FROM Files WHERE filename = '".$filename."' && username = '$username'";
        $result = mysqli_query($con, $fetchInfo);
        $return_arr = array();

        while($row = mysqli_fetch_array($result)){
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $row['filetype'];
            $return_arr['filesize'] = $row['filesize'];
            $return_arr['createtime'] = $row['createtime'];
            $return_arr['shared_users'] = $row['shared_users'];
        }
        echo json_encode($return_arr);
    }

    if($action == "showFileInfoShareBox"){
        $fileID = $_POST['fileID'];
        $fetchInfo = "SELECT * FROM Files WHERE id = '$fileID'";
        $result = mysqli_query($con, $fetchInfo);
        $return_arr = array();

        while($row = mysqli_fetch_array($result)){
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $row['filetype'];
            $return_arr['filesize'] = $row['filesize'];
            $return_arr['createtime'] = $row['createtime'];
            $return_arr['username'] = $row['username'];
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
    
    

?>
	
