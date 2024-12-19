<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Database Configuration

// Check if config.php exists
if (!file_exists('config.php')) {
    die("Configuration file is missing.");
}

// Include the database configuration file
include '../db/config.php';
// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle New Comment Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['userName'];
    $comment = $_POST['comment'];
    $profilePicture = $_FILES['profilePicture'];

    // Upload Profile Picture
    $uploadDir = "uploads/";

    // Check if the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
    }

    // Sanitize the file name to avoid issues with special characters
    $uploadFile = $uploadDir . basename(preg_replace('/[^a-zA-Z0-9-_\.]/', '', $profilePicture["name"]));

    if (move_uploaded_file($profilePicture["tmp_name"], $uploadFile)) {
        $profilePath = $uploadFile;

        // Insert Comment into Database
        $stmt = $conn->prepare("INSERT INTO comments (name, profile_picture, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $userName, $profilePath, $comment);

        if ($stmt->execute()) {
            echo "Comment added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading profile picture. Error code: " . $_FILES['profilePicture']['error'];
    }
}

// Retrieve All Comments
$sql = "SELECT name, profile_picture, comment FROM comments ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Comment Section</title>
    <link rel="stylesheet" href="../assets/comment.css">
</head>
<body>
    <div class="comment-section">
        <h1>Comment Section</h1>
        <form id="commentForm" method="POST" enctype="multipart/form-data">
            <input type="text" name="userName" placeholder="Enter your name" required>
            <input type="file" name="profilePicture" accept="image/*" required>
            <textarea name="comment" placeholder="Write your comment here..." required></textarea>
            <button type="submit">Post Comment</button>
        </form>
        <div id="commentsList">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='comment'>";
                    echo "<img src='" . $row['profile_picture'] . "' alt='" . $row['name'] . "' class='profile-picture'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No comments yet.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
