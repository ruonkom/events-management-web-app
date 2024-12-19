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

// Retrieve Links from Database
$sql = "SELECT name, url FROM links";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KomTech - Home</title>
    <!-- Linking the CSS file -->
    <link rel="stylesheet" href="../assets/index.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>KomTech</h1>
            <p>Connecting Events organizers, vendors, event attendees, and clients</p>
        </div>
        <nav>
            <ul>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li><a href='" . $row['url'] . "'>" . $row['name'] . "</a></li>";
                    }
                } else {
                    echo "<li>No links available</li>";
                }
                ?>
            </ul>
        </nav>
    </header>
    
    <main>
        <section>
            <h1>Welcome to KomTech</h1>
            <p>Your trusted platform for event planning in Ghana. Connect, share, and grow with us!
                KomTech is a web application designed to enhance communication and interaction among event organizers and clients in Ghana. 
                The platform enables users to engage with one another through various features, fostering collaboration and building a supportive community.
            </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 KomTech. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
