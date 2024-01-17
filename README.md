# CNMH test

Notre projet vise à moderniser la gestion des dossiers patients au Centre National Mohammed VI des Handicapés (CNMH). En passant de la documentation papier à la digitalisation.

## Plan

- Installation de l'application pour la première fois
- Mise à ajour de l'application
- Démonstration de l'application 
- Exécution de test Browser

# Installation de l'application pour la première fois

```bash
  npm install
  composer install
```

```bash
cp .env.example .env
php artisan key:generate
```

```bash
php artisan migrate
php artisan db:seed
```

# Démonstration de l'application

- Email : admin@gmail.com
  - Password : admin
- Email : social@gmail.com
  - Password : social
- Email : medecin@gmail.com
  - Password : medecin

## Mise à ajour de l'application

```bash
npm run build
php artisan migrate:fresh
```

## Exécution de test Browser

Création de fichier d'environement pour exécuter l'application avec la configuration convenable avec le test browser avec dusk


```bash
cp .env .env.test

```

```conf
APP_KEY=
# laravel-debug générer des exception avec dusk
APP_DEBUG=false
APP_URL=http://127.0.0.1:8000/

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
# Création d'une base de données pour le test
DB_DATABASE=cnmh_test
DB_USERNAME=
DB_PASSWORD=
```

```bash
php artisan migrate --env=test
php artisan db:seed --env=test
```

```bash
php artisan serve --env=test
```

## Installation de l'application sur le serveur

[Installation de l'application sur le serveur](doc/installation-sur-serveur.md)

