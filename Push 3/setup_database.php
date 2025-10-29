<?php
/**
 * Database Setup Script for XAMPP
 * Run this once to create the required database and tables
 */

require_once 'includes/config.php';
require_once 'includes/ErrorHandler.php';

// Initialize error handling
ErrorHandler::init();

echo "<h1>Database Setup for Tundra Tax & Accounting</h1>";

// Connect to MySQL (without selecting database first)
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<p>✅ Connected to MySQL server</p>";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "<p>✅ Database '" . DB_NAME . "' created successfully</p>";
} else {
    echo "<p>❌ Error creating database: " . $conn->error . "</p>";
}

// Select the database
$conn->select_db(DB_NAME);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✅ Users table created successfully</p>";
} else {
    echo "<p>❌ Error creating users table: " . $conn->error . "</p>";
}

// Create applications table
$sql = "CREATE TABLE IF NOT EXISTS applications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✅ Applications table created successfully</p>";
} else {
    echo "<p>❌ Error creating applications table: " . $conn->error . "</p>";
}

// Create app_contact_info table
$sql = "CREATE TABLE IF NOT EXISTS app_contact_info (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    application_id INT(11) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    id_number VARCHAR(13) NOT NULL,
    street_address VARCHAR(200) NOT NULL,
    city VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✅ App contact info table created successfully</p>";
} else {
    echo "<p>❌ Error creating app_contact_info table: " . $conn->error . "</p>";
}

// Create app_company_info table
$sql = "CREATE TABLE IF NOT EXISTS app_company_info (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    application_id INT(11) NOT NULL,
    company_name VARCHAR(100) NOT NULL,
    company_type VARCHAR(50) NOT NULL,
    registration_number VARCHAR(50),
    tax_number VARCHAR(50),
    business_address VARCHAR(200) NOT NULL,
    business_city VARCHAR(50) NOT NULL,
    business_province VARCHAR(50) NOT NULL,
    business_zip_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✅ App company info table created successfully</p>";
} else {
    echo "<p>❌ Error creating app_company_info table: " . $conn->error . "</p>";
}

// Create app_directors table
$sql = "CREATE TABLE IF NOT EXISTS app_directors (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    application_id INT(11) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    id_number VARCHAR(13) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(200) NOT NULL,
    city VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✅ App directors table created successfully</p>";
} else {
    echo "<p>❌ Error creating app_directors table: " . $conn->error . "</p>";
}

// Create app_shareholders table
$sql = "CREATE TABLE IF NOT EXISTS app_shareholders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    application_id INT(11) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    id_number VARCHAR(13) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(200) NOT NULL,
    city VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    share_percentage DECIMAL(5,2) NOT NULL,
    id_front_path VARCHAR(255),
    id_back_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "<p>✅ App shareholders table created successfully</p>";
} else {
    echo "<p>❌ Error creating app_shareholders table: " . $conn->error . "</p>";
}

// Create a test user
$testPassword = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (name, username, password) VALUES ('Admin User', 'admin', ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $testPassword);

if ($stmt->execute()) {
    echo "<p>✅ Test user created (username: admin, password: admin123)</p>";
} else {
    echo "<p>❌ Error creating test user: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();

echo "<h2>Setup Complete!</h2>";
echo "<p>You can now access the application at: <a href='index.php'>index.php</a></p>";
echo "<p>Test the error handling at: <a href='tests/test_error_handling.php'>tests/test_error_handling.php</a></p>";
echo "<p><strong>Note:</strong> You can delete this setup_database.php file after running it once.</p>";
?>
