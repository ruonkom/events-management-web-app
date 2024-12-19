<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Database connection
// Check if config.php exists
if (!file_exists('config.php')) {
    die("Configuration file is missing.");
}

// Include the database configuration file
include '../db/config.php';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding, updating, deleting, or fetching attendees
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $ticket_type = $_POST['ticket_type'];
        $registration_date = $_POST['registration_date'];
        $status = $_POST['status'];

        $sql = "INSERT INTO attendees (name, email, ticket_type, registration_date, status) 
                VALUES ('$name', '$email', '$ticket_type', '$registration_date', '$status')";
        $conn->query($sql);
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $check_in = $_POST['check_in'];

        $sql = "UPDATE attendees SET status='$status', check_in='$check_in' WHERE id=$id";
        $conn->query($sql);
    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM attendees WHERE id=$id";
        $conn->query($sql);
    }
    exit;
}

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT * FROM attendees WHERE 
        (name LIKE '%$search%' OR email LIKE '%$search%') " . 
        ($filter !== 'all' ? "AND status='$filter'" : "");
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendees Page</title>
  <link rel="stylesheet" href="../assets/attendees.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2>Attendees for Event Name</h2>
        <div class="controls">
          <div class="search-bar">
            <form method="GET" action="">
              <input type="text" name="search" placeholder="Search attendees..." value="<?php echo htmlspecialchars($search); ?>">
              <button type="submit">Search</button>
            </form>
          </div>
          <div class="filter-dropdown">
            <form method="GET" action="">
              <select name="filter" onchange="this.form.submit()">
                <option value="all" <?php if ($filter === 'all') echo 'selected'; ?>>All Attendees</option>
                <option value="confirmed" <?php if ($filter === 'confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="pending" <?php if ($filter === 'pending') echo 'selected'; ?>>Pending</option>
                <option value="cancelled" <?php if ($filter === 'cancelled') echo 'selected'; ?>>Cancelled</option>
              </select>
            </form>
          </div>
        </div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Ticket Type</th>
            <th>Registration Date</th>
            <th>Status</th>
            <th>Check-in</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="attendeeTable">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['ticket_type']); ?></td>
                <td><?php echo htmlspecialchars($row['registration_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo $row['check_in'] ? 'Yes' : 'No'; ?></td>
                <td>
                  <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7">No attendees found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="../assets/attendees.js"></script>
</body>
</html>
