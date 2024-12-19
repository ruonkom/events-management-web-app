<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Database Configuration
include('../db/config.php'); 

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve Products from Database
$sql = "SELECT name, description FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KomTech - Products</title>
    <link rel="stylesheet" href="../assets/services.css">
</head>
<body>
    <header class="header">
        <h1>Our Products</h1>
    </header>
    <nav class="navigation">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="services.php" class="active">Products</a></li>
        </ul>
    </nav>
    <section class="content">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='service'>";
                echo "<h2>" . $row['name'] . "</h2>";
                echo "<p>" . $row['description'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No products available at the moment.</p>";
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
