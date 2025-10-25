<?php
session_start();
require 'db.php';

$application_id = $_SESSION['application_id'] ?? null;

if (!$application_id) {
    die("Application ID missing. Please complete the registration steps first.");
}

// Fetch existing company info
$query = $conn->prepare("SELECT * FROM company_info WHERE application_id = ?");
$query->bind_param("i", $application_id);
$query->execute();
$result = $query->get_result();
$company = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data safely
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $share_capital = $_POST['share_capital'] ?? '';
    $financial_year = $_POST['financial_year'] ?? '';
    $physical_street = $_POST['physical_street'] ?? '';
    $physical_building = $_POST['physical_building'] ?? '';
    $physical_city = $_POST['physical_city'] ?? '';
    $physical_province = $_POST['physical_province'] ?? '';
    $physical_postal = $_POST['physical_postal'] ?? '';
    $postal_street = $_POST['postal_street'] ?? '';
    $postal_building = $_POST['postal_building'] ?? '';
    $postal_city = $_POST['postal_city'] ?? '';
    $postal_province = $_POST['postal_province'] ?? '';
    $postal_postal = $_POST['postal_postal'] ?? '';

    // Update query
    $update = $conn->prepare("UPDATE company_info 
        SET email=?, phone=?, share_capital=?, financial_year=?, 
            physical_street=?, physical_building=?, physical_city=?, physical_province=?, physical_postal=?,
            postal_street=?, postal_building=?, postal_city=?, postal_province=?, postal_postal=?
        WHERE application_id=?");
    $update->bind_param(
        "ssssssssssssssi",
        $email, $phone, $share_capital, $financial_year,
        $physical_street, $physical_building, $physical_city, $physical_province, $physical_postal,
        $postal_street, $postal_building, $postal_city, $postal_province, $postal_postal,
        $application_id
    );

    if ($update->execute()) {
        header("Location: review_information.php"); // go back to review page
        exit;
    } else {
        $error = "Failed to update company information.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <div class="header" style="display:flex; align-items:center; justify-content:center; gap:15px;  margin-bottom:20px;">
        <img src="logo.jpg"  style="height:50px; margin-top:20px">
        <h1 style="margin:0; margin-top:20px; color:#4CAF50;">Tundra Tax & Accounting</h1>
    </div>
  <meta charset="UTF-8">
  <title>Edit Company Information</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: Arial, sans-serif; background:#f5f7fa; padding:20px; }
    .form-container { max-width: 800px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align:center; margin-bottom:20px; color:#4CAF50; }
    label { font-weight:bold; margin-top:10px; display:block; }
    input[type="text"], input[type="email"], input[type="number"] {
      width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:6px;
    }
    button { margin-top:20px; padding:10px 20px; background:#4CAF50; color:#fff; border:none; border-radius:6px; cursor:pointer; }
    button:hover { background:#388e3c; }
    .back-link { display:inline-block; margin-top:15px; color:#4CAF50; text-decoration:none; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Edit Company Information</h2>
    <?php if (!empty($error)): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST">
      <label>Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($company['email'] ?? '') ?>">

      <label>Phone:</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>">

      <label>Share Capital:</label>
      <input type="number" name="share_capital" value="<?= htmlspecialchars($company['share_capital'] ?? '') ?>">

      <label>Financial Year:</label>
      <input type="text" name="financial_year" value="<?= htmlspecialchars($company['financial_year'] ?? '') ?>">

      <h3>Physical Address</h3>
      <label>Street:</label>
      <input type="text" name="physical_street" value="<?= htmlspecialchars($company['physical_street'] ?? '') ?>">
      <label>Building:</label>
      <input type="text" name="physical_building" value="<?= htmlspecialchars($company['physical_building'] ?? '') ?>">
      <label>City:</label>
      <input type="text" name="physical_city" value="<?= htmlspecialchars($company['physical_city'] ?? '') ?>">
      <label>Province:</label>
      <input type="text" name="physical_province" value="<?= htmlspecialchars($company['physical_province'] ?? '') ?>">
      <label>Postal Code:</label>
      <input type="text" name="physical_postal" value="<?= htmlspecialchars($company['physical_postal'] ?? '') ?>">

      <h3>Postal Address</h3>
      <label>Street:</label>
      <input type="text" name="postal_street" value="<?= htmlspecialchars($company['postal_street'] ?? '') ?>">
      <label>Building:</label>
      <input type="text" name="postal_building" value="<?= htmlspecialchars($company['postal_building'] ?? '') ?>">
      <label>City:</label>
      <input type="text" name="postal_city" value="<?= htmlspecialchars($company['postal_city'] ?? '') ?>">
      <label>Province:</label>
      <input type="text" name="postal_province" value="<?= htmlspecialchars($company['postal_province'] ?? '') ?>">
      <label>Postal Code:</label>
      <input type="text" name="postal_postal" value="<?= htmlspecialchars($company['postal_postal'] ?? '') ?>">

      <button type="submit">💾 Save Changes</button>
    </form>

    <a href="review_information.php" class="back-link">⬅ Back to Review</a>
  </div>
</body>
</html>
