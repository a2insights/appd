# Stage 1: Build frontend assets
FROM node:18 as frontend

WORKDIR /app

COPY package.json package-lock.json* vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm ci && npm run build

# Stage 2: Build application
FROM unit:1.32.1-php8.2

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pcntl opcache pdo pdo_mysql intl zip gd exif ftp bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure PHP
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit_buffer_size=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/custom.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Create necessary directories
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# Set permissions for Nginx Unit user
RUN chown -R unit:unit /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy application files
COPY . .

# Copy built frontend assets from the frontend stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Ensure permissions are correct after copy
RUN chown -R unit:unit storage bootstrap/cache public/build \
    && chmod -R 775 storage bootstrap/cache public/build

# Install dependencies
# We use --no-scripts first to avoid the error, then dump-autoload to generate the optimized autoloader
RUN composer install --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize

# Copy Nginx Unit configuration
COPY unit.json /docker-entrypoint.d/unit.json

EXPOSE 8000

CMD ["unitd", "--no-daemon"]
