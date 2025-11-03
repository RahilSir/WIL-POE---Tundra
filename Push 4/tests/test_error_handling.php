<?php
// Test file for error handling system
require '../includes/ErrorHandler.php';
require '../includes/Validator.php';
require '../includes/error_display.php';

echo "<h1>Error Handling System Test</h1>";

// Test 1: Validation errors
echo "<h2>Test 1: Validation Errors</h2>";
$testData = [
    'name' => '',
    'email' => 'invalid-email',
    'phone' => '123'
];

$rules = [
    'name' => ['required', 'minLength' => 2],
    'email' => ['required', 'email'],
    'phone' => ['required', 'phone']
];

Validator::validateForm($testData, $rules);
displayValidationErrors();

// Test 2: Database error simulation
echo "<h2>Test 2: Database Error Simulation</h2>";
try {
    $conn = new mysqli('invalid_host', 'user', 'pass', 'database');
    if ($conn->connect_error) {
        ErrorHandler::handleDatabaseError($conn, 'Test Connection');
    }
} catch (Exception $e) {
    ErrorHandler::handleException($e);
}

// Test 3: File upload error simulation
echo "<h2>Test 3: File Upload Error Simulation</h2>";
$testFile = [
    'name' => 'test.jpg',
    'error' => UPLOAD_ERR_INI_SIZE
];
ErrorHandler::handleFileUploadError($testFile['name'], $testFile['error']);

// Test 4: General exception
echo "<h2>Test 4: General Exception</h2>";
try {
    throw new Exception("This is a test exception for error handling");
} catch (Exception $e) {
    ErrorHandler::handleException($e);
}

echo "<h2>Test Complete</h2>";
echo "<p>Check the logs/error.log file for detailed error information.</p>";
?>
