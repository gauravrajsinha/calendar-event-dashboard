#!/bin/bash

# HeritageConnect Calendar Deployment Script
# This script automates the deployment process for the calendar application

# Exit if any command fails
set -e

echo "Starting deployment process..."

# Step 1: Pull latest changes
echo "Pulling latest changes from repository..."
git pull origin main

# Step 2: Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

# Step 3: Install node dependencies and build assets
echo "Installing Node.js dependencies and building assets..."
npm install
npm run build

# Step 4: Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Step 5: Cache configurations
echo "Caching configurations for performance..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 6: Clear old sessions and cache if needed
echo "Clearing application cache..."
php artisan cache:clear

# Step 7: Optimize the application
echo "Optimizing the application..."
php artisan optimize

# Step 8: Restart queue workers if using queues
# echo "Restarting queue workers..."
# php artisan queue:restart

# Step 9: Set appropriate permissions
echo "Setting appropriate permissions..."
find storage bootstrap/cache -type d -exec chmod 775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;

echo "Deployment completed successfully!"
echo "Please check the application to verify it's working as expected."
