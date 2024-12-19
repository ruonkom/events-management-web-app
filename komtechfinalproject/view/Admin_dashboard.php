<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Check if config.php exists
if (!file_exists('../db/config.php')) {
    die("Configuration file is missing.");
}

// Include the database configuration file
include '../db/config.php';

// Create a new MySQLi connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalUsersResult = $mysqli->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'] ?? 0;

// Fetch total events
$totalEventsQuery = "SELECT COUNT(*) AS total_events FROM events";
$totalEventsResult = $mysqli->query($totalEventsQuery);
$totalEvents = $totalEventsResult->fetch_assoc()['total_events'] ?? 0;

// Fetch average rating
$avgRatingQuery = "SELECT AVG(rating) AS avg_rating FROM events";
$avgRatingResult = $mysqli->query($avgRatingQuery);
$avgRating = $avgRatingResult->fetch_assoc()['avg_rating'] ?? 0;

// Fetch top 5 active users (by number of events hosted)
$topUsersQuery = "
    SELECT u.username, COUNT(e.id) AS events_hosted
    FROM users u
    JOIN events e ON u.id = e.hosted_by
    GROUP BY u.id
    ORDER BY events_hosted DESC
    LIMIT 5
";
$topUsersResult = $mysqli->query($topUsersQuery);

$topUsers = [];
while ($row = $topUsersResult->fetch_assoc()) {
    $topUsers[] = $row;
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/Admin_dashboard.css">
</head>
<body>
    <nav class="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <ul>
            <li><a href="users.php">Users management</a></li>
            <li><a href="attendees.php">Events Attendees</a></li>
            <li><a href="event_ticket.php">Ticket management</a></li>
            <li><a href="regular_dashboard.php">Regular dashboard</a></li>
            <li><a href="../actions/logout.php">Logout</a>
        </ul>
    </nav>

    <section class="analytics-section">
        <div class="stats">
            <div class="stat-item users">
                <i class="fas fa-users stat-icon"></i> 
                <h3>Total Users</h3>
                <p id="total-users"><?php echo $totalUsers; ?></p>
            </div>
            <div class="stat-item coconut">
                <i class="fas fa-leaf stat-icon"></i> 
                <h3>Total events organized</h3>
                <p id="total-coconuts"><?php echo $totalEvents; ?></p> 
            </div>
            <div class="stat-item ratings">
                <i class="fas fa-star stat-icon"></i> 
                <h3>Avg Rating</h3>
                <p id="avg-rating"><?php echo number_format($avgRating, 1); ?></p> 
            </div>
        </div>

        <!-- Bar Chart: events Created Per Month -->
        <div>
            <h2>Events Organized Per Month</h2>
            <div class="bar-chart">
                <div class="bar" style="height: 200px;">
                    <span>50</span> 
                    <span>Jan</span>
                </div>
                <div class="bar" style="height: 150px;">
                    <span>30</span> 
                    <span>Feb</span>
                </div>
                <div class="bar" style="height: 220px;">
                    <span>60</span> 
                    <span>Mar</span>
                </div>
                <div class="bar" style="height: 180px;">
                    <span>40</span> 
                    <span>Apr</span>
                </div>
                <div class="bar" style="height: 300px;">
                    <span>70</span> 
                    <span>May</span>
                </div>
            </div>
        </div>

        <!-- Top Users List -->
        <div class="top-users-list">
            <h2>Top 5 Most Active Users</h2>
            <ul>
                <?php foreach ($topUsers as $user): ?>
                    <li>
                        <span class="user-name"><?php echo htmlspecialchars($user['username']); ?></span><br>
                        <span class="user-events"><?php echo $user['events_hosted']; ?> events hosted</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <script>
        function toggleMenu() {
            const navbar = document.querySelector('.navbar');
            const ul = navbar.querySelector('ul');
            ul.classList.toggle('show');
        }
    </script>
</body>
</html>
