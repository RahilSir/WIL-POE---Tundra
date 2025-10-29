<?php
function displayValidationErrors() {
    $errors = Validator::getErrors();
    if (!empty($errors)) {
        echo "<div class='error-container' style='background: #ffebee; border: 1px solid #f44336; padding: 15px; margin: 10px; border-radius: 4px;'>";
        echo "<h3 style='color: #d32f2f; margin: 0 0 10px 0;'>Please correct the following errors:</h3>";
        echo "<ul style='margin: 0; padding-left: 20px;'>";
        foreach ($errors as $error) {
            echo "<li style='color: #d32f2f; margin-bottom: 5px;'>" . htmlspecialchars($error['message']) . "</li>";
        }
        echo "</ul></div>";
    }
}

function displaySuccessMessage($message) {
    echo "<div class='success-container' style='background: #e8f5e8; border: 1px solid #4caf50; padding: 15px; margin: 10px; border-radius: 4px;'>";
    echo "<p style='color: #2e7d32; margin: 0;'>" . htmlspecialchars($message) . "</p>";
    echo "</div>";
}

function displayErrorMessage($message) {
    echo "<div class='error-message' style='background: #ffebee; border: 1px solid #f44336; padding: 15px; margin: 10px; border-radius: 4px;'>";
    echo "<p style='color: #d32f2f; margin: 0;'>" . htmlspecialchars($message) . "</p>";
    echo "</div>";
}

function displayInfoMessage($message) {
    echo "<div class='info-message' style='background: #e3f2fd; border: 1px solid #2196f3; padding: 15px; margin: 10px; border-radius: 4px;'>";
    echo "<p style='color: #1976d2; margin: 0;'>" . htmlspecialchars($message) . "</p>";
    echo "</div>";
}
?>
