# TaskFlow

TaskFlow est une application Laravel de gestion personnelle des tâches. Elle propose l’inscription et la connexion, un tableau de bord avec indicateurs de productivité, ainsi que la création, la consultation, la modification, la suppression et le filtrage des tâches. Chaque compte n’accède qu’à ses propres données.

## Prérequis

- PHP 8.3 ou supérieur, avec les extensions `pdo_sqlite` et `sqlite3`
- Composer
- Node.js et npm

## Installation

Depuis la racine du projet :

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
if (-not (Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite }
php artisan migrate
npm install
npm run build
```

Le fichier `.env` doit contenir :

```dotenv
APP_NAME=TaskFlow
APP_LOCALE=fr
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

La configuration Laravel ne déclare que SQLite comme connexion. Les migrations ajoutent les nouvelles tables sans effacer les données déjà présentes. Ne lancez pas `migrate:fresh` sur une base contenant des données à conserver.

## Lancement

```powershell
php artisan serve
```

Ouvrez ensuite <http://localhost:8000>, créez un compte et commencez à ajouter des tâches.

Pour le développement avec rechargement des ressources front-end, lancez `npm run dev` dans un second terminal.

## Fonctionnalités

- Authentification avec mots de passe hachés, sessions renouvelées à la connexion et limitation des tentatives.
- Tâches avec titre, description facultative, priorité, statut et échéance.
- Tableau de bord présentant le total, les volumes par statut, les retards et le taux d’achèvement.
- Recherche par titre et filtres combinables par statut, priorité, date d’échéance ou tâches en retard.
- Autorisation par `TaskPolicy`, relation utilisateur/tâches et validation serveur.

## Tests

```powershell
php artisan test
```

La configuration de test utilise une base SQLite en mémoire et ne modifie pas le fichier `database/database.sqlite`.
