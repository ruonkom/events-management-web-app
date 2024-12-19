<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include('../db/config.php');
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $data['id'];
    $query = "DELETE FROM event_booking WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Booking deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting booking"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing booking ID"]);
}
mysqli_close($conn);
?>
