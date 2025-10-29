<?php
session_start();
require '../../includes/ErrorHandler.php';
require '../../includes/Validator.php';
require '../../includes/error_display.php';
require '../../includes/db.php';

// Check if database connection is available
if (!$conn) {
    die("Database connection failed. Please try again later.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation rules
    $rules = [
        'name' => ['required', 'minLength' => 2, 'maxLength' => 100],
        'username' => ['required', 'minLength' => 3, 'maxLength' => 50],
        'password' => ['required', 'minLength' => 6, 'maxLength' => 255]
    ];
    
    // Validate form data
    if (Validator::validateForm($_POST, $rules)) {
        $name = trim($_POST['name']);
        $user = trim($_POST['username']);
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            // Check if username already exists
            $check = $conn->prepare("SELECT id FROM users WHERE username=?");
            if (!$check) {
                throw new Exception("Database prepare failed for username check");
            }
            
            $check->bind_param("s", $user);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "Username already taken!";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, username, password) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Database prepare failed for user insertion");
                }
                
                $stmt->bind_param("sss", $name, $user, $pass);

                if ($stmt->execute()) {
                    $message = "Registration successful! You can now <a href='login.php'>login</a>.";
                } else {
                    throw new Exception("Failed to create user account");
                }
                $stmt->close();
            }
            $check->close();
        } catch (Exception $e) {
            ErrorHandler::handleException($e);
            $message = "An error occurred during registration. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Tundra Tax & Accounting</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        .container img {
            width: 100px;
            margin-bottom: 20px;
        }
        form h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #2f3640;
            color: #fff;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #353b48;
        }
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .message.error {
            background-color: #ffdddd;
            color: #d8000c;
        }
        .message a {
            color: #2f3640;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../../assets/images/logo.jpg" alt="Tundra Logo">

        <?php 
        displayValidationErrors();
        if ($message != ""): 
        ?>
            <div class="message <?php echo (str_contains($message, '❌')) ? 'error' : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <h2>Register </h2>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>

            <!-- Login Button -->
<div class="login-link">
    <p>Already have an account? 
        <a href="login.php" class="btn-link">Login Here</a>
    </p>
</div>
        </form>
    </div>
</body>
</html>
