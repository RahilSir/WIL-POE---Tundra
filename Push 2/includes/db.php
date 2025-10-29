<?php
require_once 'config.php';
require_once 'ErrorHandler.php';

// Initialize error handling
ErrorHandler::init();

$servername = DB_HOST;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$database = DB_NAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection with proper error handling
if ($conn->connect_error) {
    ErrorHandler::handleDatabaseError($conn, 'Database Connection');
    // Don't die, let the error handler manage it
    $conn = null;
} else {
    // Set charset to prevent issues
    $conn->set_charset("utf8");
}
?>
