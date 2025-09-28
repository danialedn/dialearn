#!/bin/bash

# Laravel Production Permissions Setup Script
# Run this script on your Linux server after uploading the project

echo "Setting up Laravel permissions for production..."

# Set ownership to web server user (usually www-data or nginx)
# Change 'www-data' to your web server user if different
sudo chown -R www-data:www-data /path/to/your/project

# Set directory permissions
sudo find /path/to/your/project -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /path/to/your/project -type f -exec chmod 644 {} \;

# Set storage and cache directories to be writable
sudo chmod -R 775 /path/to/your/project/storage
sudo chmod -R 775 /path/to/your/project/bootstrap/cache

# Set specific permissions for storage subdirectories
sudo chmod -R 775 /path/to/your/project/storage/app
sudo chmod -R 775 /path/to/your/project/storage/framework
sudo chmod -R 775 /path/to/your/project/storage/logs

# Make artisan executable
sudo chmod +x /path/to/your/project/artisan

echo "Permissions set successfully!"
echo "Remember to:"
echo "1. Replace '/path/to/your/project' with your actual project path"
echo "2. Replace 'www-data' with your web server user if different"
echo "3. Update your .env file with production settings"
echo "4. Run 'php artisan migrate' if you have database migrations"
echo "5. Run 'php artisan key:generate' if needed"