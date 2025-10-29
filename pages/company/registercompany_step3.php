<?php
session_start();
require '../../includes/db.php'; // include your db connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save posted fields into session
    $_SESSION['step3'] = $_POST;

    // Get application_id from session
    $application_id = $_SESSION['application_id'] ?? null;

    if ($application_id) {
        // Collect form values safely
        $physical_street   = $_POST['physical_street'] ?? '';
        $physical_building = $_POST['physical_building'] ?? '';
        $physical_city     = $_POST['physical_city'] ?? '';
        $physical_province = $_POST['physical_province'] ?? '';
        $physical_postal   = $_POST['physical_postal'] ?? '';
        $postal_same       = $_POST['postal_same'] ?? '';
        $postal_street     = $_POST['postal_street'] ?? '';
        $postal_building   = $_POST['postal_building'] ?? '';
        $postal_city       = $_POST['postal_city'] ?? '';
        $postal_province   = $_POST['postal_province'] ?? '';
        $postal_postal     = $_POST['postal_postal'] ?? '';
        $company_email     = $_POST['company_email'] ?? '';
        $company_phone     = $_POST['company_phone'] ?? '';
        $share_capital     = $_POST['share_capital'] ?? '';
        $financial_year    = $_POST['financial_year'] ?? '';

        // Insert or update app_contact_info
       $stmt = $conn->prepare("INSERT INTO company_info
    (application_id,  email, phone, share_capital, financial_year,
     physical_street, physical_building, physical_city, physical_province, physical_postal,
     postal_street, postal_building, postal_city, postal_province, postal_postal)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        
        email=VALUES(email),
        phone=VALUES(phone),
        share_capital=VALUES(share_capital),
        financial_year=VALUES(financial_year),
        physical_street=VALUES(physical_street),
        physical_building=VALUES(physical_building),
        physical_city=VALUES(physical_city),
        physical_province=VALUES(physical_province),
        physical_postal=VALUES(physical_postal),
        postal_street=VALUES(postal_street),
        postal_building=VALUES(postal_building),
        postal_city=VALUES(postal_city),
        postal_province=VALUES(postal_province),
        postal_postal=VALUES(postal_postal)");


       $stmt->bind_param(
    "issssssssssssss",
    $application_id,
  
    $_POST['company_email'],
    $_POST['company_phone'],
    $_POST['share_capital'],
    $_POST['financial_year'],
    $_POST['physical_street'],
    $_POST['physical_building'],
    $_POST['physical_city'],
    $_POST['physical_province'],
    $_POST['physical_postal'],
    $_POST['postal_street'],
    $_POST['postal_building'],
    $_POST['postal_city'],
    $_POST['postal_province'],
    $_POST['postal_postal']
);


        if (!$stmt->execute()) {
            die("DB Error: " . $stmt->error);
        }

        $stmt->close();

        // Redirect to step 4
        header("Location: registercompany_step4.php");
        exit();
    } else {
        die("Application ID missing in session.");
    }
}

// Prefill values if navigating back
$physical_street  = $_SESSION['step3']['physical_street'] ?? '';
$physical_building = $_SESSION['step3']['physical_building'] ?? '';
$physical_city     = $_SESSION['step3']['physical_city'] ?? '';
$physical_province = $_SESSION['step3']['physical_province'] ?? '';
$physical_postal   = $_SESSION['step3']['physical_postal'] ?? '';
$postal_same       = $_SESSION['step3']['postal_same'] ?? '';
$postal_street     = $_SESSION['step3']['postal_street'] ?? '';
$postal_building   = $_SESSION['step3']['postal_building'] ?? '';
$postal_city       = $_SESSION['step3']['postal_city'] ?? '';
$postal_province   = $_SESSION['step3']['postal_province'] ?? '';
$postal_postal     = $_SESSION['step3']['postal_postal'] ?? '';
$company_email     = $_SESSION['step3']['company_email'] ?? '';
$company_phone     = $_SESSION['step3']['company_phone'] ?? '';
$share_capital     = $_SESSION['step3']['share_capital'] ?? '1,000 ordinary no par value shares';
$financial_year    = $_SESSION['step3']['financial_year'] ?? '';
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Company - Step 3</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
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
        <img src="../../assets/images/logo.jpg" alt="Tundra Logo">
        <h1 class="logo">Tundra Tax & Accounting</h1>
      </div>
      <nav>
         <a href="../../index.php">Home</a>
        <a href="../public/services.html">Services</a>
        <a href="../public/contact.html">Contact</a>
         <a href="../public/about.html">About Us</a>
        <a href="../auth/registrationPage.php" >Register</a>
           <a href="../auth/login.php" >Login</a>
      </nav>
    </div>
  </header>
