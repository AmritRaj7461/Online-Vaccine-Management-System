# Stage 1: Build front-end assets using Node.js
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Deploy PHP Runtime with FPM & Nginx
FROM php:8.3-fpm-alpine

# Install system utilities & Nginx + Supervisor
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip bcmath intl opcache mbstring

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy all project source code
COPY . .

# Copy compiled production assets from Stage 1 builder
COPY --from=assets-builder /app/public/build ./public/build

# Install production PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configure Nginx & Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set up storage and caches permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Set up entrypoint boot script
COPY scripts/run.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
