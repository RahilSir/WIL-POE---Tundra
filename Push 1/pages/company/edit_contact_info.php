<?php
session_start();
require '../../includes/db.php';

$application_id = $_SESSION['application_id'] ?? null;
if (!$application_id) {
    die("Application ID missing. Please complete the registration steps first.");
}

// Always load contact info before rendering
$contactQuery = $conn->prepare("SELECT * FROM app_contact_info WHERE application_id = ?");
$contactQuery->bind_param("i", $application_id);
$contactQuery->execute();
$contactResult = $contactQuery->get_result();
$contact = $contactResult->fetch_assoc();
$contactQuery->close();

// Handle inline update for Contact Information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contact'])) {
    $c = $_POST['contact'] ?? [];

    $first_name     = trim($c['first_name'] ?? '');
    $last_name      = trim($c['last_name'] ?? '');
    $email          = trim($c['email'] ?? '');
    $phone          = trim($c['phone'] ?? '');
    $street_address = trim($c['street_address'] ?? '');
    $city           = trim($c['city'] ?? '');
    $province       = trim($c['province'] ?? '');
    $zip_code       = trim($c['zip_code'] ?? '');
    $id_number      = trim($c['id_number'] ?? '');

    // Check if record exists
    $exists = $conn->prepare("SELECT application_id FROM app_contact_info WHERE application_id = ? LIMIT 1");
    $exists->bind_param("i", $application_id);
    $exists->execute();
    $existsRes = $exists->get_result();

    if ($existsRes->num_rows) {
        // Update
        $stmt = $conn->prepare("UPDATE app_contact_info 
            SET first_name=?, last_name=?, email=?, phone=?, street_address=?, city=?, province=?, zip_code=?, id_number=?
            WHERE application_id=?");
        $stmt->bind_param(
            "sssssssssi",
            $first_name, $last_name, $email, $phone, $street_address, $city, $province, $zip_code, $id_number,
            $application_id
        );
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO app_contact_info 
            (application_id, first_name, last_name, email, phone, street_address, city, province, zip_code, id_number)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isssssssss",
            $application_id, $first_name, $last_name, $email, $phone, $street_address, $city, $province, $zip_code, $id_number
        );
    }

    if (!$stmt->execute()) {
        die("Failed to save contact info: " . $stmt->error);
    }
    $stmt->close();
    $exists->close();

    // Reload the saved info
    $contactQuery = $conn->prepare("SELECT * FROM app_contact_info WHERE application_id = ?");
    $contactQuery->bind_param("i", $application_id);
    $contactQuery->execute();
    $contactResult = $contactQuery->get_result();
    $contact = $contactResult->fetch_assoc();
    $contactQuery->close();

    $contact_saved = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Contact Info</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    body { font-family: Arial, sans-serif; background:#f5f7fa; padding:20px; }
.form-container { max-width: 600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
h2 { text-align:center; margin-bottom:20px; color:#4CAF50; }
label { font-weight:bold; margin-top:10px; display:block; }
input[type="text"], input[type="email"] { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:6px; }
button { margin-top:20px; padding:10px 20px; background:#4CAF50; color:#fff; border:none; border-radius:6px; cursor:pointer; }
button:hover { background:#388e3c; }
.back-link { display:inline-block; margin-top:15px; color:#4CAF50; text-decoration:none; }
.header .container {
      display: flex; align-items: center; justify-content: space-between;
      gap: 14px;
    }
    .header .container .brand {
      display: flex; align-items: center; gap: 10px;
    }
    .header .container .brand img { height: 46px; border-radius: 8px; }
    .logo { margin: 0; }
  </style>

  <body>

<div class="header" style="display:flex; align-items:center; justify-content:center; gap:15px;  margin-bottom:20px;">
        <img src="../../assets/images/logo.jpg"  style="height:50px; margin-top:20px">
        <h1 style="margin:0; margin-top:20px; color:#4CAF50;">Tundra Tax & Accounting</h1>
    </div>

<div class="form-container">
  <h3>Contact Information</h3>

  <?php if (!empty($contact_saved)): ?>
    <div style="margin:10px 0; padding:10px; background:#e8f5e9; border:1px solid #c8e6c9; border-radius:6px;">
      ✅ Contact information saved.
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="row">
      <div>
        <label>First Name</label>
        <input type="text" name="contact[first_name]" value="<?= htmlspecialchars($contact['first_name'] ?? '') ?>">
      </div>
      <div>
        <label>Last Name</label>
        <input type="text" name="contact[last_name]" value="<?= htmlspecialchars($contact['last_name'] ?? '') ?>">
      </div>
    </div>

    <div class="row">
      <div>
        <label>Email</label>
        <input type="email" name="contact[email]" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
      </div>
      <div>
        <label>Phone</label>
        <input type="text" name="contact[phone]" value="<?= htmlspecialchars($contact['phone'] ?? '') ?>">
      </div>
    </div>

    <label>Street Address</label>
    <input type="text" name="contact[street_address]" value="<?= htmlspecialchars($contact['street_address'] ?? '') ?>">

    <div class="row">
      <div>
        <label>City</label>
        <input type="text" name="contact[city]" value="<?= htmlspecialchars($contact['city'] ?? '') ?>">
      </div>
      <div>
        <label>Province</label>
        <input type="text" name="contact[province]" value="<?= htmlspecialchars($contact['province'] ?? '') ?>">
      </div>
      <div>
        <label>ZIP Code</label>
        <input type="text" name="contact[zip_code]" value="<?= htmlspecialchars($contact['zip_code'] ?? '') ?>">
      </div>
    </div>

    <label>ID Number</label>
    <input type="text" name="contact[id_number]" value="<?= htmlspecialchars($contact['id_number'] ?? '') ?>">

    
      
     <button type="submit" name="update_contact">💾 Save Changes</button>


      </form>

     <a href="review_information.php" class="back-link">⬅ Back to Review</a>

    


</body>
</html>