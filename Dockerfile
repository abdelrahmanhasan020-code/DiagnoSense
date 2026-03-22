FROM php:8.2-cli

WORKDIR /app

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# clear cache
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 8080

CMD php artisan config:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080} & \
    php artisan queue:work --tries=3
