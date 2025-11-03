Tundra Tax & Accounting - Project Structure Documentation

Project Overview

This project is a comprehensive web-based company registration and management system for Tundra Tax & Accounting, a South African accounting firm. The system provides functionality for company registration, user authentication, document management, PDF generation capabilities, and comprehensive error handling.

Directory Structure

├── assets/                    # Static assets
│   ├── css/                  # Stylesheets
│   │   └── style.css
│   └── images/               # Image files
│       ├── logo.jpg
│       ├── office.jpg
│       ├── aboutUs.jpg
│       ├── bookeeping.jpg
│       ├── businessAdvisory.jpg
│       ├── certified.jpg
│       ├── cpt.jpg
│       ├── debt.jpg
│       ├── deceasedestate.jpg
│       ├── deed.jpg
│       ├── referencecheck.png
│       ├── sars.jpg
│       ├── services.jpg
│       ├── tax.jpg
│       ├── tracing.jpg
│       └── Trust.jpg
├── documents/                # PDF documents and reports
│   ├── registration_*.pdf
│   └── test.pdf
├── includes/                 # PHP includes and configuration
│   ├── config.php           # Centralized configuration
│   ├── db.php               # Database connection
│   ├── ErrorHandler.php     # Error handling system
│   ├── Validator.php        # Input validation system
│   └── error_display.php    # Error display helpers
├── logs/                     # Error and system logs
│   └── error.log
├── pages/                    # All page files organized by functionality
│   ├── auth/                # Authentication pages
│   │   ├── login.php
│   │   └── registrationPage.php
│   ├── company/             # Company management pages
│   │   ├── companyStatus.php
│   │   ├── edit_company.php
│   │   ├── edit_company_names.php
│   │   ├── edit_contact_info.php
│   │   ├── edit_director.php
│   │   ├── edit_shareholder.php
│   │   ├── registerCompany.php
│   │   ├── registercompany_step2.php
│   │   ├── registercompany_step3.php
│   │   ├── registercompany_step4.php
│   │   ├── registercompany_step5.php
│   │   └── review_information.php
│   └── public/              # Public-facing pages
│       ├── about.html
│       ├── bookkeeping.html
│       ├── contact.html
│       ├── registerCompany.html
│       ├── services.html
│       └── tax-services.html
├── tests/                    # Test files
│   ├── test_dompdf.php
│   ├── test_email_pdf.php
│   ├── test_error_handling.php
│   └── test.php
├── uploads/                  # User uploaded files
│   └── .gitkeep
├── vendor/                   # Composer dependencies
│   ├── composer.json
│   ├── composer.lock
│   └── ... (vendor packages)
├── .htaccess                 # Apache configuration
├── setup_database.php        # Database setup script
├── setup_xampp.bat          # XAMPP setup automation
├── test_xampp.php           # XAMPP configuration test
└── index.php                # Main entry point

Technical Architecture

Backend Technologies
- PHP 7.4+ for server-side logic
- MySQL database for data persistence
- Session management for user state
- File upload handling for document management
- Comprehensive error handling and logging system
- Input validation and sanitization

Frontend Technologies
- HTML5 for markup structure
- CSS3 for styling and responsive design
- JavaScript for client-side interactions
- Bootstrap-inspired responsive framework

Dependencies
- DomPDF for PDF generation and document creation
- PHPMailer for email functionality and notifications
- Composer for dependency management

Error Handling System
- Centralized error management with ErrorHandler class
- Comprehensive input validation with Validator class
- User-friendly error display system
- Detailed error logging for debugging
- Database error handling
- File upload error management

Key Features

User Authentication
- User registration and login system with validation
- Session-based authentication
- Secure password handling
- Input validation and error handling

Company Registration
- Multi-step company registration process
- Director and shareholder management
- Document upload and validation with proper error handling
- Address and contact information management
- File upload system with validation

Document Management
- PDF generation for registration documents
- File upload system for required documents with validation
- Document storage and retrieval
- Email integration for document delivery with attachments
- Proper file path management

Administrative Functions
- Company status tracking
- Information editing capabilities
- Review and approval workflows
- Data validation and error handling
- Comprehensive logging system

Installation and Setup

Prerequisites
- Web server (Apache/Nginx) or XAMPP
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer package manager

XAMPP Setup (Recommended)
1. Install XAMPP on Windows
2. Start Apache and MySQL services
3. Copy project to C:\xampp\htdocs\tundra\
4. Run setup_database.php to create database and tables
5. Run test_xampp.php to verify configuration
6. Set proper permissions on uploads directory

Manual Installation Steps
1. Clone or download the project files
2. Configure web server to point to project root directory
3. Install dependencies using Composer: composer install
4. Create MySQL database named 'tundra'
5. Run setup_database.php to create required tables
6. Update database configuration in includes/config.php
7. Set appropriate file permissions for uploads and logs directories
8. Configure email settings for PHPMailer in includes/config.php

Database Configuration
The system uses centralized configuration in includes/config.php:
- Database host, username, password, and name
- Error handling settings
- File upload limits and allowed types
- Email configuration
- Security settings

Error Handling and Logging

The project includes a comprehensive error handling system:
- ErrorHandler.php: Central error management class
- Validator.php: Input validation system
- error_display.php: User-friendly error display functions
- All errors are logged to logs/error.log
- Debug mode for development vs production
- Database error handling
- File upload error management
- Validation error display

File Organization Principles

Separation of Concerns
The project structure follows established web development best practices by separating different types of files into logical directories. This approach improves maintainability and scalability.

Security Considerations
- Database configuration files are isolated in the includes directory
- User uploads are contained in a dedicated uploads directory
- Sensitive files are protected from direct web access
- Error handling prevents information disclosure
- Input validation prevents security vulnerabilities

Asset Management
- All static assets are centralized in the assets directory
- CSS and JavaScript files are organized by type
- Images are categorized and easily accessible
- Proper path management throughout the system

Code Organization
- PHP files are grouped by functionality (auth, company, public)
- Test files are separated from production code
- Vendor dependencies are managed through Composer
- Error handling is centralized and consistent

Development Guidelines

File Naming Conventions
- PHP files use camelCase for multi-word names
- HTML files use kebab-case
- Image files use descriptive names with appropriate extensions
- CSS files follow standard naming conventions

Path Management
- All internal links use relative paths from the current file location
- Asset references are updated to reflect the new directory structure
- Database includes use proper relative paths
- File uploads use consistent path construction

Error Handling
- All database operations use try-catch blocks
- File operations include proper error checking
- User input is validated before processing
- Errors are logged for debugging
- User-friendly error messages are displayed

Testing

The project includes comprehensive test files:
- test_dompdf.php: Tests PDF generation capabilities
- test_email_pdf.php: Tests email functionality with PDF attachments
- test_error_handling.php: Tests the error handling system
- test_xampp.php: Tests XAMPP configuration
- test.php: General functionality testing

Setup and Configuration Files

- setup_database.php: Automated database setup
- setup_xampp.bat: Windows batch file for XAMPP setup
- test_xampp.php: XAMPP configuration verification
- .htaccess: Apache security and configuration
- includes/config.php: Centralized configuration management



