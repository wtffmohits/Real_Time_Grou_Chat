<?php
// Display all errors for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Include the database connection and any utility functions
include("db_connect.php");
include("functions.php");

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = trim($_POST['user_name']);
    $password  = trim($_POST['password']);

    // Basic validation
    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        
        // Check user in the database
        $query  = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            // Compare plain text passwords (in production, use password hashing)
            if ($user_data['password'] === $password) {
                // Store username in session
                $_SESSION['user_name'] = $user_data['user_name'];
                // Redirect to chat page
                header("Location: chat.php");
                exit;
            }
        }
        // If we reach here, credentials are wrong
        echo "Wrong username or password!";
    } else {
        echo "Wrong username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | My Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Sign In</h1>
        <form method="POST" action="login.php">
            <input type="text" name="user_name" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>

