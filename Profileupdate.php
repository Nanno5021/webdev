<?php
session_start();
include 'dbcon.php';

if(isset($_POST["btnupdate"])) {
    $userID = $_POST['userID'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phnum = $_POST['phnum'];

    // Update profile picture if provided
    if (!empty($_FILES["image"]["name"])) {
        $fileName = basename($_FILES["image"]["name"]);
        $fileSize = $_FILES["image"]["size"];

        if($fileSize > 1048576) {
            echo "Sorry, only images under 1 MB are allowed.";
        } else {
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                $image = $_FILES['image']['tmp_name'];
                $propic = addslashes(file_get_contents($image));

                // Update profile with profile picture
                $query = "UPDATE member SET ProfilePic = '$propic', Name = '$name', Email = '$email', Phone_num = '$phnum' WHERE MemberID = '$userID'";
            } else {
                // Update profile without profile picture
                $query = "UPDATE member SET Name = '$name', Email = '$email', Phone_num = '$phnum' WHERE MemberID = '$userID'";
            }
        }
    } else {
        // Update profile without profile picture
        $query = "UPDATE member SET Name = '$name', Email = '$email', Phone_num = '$phnum' WHERE MemberID = '$userID'";
    }
        
        

    // Execute the update query
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['statusMsg'] = 'Profile updated successfully.';
    } else {
        $_SESSION['statusMsg'] = 'Failed to update profile. Please try again.';
    }
}

// Redirect back to the edit profile page
header('Location:profile.php');
exit();
?>
