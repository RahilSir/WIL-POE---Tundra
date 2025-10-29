# Error Handling System Documentation

## Overview

This project now includes a comprehensive error handling system that provides centralized error management, validation, and user-friendly error display.

## Components

### 1. ErrorHandler Class (`includes/ErrorHandler.php`)

Central error handling class that manages:
- PHP errors and exceptions
- Database errors
- Validation errors
- File upload errors
- Error logging
- User-friendly error display

**Key Methods:**
- `init()` - Initialize error handling
- `handleError()` - Handle PHP errors
- `handleException()` - Handle uncaught exceptions
- `handleDatabaseError()` - Handle database-specific errors
- `handleValidationError()` - Handle validation errors
- `handleFileUploadError()` - Handle file upload errors

### 2. Validator Class (`includes/Validator.php`)

Comprehensive validation system with methods for:
- Required field validation
- Email validation
- Phone number validation (South African format)
- ID number validation (South African)
- Length validation (min/max)
- File upload validation
- Postal code validation
- Numeric validation

**Key Methods:**
- `validateForm()` - Validate entire form with rules
- `required()` - Check if field is required
- `email()` - Validate email format
- `phone()` - Validate phone number
- `idNumber()` - Validate ID number
- `fileUpload()` - Validate file uploads

### 3. Error Display Helper (`includes/error_display.php`)

Functions for displaying errors to users:
- `displayValidationErrors()` - Show validation errors
- `displaySuccessMessage()` - Show success messages
- `displayErrorMessage()` - Show error messages
- `displayInfoMessage()` - Show info messages

### 4. Configuration (`includes/config.php`)

Centralized configuration for:
- Debug mode settings
- Database credentials
- File upload limits
- Security settings
- Application settings

## Usage Examples

### Basic Form Validation

```php
<?php
require 'includes/ErrorHandler.php';
require 'includes/Validator.php';
require 'includes/error_display.php';

// Validation rules
$rules = [
    'name' => ['required', 'minLength' => 2],
    'email' => ['required', 'email'],
    'phone' => ['required', 'phone']
];

// Validate form data
if (Validator::validateForm($_POST, $rules)) {
    // Process valid data
    echo "Form is valid!";
} else {
    // Display validation errors
    displayValidationErrors();
}
?>
```

### Database Error Handling

```php
<?php
require 'includes/db.php';

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Database prepare failed");
    }
    
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Process results...
    
} catch (Exception $e) {
    ErrorHandler::handleException($e);
}
?>
```

### File Upload Validation

```php
<?php
$allowedTypes = ['image/jpeg', 'image/png'];
$maxSize = 5242880; // 5MB

if (Validator::fileUpload($_FILES['upload'], $allowedTypes, $maxSize)) {
    // Process valid file
    echo "File upload successful!";
} else {
    displayValidationErrors();
}
?>
```

## Error Logging

All errors are logged to `logs/error.log` with detailed information including:
- Error type and severity
- Error message
- File and line number
- Timestamp
- URL where error occurred
- Stack trace (for exceptions)

## Configuration

Edit `includes/config.php` to customize:
- Debug mode (show detailed errors vs user-friendly messages)
- Database credentials
- File upload limits
- Log file location
- Security settings

## Testing

Run the test file to verify error handling:
```
http://your-domain/tests/test_error_handling.php
```

## Security Features

- No sensitive information exposed in production
- Detailed error logging for debugging
- Input validation and sanitization
- SQL injection prevention
- XSS protection through proper escaping

## Production Deployment

Before deploying to production:
1. Set `DEBUG_MODE` to `false` in `config.php`
2. Ensure `logs/` directory is writable
3. Review and update database credentials
4. Test error handling thoroughly
5. Monitor error logs regularly

## Benefits

- **Centralized Error Management**: All errors handled in one place
- **Consistent User Experience**: Uniform error display across the application
- **Better Debugging**: Detailed error logging for developers
- **Security**: No sensitive information exposed to users
- **Maintainability**: Easy to update error handling logic
- **Validation**: Comprehensive input validation system
