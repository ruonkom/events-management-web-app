<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database connection
include('../db/config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign form data
    $fullName = isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $ticketCount = isset($_POST['ticketCount']) ? (int)$_POST['ticketCount'] : 0;
    $totalPrice = $ticketCount * 50; // $50 per ticket (can be adjusted)

    // Prepare the SQL query to insert booking data using prepared statements
    $stmt = $conn->prepare("INSERT INTO bookings (full_name, email, phone, ticket_count, total_price, event_name, event_date, location) 
                            VALUES (?, ?, ?, ?, ?, 'Tech Summit 2024', '2024-01-20 10:00:00', 'Accra International Conference Center')");
    $stmt->bind_param('sssis', $fullName, $email, $phone, $ticketCount, $totalPrice);

    if ($stmt->execute()) {
        echo "Booking successful! Your booking ID is: " . $stmt->insert_id;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Booking & Ticket Management</title>
  <link rel="stylesheet" href="../assets/buy_ticket.css">
</head>
<body>
  <header class="header">
    <h1>Event Booking & Ticket Management</h1>
  </header>
  <main>
    <!-- Event Overview Section -->
    <section class="event-overview">
      <h2>Upcoming Event: Tech Summit 2024</h2>
      <img src="Eventpic1.jpg" alt="Our Event" class="event-poster">
      <p><strong>Date & Time:</strong> January 20, 2024, 10:00 AM</p>
      <p><strong>Venue:</strong> Accra International Conference Center</p>
      <p><strong>Description:</strong> Join us for an exciting day of tech talks, networking, and innovation.</p>
      <p><strong>Price per Ticket:</strong> $50</p>
    </section>

    <!-- Ticket Booking Section -->
    <section class="booking-section">
      <h2>Book Your Tickets</h2>
      <form action="buy_ticket.php" method="POST">
        <label for="fullName">Full Name:</label>
        <input type="text" id="fullName" name="fullName" value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">

        <label for="ticketCount">Number of Tickets:</label>
        <input type="number" id="ticketCount" name="ticketCount" min="1" max="10" value="<?php echo isset($_POST['ticketCount']) ? htmlspecialchars($_POST['ticketCount']) : ''; ?>" required>

        <p><strong>Total Price:</strong> $<span id="totalPrice"><?php echo isset($totalPrice) ? $totalPrice : 0; ?></span></p>
        <button type="submit">Book Now</button>
      </form>
    </section>

    <!-- Booking History Section -->
    <section class="history-section">
      <h2>Booking History</h2>
      <table id="bookingHistoryTable">
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Client Name</th>
            <th>Contact</th>
            <th>Tickets</th>
            <th>Total Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Fetch all bookings from the database
          $result = $conn->query("SELECT * FROM bookings");
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['event_name']}</td>
                    <td>{$row['event_date']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['ticket_count']}</td>
                    <td>\${$row['total_price']}</td>
                    <td>
                      <a href='edit_booking.php?id={$row['id']}'>Edit</a> |
                      <a href='delete_booking.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this booking?');\">Delete</a> |
                      <a href='download_ticket.php?id={$row['id']}'>Download</a>
                    </td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>
  <footer>
    <p>&copy; 2024 Event Management System</p>
  </footer>
  <script src="../ assets/buy_ticket.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
