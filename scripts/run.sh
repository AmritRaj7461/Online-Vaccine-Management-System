#!/bin/bash

echo "🚀 Starting VacciCare Production Boot Sequence..."

# Force routing schemes to HTTPS in production to avoid SSL mixed-content blocks
export FORCE_HTTPS=true

# Optimize Configuration & Route caches
echo "⚙️  Caching configurations and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "✅ Boot sequence completed! Launching Supervisor process manager..."

# Start supervisor as PID 1
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
