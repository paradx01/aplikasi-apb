# Use the official PHP image with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite (required for Laravel routing)
RUN a2enmod rewrite

# Configure Apache DocumentRoot to Laravel's /public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|</VirtualHost>|<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n</VirtualHost>|g' /etc/apache2/sites-available/000-default.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files and Laravel bootstrap files needed for composer scripts
COPY composer.json composer.lock ./
COPY artisan ./
COPY bootstrap ./bootstrap

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files & set permissions
COPY --chown=www-data:www-data . .

# Run composer scripts after all files are copied
RUN composer run-script post-autoload-dump || true

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# Set storage & cache permissions
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Start: create storage symlink + clear cache + start Apache
CMD php artisan storage:link --force && \
    php artisan config:clear && \
    php artisan cache:clear && \
    apache2-foreground