FROM php:8.3-cli

# Instalar extensiones necesarias para Laravel y SQLite
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Asegurar que exista el archivo SQLite y permisos de escritura
RUN mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 database storage bootstrap/cache

EXPOSE 10000

# Al arrancar: crear enlace de storage, correr migraciones pendientes y encender servidor
CMD php artisan storage:link --force && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000