# ChezMoi — Plateforme immobilière

Application web **Full Stack** (Laravel 12 / PHP 8.3 / MySQL / Blade / Tailwind CSS)
permettant aux propriétaires et agences de publier des annonces immobilières et aux
acheteurs/locataires de rechercher des biens.

## ✨ Fonctionnalités (conformes au cahier des charges)

| Domaine | Détail |
|---|---|
| **Utilisateurs** | Inscription (nom, email, mot de passe + confirmation), connexion, réinitialisation du mot de passe par email |
| **Rôles** | Administrateur, Vendeur/Propriétaire, Acheteur/Locataire (middleware `role`) |
| **Annonces** | CRUD complet, upload de photos, passage en validation, compteur de vues |
| **Recherche avancée** | Mot-clé, ville, type de bien, prix min/max, surface, chambres, vente/location + **pagination** |
| **Favoris** | Ajout/retrait, liste des favoris |
| **Messagerie** | Conversations, historique, contact du propriétaire |
| **Notifications** | Nouveau message, validation/refus d'annonce, nouvelle publication |
| **Tableaux de bord** | Vendeur (annonces, vues, messages) et Administrateur (statistiques générales) |
| **Sécurité** | Hachage des mots de passe (bcrypt), protection **CSRF**, limitation des tentatives de connexion, API protégée par **Laravel Sanctum** |
| **API REST** | `/api/register`, `/api/login`, `/api/me`, `/api/logout`, `/api/annonces` (protégée par Sanctum) |

## 🧱 Architecture technique

- **Frontend** : Blade, HTML5, CSS3, JavaScript, Tailwind CSS, Vite, Axios
- **Backend** : Laravel 12, PHP 8.3, Laravel Sanctum, Eloquent ORM
- **Base de données** : MySQL
- **Déploiement** : Docker, Docker Compose, GitHub Actions, Render

## 🚀 Installation locale

Prérequis : PHP ≥ 8.2, Composer, Node.js, MySQL.

```bash
# 1. Dépendances
composer install
npm install

# 2. Environnement
cp .env.example .env
php artisan key:generate

# 3. Base de données (créez une base « chezmoi » puis :)
php artisan migrate --seed

# 4. Lien de stockage pour les photos
php artisan storage:link

# 5. Assets front
npm run build        # ou : npm run dev

# 6. Lancer le serveur
php artisan serve
```

Accès : **http://127.0.0.1:8000**

### Comptes de démonstration

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | test@gmail.com | test |
| Propriétaire | test1@gmail.com | test1 |
| Acheteur | test2@gmail.com | test2 |

## 🧪 Tests

```bash
php artisan test
```

La suite couvre l'authentification, les rôles, les annonces, la recherche,
les favoris, la messagerie, les notifications, la validation administrateur et l'API.

## 🐳 Déploiement Docker

```bash
docker compose up --build
```

Application : **http://localhost:8000** — MySQL exposé sur le port 3307.

## 📚 Documentation

- [MCD](docs/MCD.md) — Modèle Conceptuel de Données
- [MLD](docs/MLD.md) — Modèle Logique de Données


## 🔌 API (extraits)

```bash
# Connexion -> renvoie un token Sanctum
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test1@gmail.com","password":"test1"}'

# Recherche d'annonces (public, paginé)
curl "http://127.0.0.1:8000/api/annonces?ville=Marrakech&prix_max=1000000"

# Profil (protégé)
curl http://127.0.0.1:8000/api/me -H "Authorization: Bearer <token>"
```

## 📄 Licence

Projet pédagogique — MIT.
