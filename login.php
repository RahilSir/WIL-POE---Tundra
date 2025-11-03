<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tundra";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // Fetch password and role
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if (password_verify($pass, $hashed_password)) {
            // ✅ Save session for logged-in user
            $_SESSION['username'] = $user;
            $_SESSION['role'] = $role;

            // ✅ Redirect based on role
            if ($role === 'admin') {
                header("Location: admindashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = "❌ Invalid password!";
        }
    } else {
        $message = "❌ Username not found!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Tundra Tax & Accounting</title>
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
    <img src="logo.jpg" alt="Tundra Logo">

    <?php if ($message != ""): ?>
        <div class="message <?php echo (str_contains($message, '❌')) ? 'error' : ''; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <h2>Login</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>

        <div class="login-link">
            <p>Don't have an account? 
                <a href="registrationPage.php" class="btn-link">Register Here</a>
            </p>
        </div>
    </form>
</div>
</body>
</html>
