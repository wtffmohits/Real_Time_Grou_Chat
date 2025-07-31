<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db_connect.php');

$query = "SELECT * FROM chat_messages ORDER BY id ASC";
$result = mysqli_query($conn, $query);

$messages = [];
while($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}
header('Content-Type: application/json');
echo json_encode($messages);
?>
