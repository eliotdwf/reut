# ==== Build frontend assets ====
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json .
COPY package-lock.json .
RUN npm ci
# build nodejs app
COPY . .
RUN npm run build

FROM composer:2.7 AS composer
WORKDIR /app
COPY . .
RUN composer install --no-interaction --no-progress --no-dev --no-suggest --optimize-autoloader --ignore-platform-reqs


FROM php:8.3.4-alpine AS app
LABEL authors="simde@assos.utc.fr"
LABEL maintainer="simde@assos.utc.fr"

WORKDIR /app
RUN apk add --no-cache icu-dev libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pcntl pdo_mysql zip intl

RUN apk add --no-cache supervisor
#install supervisor
COPY ./.docker/supervisor.conf /etc/supervisord.conf

COPY --from=frontend /app/public/build /app/public/build
COPY --from=composer /app/ /app/
COPY --from=composer /app/vendor/ /app/vendor/

# Healthcheck script
COPY ./.docker/healthcheck.sh /healthcheck.sh
RUN chmod +x /healthcheck.sh

# Expose healthcheck
HEALTHCHECK CMD /healthcheck.sh

RUN php artisan octane:install -n

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

