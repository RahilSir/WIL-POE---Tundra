<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tundra";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$sql = "SELECT id, username, name, role   FROM users";
$result = $conn->query($sql);

// Fetch PDF files from uploads folder
$uploadDir = "uploads/";
$pdfFiles = glob($uploadDir . "*.pdf");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #2f3640;
        }

        .logout {
            text-align: right;
            margin-bottom: 20px;
        }

        .logout a {
            background-color: #2f3640;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
        }

        .logout a:hover {
            background-color: #353b48;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2f3640;
            color: white;
        }

        tr:hover {
            background-color: #f1f2f6;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .welcome {
            text-align: center;
            margin-bottom: 30px;
            color: #555;
        }

.header .container {
      display: flex; align-items: center; justify-content: space-between;
      gap: 14px;
    }
    .header .container .brand {
      display: flex; align-items: center; gap: 10px;
    }
    .header .container .brand img { height: 46px; border-radius: 8px; }
    .logo { margin: 0; }

    nav a {
  color: black; /* default color */
  text-decoration: none;
  margin: 0 10px;
  font-weight: bold;
  transition: color 0.3s ease;
}

nav a:hover {
  color: green; /* hover effect works now */
}


.hero {
  background: url("cpt.jpg") no-repeat center center;
  background-size: cover;   /* makes it fill the container */
  color: white;             /* optional: text color for contrast */
  padding: 100px 20px;      /* space inside the section */
}

.hero .container {
  background: rgba(0, 0, 0, 0.5); /* optional: dark overlay so text is readable */
  padding: 40px;
  border-radius: 8px;            /* optional styling */
}

footer {
  background-color: #333;   /* dark grey */
  color: #fff;              /* white text for contrast */
  text-align: center;       /* center the text */
  padding: 20px 0;          /* spacing inside */
  margin-top: 40px;         /* space above footer */
  font-size: 14px;          /* smaller text */

}




/* Dropdown container */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown button style (Services link) */


/* Dropdown content hidden by default */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #333;   /* dark dropdown background */
  min-width: 160px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside dropdown */
.dropdown-content a {
  color: white;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}



/* Show dropdown on hover */
.dropdown:hover .dropdown-content {
  display: block;
}




    </style>
</head>
<body>

 <header class="header">
  <div class="container">
   <div class="brand">
  <img src="logo.jpg" alt="Tundra Logo" class="logo-img">
  <h1 class="logo-text">Tundra Tax & Accounting</h1>
</div>

    <nav>
      <a href="index.php">Home</a>
      
      <!-- Dropdown Menu -->
      <div class="dropdown">
        <a href="services.html" class="dropbtn">Services ▾</a>
        <div class="dropdown-content">
          <a href="services.html">Services</a>
          <a href="bookkeeping.html">Bookkeeping</a>
          <a href="tax-services.html">Tax Services</a>
        </div>
      </div>
      
      <a href="contact.html">Contact</a>
      <a href="about.html">About Us</a>
      
    </nav>
  </div>
</header>





    <div class="container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <h1>Admin Dashboard</h1>
        <p class="welcome">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>



<h2>Manage Users</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Action</th>
        <th>Role</th>
        
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['username']}</td>
                    <td>
                        <form method='POST' action='update_role.php'>
                            <input type='hidden' name='user_id' value='{$row['id']}'>
                            <select name='role'>
                                <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>
                                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                            </select>
                            <button type='submit'>Update</button>
                        </form>
                    </td>
                    <td>{$row['role']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' style='text-align:center;'>No users found.</td></tr>";
    }
    ?>
</table>


 <!-- PDF FILES SECTION -->
    <h2>Registration Documents</h2>
   <table>
    <tr>
        <th>File Name</th>
        <th>Download</th>
    </tr>

    <?php
    // Folder where PDFs are stored
    $folder = __DIR__; // current directory (app folder)
    
    // Get all PDF files in the folder
    $files = glob($folder . "/*.pdf");

    if (!empty($files)) {
        foreach ($files as $file) {
            $fileName = basename($file); // get only the filename
            echo "<tr>
                    <td>$fileName</td>
                    <td><a href='$fileName' download>Download</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='2' style='text-align:center;'>No PDF files found.</td></tr>";
    }
    ?>
</table>












    </div>
</body>
</html>

<?php
$conn->close();
?>
