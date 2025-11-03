<?php
session_start();
require 'db.php';

$application_id = $_SESSION['application_id'] ?? null;

if (!$application_id) {
    die("Application ID missing. Please complete the registration steps first.");
}













// Fetch data from all tables

// 1. Company Info
$companyQuery = $conn->prepare("SELECT * FROM company_info WHERE application_id = ?");
$companyQuery->bind_param("i", $application_id);
$companyQuery->execute();
$companyResult = $companyQuery->get_result();
$company = $companyResult->fetch_assoc();

// 2. Company Names
$namesQuery = $conn->prepare("SELECT * FROM company_names WHERE application_id = ?");
$namesQuery->bind_param("i", $application_id);
$namesQuery->execute();
$namesResult = $namesQuery->get_result();
$company_names = $namesResult->fetch_all(MYSQLI_ASSOC);

// 3. Directors
$directorsQuery = $conn->prepare("SELECT * FROM directors WHERE application_id = ?");
$directorsQuery->bind_param("i", $application_id);
$directorsQuery->execute();
$directorsResult = $directorsQuery->get_result();
$directors = $directorsResult->fetch_all(MYSQLI_ASSOC);

// 4. Shareholders
$shareholdersQuery = $conn->prepare("SELECT * FROM shareholders WHERE application_id = ?");
$shareholdersQuery->bind_param("i", $application_id);
$shareholdersQuery->execute();
$shareholdersResult = $shareholdersQuery->get_result();
$shareholders = $shareholdersResult->fetch_all(MYSQLI_ASSOC);

// 5. Contact Info
$contactQuery = $conn->prepare("SELECT * FROM app_contact_info WHERE application_id = ?");
$contactQuery->bind_param("i", $application_id);
$contactQuery->execute();
$contactResult = $contactQuery->get_result();
$contact = $contactResult->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Application Information</title>
    <link rel="stylesheet" href="style.css">
    <style>
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

body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background: #f5f7fa;
  color: #333;
}

h2 {
  text-align: center;
  margin: 20px 0;
  font-size: 28px;
}

h3 {
  border-bottom: 2px solid #4CAF50;
  padding-bottom: 6px;
  margin-top: 30px;
  color: #4CAF50;
}

