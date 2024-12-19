<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case "GET":
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM purchases WHERE id = $id";
            $result = $conn->query($sql);
            echo json_encode($result->fetch_assoc());
        } else {
            $sql = "SELECT * FROM purchases ORDER BY id DESC";
            $result = $conn->query($sql);
            $purchases = [];
            while ($row = $result->fetch_assoc()) {
                $purchases[] = $row;
            }
            echo json_encode($purchases);
        }
        break;

    case "POST":
        $date = $data['date'];
        $region = $data['region'];
        $quantity = intval($data['quantity']);
        $cost = floatval($data['cost']);
        $seller = $data['seller'];
        $status = $data['status'];

        $sql = "INSERT INTO purchases (date, region, quantity, cost, seller, status)
                VALUES ('$date', '$region', $quantity, $cost, '$seller', '$status')";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "id" => $conn->insert_id]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    case "PUT":
        $id = intval($data['id']);
        $date = $data['date'];
        $region = $data['region'];
        $quantity = intval($data['quantity']);
        $cost = floatval($data['cost']);
        $seller = $data['seller'];
        $status = $data['status'];

        $sql = "UPDATE purchases 
                SET date = '$date', region = '$region', quantity = $quantity, 
                    cost = $cost, seller = '$seller', status = '$status' 
                WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    case "DELETE":
        $id = intval($_GET['id']);
        $sql = "DELETE FROM purchases WHERE id = $id";

        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Unsupported HTTP method"]);
        break;
}

$conn->close();
?>

