<?php
session_start();
include 'dbcon.php';

// If the registration form is submitted
if (isset($_POST["btnregister"])) {
    $name = $_POST['name'];
    $em = $_POST['email'];
    $pw = $_POST['pass'];
    $phnum = $_POST['phnum'];
    $ff = $_POST['ff'];
    $fd = $_POST['fd'];
    $ft = $_POST['ft'];
    $status = 'error';
    $propic = '';
    $statusMsg = ''; // Initialize status message

    // Check if an image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        // Get uploaded file info
        $fileName = basename($_FILES["image"]["name"]);
        $fileSize = $_FILES["image"]["size"]; // Get the size of the uploaded file in bytes

        // Check if file size exceeds 1 MB (1048576 bytes)
        if ($fileSize > 1048576) {
            $statusMsg = "Sorry, the uploaded image size exceeds 1 MB. Please choose an image under 1 MB in size.";
        } else {
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array($fileType, $allowTypes)) {
                $image = $_FILES['image']['tmp_name'];
                $propic = addslashes(file_get_contents($image));
            } else {
                $statusMsg = "Sorry, only JPG, PNG, JPEG, and GIF files are allowed to upload.";
            }
        }
    } else {
        // Set default profile picture path
        $defaultImage = 'profile.png';
        $propic = addslashes(file_get_contents($defaultImage));
    }

    // Proceed with storing user data if status message is empty (no errors)
    if (empty($statusMsg)) {
        // Proceed with storing user data
        $queryCount = "SELECT COUNT(MemberID) as totalMembers FROM member";
        $resultCount = $conn->query($queryCount);

        // Fetch the result
        $row = $resultCount->fetch_assoc();
        $totalMembers = $row['totalMembers'];
                    
        // Insert profile content into the database
        $newMemberID = 'M' . ($totalMembers + 1);
        $query = "INSERT INTO member (MemberID, ProfilePic, Name, Role, Email, Password, Phone_num, Sec_ques1, Sec_ques2, Sec_ques3) 
                VALUES ('$newMemberID','$propic', '$name', 'Member', '$em', '$pw', '$phnum', '$ff', '$fd', '$ft')";
        $insert = mysqli_query($conn, $query);
        
        if ($insert) {
            $status = 'success';
            $statusMsg = 'Registration successful.';
        } else {
            $statusMsg = 'Registration failed. Please try again.';
        }
    }

    // Redirect the user based on the registration status
    if ($status === 'success') {
        echo '<script>
        alert("Registered successfully");
        window.location.replace("login.php");
        </script>';
    } else {
        echo '<script>
        alert("' . $statusMsg . '");
        window.location.replace("register.php");
        </script>';
    }
}
?>
