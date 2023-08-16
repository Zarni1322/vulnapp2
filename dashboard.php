<?php
include 'config.php';

session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// Check if the user has permission to access the dashboard
$allowed_user_id = 1; // Change this to the specific user ID that is allowed
if ($_SESSION["user_id"] != $allowed_user_id) {
    echo "You don't have permission to access this dashboard.";
    exit();
}

$users = [];
$search_results = [];

// Fetch all user information
$fetchUsersSQL = "SELECT id, username, email FROM users";
$result = $conn->query($fetchUsersSQL);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search_term = $_POST["search_term"];

    $searchSQL = "SELECT id, username, email FROM users WHERE username LIKE '%$search_term%' OR email LIKE '%$search_term%'";
    $search_result = $conn->query($searchSQL);
    if ($search_result->num_rows > 0) {
        while ($row = $search_result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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

        .dashboard-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            width: 600px;
        }

        .dashboard-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .user-table th,
        .user-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .user-list-header {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .logout-link {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>User Information</h2>
        <form class="search-form" method="post" action="">
            <input type="text" name="search_term" placeholder="Search by username or email">
            <button type="submit" name="search">Search</button>
        </form>
        
        <table class="user-table">
            <tr class="user-list-header">
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
            </tr>
            <?php
            if (!empty($search_results)) {
                foreach ($search_results as $user) {
                    echo '<tr>
                            <td>' . $user["id"] . '</td>
                            <td>' . $user["username"] . '</td>
                            <td>' . $user["email"] . '</td>
                          </tr>';
                }
            } else {
                foreach ($users as $user) {
                    echo '<tr>
                            <td>' . $user["id"] . '</td>
                            <td>' . $user["username"] . '</td>
                            <td>' . $user["email"] . '</td>
                          </tr>';
                }
            }
            ?>
        </table>
        
        <div class="logout-link">
            <p><a href="index.php">Logout</a></p>
        </div>
    </div>
</body>
</html>
