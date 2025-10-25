<?php
session_start();
require 'db.php'; // DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure we have an application_id from Step 1
    if (!isset($_SESSION['application_id'])) {
        die("Error: Application ID not found. Please start from Step 1.");
    }

    $application_id = $_SESSION['application_id'];

    // Collect form data
    $preferred_name = trim($_POST['name1'] ?? '');
    $alt_name1      = trim($_POST['name2'] ?? '');
    $alt_name2      = trim($_POST['name3'] ?? '');
    $alt_name3      = trim($_POST['name4'] ?? '');
    $has_similar    = trim($_POST['similar'] ?? 'no');
    $similar_name   = trim($_POST['similar_name'] ?? '');
    $similar_reg    = trim($_POST['similar_reg'] ?? '');

    // Save also in session so user can review later
    $_SESSION['preferred_name'] = $preferred_name;
    $_SESSION['alt_name1'] = $alt_name1;
    $_SESSION['alt_name2'] = $alt_name2;
    $_SESSION['alt_name3'] = $alt_name3;
    $_SESSION['has_similar'] = $has_similar;
    $_SESSION['similar_name'] = $similar_name;
    $_SESSION['similar_reg'] = $similar_reg;

    // Insert into company_names table
    $stmt = $conn->prepare("
        INSERT INTO company_names 
        (application_id, preferred_name, alt_name1, alt_name2, alt_name3, has_similar, similar_name, similar_reg) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            preferred_name = VALUES(preferred_name),
            alt_name1 = VALUES(alt_name1),
            alt_name2 = VALUES(alt_name2),
            alt_name3 = VALUES(alt_name3),
            has_similar = VALUES(has_similar),
            similar_name = VALUES(similar_name),
            similar_reg = VALUES(similar_reg)
    ");

    $stmt->bind_param(
        "isssssss",
        $application_id,
        $preferred_name,
        $alt_name1,
        $alt_name2,
        $alt_name3,
        $has_similar,
        $similar_name,
        $similar_reg
    );

    if ($stmt->execute()) {
        header("Location: registercompany_step3.php");
        exit();
    } else {
        echo "Database Error: " . $stmt->error;
    }
}

// Pre-fill values if returning to the page
$name1 = $_SESSION['preferred_name'] ?? '';
$name2 = $_SESSION['alt_name1'] ?? '';
$name3 = $_SESSION['alt_name2'] ?? '';
$name4 = $_SESSION['alt_name3'] ?? '';
$similar = $_SESSION['has_similar'] ?? 'no';
$similar_name = $_SESSION['similar_name'] ?? '';
$similar_reg  = $_SESSION['similar_reg'] ?? '';
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Company - Step 2</title>
  <link rel="stylesheet" href="style.css">
  <style>
   body {
  background: linear-gradient(135deg, rgba(88,228,18,0.08), rgba(0,0,0,0.03));
  margin: 0;
  font-family: Arial, sans-serif;
}

.form-shell {
  max-width: 920px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
  overflow: hidden;
  border: 1px solid #eee;
}

.form-header {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px 22px;
  background: linear-gradient(90deg, rgba(88,228,18,0.10), rgba(88,228,18,0.02));
  border-bottom: 1px solid #eaeaea;
}

.form-header img {
  height: 42px;
  border-radius: 8px;
}

.form-header h2 {
  margin: 0;
  font-size: 1.4rem;
  color: #333;
}

.form-body {
  padding: 26px 24px 10px;
}

.page-intro {
  text-align: center;
  color: #555;
  margin: 6px 0 24px;
  font-size: 0.98rem;
}

form {
  display: flex;
  flex-direction: column;
  gap: 26px;
}

fieldset {
  border: 1px solid #e6e6e6;
  border-radius: 10px;
  padding: 18px 18px 8px;
  background: #fff;
}

legend {
  font-weight: 800;
  font-size: 1.05rem;
  color: #58e412; /* your green */
  padding: 0 8px;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: 700;
  font-size: 0.93rem;
  color: #333;
}

input[type="text"], input[type="email"], input[type="tel"], select {
  width: 100%;
  padding: 11px;
  border: 1px solid #cfcfcf;
  border-radius: 8px;
  font-size: 1rem;
}

.actions {
  display: flex;
  justify-content: center;
  padding-top: 10px;
}

.btn-register {
  background-color: #58e412;
  color: white;
  border: none;
  padding: 14px 22px;
  font-size: 1.05rem;
  font-weight: 800;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 6px 16px rgba(88,228,18,0.25);
}

.btn-register:hover {
  background-color: #0a6b22;
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
  </style>
</head>
<body>

<header class="header">
    <div class="container">
      <div class="brand">
        <img src="logo.jpg" alt="Tundra Logo">
        <h1 class="logo">Tundra Tax & Accounting</h1>
      </div>
      <nav>
         <a href="index.php">Home</a>
        <a href="services.html">Services</a>
        <a href="contact.html">Contact</a>
         <a href="about.html">About Us</a>
        <a href="registrationPage.php" >Register</a>
           <a href="login.php" >Login</a>
      </nav>
    </div>
  </header>

<main>


  <div class="form-shell">
  <div class="form-header">
    <img src="logo.jpg" alt="Tundra Logo">
    <h2>Register Your Company - Step 2</h2>
  </div>

  <div class="form-body">
    <p class="page-intro">
      Please complete the next set of details to proceed with your registration.
    </p>
  <form action="registercompany_step2.php" method="post">
   
    <fieldset>
      <legend>Company Name Choices</legend>
      <label for="name1">Preferred Name *</label>
      <input type="text" id="name1" name="name1" value="<?= htmlspecialchars($name1) ?>" required>

      <label for="name2">Alternative Name 1</label>
     <input type="text" id="name2" name="name2" value="<?= htmlspecialchars($name2) ?>">
      <label for="name3">Alternative Name 2</label>
      <input type="text" id="name3" name="name3" value="<?= htmlspecialchars($name3) ?>">

      <label for="name4">Alternative Name 3</label>
     <input type="text" id="name4" name="name4" value="<?= htmlspecialchars($name4) ?>">
    </fieldset>

   <fieldset>
  <legend>Similar Company Details</legend>
  <label>Do you have a registered company or CC with a similar name?</label>
  <label>
    <input type="radio" name="similar" value="no" onclick="toggleSimilarFields(false)"> No
  </label>
  <label>
    <input type="radio" name="similar" value="yes" onclick="toggleSimilarFields(true)"> Yes
  </label>

  <div id="similarFields" style="display: none; margin-top: 10px;">
    <label for="similar_name">Company or CC Name</label>
    <input type="text" id="similar_name" name="similar_name" value="<?= htmlspecialchars($similar_name) ?>">

    <label for="similar_reg">Registration Number</label>
    <input type="text" id="similar_reg" name="similar_reg" value="<?= htmlspecialchars($similar_reg) ?>">
  </div>
</fieldset>

<script>
  function toggleSimilarFields(show) {
    const fields = document.querySelectorAll("#similarFields input");

    if (show) {
      document.getElementById("similarFields").style.display = "block";
      fields.forEach(field => field.setAttribute("required", "required"));
    } else {
      document.getElementById("similarFields").style.display = "none";
      fields.forEach(field => field.removeAttribute("required"));
    }
  }
</script>



   <div class="actions">
    <button type="submit" class="btn-register">Next</button>
</div>

  </form>
  </div>
</div>
</main>

</body>
</html>
