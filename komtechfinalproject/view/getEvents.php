<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include('../db/config.php');

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$event_type = $_GET['event_type'] ?? 'all';
$query = "SELECT * FROM events"; // Replace 'events' with your table name
if ($event_type !== 'all') {
    $query .= " WHERE event_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $event_type);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode(['success' => true, 'events' => $events]);
