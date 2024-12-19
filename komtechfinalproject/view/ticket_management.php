<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database connection file
include('../db/config.php');

// Check if it's a GET request to fetch tickets
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all tickets from the database
    $sql = "SELECT * FROM events_tickets";
    $result = $conn->query($sql);

    $tickets = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
    }

    // Output tickets as JSON
    echo json_encode($tickets);

    $conn->close();
    exit;
}

// Check if it's a POST request to add or update ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form data
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $event_date_time = $conn->real_escape_string($_POST['event_date_time']);
    $venue = $conn->real_escape_string($_POST['venue']);
    $price = floatval($_POST['price']);
    $status = $conn->real_escape_string($_POST['status']);
    $ticket_holder = $conn->real_escape_string($_POST['ticket_holder']);

    if ($id) {
        // Update existing ticket
        $sql = "UPDATE events_tickets 
                SET event_name = '$event_name', 
                    event_date_time = '$event_date_time', 
                    venue = '$venue', 
                    price = '$price', 
                    status = '$status', 
                    ticket_holder = '$ticket_holder' 
                WHERE id = $id";
        
        if ($conn->query($sql) === TRUE) {
            echo "Ticket updated successfully.";
        } else {
            echo "Error updating ticket: " . $conn->error;
        }
    } else {
        // Insert new ticket
        $sql = "INSERT INTO events_tickets (event_name, event_date_time, venue, price, status, ticket_holder) 
                VALUES ('$event_name', '$event_date_time', '$venue', '$price', '$status', '$ticket_holder')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New ticket added successfully.";
        } else {
            echo "Error adding ticket: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Tickets</title>
</head>
<body>
    <h2>Event Tickets</h2>
    <form id="ticketForm" method="POST" action="your_php_script.php">
        <input type="hidden" name="id" id="ticketId"> <!-- Hidden input for ID when updating -->
        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name"><br>

        <label for="event_date_time">Event Date & Time:</label>
        <input type="datetime-local" id="event_date_time" name="event_date_time"><br>

        <label for="venue">Venue:</label>
        <input type="text" id="venue" name="venue"><br>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price"><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status"><br>

        <label for="ticket_holder">Ticket Holder:</label>
        <input type="text" id="ticket_holder" name="ticket_holder"><br>

        <button type="submit">Submit</button>
    </form>

    <h3>Ticket List</h3>
    <button id="loadTicketsBtn">Load Tickets</button>
    <table id="ticketTable">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Event Date & Time</th>
                <th>Venue</th>
                <th>Price</th>
                <th>Status</th>
                <th>Ticket Holder</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Function to load tickets from the database
            function loadTickets() {
                fetch("", { method: "GET" })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Log the fetched data to check if it's correct
                        const tableBody = document.querySelector("#ticketTable tbody");
                        tableBody.innerHTML = ""; // Clear existing rows

                        if (data.length === 0) {
                            // Handle empty data case
                            tableBody.innerHTML = "<tr><td colspan='7'>No tickets available.</td></tr>";
                        } else {
                            // Loop through the data and create table rows dynamically
                            data.forEach(ticket => {
                                const row = document.createElement("tr");
                                row.innerHTML = `
                                    <td>${ticket.event_name}</td>
                                    <td>${ticket.event_date_time}</td>
                                    <td>${ticket.venue}</td>
                                    <td>${ticket.price}</td>
                                    <td>${ticket.status}</td>
                                    <td>${ticket.ticket_holder}</td>
                                    <td>
                                        <button class="editBtn" data-id="${ticket.id}">Edit</button>
                                        <button class="deleteBtn" data-id="${ticket.id}">Delete</button>
                                    </td>
                                `;
                                tableBody.appendChild(row);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error loading tickets:", error);
                    });
            }

            // Load tickets when the page is loaded
            loadTickets();

            // Optional: Add functionality for edit/delete buttons here (if needed)
        });
    </script>
</body>
</html>
