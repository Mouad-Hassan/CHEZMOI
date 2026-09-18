#!/bin/sh
set -e

# Cache de configuration (variables d'environnement injectées par l'hôte)
php artisan config:clear
php artisan route:clear

# Lien de stockage pour les photos d'annonces
php artisan storage:link || true

# Migrations + données de démonstration
php artisan migrate --force
php artisan db:seed --force || true

# Optimisations de production
php artisan config:cache
php artisan route:cache

exec apache2-foreground
