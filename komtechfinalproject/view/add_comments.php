<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include('../db/config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are filled
    if (empty($_POST['userName']) || empty($_POST['commentInput']) || empty($_FILES['profilePicture'])) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Get the form data
    $userName = $conn->real_escape_string($_POST['userName']);
    $comment = $conn->real_escape_string($_POST['commentInput']);

    // Handle profile picture upload
    $profilePicture = $_FILES['profilePicture'];
    $profilePictureName = time() . '-' . basename($profilePicture['name']);
    $uploadDir = 'uploads/';
    
    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file to the upload directory
    if (move_uploaded_file($profilePicture['tmp_name'], $uploadDir . $profilePictureName)) {
        // Insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, profile_picture) VALUES (?, ?)");
        $stmt->bind_param("ss", $userName, $profilePictureName);
        if ($stmt->execute()) {
            $userId = $stmt->insert_id; // Get the inserted user ID

            // Insert the comment into the database
            $stmt = $conn->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $comment);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to insert comment"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert user"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload profile picture"]);
    }
}

$conn->close();
?>
