<?php
session_start();
require 'db.php';

$application_id = $_SESSION['application_id'] ?? null;
$id = $_GET['id'] ?? null;

if (!$application_id || !$id) {
    die("Missing application or director ID.");
}

// Fetch the director record
$query = $conn->prepare("SELECT * FROM directors WHERE  application_id = ?");
$query->bind_param("i",  $application_id);
$query->execute();
$result = $query->get_result();
$director = $result->fetch_assoc();

if (!$director) {
    die("Director record not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $citizen = $_POST['citizen'] ?? '';
    $residential_address = $_POST['residential_address'] ?? '';
    $business_address = $_POST['business_address'] ?? '';
    $postal_address = $_POST['postal_address'] ?? '';

    $update = $conn->prepare("UPDATE directors SET 
        first_name=?, surname=?, id_number=?, email=?, phone=?, citizen=?, residential_address=?, business_address=?, postal_address=?
        WHERE  application_id=?");
    $update->bind_param(
        "sssssssssi",
        $first_name, $surname, $id_number, $email, $phone, $citizen, $residential_address, $business_address, $postal_address,  $application_id
    );

    if ($update->execute()) {
        header("Location: review_information.php");
        exit;
    } else {
        $error = "Failed to update director info.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<div class="header" style="display:flex; align-items:center; justify-content:center; gap:15px;  margin-bottom:20px;">
        <img src="logo.jpg"  style="height:50px; margin-top:20px">
        <h1 style="margin:0; margin-top:20px; color:#4CAF50;">Tundra Tax & Accounting</h1>
    </div>

<title>Edit Director</title>
<link rel="stylesheet" href="style.css">
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
</head>
<body>
    
<div class="form-container">
    <div class="form-header">
      
      <h2>Edit Director</h2>
    </div>


<?php if (!empty($error)): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="POST">
    <label>First Name:</label>
    <input type="text" name="first_name" value="<?= htmlspecialchars($director['first_name'] ?? '') ?>">

    <label>Surname:</label>
    <input type="text" name="surname" value="<?= htmlspecialchars($director['surname'] ?? '') ?>">

    <label>ID Number:</label>
    <input type="text" name="id_number" value="<?= htmlspecialchars($director['id_number'] ?? '') ?>">

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($director['email'] ?? '') ?>">

    <label>Phone:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($director['phone'] ?? '') ?>">

    <label>Citizenship:</label>
    <input type="text" name="citizen" value="<?= htmlspecialchars($director['citizen'] ?? '') ?>">

    <label>Residential Address:</label>
    <input type="text" name="residential_address" value="<?= htmlspecialchars($director['residential_address'] ?? '') ?>">

    <label>Business Address:</label>
    <input type="text" name="business_address" value="<?= htmlspecialchars($director['business_address'] ?? '') ?>">

    <label>Postal Address:</label>
    <input type="text" name="postal_address" value="<?= htmlspecialchars($director['postal_address'] ?? '') ?>">

    <button type="submit">💾 Save Changes</button>
</form>

<a href="review_information.php" class="back-link">⬅ Back to Review</a>
</div>
</body>
</html>
