<?php 
    include 'controllers/authController.php';
    $username = $_SESSION['username'];
    $action = $_POST['action'];
    $filename = $_POST['filename'];

    if($action == "showFileInfo"){
        $fetchInfo = "SELECT * FROM Files WHERE filename = '".$filename."' && username = '$username'";
        $result = mysqli_query($con, $fetchInfo);
        $return_arr = array();

        while($row = mysqli_fetch_array($result)){
            $return_arr['filename'] = $row['filename'];
            $return_arr['filetype'] = $row['filetype'];
            $return_arr['filesize'] = $row['filesize'];
            $return_arr['createtime'] = $row['createtime'];
    
        }
        echo json_encode($return_arr);
    }

    if($action == "deleteFile"){
        $status = "";
        
        //delete filepath from database
        $query = "DELETE FROM Files WHERE filename = '".$filename."' && username = '$username'";
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
    

?>
	
