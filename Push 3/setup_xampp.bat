@echo off
echo ========================================
echo Tundra Tax & Accounting - XAMPP Setup
echo ========================================
echo.

echo Step 1: Starting XAMPP services...
echo Please make sure XAMPP Control Panel is open and start Apache and MySQL services.
echo Press any key when services are running...
pause >nul

echo.
echo Step 2: Testing setup...
echo Opening test page in browser...
start http://localhost/tundra/test_xampp.php

echo.
echo Step 3: Database setup...
echo Do you want to run database setup? (y/n)
set /p choice=
if /i "%choice%"=="y" (
    echo Opening database setup...
    start http://localhost/tundra/setup_database.php
)

echo.
echo Step 4: Testing error handling...
echo Do you want to test error handling? (y/n)
set /p choice=
if /i "%choice%"=="y" (
    echo Opening error handling test...
    start http://localhost/tundra/tests/test_error_handling.php
)

echo.
echo Setup complete!
echo You can now access the application at: http://localhost/tundra/
echo.
pause
