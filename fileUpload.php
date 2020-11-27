<?php 

include 'controllers/authController.php';




//UPLOAD
if(isset($_POST['upload-btn'])){

    $defaultDir = "./file_dir/";
    $username = $_SESSION['username'];
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
            header('location: home.php');
            exit();
        }
        else{
            echo "Sorry! There was an error in uploading your file";
        }
        mysqli_close($con);

    }
    else{
        echo "File <html><b>".$fileName."</b></html> already exist.";  //error
        mysqli_close($con);
    }

}
?>