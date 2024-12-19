<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include the database configuration file
include('../db/config.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database
function fetchUsers($conn) {
    $sql = "SELECT id, username, email FROM users";
    $result = $conn->query($sql);
    $users = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Add user to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        // Sanitize inputs
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);

        // Validate inputs
        if (empty($username) || empty($email)) {
            echo "Username and email are required.";
            exit();
        }

        // Check if the email already exists in the database
        $sqlCheckEmail = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($sqlCheckEmail);
        if ($result && $result->num_rows > 0) {
            echo "The email address is already in use.";
            exit();
        }

        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email) VALUES ('$username', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo "New user added successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
        exit();
    }

    // Update user information
    if ($action === 'update') {
        $id = intval($_POST['id']);
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);

        // Update the user in the database
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "User updated successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
        exit();
    }

    // Delete user from the database
    if ($action === 'delete') {
        $id = intval($_POST['id']);

        $sql = "DELETE FROM users WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "User deleted successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
        exit();
    }
}

// Fetch users
$users = fetchUsers($conn);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../assets/user.css">
</head>
<body>
    <div class="container">
        <h2>User Management Interface</h2>

        <form id="addUserForm" method="POST">
            <h3>Add User</h3>
            <input type="hidden" name="action" value="add">
            <label for="username">Name:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Add User</button>
        </form>

        <form id="updateUserForm" method="POST" style="display: none;">
            <h3>Update User</h3>
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="update-id" name="id">
            <label for="update-username">Name:</label>
            <input type="text" id="update-username" name="username" required>
            <label for="update-email">Email:</label>
            <input type="email" id="update-email" name="email" required>
            <button type="submit">Update User</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']); ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['email']); ?></td>
                            <td>
                                <button onclick="viewUser('<?= htmlspecialchars($user['username']); ?>', '<?= htmlspecialchars($user['email']); ?>')">View</button>
                                <button onclick="editUser(<?= htmlspecialchars($user['id']); ?>, '<?= htmlspecialchars($user['username']); ?>', '<?= htmlspecialchars($user['email']); ?>')">Update</button>
                                <button onclick="deleteUser(<?= htmlspecialchars($user['id']); ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                const form = new FormData();
                form.append('action', 'delete');
                form.append('id', id);

                fetch('', {
                    method: 'POST',
                    body: form
                }).then(response => response.text()).then(data => {
                    alert(data);
                    location.reload();
                });
            }
        }

        function editUser(id, username, email) {
            document.getElementById('addUserForm').style.display = 'none';
            const updateForm = document.getElementById('updateUserForm');
            updateForm.style.display = 'block';
            document.getElementById('update-id').value = id;
            document.getElementById('update-username').value = username;
            document.getElementById('update-email').value = email;
        }

        function viewUser(username, email) {
            alert(`Name: ${username}\nEmail: ${email}`);
        }
    </script>
</body>
</html>
