<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0d6539403e.js" crossorigin="anonymous"></script>
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="container">
        <div class='home'><a href="index.php"><i class="fa-solid fa-x"></i></a></div>
        <?php
        session_start();
        include 'dbcon.php';

        // Handle logout
        if(isset($_POST['btnlogout'])){
            unset($_SESSION['loggedin']);
            header('Location: logout.php');
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
                ?>
                <h2>Profile</h2>
                <div class="profile-picture">
                    <?php if($row['ProfilePic']) { ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['ProfilePic']); ?>" alt="Profile Picture">
                    <?php } else { ?>
                        <p>No profile picture uploaded.</p>
                    <?php } ?>
                </div>
                <div class="profile-info">
                    <div class="info">Name: <?php echo $row['Name']; ?></div>
                    <div class="info"><?php echo $row['Email']; ?></div>
                    <div class="info"><?php echo $row['Phone_num']; ?></div>
                </div>
                <div class="actions">
                    <button><a href="Profileedit.php">Edit</a></button>
                </div>
                <div class="logout">
                    <form action="" method="post"><input type="submit" value="Logout" name="btnlogout"></form>
                </div>
            <?php } else {
                echo 'No record found.';
            }
        } else {
            // Redirect to login page if user is not logged in
            header("Location: login.php");
            exit();
        }
        ?>
    </div>
</body>
</html>
