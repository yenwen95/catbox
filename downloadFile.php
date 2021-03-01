<?php
include 'controllers/authController.php';

if(isset($_GET['action'])){
    $action = $_GET['action'];

   if($action == "downloadFile"){
        if(isset($_GET['path'])){
            $fileID = basename($_GET['path']);
            $query = $con->prepare("SELECT filename, filepath FROM Files WHERE id = ?");
            $query->execute([$fileID]);
            $file = $query->fetch();

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