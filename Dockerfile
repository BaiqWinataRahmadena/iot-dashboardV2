# Gunakan image PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependency sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev

# Bersihkan cache apt untuk mengurangi ukuran image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang umum dipakai Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Aktifkan mod_rewrite Apache (Wajib untuk URL cantik Laravel)
RUN a2enmod rewrite

# Ubah Document Root Apache ke folder 'public' Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Install Composer (Manajer paket PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set direktori kerja
WORKDIR /var/www/html

# Copy semua file project ke dalam container
COPY . .

# Install dependency project via Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# --- PERBAIKAN 1: Buat folder views secara manual untuk jaga-jaga ---
RUN mkdir -p resources/views storage/framework/views

# Ubah permission folder storage dan cache agar bisa ditulisi
RUN chown -R www-data:www-data storage bootstrap/cache resources/views
RUN chmod -R 775 storage bootstrap/cache resources/views

# Konfigurasi Port untuk Render
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# --- PERBAIKAN 2: Hapus 'php artisan view:cache' dari perintah start ---
# Perintah yang dijalankan saat container start
CMD php artisan config:cache && \
    php artisan route:cache && \
    apache2-foreground