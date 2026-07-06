# -----------------------------------------------------
# Base Image
# -----------------------------------------------------
FROM php:7.3-fpm

# -----------------------------------------------------
# Install System Packages
# -----------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------
# Configure GD Extension
# -----------------------------------------------------
RUN docker-php-ext-configure gd \
    --with-freetype-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/

# -----------------------------------------------------
# Install PHP Extensions
# -----------------------------------------------------
RUN docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo_mysql \
    mbstring \
    zip \
    intl \
    gd \
    opcache

# -----------------------------------------------------
# Install Composer
# -----------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer --version

# -----------------------------------------------------
# Set Working Directory
# -----------------------------------------------------
WORKDIR /var/www/html

# -----------------------------------------------------
# Default Command
# -----------------------------------------------------
CMD ["php-fpm"]