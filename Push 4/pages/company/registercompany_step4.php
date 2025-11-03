<?php
session_start();
require '../../includes/db.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $application_id = $_SESSION['application_id'] ?? null;

    if (!$application_id) {
        die("Application ID missing. Please complete previous steps first.");
    }

    if (isset($_POST['director']) && is_array($_POST['director'])) {
        $stmt = $conn->prepare("INSERT INTO directors 
            (application_id, first_name, surname, id_number, citizen, residential_address, business_address, postal_address, phone, email)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($_POST['director'] as $dir) {
            $stmt->bind_param(
                "isssssssss",
                $application_id,
                $dir['firstName'],
                $dir['surname'],
                $dir['ID'],
                $dir['citizen'],
                $dir['residential_address'],
                $dir['business_address'],
                $dir['postal_address'],
                $dir['phone'],
                $dir['email']
            );
            $stmt->execute();
        }

        $stmt->close();
        header("Location: registercompany_step5.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register Company - Step 4</title>
  <link rel="stylesheet" href="../../assets/css/style.css" />
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
  <script>
function addDirector() {
    const container = document.getElementById('directors-container');
    const index = container.children.length + 1; // next director number
    const div = document.createElement('div');
    div.className = 'director';
    div.innerHTML = `
        <h3>Director ${index}</h3>

        <label>Director Name *</label>
        <input type="text" name="director[${index}][firstName]" required placeholder="First Name">

        <label>Director Surname *</label>
        <input type="text" name="director[${index}][surname]" required placeholder="Surname">

        <label>Director ID *</label>
        <input type="text" name="director[${index}][ID]" required placeholder="ID Number">

        <label>Are you a South African Citizen? *</label>
        <select name="director[${index}][citizen]" required>
            <option value="">Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

        <label>Residential Address *</label>
        <input type="text" name="director[${index}][residential_address]" required placeholder="Street / Building / City / Province / Postal Code">

        <label>Business Address *</label>
        <input type="text" name="director[${index}][business_address]" required placeholder="Street / Building / City / Province / Postal Code">

        <label>Postal Address (if different)</label>
        <input type="text" name="director[${index}][postal_address]" placeholder="P.O. Box or Private Bag">

        <label>Director Contact Number *</label>
        <input type="tel" name="director[${index}][phone]" required placeholder="e.g. 0211234567">

        <label>Director Email Address *</label>
        <input type="email" name="director[${index}][email]" required placeholder="name@example.com">
    `;
    container.appendChild(div);
}
</script>

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

   <div class="form-shell">
  <div class="form-header">
    <img src="../../assets/images/logo.jpg" alt="Tundra Logo">
    <h2>Register Your Company - Step 4/ Director Details</h2>
  </div>
<div class="form-body">
<p>Please complete the details of each Director. We need all the information to proceed.</p>


 <div class="form-shell">

<form action="registercompany_step4.php" method="POST"> 
    <div id="directors-container" class="directors">
        <div class="director">
            <h3>Director 1</h3>
            <label>Director Name *</label>
            <input type="text" name="director[1][firstName]" required placeholder="First Name">

            <label>Director Surname *</label>
            <input type="text" name="director[1][surname]" required placeholder="Surname">

            <label>Director ID *</label>
            <input type="text" name="director[1][ID]" required placeholder="ID Number">

            <label>Are you a South African Citizen? *</label>
            <select name="director[1][citizen]" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <label>Residential Address *</label>
            <input type="text" name="director[1][residential_address]" required placeholder="Street / Building / City / Province / Postal Code">

            <label>Business Address *</label>
            <input type="text" name="director[1][business_address]" required placeholder="Street / Building / City / Province / Postal Code">

            <label>Postal Address (if different)</label>
            <input type="text" name="director[1][postal_address]" placeholder="P.O. Box or Private Bag">

            <label>Director Contact Number *</label>
            <input type="tel" name="director[1][phone]" required placeholder="e.g. 0211234567">

            <label>Director Email Address *</label>
            <input type="email" name="director[1][email]" required placeholder="name@example.com">
        </div>
    </div>

    <div class="add-director">
        <button type="button" onclick="addDirector()">Add Another Director</button>
    </div>

   <div class="actions">
    <button type="submit" class="btn-register">Next</button>
</div>

</form>
</div>
</div>

</body>
</html>
