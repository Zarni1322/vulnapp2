<?php
include 'config.php';

session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user profile
$user_profile = [];
$profileSQL = "SELECT id, username, email FROM users WHERE id = ?";
$profile_stmt = $conn->prepare($profileSQL);
$profile_stmt->bind_param("i", $user_id);
$profile_stmt->execute();
$profile_stmt->bind_result($profile_id, $profile_username, $profile_email);
$profile_stmt->fetch();
$profile_stmt->close();

// Handle photo upload
$upload_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_photo"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["upload_photo"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $upload_message = "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        $upload_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $upload_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $upload_message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $upload_message = "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";
        } else {
            $upload_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        /* Your existing CSS styles */

        .profile-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 0 auto;
            margin-top: 50px;
        }

        .profile-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-details {
            margin-top: 20px;
        }

        .profile-details p {
            font-size: 18px;
        }

        .profile-photo {
            text-align: center;
            margin-top: 20px;
        }

        .profile-photo img {
            max-width: 100%;
            max-height: 200px;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <div class="profile-details">
            <p><strong>ID:</strong> <?php echo $profile_id; ?></p>
            <p><strong>Username:</strong> <?php echo $profile_username; ?></p>
            <p><strong>Email:</strong> <?php echo $profile_email; ?></p>
        </div>

        <div class="profile-photo">
            <h3>Upload Profile Photo</h3>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="file" name="photo" id="photo" accept="image/*" required>
                <button type="submit" name="upload_photo">Upload</button>
            </form>
            <p><?php echo $upload_message; ?></p>
        </div>
        
        <div class="profile-photo">
            <?php
            $photo_path = "uploads/" . $profile_username . ".jpg"; // Assuming photo names are based on usernames//
            if (file_exists($photo_path)) {
                echo '<img src="' . $photo_path . '" alt="Profile Photo">';
            }
            ?>
        </div>
        
        <div class="logout-link">
            <p><a href="index.php">Logout</a></p>
        </div>
    </div>
</body>
</html>
