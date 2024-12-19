<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../db/config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the comments and associated user data
$query = "SELECT comments.comment, comments.timestamp, users.name, users.profile_picture 
          FROM comments
          JOIN users ON comments.user_id = users.id
          ORDER BY comments.timestamp DESC";

$result = $conn->query($query);

// Check for errors in the query
if (!$result) {
    die("Error fetching data: " . $conn->error);
}

if ($result->num_rows > 0) {
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode(['comments' => $comments]);
} else {
    echo json_encode(['comments' => []]);
}

$conn->close();
?>
