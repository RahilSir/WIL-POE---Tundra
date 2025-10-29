<?php
session_start();
require '../../includes/db.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get application ID
$application_id = $_SESSION['application_id'] ?? null;
if (!$application_id) {
    die("Application ID missing. Please complete registration first.");
}

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
$company_names = $namesResult->fetch_assoc(); // single row


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




$logoPath = __DIR__ . "/../../assets/images/logo.jpg";
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc  = "data:image/jpeg;base64," . $logoData;
} else {
    $logoSrc = ""; 
    error_log("Logo file not found: " . $logoPath);
}


// Generate PDF HTML
$pdfHtml = '

<html>
<head>
<style>
body { font-family: Arial, sans-serif; }
.header { text-align: center; }
.header img { height: 60px; }
h1 { color: #28a745; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; word-wrap: break-word; }
th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
th { background-color: #f2f2f2; }
</style>
</head>
<body>
<div class="header">
<img src="' . $logoSrc . '" style="height:60px;">

<h1>Company Registration Details</h1>
</div>


<h2>Contact Info</h2>
<table>
<tr><td>First Name</td><td>' . htmlspecialchars($contact['first_name']) . '</td></tr>
<tr><td>Last Name</td><td>' . htmlspecialchars($contact['last_name']) . '</td></tr>
<tr><td>Email</td><td>' . htmlspecialchars($contact['email']) . '</td></tr>
<tr><td>Phone</td><td>' . htmlspecialchars($contact['phone']) . '</td></tr>
<tr><td>ID Number</td><td>' . htmlspecialchars($contact['id_number']) . '</td></tr>
<tr><td>Street Address</td><td>' . htmlspecialchars($contact['street_address']) . '</td></tr>
<tr><td>City</td><td>' . htmlspecialchars($contact['city']) . '</td></tr>
<tr><td>Province</td><td>' . htmlspecialchars($contact['province']) . '</td></tr>
<tr><td>ZIP Code</td><td>' . htmlspecialchars($contact['zip_code']) . '</td></tr>
</table>

<h2>Company Names</h2>
<table>
<tr><td>Preferred Name</td><td>' . htmlspecialchars($company_names['preferred_name']) . '</td></tr>
<tr><td>Alt Name 1</td><td>' . htmlspecialchars($company_names['alt_name1']) . '</td></tr>
<tr><td>Alt Name 2</td><td>' . htmlspecialchars($company_names['alt_name2']) . '</td></tr>
<tr><td>Alt Name 3</td><td>' . htmlspecialchars($company_names['alt_name3']) . '</td></tr>
<tr><td>Has Similar Name</td><td>' . htmlspecialchars($company_names['has_similar']) . '</td></tr>
<tr><td>Similar Name</td><td>' . htmlspecialchars($company_names['similar_name']) . '</td></tr>
<tr><td>Similar Reg Number</td><td>' . htmlspecialchars($company_names['similar_reg']) . '</td></tr>
</table>



<h2>Company Information</h2>
<table>
<tr><td>Email</td><td>' . htmlspecialchars($company['email']) . '</td></tr>
<tr><td>Phone</td><td>' . htmlspecialchars($company['phone']) . '</td></tr>
<tr><td>Share Capital</td><td>' . htmlspecialchars($company['share_capital']) . '</td></tr>
<tr><td>Financial Year</td><td>' . htmlspecialchars($company['financial_year']) . '</td></tr>
<tr><td>Physical Street Address</td><td>' . htmlspecialchars($company['physical_street']) . '</td></tr>
<tr><td>Building</td><td>' . htmlspecialchars($company['physical_building']) . '</td></tr>
<tr><td>City</td><td>' . htmlspecialchars($company['physical_city']) . '</td></tr>
<tr><td>Province</td><td>' . htmlspecialchars($company['physical_province']) . '</td></tr>
<tr><td>Postal Code</td><td>' . htmlspecialchars($company['physical_postal']) . '</td></tr>
<tr><td>Postal Street</td><td>' . htmlspecialchars($company['postal_street']) . '</td></tr>
<tr><td>Postal Building</td><td>' . htmlspecialchars($company['postal_building']) . '</td></tr>
<tr><td>Postal City</td><td>' . htmlspecialchars($company['postal_city']) . '</td></tr>
<tr><td>Postal Province</td><td>' . htmlspecialchars($company['postal_province']) . '</td></tr>
<tr><td>Postal Code</td><td>' . htmlspecialchars($company['postal_postal']) . '</td></tr>
</table>

<h2>Directors</h2>
<table>
<thead>
<tr>
<th>First Name</th>
<th>Surname</th>
<th>ID Number</th>
<th>Citizen</th>
<th>Residential Address</th>
<th>Business Address</th>
<th>Postal Address</th>
<th>Phone</th>
<th>Email</th>
</tr>
</thead>
<tbody>';
foreach ($directors as $dir) {
    $pdfHtml .= '<tr>
        <td>' . htmlspecialchars($dir['first_name']) . '</td>
        <td>' . htmlspecialchars($dir['surname']) . '</td>
        <td>' . htmlspecialchars($dir['id_number']) . '</td>
        <td>' . htmlspecialchars($dir['citizen']) . '</td>
        <td>' . htmlspecialchars($dir['residential_address']) . '</td>
        <td>' . htmlspecialchars($dir['business_address']) . '</td>
        <td>' . htmlspecialchars($dir['postal_address']) . '</td>
        <td>' . htmlspecialchars($dir['phone']) . '</td>
        <td>' . htmlspecialchars($dir['email']) . '</td>
    </tr>';
}
$pdfHtml .= '</tbody></table>

<h2>Shareholders</h2>
<table>
<thead>
<tr>
<th>Name</th>
<th>Shares Owned</th>
<th>Class of Shares</th>
<th>Allotment Date</th>
<th>Citizenship</th>
<th>Cell Number</th>
<th>Email</th>

</tr>
</thead>
<tbody>';
foreach ($shareholders as $s) {
    $pdfHtml .= '<tr>
        <td>' . htmlspecialchars($s['forenames'] . ' ' . $s['surname']) . '</td>
        <td>' . htmlspecialchars($s['shares_owned']) . ' (' . htmlspecialchars($s['shares_percentage']) . '%)</td>
        <td>' . htmlspecialchars($s['class_of_shares']) . '</td>
        <td>' . htmlspecialchars($s['allotment_date']) . '</td>
        <td>' . htmlspecialchars($s['citizenship']) . '</td>
        <td>' . htmlspecialchars($s['cell_number']) . '</td>
        <td>' . htmlspecialchars($s['email']) . '</td>
        
    </tr>';


    
}



$pdfHtml .= '</tbody></table>
</body>
</html>';


// Render PDF
/** @var Dompdf $dompdf */
$dompdf = new Dompdf();
$dompdf->loadHtml($pdfHtml);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdfOutput = $dompdf->output();
$pdfFilePath = __DIR__ . "/../../documents/registration_{$application_id}.pdf";
if (file_put_contents($pdfFilePath, $pdfOutput) === false) {
    error_log("Failed to save PDF file: " . $pdfFilePath);
    throw new Exception("Failed to save PDF file");
}

// Send email using PHPMailer
/** @var PHPMailer $mail */

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rahilsirkissoon@gmail.com'; // Replace with sender email
    $mail->Password   = 'nest nnsm gidz iqcq'; // Replace with app password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('rahilsirkissoon@gmail.com', 'Tundra Tax & Accounting');
    $mail->addAddress('rahilsirkissoon@gmail.com'); // Replace with recipient

    // Attach PDF
    $mail->addAttachment($pdfFilePath);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Company Registration Submission';
    $mail->AddEmbeddedImage(__DIR__ . '/../../assets/images/logo.jpg', 'logoimg'); // local path, cid 'logoimg'


// ids
// ID Front
$idFrontPath = _DIR_ . '/' . $s['id_front'];
if (file_exists($idFrontPath)) {
    $mail->addAttachment($idFrontPath, $s['forenames'] . '_ID_Front' . pathinfo($idFrontPath, PATHINFO_EXTENSION));
}

// ID Back
$idBackPath = _DIR_ . '/' . $s['id_back'];
if (file_exists($idBackPath)) {
    $mail->addAttachment($idBackPath, $s['forenames'] . '_ID_Back' . pathinfo($idBackPath, PATHINFO_EXTENSION));
}



    // Email body with embedded logo
    $mail->Body = '
    <html>
    <body>
        <div style="text-align:center;">
            <img src="cid:logoimg" height="60" alt="Tundra Logo">
            <h2>New Company Registration Submission</h2>
            <p>A new company has submitted their registration. Please see attached PDF for details.</p>
        </div>
    </body>
    </html>';

    $mail->send();
    $email_status = "Email sent successfully!";
} catch (Exception $e) {
    $email_status = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Registration Status</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; }
        header { background-color: #fff; padding: 20px 0; box-shadow: 0 2px 6px rgba(0,0,0,0.05); text-align: center; }
        header img { height: 60px; vertical-align: middle; }
        header h1 { display: inline-block; margin: 0; margin-left: 15px; color: #28a745; font-size: 28px; vertical-align: middle; }
        .container { width: 50%; margin: 80px auto; background: white; padding: 40px 30px; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0,0,0,0.1); text-align: center; }
        .container h1 { color: #28a745; font-size: 32px; margin-bottom: 20px; }
        .container p { font-size: 16px; color: #555; line-height: 1.6; margin-bottom: 15px; }
        .btn { display: inline-block; margin-top: 25px; background-color: #28a745; color: white; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: background-color 0.3s ease; }
        .btn:hover { background-color: #218838; }
    </style>
</head>
<body>

<header>
    <img src="../../assets/images/logo.jpg" alt="Tundra Logo">
    <h1>Tundra Tax & Accounting</h1>
</header>

<div class="container">
    <h1>✅ Registration in Process</h1>
    <p>Thank you for submitting your company registration details.</p>
    <p>Our team is currently reviewing your application. We will get back to you soon with further updates.</p>
    <p><?php echo $email_status; ?></p>
    <a href="../../index.php" class="btn">Return to Home</a>
</div>

</body>
</html>
