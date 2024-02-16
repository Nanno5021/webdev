<?php
session_start();
include 'dbcon.php';

// if file upload form is submitted
$status = $statusMsg = '';

if(isset($_POST["submit"])){
    $status = 'error';

    if(!empty($_FILES["image"]["name"])){
        // get uploaded file info
        $fileName = basename($_FILES["image"]["name"]);
        $fileSize = $_FILES["image"]["size"]; // Get the size of the uploaded file in bytes
        
        // Check if file size exceeds 1 MB (1048576 bytes)
        if($fileSize > 1048576) {
            $statusMsg = "Sorry, only images under 1 MB are allowed.";
        } else {
            // allow certain file format
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if(in_array($fileType, $allowTypes)){
                $image = $_FILES['image']['tmp_name'];
                $imgContent = addslashes(file_get_contents($image));

                // insert image content into database 
                $query = "INSERT INTO gallery (Image, Created) VALUES ('$imgContent', NOW())";
                $insert = mysqli_query($conn, $query);

                if($insert){
                    $statusMsg = 'File uploaded successfully.';
                } else {
                    $statusMsg = 'File upload failed. Please try again.';
                }
            } else {
                $statusMsg = "Sorry, only JPG, PNG, JPEG, and GIF files are allowed to upload.";
            }
        }
    } else{
        $statusMsg = "Please select an image file to upload.";
    } 
}

if ($status === 'success') {
    $_SESSION['statusMsg'] = 'File uploaded successfully.';
    header('Location: Glry-admin.php');
    exit();
}
?>
