#!/bin/bash

echo "🚀 Starting VacciCare Production Boot Sequence..."

# Force routing schemes to HTTPS in production to avoid SSL mixed-content blocks
# Since Render terminates SSL, this ensures Laravel generates secure URLs.
export FORCE_HTTPS=true

# Optimize Configuration & Route caches
echo "⚙️  Caching configurations and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "✅ Boot sequence completed! Launching web server..."
