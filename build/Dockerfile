FROM php:7.3.10-cli-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
COPY ./composer.json ./composer.json
COPY ./composer.lock ./composer.lock
RUN composer install
COPY ./src ./src
RUN composer dump-autoload
RUN mkdir -p /assets/images
RUN ./vendor/bin/openapi --pattern "*.php" --output "./src/swagger.json" --format json ./src
EXPOSE 80

CMD php /vendor/bin/server Waryway\\ImageServerRest 0.0.0.0:80

