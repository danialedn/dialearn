@echo off
echo Creating deployment package for DiaLearn...

REM Create a temporary directory for packaging
if exist "deployment-temp" rmdir /s /q "deployment-temp"
mkdir "deployment-temp"

echo Copying project files...

REM Copy all necessary files except excluded ones
xcopy /E /I /Q . "deployment-temp" /EXCLUDE:deployment-exclude.txt

echo Cleaning up unnecessary files...

REM Remove development files from temp directory
if exist "deployment-temp\node_modules" rmdir /s /q "deployment-temp\node_modules"
if exist "deployment-temp\.git" rmdir /s /q "deployment-temp\.git"
if exist "deployment-temp\tests" rmdir /s /q "deployment-temp\tests"
if exist "deployment-temp\storage\logs\*" del /q "deployment-temp\storage\logs\*"

REM Copy production env file
copy ".env.production" "deployment-temp\.env"

echo Creating ZIP file...

REM Create ZIP file (requires PowerShell)
powershell -command "Compress-Archive -Path 'deployment-temp\*' -DestinationPath 'dialearn-production.zip' -Force"

echo Cleaning up...
rmdir /s /q "deployment-temp"

echo.
echo ========================================
echo Deployment package created successfully!
echo File: dialearn-production.zip
echo ========================================
echo.
echo Next steps:
echo 1. Upload dialearn-production.zip to your server
echo 2. Extract the files
echo 3. Run: composer install --optimize-autoloader --no-dev
echo 4. Run: php artisan key:generate
echo 5. Run: php artisan migrate
echo 6. Set proper file permissions using set-permissions.sh
echo.
pause