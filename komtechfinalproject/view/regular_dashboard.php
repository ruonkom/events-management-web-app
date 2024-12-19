<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Database connection
include '../db/config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events for Events by Region Section
$regionEventsQuery = "SELECT region, COUNT(*) AS event_count FROM events GROUP BY region";
$regionEventsResult = $conn->query($regionEventsQuery);
$regionData = [];
while ($row = $regionEventsResult->fetch_assoc()) {
    $regionData[] = $row;
}

// Fetch total vendors and last event date for Purchase Summary Section
$vendorsQuery = "SELECT COUNT(*) AS total_events, MAX(date) AS last_event_date FROM events";
$vendorsResult = $conn->query($vendorsQuery);
$vendorsData = $vendorsResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regular Dashboard</title>
    <link rel="stylesheet" href="../assets/regular_dashboard.css">
    <script src="../assets/regular_dashboard.js"></script>
</head>
<body>
    <header class="header">
        <h1>Regular Dashboard</h1>
        <p>Welcome to the Event Management System</p>
    </header>
    <nav>
        <ul>
            <li><a href="attendees.php">Attendees</a></li> 
            <li><a href="comment.php">Comments/Recommendation</a></li>
            <li><a href="event_by_region.php">Events per Regions</a></li>
            <li><a href="vendor.php">Vendors</a></li>
            <li><a href="event_booking.php">Book Events Now</a></li>
            <li><a href="buy_ticket.php">Buy Ticket</a></li>
        </ul>
    </nav>
    <main>
        <!-- Product Distribution by Region Section -->
        <section id="regionsSection">
            <h2>Product Distribution by Region</h2>
            <canvas id="regionPieChart" width="400" height="400"></canvas>
        </section>

        <!-- Purchase Summary Section -->
        <section id="summarySection">
            <h2>Our Vendors</h2>
            <div id="purchaseSummary">
                <p>Total events organized: <span id="totalEvents"><?php echo $vendorsData['total_events'] ?? 0; ?></span></p>
                <p>Last event date: <span id="lastPurchaseDate"><?php echo $vendorsData['last_event_date'] ?? 'N/A'; ?></span></p>
            </div>
        </section>
    </main>

    <script>
        // Pass PHP data to JavaScript for chart rendering
        const regionData = <?php echo json_encode($regionData); ?>;

        const labels = regionData.map(data => data.region);
        const values = regionData.map(data => data.event_count);

        // Render pie chart
        const ctx = document.getElementById('regionPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Events by Region',
                    data: values,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                }],
            },
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
