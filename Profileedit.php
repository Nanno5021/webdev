<?php
session_start();
include 'dbcon.php';

// Handle logout
if(isset($_POST['btnlogout'])){
    unset($_SESSION['loggedin']);
    header('Location: index.php');
    exit();
}

// Check if user is logged in
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $userID = $_SESSION['id'];
    $query = "SELECT * FROM member WHERE MemberID = '$userID'";
    $result = mysqli_query($conn, $query);

    // Check if any record is found
    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);

        // Handle form submission for updating profile
        if(isset($_POST["btnupdate"])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phnum = $_POST['phnum'];
            $statusMsg = ''; // Initialize status message

            // Update profile picture if provided
            if (!empty($_FILES["image"]["name"])) {
                $fileName = basename($_FILES["image"]["name"]);
                $fileSize = $_FILES["image"]["size"];
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

                // Check if file size exceeds 1 MB (1048576 bytes)
                if ($fileSize > 1048576) {
                    $statusMsg = "Sorry, the uploaded image size exceeds 1 MB. Please choose an image under 1 MB in size.";
                } else {
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

            // Execute the update query if there are no errors
            if (empty($statusMsg)) {
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $_SESSION['statusMsg'] = 'Profile updated successfully.';
                } else {
                    $_SESSION['statusMsg'] = 'Failed to update profile. Please try again.';
                }
            } else {
                $_SESSION['statusMsg'] = $statusMsg; // Set status message for image size limit exceeded
            }

            // Redirect back to the edit profile page
            header('Location: Profileedit.php');
            exit();
        }
        
        // Display user details in a form for editing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0d6539403e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/profileedit.css">
    <title>Edit Profile</title>
</head>
<body>
    <?php
    if(isset($_SESSION['statusMsg'])) {
        echo '<p>' . $_SESSION['statusMsg'] . '</p>';
        unset($_SESSION['statusMsg']);
    }
    ?>
    <div class="wrapper">
        <a href="profile.php"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="form">
            <h2>Edit Profile</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="name">Name:<input type="text" name="name" value="<?php echo $row['Name']; ?>"></div>
                <div class="email">Email:<input type="email" name="email" value="<?php echo $row['Email']; ?>"></div>
                <div class="phnum">Phone Number:<input type="tel" name="phnum" value="<?php echo $row['Phone_num']; ?>"></div>
                <div class="propic">Profile Picture:<input type="file" name="image"><br></div>  
            </form>
        </div>
        <button class="update" type="submit" name="btnupdate">Update</button>
    </div>
</body>
</html>
<?php
    } else {
        echo 'No record found.';
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
?>
