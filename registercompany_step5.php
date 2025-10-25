<?php
session_start();
require 'db.php'; // Your database connection (ensure this file exists and contains $conn)

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $application_id = $_SESSION['application_id'] ?? null;

    if (!$application_id) {
        // Use a less aggressive UI notification than die()
        echo "<script>alert('Application ID missing. Complete previous steps first.'); window.location.href='registrationPage.php';</script>";
        exit();
    }

    if (empty($_POST['shareholders'])) {
        echo "<script>alert('No shareholder data submitted.');</script>";
        exit();
    }

    $uploadDirBase = "uploads/" . $application_id . "/";
    if (!is_dir($uploadDirBase)) {
        mkdir($uploadDirBase, 0777, true);
    }

    $all_shareholders_saved = true;

    // Loop through each submitted shareholder
    foreach ($_POST['shareholders'] as $index => $shareholder_data) {
        
        // 1. Handle File Uploads for the current shareholder ($index)
        $uploadDir = $uploadDirBase . "shareholder_" . $index . "/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Accessing files from the indexed $_FILES structure
        $file_data = $_FILES['shareholders'];
        $id_front_path = '';
        $id_back_path = '';

        try {
            // Check for upload success and move files
            if (isset($file_data['tmp_name'][$index]['id_front']) && $file_data['error'][$index]['id_front'] === UPLOAD_ERR_OK) {
                $id_front_temp = $file_data['tmp_name'][$index]['id_front'];
                $id_front_name = basename($file_data['name'][$index]['id_front']);
                $id_front_path = $uploadDir . $id_front_name;
                if (!move_uploaded_file($id_front_temp, $id_front_path)) {
                    throw new Exception("Failed to move ID front file.");
                }
            } else {
                 throw new Exception("ID front file is required or upload failed.");
            }

            if (isset($file_data['tmp_name'][$index]['id_back']) && $file_data['error'][$index]['id_back'] === UPLOAD_ERR_OK) {
                $id_back_temp = $file_data['tmp_name'][$index]['id_back'];
                $id_back_name = basename($file_data['name'][$index]['id_back']);
                $id_back_path = $uploadDir . $id_back_name;
                if (!move_uploaded_file($id_back_temp, $id_back_path)) {
                    throw new Exception("Failed to move ID back file.");
                }
            } else {
                 throw new Exception("ID back file is required or upload failed.");
            }

            // 2. Prepare and execute SQL Insert
            $shares_owned = (int)$shareholder_data['shares_owned'];
            $shares_percentage = (float)$shareholder_data['shares_percentage'];

            $stmt = $conn->prepare("
                INSERT INTO shareholders (
                    application_id, interest_type, forenames, surname,
                    shares_owned, shares_percentage, class_of_shares, allotment_date,
                    citizenship, id_front, id_back,
                    res_street_no, res_street_name, res_city, res_province, res_postal,
                    bus_street_no, bus_street_name, bus_city, bus_province, bus_postal,
                    postal_address, postal_city, postal_province, postal_code,
                    cell_number, email
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "isssiddssssssssssssssssssss",
                $application_id,
                $shareholder_data['interest_type'],
                $shareholder_data['forenames'],
                $shareholder_data['surname'],
                $shares_owned,
                $shares_percentage,
                $shareholder_data['class_of_shares'],
                $shareholder_data['allotment_date'],
                $shareholder_data['citizenship'],
                $id_front_path, 
                $id_back_path,
                $shareholder_data['res_street_no'],
                $shareholder_data['res_street_name'],
                $shareholder_data['res_city'],
                $shareholder_data['res_province'],
                $shareholder_data['res_postal'],
                $shareholder_data['bus_street_no'],
                $shareholder_data['bus_street_name'],
                $shareholder_data['bus_city'],
                $shareholder_data['bus_province'],
                $shareholder_data['bus_postal'],
                $shareholder_data['postal_address'],
                $shareholder_data['postal_city'],
                $shareholder_data['postal_province'],
                $shareholder_data['postal_code'],
                $shareholder_data['cell_number'],
                $shareholder_data['email']
            );

            if (!$stmt->execute()) {
                throw new Exception("Error saving shareholder " . ($index + 1) . ": " . $stmt->error);
            }
            $stmt->close();
            
        } catch (Exception $e) {
            echo "<script>console.error('Shareholder " . ($index + 1) . " error: " . $e->getMessage() . "');</script>";
            $all_shareholders_saved = false;
        }
    }
    
    $conn->close();

    if ($all_shareholders_saved) {
        header("Location: review_information.php");
        exit();
    } else {
        // If there was an error, the script continues to render the form, 
        // and the console error shows the specific failure.
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Company - Step 5: Shareholder Details</title>
  <!-- Consolidated Styles -->
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
/* Update the container in the header */
.header .container {
    max-width: 920px; /* Use the width of your main content area */
    margin: 0 auto;   /* Keep the container centered */
    
    display: flex; 
    align-items: center; 
    
    /* Crucial: Ensures the brand starts at the left edge of the 920px container */
    justify-content: flex-start; 
    
    /* Keep padding for space between content and container edges */
    padding: 0 30px; 
}

/* Ensure the navigation links are always on one line */
nav {
    white-space: nowrap; 
    /* Crucial: This pushes the navigation links all the way to the right side 
       of the 920px container, creating a tight gap between the brand and nav */
    margin-left: auto; 
}
    .header .container .brand {
      display: flex; align-items: center; 
    }
    .header .container .brand img { height: 46px; border-radius: 8px; }
    .logo { margin: 0; }

.logo { 
    margin: 0; 
    white-space: nowrap; 
    color: #04c707;
    /* Ensure a decent font size */
    font-size: 1.7rem; 
}

    

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



/* Inside the <style> tag of the Shareholder Details page */

/* --- Header & Navigation Styles --- */
/* Locate and update the .header rule in your <style> block */
.header {
    background-color: #f7f7f7; 
    border-bottom: 1px solid #ddd;
    /* Increase vertical padding to match the target design's height */
    padding: 29px 0; /* Use 25px (or maybe 30px) for a taller look */
}

/* Ensure the logo image is the size you want for height */
.header .container .brand img { 
    height: 55px; /* Use a height that looks good for the final size */
    border-radius: 8px; 
}









nav a {
    color: black; 
    text-decoration: none;
    margin: 0 10px;
    font-weight: bold;
    transition: color 0.3s ease;
}






  </style>

<body>
</head>
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
      <h2>Register Your Company - Step 5: Shareholder Details</h2>
    </div>
    <div class="form-body">
      <p class="page-intro">Please complete the details for all shareholders. Click "Add Another Shareholder" for additional entries.</p>

      <form action="" method="POST" enctype="multipart/form-data" id="shareholderForm">

        <!-- Shareholder Container (Template for cloning) -->
        <div id="shareholder-list">
            <!-- Initial Shareholder Block -->
            <div class="shareholder-entry" data-index="0">
                <h3>Shareholder 1</h3>
                
                <label>Interest Type *</label>
                <select name="shareholders[0][interest_type]" required>
                    <option value="Shareholder">Shareholder</option>
                </select>

                <div class="row">
                    <div>
                        <label>Full Forenames *</label>
                        <input type="text" name="shareholders[0][forenames]" required>
                    </div>
                    <div>
                        <label>Surname *</label>
                        <input type="text" name="shareholders[0][surname]" required>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Number of Shares *</label>
                        <input type="number" name="shareholders[0][shares_owned]" required min="0" step="1">
                    </div>
                    <div>
                        <label>Percentage Shares Issued (%) *</label>
                        <input type="number" name="shareholders[0][shares_percentage]" required step="0.01" min="0" max="100">
                    </div>
                </div>

                <label>Class of Shares *</label>
                <select name="shareholders[0][class_of_shares]" required>
                    <option value="Ordinary Shares">Ordinary Shares</option>
                    <option value="Other Shares">Other Shares</option>
                </select>

                <label>Allotment Date *</label>
                <input type="date" name="shareholders[0][allotment_date]" required>

                <label>Are you a South African Citizen? *</label>
                <select name="shareholders[0][citizenship]" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                




                
                <label>Upload FRONT of Shareholder ID *</label>
                <input type="file" name="shareholders[0][id_front]" accept="image/*,application/pdf" required>

                <label>Upload BACK of Shareholder ID *</label>
                <input type="file" name="shareholders[0][id_back]" accept="image/*,application/pdf" required>

                <fieldset>
                    <legend>Residential Address *</legend>
                    <div class="row">
                        <div><label>Street / Building Number</label><input type="text" name="shareholders[0][res_street_no]" placeholder="Number" required></div>
                        <div><label>Street Name</label><input type="text" name="shareholders[0][res_street_name]" placeholder="Street Name" required></div>
                    </div>
                    <div class="row">
                        <div><label>City / Town / Suburb</label><input type="text" name="shareholders[0][res_city]" placeholder="City" required></div>
                        <div><label>Province</label><input type="text" name="shareholders[0][res_province]" placeholder="Province" required></div>
                    </div>
                    <label>Postal Code</label><input type="text" name="shareholders[0][res_postal]" placeholder="Postal Code" required>
                </fieldset>

                <fieldset>
                    <legend>Business Address *</legend>
                    <div class="row">
                        <div><label>Street / Building Number</label><input type="text" name="shareholders[0][bus_street_no]" placeholder="Number" required></div>
                        <div><label>Street Name</label><input type="text" name="shareholders[0][bus_street_name]" placeholder="Street Name" required></div>
                    </div>
                    <div class="row">
                        <div><label>City / Town / Suburb</label><input type="text" name="shareholders[0][bus_city]" placeholder="City" required></div>
                        <div><label>Province</label><input type="text" name="shareholders[0][bus_province]" placeholder="Province" required></div>
                    </div>
                    <label>Postal Code</label><input type="text" name="shareholders[0][bus_postal]" placeholder="Postal Code" required>
                </fieldset>

                <fieldset>
                    <legend>Postal Address *</legend>
                    <label>Postal Address</label><input type="text" name="shareholders[0][postal_address]" placeholder="P.O. Box or Private Bag" required>
                    <div class="row">
                        <div><label>City / Town / Suburb</label><input type="text" name="shareholders[0][postal_city]" placeholder="City" required></div>
                        <div><label>Province/State</label><input type="text" name="shareholders[0][postal_province]" placeholder="Province/State" required></div>
                    </div>
                    <label>Postal Code</label><input type="text" name="shareholders[0][postal_code]" placeholder="Postal Code" required>
                </fieldset>

                <label>Shareholder Cell Phone Number *</label>
                <input type="tel" name="shareholders[0][cell_number]" required pattern="[0-9]{10}" placeholder="e.g. 0821234567">

                <label>Shareholder Email Address *</label>
                <input type="email" name="shareholders[0][email]" required>
                
                <button type="button" class="remove-btn" onclick="removeShareholder(this)" disabled>Remove</button>
            </div>
            <!-- End Initial Shareholder Block -->
        </div>
        
        <!-- Add Shareholder Button (Placement matching Director file) -->
        <div class="add-shareholder-container">
            <button type="button" class="btn-add" id="add-shareholder-btn">Add Another Shareholder</button>
        </div>

        <div class="actions">
            <button type="submit" class="btn-register">Save and Continue</button>
        </div>
      </form>
    </div>
  </div>
</main>



<script>
    let shareholderCount = 1; 

    document.getElementById('add-shareholder-btn').addEventListener('click', addShareholder);

    /**
     * Clones the last shareholder entry block, updates the names/IDs with the new index,
     * clears the input values, and adds a working remove button.
     */
    function addShareholder() {
        const list = document.getElementById('shareholder-list');
        
        // Use the actual last entry for cloning
        const lastEntry = list.lastElementChild;
        const clone = lastEntry.cloneNode(true);

        const newIndex = shareholderCount;
        
        // 1. Update the overall container attributes
        clone.setAttribute('data-index', newIndex);
        
        // 2. Update the header text
        const header = clone.querySelector('h3');
        header.textContent = `Shareholder ${newIndex + 1}`;

        // 3. Update all input names and clear values
        clone.querySelectorAll('[name*="shareholders["]').forEach(input => {
            const oldName = input.name;
            const newName = oldName.replace(/shareholders\[\d+\]/, `shareholders[${newIndex}]`);
            
            input.name = newName;
            
            // Clear input values
            if (input.type === 'text' || input.type === 'email' || input.type === 'tel' || input.type === 'number' || input.type === 'date') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                 // Reset selects to the first option
                 input.selectedIndex = 0;
            } else if (input.type === 'file') {
                 // Reset file inputs
                 input.value = ''; 
            }
        });

        // 4. Update the remove button
        const removeBtn = clone.querySelector('.remove-btn');
        if (removeBtn) {
            removeBtn.disabled = false; // Enable the remove button on cloned entries
        }

        list.appendChild(clone);
        shareholderCount++;
        
        // Ensure the first entry's remove button is still disabled if it was the only one
        toggleRemoveButtons();
    }
    
    /**
     * Removes a shareholder block from the DOM.
     * @param {HTMLElement} button - The button clicked.
     */
    function removeShareholder(button) {
        const entry = button.closest('.shareholder-entry');
        if (entry) {
            // Use a confirmation modal instead of alert/confirm
            showCustomModal("Are you sure you want to remove this shareholder?", () => {
                entry.remove();
                reindexShareholders();
            });
        }
    }
    
    /**
     * Reindexes all shareholder fields to ensure sequential array indices (0, 1, 2, ...)
     * after an element is removed.
     */
    function reindexShareholders() {
        const list = document.getElementById('shareholder-list');
        const entries = list.querySelectorAll('.shareholder-entry');
        
        entries.forEach((entry, newIndex) => {
            // 1. Update container data index
            entry.setAttribute('data-index', newIndex);
            
            // 2. Update header text
            const header = entry.querySelector('h3');
            header.textContent = `Shareholder ${newIndex + 1}`;

            // 3. Update all input names
            entry.querySelectorAll('[name*="shareholders["]').forEach(input => {
                const oldName = input.name;
                // Use a RegExp to ensure only the index is replaced
                const newName = oldName.replace(/shareholders\[\d+\]/, `shareholders[${newIndex}]`);
                input.name = newName;
            });
        });

        // Update the counter to reflect the total number of shareholders present
        shareholderCount = entries.length;
        
        // Update button state based on remaining count
        toggleRemoveButtons();
    }
    
    /**
     * Toggles the 'Remove' button's disabled state for the first entry.
     */
    function toggleRemoveButtons() {
        const list = document.getElementById('shareholder-list');
        const entries = list.querySelectorAll('.shareholder-entry');
        
        entries.forEach(entry => {
            const removeBtn = entry.querySelector('.remove-btn');
            if (removeBtn) {
                // Disable removal if there is only one shareholder left
                removeBtn.disabled = entries.length === 1;
            }
        });
    }

    // --- Custom Modal Implementation (Replaces alert/confirm) ---
    
    function showCustomModal(message, callback) {
        // Create modal backdrop
        const backdrop = document.createElement('div');
        backdrop.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; justify-content: center; align-items: center;';
        
        // Create modal content box
        const modal = document.createElement('div');
        modal.style.cssText = 'background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); max-width: 400px; text-align: center;';

        const text = document.createElement('p');
        text.textContent = message;
        text.style.marginBottom = '20px';

        const confirmBtn = document.createElement('button');
        confirmBtn.textContent = 'Yes, Remove';
        confirmBtn.style.cssText = 'background: #ff4d4d; color: white; padding: 10px 15px; border: none; border-radius: 8px; margin-right: 10px; cursor: pointer;';
        confirmBtn.onclick = () => {
            callback();
            backdrop.remove();
        };

        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.style.cssText = 'background: #f0f0f0; color: #333; padding: 10px 15px; border: 1px solid #ccc; border-radius: 8px; cursor: pointer;';
        cancelBtn.onclick = () => {
            backdrop.remove();
        };

        modal.appendChild(text);
        modal.appendChild(confirmBtn);
        modal.appendChild(cancelBtn);
        backdrop.appendChild(modal);
        document.body.appendChild(backdrop);
    }
    
    // Initial setup when the page loads
    window.onload = toggleRemoveButtons;

</script>

</body>
</html>