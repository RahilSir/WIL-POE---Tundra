<?php
/**
 * XAMPP Setup Test Script
 * Run this to verify your XAMPP configuration
 */

echo "<h1>XAMPP Configuration Test</h1>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "<p>✅ PHP version is compatible</p>";
} else {
    echo "<p>❌ PHP version is too old. Please upgrade to PHP 7.4 or higher</p>";
}

// Test 2: Required Extensions
echo "<h2>2. Required Extensions</h2>";
$required_extensions = ['mysqli', 'session', 'json', 'mbstring', 'fileinfo', 'gd'];
$all_good = true;

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p>✅ $ext extension loaded</p>";
    } else {
        echo "<p>❌ $ext extension NOT loaded</p>";
        $all_good = false;
    }
}

if ($all_good) {
    echo "<p>✅ All required extensions are loaded</p>";
} else {
    echo "<p>❌ Some required extensions are missing. Please install them in XAMPP</p>";
}

// Test 3: File Permissions
echo "<h2>3. File Permissions</h2>";
$directories_to_check = ['logs', 'uploads', 'documents'];

foreach ($directories_to_check as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<p>✅ $dir directory is writable</p>";
        } else {
            echo "<p>❌ $dir directory is NOT writable</p>";
        }
    } else {
        echo "<p>⚠️ $dir directory does not exist</p>";
    }
}

// Test 4: Database Connection
echo "<h2>4. Database Connection</h2>";
try {
    $conn = new mysqli('localhost', 'root', '', 'tundra');
    if ($conn->connect_error) {
        echo "<p>❌ Database connection failed: " . $conn->connect_error . "</p>";
        echo "<p>💡 Make sure MySQL is running in XAMPP and the 'tundra' database exists</p>";
    } else {
        echo "<p>✅ Database connection successful</p>";
        
        // Test if tables exist
        $tables = ['users', 'applications', 'app_contact_info'];
        $tables_exist = true;
        
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                echo "<p>✅ Table '$table' exists</p>";
            } else {
                echo "<p>❌ Table '$table' does NOT exist</p>";
                $tables_exist = false;
            }
        }
        
        if (!$tables_exist) {
            echo "<p>💡 Run setup_database.php to create the required tables</p>";
        }
    }
    $conn->close();
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 5: Error Handling System
echo "<h2>5. Error Handling System</h2>";
$error_files = [
    'includes/ErrorHandler.php',
    'includes/Validator.php',
    'includes/error_display.php',
    'includes/config.php',
    'includes/db.php'
];

$all_files_exist = true;
foreach ($error_files as $file) {
    if (file_exists($file)) {
        echo "<p>✅ $file exists</p>";
    } else {
        echo "<p>❌ $file does NOT exist</p>";
        $all_files_exist = false;
    }
}

if ($all_files_exist) {
    echo "<p>✅ All error handling files are present</p>";
} else {
    echo "<p>❌ Some error handling files are missing</p>";
}

// Test 6: Composer Dependencies
echo "<h2>6. Composer Dependencies</h2>";
if (file_exists('vendor/autoload.php')) {
    echo "<p>✅ Composer autoload exists</p>";
    
    // Test if dompdf is available
    if (class_exists('Dompdf\Dompdf')) {
        echo "<p>✅ DomPDF is available</p>";
    } else {
        echo "<p>❌ DomPDF is NOT available</p>";
    }
    
    // Test if PHPMailer is available
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "<p>✅ PHPMailer is available</p>";
    } else {
        echo "<p>❌ PHPMailer is NOT available</p>";
    }
} else {
    echo "<p>❌ Composer autoload does NOT exist</p>";
    echo "<p>💡 Run 'composer install' to install dependencies</p>";
}

// Test 7: URL Configuration
echo "<h2>7. URL Configuration</h2>";
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo "<p>Current URL: $current_url</p>";

if (strpos($current_url, 'localhost') !== false) {
    echo "<p>✅ Running on localhost (XAMPP)</p>";
} else {
    echo "<p>⚠️ Not running on localhost - make sure APP_URL is configured correctly</p>";
}

// Summary
echo "<h2>Summary</h2>";
echo "<p>If all tests show ✅, your XAMPP setup is ready!</p>";
echo "<p>If you see ❌, please follow the XAMPP_SETUP_GUIDE.md instructions.</p>";

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>If database tables are missing, run <a href='setup_database.php'>setup_database.php</a></li>";
echo "<li>Test the error handling system: <a href='tests/test_error_handling.php'>tests/test_error_handling.php</a></li>";
echo "<li>Access the main application: <a href='index.php'>index.php</a></li>";
echo "</ul>";
?>
