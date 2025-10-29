<?php
require_once 'config.php';

class ErrorHandler {
    private static $logFile = LOG_FILE;
    private static $debugMode = DEBUG_MODE;
    
    /**
     * Initialize error handling
     */
    public static function init() {
        // Set error reporting
        error_reporting(E_ALL);
        
        // Set custom error handler
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Handle PHP errors
     */
    public static function handleError($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $error = [
            'type' => 'Error',
            'severity' => self::getSeverityName($severity),
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ];
        
        self::logError($error);
        self::displayError($error);
        
        return true;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public static function handleException($exception) {
        $error = [
            'type' => 'Exception',
            'severity' => 'Critical',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ];
        
        self::logError($error);
        self::displayError($error);
    }
    
    /**
     * Handle database errors
     */
    public static function handleDatabaseError($connection, $operation = 'Database Operation') {
        $error = [
            'type' => 'Database Error',
            'severity' => 'Critical',
            'message' => $connection->error,
            'errno' => $connection->errno,
            'operation' => $operation,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ];
        
        self::logError($error);
        self::displayError($error);
    }
    
    /**
     * Handle validation errors
     */
    public static function handleValidationError($field, $message, $value = null) {
        $error = [
            'type' => 'Validation Error',
            'severity' => 'Warning',
            'field' => $field,
            'message' => $message,
            'value' => $value,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ];
        
        self::logError($error);
        return $error;
    }
    
    /**
     * Handle file upload errors
     */
    public static function handleFileUploadError($file, $errorCode) {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
            UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
            UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        $error = [
            'type' => 'File Upload Error',
            'severity' => 'Warning',
            'file' => $file,
            'message' => $messages[$errorCode] ?? 'Unknown upload error',
            'error_code' => $errorCode,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
        ];
        
        self::logError($error);
        return $error;
    }
    
    /**
     * Log error to file
     */
    private static function logError($error) {
        $logEntry = json_encode($error) . "\n";
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Display error to user
     */
    private static function displayError($error) {
        if (self::$debugMode) {
            echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 15px; margin: 10px; border-radius: 4px;'>";
            echo "<h3 style='color: #d32f2f; margin: 0 0 10px 0;'>" . $error['type'] . "</h3>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($error['message']) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($error['file']) . "</p>";
            echo "<p><strong>Line:</strong> " . $error['line'] . "</p>";
            echo "<p><strong>Time:</strong> " . $error['timestamp'] . "</p>";
            if (isset($error['trace'])) {
                echo "<p><strong>Stack Trace:</strong></p><pre>" . htmlspecialchars($error['trace']) . "</pre>";
            }
            echo "</div>";
        } else {
            echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 15px; margin: 10px; border-radius: 4px;'>";
            echo "<h3 style='color: #d32f2f; margin: 0 0 10px 0;'>An error occurred</h3>";
            echo "<p>Please try again later. If the problem persists, contact support.</p>";
            echo "</div>";
        }
    }
    
    /**
     * Get severity name
     */
    private static function getSeverityName($severity) {
        $severities = [
            E_ERROR => 'Fatal Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated'
        ];
        
        return $severities[$severity] ?? 'Unknown';
    }
    
    /**
     * Set debug mode
     */
    public static function setDebugMode($mode) {
        self::$debugMode = $mode;
    }
}
?>
