# Camagru

Une petite application web de retouche photo : prenez une photo avec votre webcam (ou importez-en une), superposez des overlays, puis partagez le résultat dans une galerie publique avec likes et commentaires.

Réalisée de zéro dans le cadre du cursus 42 : **PHP natif (sans framework)**, JavaScript vanilla, MySQL et Docker.

## Fonctionnalités

**Comptes**
- Inscription avec confirmation par email (lien unique)
- Connexion / déconnexion, accessible sur toutes les pages
- Réinitialisation du mot de passe par email (lien valable 15 minutes)
- Modification du profil : pseudo, email, mot de passe, préférence de notification par email

**Galerie (publique)**
- Toutes les images, de la plus récente à la plus ancienne, paginées (6 par page)
- Likes et commentaires (utilisateurs connectés uniquement)
- L'auteur est prévenu par email à chaque nouveau commentaire (désactivable dans le profil)

**Éditeur (utilisateurs connectés uniquement)**
- Aperçu webcam en direct, ou import d'une photo
- Plusieurs overlays superposables, avec aperçu en direct sur la vidéo
- Le bouton de capture reste désactivé tant qu'aucun overlay n'est sélectionné
- L'image finale est composée **côté serveur** avec GD
- Panneau latéral avec vos images précédentes ; vous ne pouvez supprimer que les vôtres

## Stack technique

| Couche | Choix |
|---|---|
| Serveur | PHP 8.3 (PHP-FPM), bibliothèque standard uniquement, MVC / routeur / autoloader maison |
| Serveur web | Nginx (front controller) |
| Base de données | MySQL 8 (PDO, requêtes préparées) |
| Traitement d'image | GD |
| Client | HTML, CSS, JavaScript vanilla (API natives du navigateur uniquement) |
| Mail (dev) | Mailpit, via `msmtp` et la fonction PHP `mail()` |
| Déploiement | Docker Compose |

## Démarrage

### Prérequis
- Docker avec Docker Compose (Podman fonctionne via l'émulation de la CLI Docker)
- `make` (optionnel)

### Installation

```bash
git clone <url-du-depot> camagru
cd camagru
touch .env     # puis éditer les valeurs
make
```

Ouvrir :
- Application : <http://localhost:8080>
- Mailpit (capture tous les emails sortants en développement) : <http://localhost:8025>

Le schéma de la base est créé automatiquement au premier démarrage à partir de `docker/mysql/init.sql`.

### Variables d'environnement

Les identifiants sont à rajouter dans un fichier `.env`

```env
MYSQL_ROOT_PASSWORD=change_me
MYSQL_DATABASE=camagrudb
MYSQL_USER=camagru
MYSQL_PASSWORD=change_me
MYSQL_HOST=mysql
APP_URL=http://localhost:8080
```

### Cibles du Makefile

| Commande | Effet |
|---|---|
| `make up` | Construit et démarre tous les services |
| `make down` | Arrête les services |
| `make clean-uploads` | Supprime les images générées (depuis un conteneur, car les fichiers appartiennent à `www-data`) |
| `make fclean` | Supprime les uploads **et** les volumes (base de données comprise) |
| `make re` | `fclean` puis `up` |

## Structure du projet

```
.
├── docker-compose.yml
├── docker/
│   ├── app/            # image PHP-FPM (GD, pdo_mysql, msmtp)
│   ├── nginx/          # image Nginx et configuration du site
│   └── mysql/init.sql  # schéma
├── public/             # seul dossier servi par Nginx
│   ├── index.php       # front controller
│   ├── css/  js/  fonts/  overlays/
│   └── uploads/        # images générées
└── srcs/
    ├── Core/           # Router, Database, Autoloader
    ├── Controllers/    # Auth, Gallery, Edit, Profile
    ├── Models/         # User, Image, Like, Comment
    └── Views/          # templates PHP (header/footer partagés)
```

### Parcours d'une requête

`Nginx` → `public/index.php` → `Router` → `Controller` → `Model` (PDO) → `View`.

Nginx ne sert que `public/` : le code de l'application, le `.env` et les fichiers Docker ne sont jamais accessibles par URL. Les chemins inconnus retombent sur le front controller.

## Routes

| Méthode | Chemin | Description |
|---|---|---|
| GET | `/` | Galerie |
| GET | `/gallery/{id}` | Détail d'une image avec ses commentaires |
| POST | `/like/{id}` | Ajouter / retirer un like |
| POST | `/comment/{id}` | Ajouter un commentaire |
| GET/POST | `/register`, `/login` | Authentification |
| GET | `/confirm?token=` | Confirmation du compte |
| GET/POST | `/forgot-password`, `/reset-password` | Réinitialisation du mot de passe |
| GET/POST | `/profile`, `/modify` | Profil et paramètres |
| GET | `/edit-images` | Éditeur (connexion requise) |
| POST | `/edit-images/create` | Composer et enregistrer une image |
| POST | `/delete-image/{id}` | Supprimer une de ses images |

## Sécurité

- Mots de passe hachés avec `password_hash()` (bcrypt), jamais stockés en clair
- Toutes les requêtes SQL utilisent des requêtes préparées PDO
- Toute sortie contenant des données utilisateur est échappée avec `htmlspecialchars()` (XSS)
- Les jetons de confirmation et de réinitialisation viennent de `random_bytes()` ; les jetons de réinitialisation expirent après 15 minutes
- Vérification de propriété avant la suppression d'une image ; les pages protégées redirigent les visiteurs anonymes
- Les images envoyées ne sont jamais stockées telles quelles : elles sont décodées puis **ré-encodées avec GD** ; les overlays sont choisis dans une liste blanche côté serveur
- Les secrets sont dans `.env`, hors de la racine web et hors de git

## Auteur

Réalisé par [junguyen](https://github.com/jln-nguyen)
