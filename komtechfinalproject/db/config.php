<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Define server details needed to connect to the database
$servername = 'localhost'; 
$username = 'ruon.kom';        
$password = 'Ranlel$20#';            // The password to access the database (empty if none is set)
$dbname = 'webtech_fall2024_ruon_kom'; // actual_database_name

// Attempt to connect to the database using the provided details
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection and handle errors
if (!$conn) {
    die('Unable to connect: ' . mysqli_connect_error());
} else {
    echo '';
}
?>
