<?php
include 'config.php';

// Create the users table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($createTableSQL) === TRUE) {
    echo "";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// User registration
$registration_success = "";
$registration_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $reg_username = $_POST["reg_username"];
    $reg_email = $_POST["reg_email"];
    $reg_password = password_hash($_POST["reg_password"], PASSWORD_DEFAULT);

    $reg_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $reg_stmt = $conn->prepare($reg_sql);
    $reg_stmt->bind_param("sss", $reg_username, $reg_email, $reg_password);

    if ($reg_stmt->execute()) {
        $registration_success = "Registration successful. <a href='index.php'>Login</a>";
    } else {
        $registration_error = "Error: " . $reg_stmt->error;
    }

    $reg_stmt->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>User Registration and Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-form button {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Registration Form</h2>
        <form class="login-form" method="post" action="">
            <input type="text" name="reg_username" placeholder="Username" required><br>
            <input type="email" name="reg_email" placeholder="Email" required><br>
            <input type="password" name="reg_password" placeholder="Password" required><br>
            <button type="submit" name="register">Register</button>
        </form>
        <?php
            if (!empty($registration_success)) {
                echo '<p style="color: green;">' . $registration_success . '</p>';
            }
            if (!empty($registration_error)) {
                echo '<p style="color: red;">' . $registration_error . '</p>';
            }
        ?>
    </div>
</body>
</html>
