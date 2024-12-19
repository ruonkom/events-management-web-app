<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Database Configuration
include '../db/config.php';

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve About Content from Database
$sql = "SELECT section_title, section_content FROM about";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KomTech - About Us</title>
    <link rel="stylesheet" href="../assets/about.css">
</head>
<body>
    <header class="header">
        <h1>About KomTech</h1>
    </header>
    <nav class="navigation">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" class="active">About Us</a></li>
            <li><a href="services.php">Services</a></li>
        </ul>
    </nav>
    <section class="content">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<h2>" . $row['section_title'] . "</h2>";
                echo "<p>" . $row['section_content'] . "</p>";
            }
        } else {
            echo "<p>No content available at the moment.</p>";
        }
        ?>
    </section>
    <footer class="footer">
        &copy; 2024 KomTech. All rights reserved.
    </footer>
</body>
</html>

<?php
$conn->close();
?>
