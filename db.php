<?php
$servername = "localhost";
$username = "root"; // change if needed
$password = "";     // change if needed
$database = "tundra"; // replace with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
