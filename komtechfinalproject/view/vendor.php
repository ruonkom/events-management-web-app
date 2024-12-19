<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database connection
include('../db/config.php'); // Adjust the path as needed

// Fetch the vendor_id from the URL or set a default
$vendor_id = $_GET['vendor_id'] ?? 1; // Default to vendor ID 1 if not provided

// Prepare the query to fetch vendor details
$query = "SELECT * FROM vendors WHERE vendor_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the vendor exists
if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $name = $vendor['name'];
    $contact_info = $vendor['contact_info'];
    $created_at = $vendor['created_at'];
} else {
    // Handle the case where no vendor is found
    $name = "Vendor not found";
    $contact_info = "N/A";
    $created_at = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Page</title>
    <link rel="stylesheet" href="../assets/vendor.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="#vendor">Vendor</a></li>
            <li><a href="#gallery">Gallery</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <header>
        <h1>Vendor Profile</h1>
    </header>

    <!-- Dropdown for Event Types -->
    <section class="event-dropdown">
        <label for="event-types">Select Event Type:</label>
        <select id="event-types" onchange="filterEvents()">
            <option value="all">All Events</option>
            <option value="wedding">Wedding</option>
            <option value="corporate">Corporate</option>
            <option value="birthday">Birthday</option>
            <option value="conference">Conference</option>
        </select>
    </section>

    <!-- Vendor Details Section -->
    <section class="vendor-details">
        <h2><?php echo htmlspecialchars($name); ?></h2>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact_info); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($created_at); ?></p>
    </section>

    <!-- Pricing Section -->
    <section class="pricing">
        <h3>Pricing</h3>
        <ul id="pricing-list">
            <li class="wedding"><strong>Wedding Package:</strong> $5000</li>
            <li class="corporate"><strong>Corporate Event:</strong> $3000</li>
            <li class="birthday"><strong>Birthday Party:</strong> $1500</li>
            <li class="conference"><strong>Conference:</strong> $4000</li>
        </ul>
    </section>

    <!-- Gallery Section -->
    <section class="vendor-gallery" id="gallery">
        <h3>Event Gallery</h3>
        <div class="gallery-grid" id="gallery-grid">
            <img src="..//assets/images/Eventpic1.jpg" alt="Event Image 1">
            <img src="assets/images/Eventpic2.jpg" alt="Event Image 2">
            <img src="../assets/images/Eventpic3.jpg" alt="Event Image 3">
            <img src="../assets/images/Eventpic4.jpg" alt="Event Image 4">
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form" id="contact">
        <h3>Contact Vendor</h3>
        <form action="save_message.php" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" required></textarea>

            <input type="hidden" name="vendor_id" value="<?php echo htmlspecialchars($vendor_id); ?>">

            <button type="submit">Send Message</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Vendor Platform. All Rights Reserved.</p>
    </footer>

    <script src="../assets/vendor.js"></script>
</body>
</html>
