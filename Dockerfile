FROM php:8.2-fpm

# Встановлюємо додаткові залежності
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
	libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    zip \
    libzip-dev \
    unzip \
    git \
	npm \
    curl \
	nginx \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libpng-dev \
    netcat-openbsd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
	&& curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY . /var/www

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 80
EXPOSE 9000

CMD service nginx start && php-fpm