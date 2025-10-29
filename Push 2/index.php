<?php
// homepage.php
$servername = "localhost"; // correct for XAMPP
$username = "root";         // default XAMPP MySQL user
$password = "";             // default XAMPP MySQL password is empty (only fill if you set one)
$dbname = "tundra";         // your database name

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tundra Tax & Accounting</title>
  <link rel="stylesheet" href="assets/css/style.css">
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



.hero {
  background: url("assets/images/office.jpg") no-repeat center center;
  background-size: cover;   /* makes it fill the container */
  color: white;             /* optional: text color for contrast */
  padding: 100px 20px;      /* space inside the section */
}

.hero .container {
  background: rgba(0, 0, 0, 0.5); /* optional: dark overlay so text is readable */
  padding: 40px;
  border-radius: 8px;            /* optional styling */
}


footer {
  background-color: #333;   /* dark grey */
  color: #fff;              /* white text for contrast */
  text-align: center;       /* center the text */
  padding: 20px 0;          /* spacing inside */
  margin-top: 40px;         /* space above footer */
  font-size: 14px;          /* smaller text */

}




/* Dropdown container */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown button style (Services link) */


/* Dropdown content hidden by default */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #333;   /* dark dropdown background */
  min-width: 160px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside dropdown */
.dropdown-content a {
  color: white;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}



/* Show dropdown on hover */
.dropdown:hover .dropdown-content {
  display: block;
}




  </style>
</head>
<body>

  <!-- Header -->
  <header class="header">
  <div class="container">
    <div class="brand">
      <img src="assets/images/logo.jpg" alt="Tundra Logo">
      <h1 class="logo">Tundra Tax & Accounting</h1>
    </div>
    <nav>
      <a href="index.php">Home</a>
      
      <!-- Dropdown Menu -->
      <div class="dropdown">
        <a href="pages/public/services.html" class="dropbtn">Services ▾</a>
        <div class="dropdown-content">
          <a href="pages/public/services.html">Services</a>
          <a href="pages/public/bookkeeping.html">Bookkeeping</a>
          <a href="pages/public/tax-services.html">Tax Services</a>
        </div>
      </div>
      
      <a href="pages/public/contact.html">Contact</a>
      <a href="pages/public/about.html">About Us</a>
      <a href="pages/auth/registrationPage.php">Register</a>
      <a href="pages/auth/login.php">Login</a>
    </nav>
  </div>
</header>






  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h2>Your Trusted Tax & Accounting Partner</h2>
      <p>100% Black-owned South African firm of accountants, tax consultants, auditors, and business advisors.</p>
      <a href="pages/public/contact.html" class="btn">Get in Touch</a>
    </div>
  </section>

  <!-- About Us -->
  <section id="about" class="about container">
    <h2>About Us</h2>
    <p>Founded in 2009, Tundra Tax & Accounting has a broad client base — from private individuals to large companies, professional partnerships, charities, and nonprofits across South Africa. We are members of:</p>
    <ul>
      <li>IRBA: Independent Regulatory Board for Auditors</li>
      <li>SAICA: The South African Institute of Chartered Accountants</li>
    </ul>
    <p>Our goal is to minimise business disruption, maintain stakeholder confidence, and navigate economic unpredictability with professional expertise.</p>
    <div class="stats">
      <div><strong>20+</strong><br>Years of Experience</div>
      <div><strong>10</strong><br>Team Members</div>
    </div>
  </section>

  <!-- Services -->
  <section id="services" class="services">
    <div class="container">
      <h2>What We Offer</h2>
      <p>We provide cost-effective, high-value solutions to meet all your accounting needs, offering timely, individual advice to help you improve your business.</p>
      <div class="service-list">
        <div class="service-card">
          <h3>Accounting</h3>
          <p>Comprehensive accounting services tailored to your business.</p>
        </div>
        <div class="service-card">
          <h3>Bookkeeping</h3>
          <p>From small businesses to large enterprises.</p>
        </div>
        <div class="service-card">
          <h3>Tax</h3>
          <p>Expert taxation knowledge and year-round support.</p>
        </div>
        <div class="service-card">
          <h3>Payroll</h3>
          <p>Free up your time with our payroll management solutions.</p>
        </div>
        <div class="service-card">
          <h3>Audit</h3>
          <p>Financial reporting and assurance services for all entities.</p>
        </div>
        <div class="service-card">
          <h3>SARS Registration</h3>
          <p>Fast and reliable SARS registration services.</p>
        </div>
        <div class="service-card">
          <h3>Corporate Legal</h3>
          <p>Guidance on the best legal structures for your business.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section id="team" class="team container">
    <h2>Management Advisory Team</h2>
    <p>Our experienced tax specialists take a practical approach to managing tax, delivering strong financial outcomes.</p>
    <div class="skills">
      <div>Tax <span>86%</span></div>
      <div class="bar"><span style="width: 86%"></span></div>

      <div>Accounting <span>80%</span></div>
      <div class="bar"><span style="width: 80%"></span></div>

      <div>Audit <span>88%</span></div>
      <div class="bar"><span style="width: 88%"></span></div>

      <div>Payroll & Bookkeeping <span>85%</span></div>
      <div class="bar"><span style="width: 85%"></span></div>
    </div>
  </section>

  <!-- Contact -->
  <section id="contact" class="contact container">
    <h2>Contact Us</h2>
     <a href="pages/public/contact.html" class="btn">Get in Touch</a>
    <p>Email: <a href="info@tundratax.co.za">info@tundratax.co.za</a> |  Phone: +27 (011) 794-5856</p>
    


  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Tundra Tax & Accounting. All Rights Reserved.</p>
  </footer>

</body>
</html>
