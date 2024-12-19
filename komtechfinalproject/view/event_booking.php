<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database connection
include('../db/config.php');

// Fetch bookings from the database
$sql = "SELECT * FROM event_booking";
$result = $conn->query($sql);

// Fetching the total number of bookings and the last booking date
$totalEvents = $result->num_rows;
$lastBookingDate = 'N/A';
$bookings = [];

if ($totalEvents > 0) {
    // Store bookings in an array
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    // Get the last booking date from the most recent entry
    $lastBookingDate = end($bookings)['booking_date'];
}

// Closing the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking</title>
    <link rel="stylesheet" href="../assets/event_booking.css">
    <script src="../assets/event_booking.js" defer></script>
</head>
<body>
    <header class="header">
        <h1>Event Booking Management</h1>
        <button onclick="goBack()" class="back-button">Back</button>
    </header>
    
    <main>
        <section class="summary-section">
            <h2>Booking Summary</h2>
            <div class="summary-details">
                <p>Total Events Booked: <span id="totalEvents"><?= $totalEvents ?></span></p>
                <p>Last Booking Date: <span id="lastBookingDate"><?= $lastBookingDate ?></span></p>
            </div>
        </section>

        <section class="history-section">
            <h2>Booking History</h2>
            <button onclick="openAddBookingModal()">Book NOW</button>
            <table id="bookingHistoryTable">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Client Name</th>
                        <th>Contact</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody">
                    <!-- Dynamic Content from JavaScript -->
                </tbody>
            </table>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2024 Event Management System</p>
    </footer>

    <!-- Add Booking Modal -->
    <div id="addBookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addBookingModal')">&times;</span>
            <h3>Add or Update Booking</h3>
            <form id="addBookingForm">
                <input type="hidden" id="bookingId">
                <label for="eventName">Event Name:</label>
                <input type="text" id="eventName" required>
                <label for="bookingDate">Date:</label>
                <input type="date" id="bookingDate" required>
                <label for="eventLocation">Location:</label>
                <input type="text" id="eventLocation" required>
                <label for="clientName">Client Name:</label>
                <input type="text" id="clientName" required>
                <label for="clientContact">Contact:</label>
                <input type="text" id="clientContact" required>
                <label for="paymentStatus">Payment Status:</label>
                <select id="paymentStatus" required>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                </select>
                <button type="button" onclick="eventBookingSystem.addOrUpdateBooking()">Submit</button>
            </form>
        </div>
    </div>

    <script>
        // Pass PHP data to JavaScript (encode PHP array to JS)
        const bookings = <?php echo json_encode($bookings); ?>;
        const totalEvents = <?php echo $totalEvents; ?>;
        const lastBookingDate = <?php echo json_encode($lastBookingDate); ?>;

        // Function to render the booking history dynamically
        function renderBookingHistory() {
            const tableBody = document.getElementById('bookingTableBody');
            tableBody.innerHTML = '';  // Clear existing table rows

            bookings.forEach(booking => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${booking.id}</td>
                    <td>${booking.event_name}</td>
                    <td>${booking.booking_date}</td>
                    <td>${booking.event_location}</td>
                    <td>${booking.client_name}</td>
                    <td>${booking.client_contact}</td>
                    <td>${booking.payment_status}</td>
                    <td>
                        <button onclick="editBooking(${booking.id})">Edit</button>
                        <button onclick="deleteBooking(${booking.id})">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Call the render function on page load
        window.onload = renderBookingHistory;
    </script>
</body>
</html>
