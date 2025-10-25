<?php
session_start();
require 'db.php';

$application_id = $_SESSION['application_id'] ?? null;
$id = $_GET['id'] ?? null;

if (!$application_id || !$id) {
    die("Missing application or shareholder ID.");
}

// Fetch the shareholder record
$query = $conn->prepare("SELECT * FROM shareholders WHERE id = ? AND application_id = ?");
$query->bind_param("ii", $id, $application_id);
$query->execute();
$result = $query->get_result();
$shareholder = $result->fetch_assoc();

if (!$shareholder) {
    die("Shareholder record not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $forenames = $_POST['forenames'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $shares_owned = $_POST['shares_owned'] ?? '';
    $shares_percentage = $_POST['shares_percentage'] ?? '';
    $class_of_shares = $_POST['class_of_shares'] ?? '';
    $allotment_date = $_POST['allotment_date'] ?? '';
    $citizenship = $_POST['citizenship'] ?? '';
    $cell_number = $_POST['cell_number'] ?? '';
    $email = $_POST['email'] ?? '';

    // Handle file uploads
    $uploadDir = 'uploads/'; // Make sure this directory exists and is writable
    $id_front = $shareholder['id_front'];
    $id_back = $shareholder['id_back'];

    if (!empty($_FILES['id_front']['name'])) {
        $frontName = basename($_FILES['id_front']['name']);
        $frontPath = $uploadDir . uniqid() . '_' . $frontName;
        if (move_uploaded_file($_FILES['id_front']['tmp_name'], $frontPath)) {
            $id_front = $frontPath;
        }
    }

    if (!empty($_FILES['id_back']['name'])) {
        $backName = basename($_FILES['id_back']['name']);
        $backPath = $uploadDir . uniqid() . '_' . $backName;
        if (move_uploaded_file($_FILES['id_back']['tmp_name'], $backPath)) {
            $id_back = $backPath;
        }
    }

    $update = $conn->prepare("UPDATE shareholders SET 
        forenames=?, surname=?, shares_owned=?, shares_percentage=?, class_of_shares=?, allotment_date=?, citizenship=?, cell_number=?, email=?, id_front=?, id_back=?
        WHERE  application_id=?");

    $update->bind_param(
        "ssddsssssssi",
        $forenames, $surname, $shares_owned, $shares_percentage, $class_of_shares, $allotment_date, $citizenship, $cell_number, $email, $id_front, $id_back,  $application_id
    );

    if ($update->execute()) {
        header("Location: review_information.php");
        exit;
    } else {
        $error = "Failed to update shareholder info.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Shareholder</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; background:#f5f7fa; padding:20px; }
.form-container { max-width: 600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
h2 { text-align:center; margin-bottom:20px; color:#4CAF50; }
label { font-weight:bold; margin-top:10px; display:block; }
input[type="text"], input[type="email"], input[type="number"], input[type="file"], input[type="date"] { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:6px; }
button { margin-top:20px; padding:10px 20px; background:#4CAF50; color:#fff; border:none; border-radius:6px; cursor:pointer; }
button:hover { background:#388e3c; }
.back-link { display:inline-block; margin-top:15px; color:#4CAF50; text-decoration:none; }
</style>
</head>
<body>

<div class="header" style="display:flex; align-items:center; justify-content:center; gap:15px;  margin-bottom:20px;">
        <img src="logo.jpg"  style="height:50px; margin-top:20px">
        <h1 style="margin:0; margin-top:20px; color:#4CAF50;">Tundra Tax & Accounting</h1>
    </div>

<div class="form-container">
<h2>Edit Shareholder</h2>
<?php if (!empty($error)): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Forenames:</label>
    <input type="text" name="forenames" value="<?= htmlspecialchars($shareholder['forenames'] ?? '') ?>">

    <label>Surname:</label>
    <input type="text" name="surname" value="<?= htmlspecialchars($shareholder['surname'] ?? '') ?>">

    <label>Shares Owned:</label>
    <input type="number" name="shares_owned" step="1" value="<?= htmlspecialchars($shareholder['shares_owned'] ?? '') ?>">

    <label>Shares Percentage:</label>
    <input type="number" name="shares_percentage" step="0.01" value="<?= htmlspecialchars($shareholder['shares_percentage'] ?? '') ?>">

    <label>Class of Shares:</label>
    <input type="text" name="class_of_shares" value="<?= htmlspecialchars($shareholder['class_of_shares'] ?? '') ?>">

    <label>Allotment Date:</label>
    <input type="date" name="allotment_date" value="<?= htmlspecialchars($shareholder['allotment_date'] ?? '') ?>">

    <label>Citizenship:</label>
    <input type="text" name="citizenship" value="<?= htmlspecialchars($shareholder['citizenship'] ?? '') ?>">

    <label>Cell Number:</label>
    <input type="text" name="cell_number" value="<?= htmlspecialchars($shareholder['cell_number'] ?? '') ?>">

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($shareholder['email'] ?? '') ?>">

    <label>ID Front:</label>
    <?php if (!empty($shareholder['id_front'])): ?>
        <p><a href="<?= htmlspecialchars($shareholder['id_front']) ?>" target="_blank">View Current File</a></p>
    <?php endif; ?>
    <input type="file" name="id_front">

    <label>ID Back:</label>
    <?php if (!empty($shareholder['id_back'])): ?>
        <p><a href="<?= htmlspecialchars($shareholder['id_back']) ?>" target="_blank">View Current File</a></p>
    <?php endif; ?>
    <input type="file" name="id_back">

    <button type="submit">💾 Save Changes</button>
</form>

<a href="review_information.php" class="back-link">⬅ Back to Review</a>
</div>
</body>
</html>
