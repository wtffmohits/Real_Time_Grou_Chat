<?php 
session_start();
include("db_connect.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['password']);

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){
        $user_id = random_num(20);
        $query = "INSERT INTO users (user_id, user_name, password) VALUES ('$user_id', '$user_name', '$password')";
        mysqli_query($conn, $query);

        $_SESSION['user_name'] = $user_name;
        header("Location: chat.php");
        die;
    } else {
        echo "Please enter some valid information!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <form method="POST" action="signup.php">
            <input type="text" name="user_name" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>
</body>
</html>
