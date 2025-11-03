<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Database connection using centralized db.php
require_once '../../includes/db.php';

if (!$conn) {
    die("Database connection failed");
}

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    // Update query
    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $role, $user_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('User role updated successfully!');
                window.location.href = 'admindashboard.php'; // redirect back to dashboard
              </script>";
    } else {
        echo "Error updating role: " . $conn->error;
    }
}

$conn->close();
?>
