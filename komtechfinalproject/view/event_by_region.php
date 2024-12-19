<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Define server details needed to connect to the database
include('../db/config.php');

// Attempt to connect to the database using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
} else {
    echo '<br>';
}

// Fetch data from the 'regions' table
$query = "SELECT region_name, events_planned FROM regions";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Initialize an array to store the results for use in the JavaScript chart
$regions = [];
while ($row = $result->fetch_assoc()) {
    $regions[] = $row;
}

// Free the result set and close the connection
$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event per Region</title>
    <link rel="stylesheet" href="../assets/region.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Events Planned in All Regions</h1>
    </header>
    <main>
        <section class="chart-section">
            <h2>Events Organized per Regions</h2>
            <canvas id="eventChart" width="400" height="400"></canvas>
        </section>
        <section class="data-section">
            <h2>Regional Data</h2>
            <table>
                <thead>
                    <tr>
                        <th>Region</th>
                        <th>Events Planned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regions as $region): ?>
                        <tr>
                            <td><?= htmlspecialchars($region['region_name']) ?></td>
                            <td><?= htmlspecialchars($region['events_planned']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Event Planner Management System</p>
    </footer>

    <!-- Pass data from PHP to JavaScript -->
    <script>
        const regionData = <?php echo json_encode($regions, JSON_HEX_TAG); ?>;

        // Process and chart the data using Chart.js
        const ctx = document.getElementById('eventChart').getContext('2d');
        const eventChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: regionData.map(region => region.region_name),
                datasets: [{
                    label: 'Events by Region',
                    data: regionData.map(region => region.events_planned),
                    backgroundColor: [
                        '#FF6384', // Example color for regions
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return `${tooltipItem.label}: ${tooltipItem.raw} events`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
