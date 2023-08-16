<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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
    <?php
    include 'config.php';

    session_start();
    $login_error = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        $login_username = $_POST["login_username"];
        $login_password = $_POST["login_password"];
        $login_sql = "SELECT id, username, password FROM users WHERE username = ?";
        $login_stmt = $conn->prepare($login_sql);
        $login_stmt->bind_param("s", $login_username);
        $login_stmt->execute();
        $login_stmt->bind_result($id, $username, $hashed_password);
        $login_stmt->fetch();

    if (password_verify($login_password, $hashed_password)) {
        $_SESSION["user_id"] = $id;
        header("Location: profile.php"); // Redirect to the dashboard or home page
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }

        $login_stmt->close();
    }
    ?>
    <div class="login-container">
        <h2>Login Page</h2>
        <form class="login-form" method="post" action="">
            <input type="text" name="login_username" placeholder="Username" required><br>
            <input type="password" name="login_password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="register-link">
            <p>If you don't have an account.</p>
            <a href="register.php">Register Here!</a>
        </div>
        <?php if (isset($login_error)) { echo '<p style="color: red;">' . $login_error . '</p>'; } ?>
    </div>
</body>
</html>
