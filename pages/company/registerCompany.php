<?php
session_start();
require '../../includes/db.php'; // Make sure you have db.php with $conn = new mysqli(...);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim and collect form data
    $firstName     = trim($_POST['firstName'] ?? '');
    $lastName      = trim($_POST['lastName'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $idNum         = trim($_POST['idNum'] ?? '');
    $streetAddress = trim($_POST['streetAddress'] ?? '');
    $city          = trim($_POST['city'] ?? '');
    $province      = trim($_POST['Province'] ?? '');
    $zipCode       = trim($_POST['zipCode'] ?? '');

    // 1. Insert into applications table
    $stmtApp = $conn->prepare("INSERT INTO applications (status, created_at) VALUES (?, NOW())");
    $status = 'pending';
    $stmtApp->bind_param("s", $status);
    $stmtApp->execute();

    // 2. Get new application_id
    $application_id = $stmtApp->insert_id;
    $_SESSION['application_id'] = $application_id;

    // 3. Insert into app_contact_info table
    $stmt = $conn->prepare("
        INSERT INTO app_contact_info 
        (application_id, first_name, last_name, email, phone, id_number, street_address, city, province, zip_code)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssssssss",
        $application_id,
        $firstName,
        $lastName,
        $email,
        $phone,
        $idNum,
        $streetAddress,
        $city,
        $province,
        $zipCode
    );

    if ($stmt->execute()) {
        // Success, redirect to Step 2
        header("Location: registercompany_step2.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Pre-fill fields if session data exists
$firstName     = $_SESSION['firstName'] ?? '';
$lastName      = $_SESSION['lastName'] ?? '';
$email         = $_SESSION['email'] ?? '';
$phone         = $_SESSION['phone'] ?? '';
$idNum         = $_SESSION['idNum'] ?? '';
$streetAddress = $_SESSION['streetAddress'] ?? '';
$city          = $_SESSION['city'] ?? '';
$province      = $_SESSION['province'] ?? '';
$zipCode       = $_SESSION['zipCode'] ?? '';


    
    

// Pre-fill fields if session data exists
//$firstName     = $_SESSION['firstName'] ?? '';
//$lastName      = $_SESSION['lastName'] ?? '';
//$email         = $_SESSION['email'] ?? '';
//$phone         = $_SESSION['phone'] ?? '';
//$idNum         = $_SESSION['idNum'] ?? '';
//$streetAddress = $_SESSION['streetAddress'] ?? '';
//$city          = $_SESSION['city'] ?? '';
//$province      = $_SESSION['province'] ?? '';
//$zipCode       = $_SESSION['zipCode'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register Your Company - Tundra Legal</title>
<link rel="stylesheet" href="../../assets/css/style.css">
<style>
 /* Page-specific styling that matches your registration look */
    body {
      background: linear-gradient(135deg, rgba(88,228,18,0.08), rgba(0,0,0,0.03));
      margin: 0;
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
    .form-header img { height: 42px; border-radius: 8px; }
    .form-header h2 {
      margin: 0;
      font-size: 1.4rem;
      color: var(--dark-grey);
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

    form { display: flex; flex-direction: column; gap: 26px; }

    fieldset {
      border: 1px solid #e6e6e6;
      border-radius: 10px;
      padding: 18px 18px 8px;
      background: #fff;
    }
    legend {
      font-weight: 800;
      font-size: 1.05rem;
      color: var(--green);
      padding: 0 8px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 700;
      font-size: 0.93rem;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    select,
    textarea {
      width: 100%;
      padding: 11px 12px;
      border: 1px solid #cfcfcf;
      border-radius: 8px;
      font-size: 1rem;
      box-sizing: border-box;
      font-family: inherit;
      background: #fff;
      transition: border-color .2s, box-shadow .2s;
    }
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(88,228,18,0.15);
    }

    textarea { min-height: 96px; resize: vertical; }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    .grid-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px;
    }
    @media (max-width: 780px) {
      .grid-2, .grid-3 { grid-template-columns: 1fr; }
    }

    .actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 0 2px 26px;
    }

    .btn-primary {
      background-color: var(--green);
      color: #fff;
      border: none;
      padding: 14px 22px;
      font-size: 1.05rem;
      font-weight: 800;
      border-radius: 10px;
      cursor: pointer;
      transition: transform .06s ease, box-shadow .2s ease, background-color .2s ease;
      box-shadow: 0 6px 16px rgba(88,228,18,0.25);
    }
    .btn-primary:hover { background-color: #0a6b22; }
    .btn-primary:active { transform: translateY(1px); }

    .link-muted a {
      color: var(--dark-grey);
      text-decoration: none;
      border-bottom: 1px dashed #bbb;
    }
    .link-muted a:hover { color: #111; border-bottom-color: #888; }

    /* Header tweaks to align logo nicely */
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
      <a href="../auth/registrationPage.php">Register</a>
      <a href="../auth/login.php">Login</a>
     
    </nav>
  </div>
</header>

<div class="form-shell">
  <div class="form-header">
    <img src="../../assets/images/logo.jpg" alt="Tundra Logo">
    <h2>Register Your Company</h2>
  </div>

  <div class="form-body">
    <p class="page-intro">
      Fill out the form below to start the company registration process. Our team will review your information and contact you within 24 hours.
    </p>

    <form action="" method="post" novalidate>
      <fieldset>
        <legend>Contact Information</legend>

        <div class="grid-2">
          <div>
            <label for="firstName">First Name *</label>
            <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($firstName) ?>" required>
          </div>
          <div>
            <label for="lastName">Last Name *</label>
            <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($lastName) ?>" required>
          </div>
        </div>

        <div class="grid-2">
          <div>
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
          </div>
          <div>
            <label for="phone">Phone Number *</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
          </div>
        </div>

        <div class="grid-2">
          <div>
            <label for="idNum">ID Number *</label>
            <input type="text" id="idNum" name="idNum" value="<?= htmlspecialchars($idNum) ?>" required>
          </div>
          <div>
            <label for="streetAddress">Street Address *</label>
            <input type="text" id="streetAddress" name="streetAddress" value="<?= htmlspecialchars($streetAddress) ?>" required>
          </div>
        </div>

        <div class="grid-3">
          <div>
            <label for="city">City *</label>
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($city) ?>" required>
          </div>
          <div>
            <label for="Province">Province *</label>
            <select id="Province" name="Province" required>
              <option value="" disabled <?= $province === '' ? 'selected' : '' ?>>Select Province</option>
              <?php
              $provinces = ["Gauteng","Western Cape","KwaZulu-Natal","Eastern Cape","Free State","Limpopo","Mpumalanga","North West","Northern Cape","Other"];
              foreach ($provinces as $prov) {
                  $sel = $province === $prov ? 'selected' : '';
                  echo "<option $sel>$prov</option>";
              }
              ?>
            </select>
          </div>
          <div>
            <label for="zipCode">ZIP Code *</label>
            <input type="text" id="zipCode" name="zipCode" value="<?= htmlspecialchars($zipCode) ?>" required>
          </div>
        </div>
      </fieldset>

      <div class="actions">
        <button type="submit" class="btn-register">Next</button>
      </div>
    </form>
  </div>
</div>

<footer>
  <p style="text-align:center; color:#666; padding:18px 12px;">
    &copy; 2025 Tundra Tax & Accounting. All Rights Reserved.
  </p>
</footer>

</body>
</html>