<main>

 <div class="form-shell">
  <div class="form-header">
    <img src="../../assets/images/logo.jpg" alt="Tundra Logo">
    <h2>Register Your Company - Step 3</h2>
  </div>
  <div class="form-body">
  <p class="intro">Please fill in all required information to proceed with registration.</p>

  <form action="registercompany_step3.php" method="POST">


    <!-- Physical Address -->
<h3>Registered Physical Address</h3>

<label>Street Address *</label>
<input type="text" id="physical_street" name="physical_street" required placeholder="Street number and name">

<label>Building Name or Details</label>
<input type="text" id="physical_building" name="physical_building" placeholder="Building name / suite">

<div class="row">
  <div>
    <label>City / Town / Suburb *</label>
    <input type="text" id="physical_city" name="physical_city" required>
  </div>
  <div>
    <label>Province *</label>
    <select id="physical_province" name="physical_province" required>
      <option value="" disabled selected>Select Province</option>
      <option>Eastern Cape</option>
      <option>Free State</option>
      <option>Gauteng</option>
      <option>KwaZulu-Natal</option>
      <option>Limpopo</option>
      <option>Mpumalanga</option>
      <option>Northern Cape</option>
      <option>North West</option>
      <option>Western Cape</option>
    </select>
  </div>
</div>

<label>Postal Code *</label>
<input type="text" id="physical_postal" name="physical_postal" required pattern="\d{4}" placeholder="4-digit code">


<!-- Same Address Question -->
<label>Is your Postal Address the same as your Physical Address? *</label>
<select id="postal_same" name="postal_same" required>
  <option value="" disabled selected>Select</option>
  <option value="yes">Yes</option>
  <option value="no">No</option>
</select>


<!-- Postal Address -->
<div id="postal_address_section" style="display:none;">
  <h3>Postal Address</h3>

  <label>Street Address *</label>
  <input type="text" id="postal_street" name="postal_street" placeholder="Street number and name">

  <label>Building Name or Details</label>
  <input type="text" id="postal_building" name="postal_building" placeholder="Building name / suite">

  <div class="row">
    <div>
      <label>City / Town / Suburb *</label>
      <input type="text" id="postal_city" name="postal_city">
    </div>
    <div>
      <label>Province *</label>
      <select id="postal_province" name ="postal_province">
        <option value="" disabled selected>Select Province</option>
        <option>Eastern Cape</option>
        <option>Free State</option>
        <option>Gauteng</option>
        <option>KwaZulu-Natal</option>
        <option>Limpopo</option>
        <option>Mpumalanga</option>
        <option>Northern Cape</option>
        <option>North West</option>
        <option>Western Cape</option>
      </select>
    </div>
  </div>

  <label>Postal Code *</label>
  <input type="text" id="postal_postal" name="postal_postal" pattern="\d{4}" placeholder="4-digit code">
</div>


<!-- Company Info -->
<label>Email Address of Company *</label>
<input type="email" name="company_email" required>

<label>Telephone Number of Company *</label>
<input type="tel" name="company_phone" required pattern="[0-9]{10}" placeholder="e.g. 0211234567">

<label>Authorised Share Capital *</label>
<input type="text" name="share_capital" value="1,000 ordinary no par value shares" required>

<label>Financial Year End *</label>
<select name="financial_year" required>
  <option>February</option>
  <option>March</option>
  <option>June</option>
  <option>September</option>
  <option>December</option>
</select>


<!-- JavaScript -->
<script>
document.getElementById("postal_same").addEventListener("change", function() {
  const postalSection = document.getElementById("postal_address_section");

  if (this.value === "yes") {
    // Hide postal section
    postalSection.style.display = "none";

    // Auto-fill postal fields with physical address values
    document.getElementById("postal_street").value = document.getElementById("physical_street").value;
    document.getElementById("postal_building").value = document.getElementById("physical_building").value;
    document.getElementById("postal_city").value = document.getElementById("physical_city").value;
    document.getElementById("postal_province").value = document.getElementById("physical_province").value;
    document.getElementById("postal_postal").value = document.getElementById("physical_postal").value;
  } 
  else if (this.value === "no") {
    // Show postal section
    postalSection.style.display = "block";

    // Clear postal fields for manual entry
    document.getElementById("postal_street").value = "";
    document.getElementById("postal_building").value = "";
    document.getElementById("postal_city").value = "";
    document.getElementById("postal_province").value = "";
    document.getElementById("postal_postal").value = "";
  }
});
</script>


    <div class="actions">
         
        <form action="registerCompanyStep3.php" method="POST">
<button type="submit" class="btn-register">Next</button>


        </div>

  </form>
   </div>
</div>
 
</main>

</body>
</html>
