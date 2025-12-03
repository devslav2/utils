# Usa un'immagine PHP con Apache preconfigurato
FROM php:8.2-apache

# Installa le dipendenze di sistema necessarie (libpng, libjpeg, ecc.)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    libpq-dev \
    && apt-get clean

# Installare le estensioni PHP necessarie
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Abilita il modulo Apache mod_rewrite (necessario per Laravel)
RUN a2enmod rewrite

# Imposta la cartella di lavoro all'interno del container
WORKDIR /var/www/html

# Copia i file del progetto nel container (copia tutto il contenuto della tua app Laravel)
COPY . /var/www/html/

# Imposta i permessi per le cartelle di storage e cache di Laravel (importante per i permessi di scrittura)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Installa Composer (gestore di dipendenze PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installa le dipendenze di Laravel con Composer
RUN composer install --no-dev --optimize-autoloader

# Espone la porta 80 (per Apache)
EXPOSE 80

# Avvia Apache (Apache è configurato per ascoltare sulla porta 80)
CMD ["apache2-foreground"]