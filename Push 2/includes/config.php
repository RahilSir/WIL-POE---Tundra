<?php
// Configuration file for error handling and general settings

// Error handling configuration
define('DEBUG_MODE', true); // Set to false in production
define('LOG_ERRORS', true);
define('LOG_FILE', 'logs/error.log');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'tundra');

// File upload configuration
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_FILE_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);

// Session configuration
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Email configuration (if using PHPMailer)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('FROM_EMAIL', 'noreply@tundra.co.za');
define('FROM_NAME', 'Tundra Tax & Accounting');

// Application settings
define('APP_NAME', 'Tundra Tax & Accounting');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost'); // Update with your domain

// Security settings
define('PASSWORD_MIN_LENGTH', 6);
define('USERNAME_MIN_LENGTH', 3);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes in seconds

// Timezone
date_default_timezone_set('Africa/Johannesburg');
?>
