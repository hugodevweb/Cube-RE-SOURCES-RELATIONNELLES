# Utilisation de l'image PHP officielle de Docker Hub avec PHP 8.2.1 et Apache
FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    nano \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier le fichier composer.json et composer.lock (s'ils existent déjà) dans le conteneur
COPY composer.json composer.lock ./

# Définir l'environnement pour autoriser l'exécution de Composer en tant que superutilisateur
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer les dépendances du projet Symfony
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-progress --no-interaction

# Copier les fichiers du projet Symfony dans le conteneur
COPY . .

# Définir les permissions correctes sur les répertoires de cache et de logs
RUN chown -R www-data:www-data /var

# Ajouter la configuration Apache pour définir le répertoire public comme racine
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Exposer le port 80 (Apache)
EXPOSE 80

# Commande par défaut pour démarrer Apache et exécuter Symfony
CMD ["apache2-foreground"]
