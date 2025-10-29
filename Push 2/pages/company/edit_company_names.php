<?php
session_start();
require '../../includes/db.php';

$application_id = $_SESSION['application_id'] ?? null;
$id = $_GET['id'] ?? null;

if (!$application_id || !$id) {
    die("Missing application or company name ID.");
}

// Fetch the record
$query = $conn->prepare("SELECT * FROM company_names WHERE id = ? AND application_id = ?");
$query->bind_param("ii", $id, $application_id);
$query->execute();
$result = $query->get_result();
$company_name = $result->fetch_assoc();

if (!$company_name) {
    die("Company name record not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $preferred_name = $_POST['preferred_name'] ?? '';
    $alt_name1 = $_POST['alt_name1'] ?? '';
    $alt_name2 = $_POST['alt_name2'] ?? '';
    $alt_name3 = $_POST['alt_name3'] ?? '';
    $has_similar = $_POST['has_similar'] ?? '';
    $similar_name = $_POST['similar_name'] ?? '';
    $similar_reg = $_POST['similar_reg'] ?? '';

    $update = $conn->prepare("UPDATE company_names 
        SET preferred_name=?, alt_name1=?, alt_name2=?, alt_name3=?, has_similar=?, similar_name=?, similar_reg=?
        WHERE id=? AND application_id=?");
    $update->bind_param(
        "ssssssssi",
        $preferred_name, $alt_name1, $alt_name2, $alt_name3, $has_similar, $similar_name, $similar_reg, $id, $application_id
    );

    if ($update->execute()) {
        header("Location: review_information.php");
        exit;
    } else {
        $error = "Failed to update company name.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Company Name</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    body { font-family: Arial, sans-serif; background:#f5f7fa; padding:20px; }
    .form-container { max-width: 600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    h2 { text-align:center; margin-bottom:20px; color:#4CAF50; }
    label { font-weight:bold; margin-top:10px; display:block; }
    input[type="text"] { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:6px; }
    button { margin-top:20px; padding:10px 20px; background:#4CAF50; color:#fff; border:none; border-radius:6px; cursor:pointer; }
    button:hover { background:#388e3c; }
    .back-link { display:inline-block; margin-top:15px; color:#4CAF50; text-decoration:none; }
  </style>
</head>
<body>

<div class="header" style="display:flex; align-items:center; justify-content:center; gap:15px;  margin-bottom:20px;">
        <img src="../../assets/images/logo.jpg"  style="height:50px; margin-top:20px">
        <h1 style="margin:0; margin-top:20px; color:#4CAF50;">Tundra Tax & Accounting</h1>
    </div>

  <div class="form-container">
    <h2>Edit Company Name</h2>
    <?php if (!empty($error)): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST">
      <label>Preferred Name:</label>
      <input type="text" name="preferred_name" value="<?= htmlspecialchars($company_name['preferred_name'] ?? '') ?>">

      <label>Alt Name 1:</label>
      <input type="text" name="alt_name1" value="<?= htmlspecialchars($company_name['alt_name1'] ?? '') ?>">

      <label>Alt Name 2:</label>
      <input type="text" name="alt_name2" value="<?= htmlspecialchars($company_name['alt_name2'] ?? '') ?>">

      <label>Alt Name 3:</label>
      <input type="text" name="alt_name3" value="<?= htmlspecialchars($company_name['alt_name3'] ?? '') ?>">

      <label>Has Similar Name? (Yes/No):</label>
      <input type="text" name="has_similar" value="<?= htmlspecialchars($company_name['has_similar'] ?? '') ?>">

      <label>Similar Name:</label>
      <input type="text" name="similar_name" value="<?= htmlspecialchars($company_name['similar_name'] ?? '') ?>">

      <label>Similar Reg Number:</label>
      <input type="text" name="similar_reg" value="<?= htmlspecialchars($company_name['similar_reg'] ?? '') ?>">

      <button type="submit">💾 Save Changes</button>
    </form>

    <a href="review_information.php" class="back-link">⬅ Back to Review</a>
  </div>
</body>
</html>
