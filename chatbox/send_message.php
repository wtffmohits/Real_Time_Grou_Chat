<?php
// 1) Show errors (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2) Start session so we can get username
session_start();

// 3) Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    echo "Error: Not logged in";
    exit();
}

// 4) Include your DB connection file
include("db_connect.php");

// 5) Sanitize the message input
$message = mysqli_real_escape_string($conn, $_POST['message']);

// 6) Insert message into your chat_messages table
$username = $_SESSION['user_name']; // from the session
$query = "INSERT INTO chat_messages (username, message) VALUES ('$username', '$message')";

if (mysqli_query($conn, $query)) {
    echo "Message Sent!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
