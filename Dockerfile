FROM richarvey/nginx-php-fpm:latest

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Install Node.js (required for Vite asset compilation), PHP dependencies, and build assets
RUN apk add --no-cache nodejs npm \
    && composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build

# Set the Nginx web root folder to Laravel's public directory
ENV WEBROOT /var/www/html/public
ENV APP_ENV production
ENV APP_DEBUG false
ENV RUN_SCRIPTS 1

# Adjust folder ownership and permissions for Laravel's writeable directories
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for the Nginx server
EXPOSE 80