.section-card {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  margin: 20px auto;
  max-width: 900px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.section-card p {
  margin: 6px 0;
}

.section-card strong {
  display: inline-block;
  min-width: 160px;
  color: #444;
}

a.edit-btn, button {
  display: inline-block;
  padding: 8px 16px;
  margin-top: 12px;
  background: #4CAF50;
  color: #fff !important;
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
  transition: background 0.3s;
  border: none;
  cursor: pointer;
}

a.edit-btn:hover, button:hover {
  background: #388e3c;
}

hr {
  border: none;
  border-top: 1px solid #ddd;
  margin: 15px 0;
}

form {
  text-align: center;
}



  </style>
</head>
<body>

<!-- Header -->
  <div class="form-container">

    <!-- Header with logo and centered title -->
    <div class="header" style="display:flex; align-items:center; justify-content:center; gap:15px;  margin-bottom:20px;">
        <img src="logo.jpg"  style="height:50px; margin-top:40px">
        <h1 style="margin:0; margin-top:40px; color:#4CAF50;">Tundra Tax & Accounting</h1>
    </div>
    <br>
    <h2>Review Your Application Information</h2>

    <!-- Company Info -->
     <div class="section-card">
    <h3>Company Information</h3>
    <?php if ($company): ?>

         <!-- Company Info -->
        <p><strong>Email:</strong> <?= htmlspecialchars($company['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($company['phone']) ?></p>
         <p><strong>Share Capital:</strong> <?= htmlspecialchars($company['share_capital']) ?></p>
          <p><strong>Financial Year:</strong> <?= htmlspecialchars($company['financial_year']) ?></p>
           <p><strong>Physical Street:</strong> <?= htmlspecialchars($company['physical_street']) ?></p>
            <p><strong>Physical Building:</strong> <?= htmlspecialchars($company['physical_building']) ?></p>
             <p><strong>Physical City:</strong> <?= htmlspecialchars($company['physical_city']) ?></p>
              <p><strong>Physical Province:</strong> <?= htmlspecialchars($company['physical_province']) ?></p>
               <p><strong>Physical Postal:</strong> <?= htmlspecialchars($company['physical_postal']) ?></p>

 <!-- postal Info -->
 <p><strong>Postal Street:</strong> <?= htmlspecialchars($company['postal_street']) ?></p>
  <p><strong>Postal Building:</strong> <?= htmlspecialchars($company['postal_building']) ?></p>
   <p><strong>Postal City:</strong> <?= htmlspecialchars($company['postal_city']) ?></p>
    <p><strong>Postal Province:</strong> <?= htmlspecialchars($company['postal_province']) ?></p>
     <p><strong>Postal Postal:</strong> <?= htmlspecialchars($company['postal_postal']) ?></p>





        <!-- Add more company_info fields as needed -->
        <a href="edit_company.php">Edit Company Info</a>
    <?php endif; ?>
</div>



    <!-- Company Names -->
     <div class="section-card">
    <h3>Company Names</h3>
    <?php foreach ($company_names as $name): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($name['preferred_name']) ?></p>
<p><strong>Alt name 1:</strong> <?= htmlspecialchars($name['alt_name1']) ?></p>
<p><strong>Alt name 2:</strong> <?= htmlspecialchars($name['alt_name2']) ?></p>
<p><strong>Alt name 3:</strong> <?= htmlspecialchars($name['alt_name3']) ?></p>
<p><strong>Has Similair:</strong> <?= htmlspecialchars($name['has_similar']) ?></p>
<p><strong>Similair Name:</strong> <?= htmlspecialchars($name['similar_name']) ?></p>
<p><strong>Similair Reg:</strong> <?= htmlspecialchars($name['similar_reg']) ?></p>



        <a href="edit_company_names.php?id=<?= $name['id'] ?>">Edit Name</a>
    <?php endforeach; ?>
</div>



    <!-- Directors -->
      <div class="section-card">
    <h3>Directors</h3>
    <?php foreach ($directors as $d): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($d['first_name'] . ' ' . $d['surname']) ?></p>
        <p><strong>ID Number:</strong> <?= htmlspecialchars($d['id_number']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($d['email']) ?></p>
         <p><strong>Phone Number:</strong> <?= htmlspecialchars($d['phone']) ?></p>
          <p><strong>Citizen:</strong> <?= htmlspecialchars($d['citizen']) ?></p>
           <p><strong>Residential Address:</strong> <?= htmlspecialchars($d['residential_address']) ?></p>
            <p><strong>Business Address:</strong> <?= htmlspecialchars($d['business_address']) ?></p>
             <p><strong>Postal Address:</strong> <?= htmlspecialchars($d['postal_address']) ?></p>
              
             <a href="edit_director.php?id=<?= $d['director_id'] ?>">Edit Director</a>

            
        
        <hr>
    <?php endforeach; ?>
</div>



    <!-- Shareholders -->
      <div class="section-card">
    <h3>Shareholders</h3>
    <?php foreach ($shareholders as $s): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($s['forenames'] . ' ' . $s['surname']) ?></p>
        <p><strong>Shares:</strong> <?= htmlspecialchars($s['shares_owned']) ?> (<?= htmlspecialchars($s['shares_percentage']) ?>%)</p>
        <p><strong>Class of Shares:</strong> <?= htmlspecialchars($s['class_of_shares']) ?></p>
        <p><strong>Allotment Date:</strong> <?= htmlspecialchars($s['allotment_date']) ?></p>
        <p><strong>Citizenship:</strong> <?= htmlspecialchars($s['citizenship']) ?></p>
        <p><strong>Cell Number:</strong> <?= htmlspecialchars($s['cell_number']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($s['email']) ?></p>
        <p><strong>ID Front:</strong> <a href="<?= htmlspecialchars($s['id_front']) ?>" target="_blank">View</a></p>
        <p><strong>ID Back:</strong> <a href="<?= htmlspecialchars($s['id_back']) ?>" target="_blank">View</a></p>
        <a href="edit_shareholder.php?id=<?= $s['id'] ?>">Edit Shareholder</a>
        <hr>
    <?php endforeach; ?>
</div>
    <!-- Contact Info -->
     <div class="section-card">
    <h3>Contact Information</h3>
    <?php if ($contact): ?>
       
         <p><strong>Name:</strong> <?= htmlspecialchars($contact['first_name'].' ' .$contact['last_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($contact['phone']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($contact['street_address']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($contact['city'] ) ?></p>
         <p><strong>Province:</strong> <?= htmlspecialchars($contact['province'] ) ?></p>
  <p><strong>ZIP Code:</strong> <?= htmlspecialchars($contact['zip_code'] ) ?></p>
         <p><strong>ID Number:</strong> <?= htmlspecialchars($contact['id_number']) ?></p>
      
       <a href="edit_contact_info.php?id=<?= $s['id'] ?>">Edit Contact Info</a>

    <?php endif; ?>
</div>
   <div class="section-card" style="text-align:center;">
  <form action="payment-demo.html" method="POST">
    <input type="hidden" name="nextPage" value="companyStatus.php">
    <button type="submit">💳  Pay and Submit </button>
  </form>
</div>


</body>
</html>
