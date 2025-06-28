#!/bin/bash

# Simple Laravel Forge Deployment Script - Handles Git Divergent Branches
cd $FORGE_SITE_PATH

# Configure Git to handle divergent branches
git config pull.rebase false

# Force sync with remote repository
echo "Syncing with remote repository..."
git fetch origin main
git reset --hard origin/main
git clean -fd

# Update dependencies and optimize
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan optimize

echo "Deployment completed!"
