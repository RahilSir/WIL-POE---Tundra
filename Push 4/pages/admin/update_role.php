<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tundra"; // change if needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
