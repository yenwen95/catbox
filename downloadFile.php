<?php
include 'controllers/authController.php';

if(isset($_GET['action'])){
    $action = $_GET['action'];

    if($action == "downloadFile"){
        if(isset($_GET['path'])){
            $filename = basename($_GET['path']);
            $defaultDir = "./file_dir/";
            $username = $_SESSION['username'];
            $checkFile = $defaultDir.$username.'/'.$filename;
    
            if(file_exists($checkFile)){
                header('Content-Description: File Transfer');
                header('Content-Type: '.mime_content_type($checkFile));
                header('Content-Disposition: attachment; filename='.$filename);
    
                readfile($checkFile);
            }
    
        }

    }elseif($action == "downloadShareFile"){
        if(isset($_GET['path'])){
            $fileID = basename($_GET['path']);
            $query = "SELECT filename, filepath FROM Files WHERE id = '$fileID'";
            $result = mysqli_query($con, $query);
            $file = mysqli_fetch_array($result);
            $filepath = $file['filepath'];
            $filename = $file['filename'];

            if(file_exists($filepath)){
                header('Content-Description: File Transfer');
                header('Content-Type: '.mime_content_type($filepath));
                header('Content-Disposition: attachment; filename='.$filename);
    
                readfile($filepath);
            }

        }
    }

}
    

?>