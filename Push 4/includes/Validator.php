<?php
require_once 'config.php';

class Validator {
    private static $errors = [];
    
    /**
     * Validate required fields
     */
    public static function required($value, $fieldName) {
        if (empty(trim($value))) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Field is required");
            return false;
        }
        return true;
    }
    
    /**
     * Validate email
     */
    public static function email($value, $fieldName) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Invalid email format", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate phone number (South African format)
     */
    public static function phone($value, $fieldName) {
        $pattern = '/^(\+27|0)[0-9]{9}$/';
        if (!preg_match($pattern, $value)) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Invalid phone number format", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate ID number (South African)
     */
    public static function idNumber($value, $fieldName) {
        $pattern = '/^[0-9]{13}$/';
        if (!preg_match($pattern, $value)) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Invalid ID number format", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate minimum length
     */
    public static function minLength($value, $minLength, $fieldName) {
        if (strlen(trim($value)) < $minLength) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Must be at least {$minLength} characters", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate maximum length
     */
    public static function maxLength($value, $maxLength, $fieldName) {
        if (strlen(trim($value)) > $maxLength) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Must not exceed {$maxLength} characters", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate file upload
     */
    public static function fileUpload($file, $allowedTypes = [], $maxSize = 5242880) { // 5MB default
        if ($file['error'] !== UPLOAD_ERR_OK) {
            self::$errors[] = ErrorHandler::handleFileUploadError($file['name'], $file['error']);
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            self::$errors[] = ErrorHandler::handleValidationError('file', "File too large. Maximum size: " . ($maxSize / 1024 / 1024) . "MB");
            return false;
        }
        
        if (!empty($allowedTypes)) {
            $fileType = mime_content_type($file['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                self::$errors[] = ErrorHandler::handleValidationError('file', "Invalid file type. Allowed: " . implode(', ', $allowedTypes));
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate postal code (South African)
     */
    public static function postalCode($value, $fieldName) {
        $pattern = '/^[0-9]{4}$/';
        if (!preg_match($pattern, $value)) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Invalid postal code format", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate numeric value
     */
    public static function numeric($value, $fieldName) {
        if (!is_numeric($value)) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Must be a valid number", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Validate positive number
     */
    public static function positiveNumber($value, $fieldName) {
        if (!is_numeric($value) || $value <= 0) {
            self::$errors[] = ErrorHandler::handleValidationError($fieldName, "Must be a positive number", $value);
            return false;
        }
        return true;
    }
    
    /**
     * Get all validation errors
     */
    public static function getErrors() {
        return self::$errors;
    }
    
    /**
     * Check if there are validation errors
     */
    public static function hasErrors() {
        return !empty(self::$errors);
    }
    
    /**
     * Clear validation errors
     */
    public static function clearErrors() {
        self::$errors = [];
    }
    
    /**
     * Validate all form data
     */
    public static function validateForm($data, $rules) {
        self::clearErrors();
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule => $params) {
                if ($rule === 'required') {
                    self::required($value, $field);
                } elseif ($rule === 'email') {
                    self::email($value, $field);
                } elseif ($rule === 'phone') {
                    self::phone($value, $field);
                } elseif ($rule === 'idNumber') {
                    self::idNumber($value, $field);
                } elseif ($rule === 'minLength') {
                    self::minLength($value, $params, $field);
                } elseif ($rule === 'maxLength') {
                    self::maxLength($value, $params, $field);
                } elseif ($rule === 'postalCode') {
                    self::postalCode($value, $field);
                } elseif ($rule === 'numeric') {
                    self::numeric($value, $field);
                } elseif ($rule === 'positiveNumber') {
                    self::positiveNumber($value, $field);
                }
            }
        }
        
        return !self::hasErrors();
    }
}
?>
