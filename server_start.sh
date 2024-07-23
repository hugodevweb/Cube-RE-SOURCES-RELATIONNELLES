#!/bin/bash
apache2-foreground
# Exécuter la commande de migration de la base de données
php bin/console app:migrate-database

# Démarrer Apache

