<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include('../db/config.php'); 

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve Team Members from Database
$sql = "SELECT name, role, image_path FROM team";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../assets/images/pexels-photo-12881020.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: #212529;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-color: rgba#f4f0ec;
            background-blend-mode: lighten;
        }

        .team-section {
            padding: 50px 20px;
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .team-section h1 {
            margin-bottom: 30px;
            font-size: 2.5em;
            color: #fff600;
            text-align: center;
        }

        .team-members {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
        }

        .member {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 30px;
            width: 300px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .member:hover {
            transform: scale(1.05);
        }

        .member img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 5px solid #007BFF;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .member h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #333;
        }

        .member p {
            margin: 5px 0;
            font-size: 1.1em;
            color: #555;
        }

        .go-back {
            display: inline-block;
            margin: 20px auto;
            padding: 12px 25px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .go-back:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="team-section">
        <h1>Meet Our Team</h1>
        <div class="team-members">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='member'>";
                    echo "<img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>";
                    echo "<h2>" . $row['name'] . "</h2>";
                    echo "<p>" . $row['role'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No team members available at the moment.</p>";
            }
            ?>
        </div>
    </div>
    <a href="index.php" class="go-back">Go Back to Home</a>
</body>
</html>

<?php
$conn->close();
?>
