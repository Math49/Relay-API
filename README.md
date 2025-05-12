# 📦 Relay API

Bienvenue sur **Relay API**, l'API RESTful développée en **Laravel 11** pour la gestion des magasins, des stocks, des produits, des listes de commande et des messages d'information dans le réseau Relay.

---

## 🚀 Fonctionnalités principales

- Gestion des **comptes utilisateurs** (authentification Sanctum)
- Gestion des **magasins** (stores)
- Gestion des **produits** (products)
- Gestion des **stocks** magasin par produit
- Création de **listes de commandes** avec produits associés
- Système de **messages** personnalisés par magasin
- **Gestion des catégories activées** par magasin
- API sécurisée avec **Laravel Sanctum**
- Déploiement **CI/CD** sur **PlanetHoster** via **GitHub Actions**

---

## 🛠️ Technologies utilisées

- [Laravel 11](https://laravel.com/)
- [MySQL](https://www.mysql.com/)
- [PHP 8.2+](https://www.php.net/)
- [Sanctum](https://laravel.com/docs/11.x/sanctum)
- [GitHub Actions](https://github.com/features/actions) pour le CI/CD
- Hébergement : **PlanetHoster**

---

## ⚙️ Installation locale

1. **Cloner le projet :**

```bash
git clone https://github.com/Math49/Relay-API.git
cd Relay-API
```

2. **Installer les dépendances :**

```bash
composer install
```

3. **Copier le fichier d'environnement et configurer votre `.env` :**

```bash
cp .env.example .env
```
**À configurer :**
- Base de données MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- Sanctum (`SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`, etc.)

4. **Générer la clé d'application :**

```bash
php artisan key:generate
```

5. **Lancer les migrations et les seeders :**

```bash
php artisan migrate:fresh --seed
```

6. **Démarrer le serveur local :**

```bash
php artisan serve
```

L'API sera accessible sur [http://localhost:8000](http://localhost:8000)

---

## 🧪 Lancer les tests

Le projet utilise **PestPHP** pour les tests.

```bash
./vendor/bin/pest
```

---

## 🔐 Authentification

Toutes les routes API (sauf `/api/login` et `/api/register`) sont protégées via **Sanctum**.

- **Se connecter** : `POST /api/login`
- **Se déconnecter** : `POST /api/logout`
- Le token reçu doit être utilisé dans le header `Authorization: Bearer {token}` pour toutes les requêtes protégées.

---

## 📊 Liste Complète des Routes Relay API

### 🔐 Authentification

| Méthode | URL           | Description                   |
|:---------|:--------------|:------------------------------|
| POST     | `/api/register` | Création d'un utilisateur      |
| POST     | `/api/login`    | Connexion                     |
| POST     | `/api/logout`   | Déconnexion                   |

---

### 🏢 Gestion des magasins (Stores)

| Méthode | URL                    | Description                      |
|:---------|:-----------------------|:---------------------------------|
| GET      | `/api/stores`           | Liste de tous les magasins       |
| GET      | `/api/store/{id}`       | Détails d'un magasin par ID      |
| POST     | `/api/store`            | Création d'un magasin            |
| PUT      | `/api/store/{id}`       | Mise à jour d'un magasin        |
| DELETE   | `/api/store`            | Suppression d'un magasin (ID via body) |

---

### 🌍 Gestion des produits (Products)

| Méthode | URL                    | Description                      |
|:---------|:-----------------------|:---------------------------------|
| GET      | `/api/products`         | Liste de tous les produits       |
| GET      | `/api/product/{id}`     | Détails d'un produit par ID      |
| POST     | `/api/product`          | Création d'un produit            |
| PUT      | `/api/product/{id}`     | Mise à jour d'un produit         |
| DELETE   | `/api/product`          | Suppression d'un produit (ID via body) |

---

### 👥 Gestion des stocks (Stocks)

| Méthode | URL                              | Description                      |
|:---------|:---------------------------------|:---------------------------------|
| GET      | `/api/stocks`                    | Liste de tous les stocks         |
| GET      | `/api/stock/{ID_store}`           | Liste des stocks d'un magasin    |
| GET      | `/api/stock/{ID_store}/{ID_product}` | Détail du stock pour un produit dans un magasin |
| POST     | `/api/stock`                      | Créer un stock                  |
| POST     | `/api/stocks`                     | Créer plusieurs stocks          |
| PUT      | `/api/stock/{ID_store}/{ID_product}` | Mettre à jour un stock          |
| PUT      | `/api/stocks/{ID_store}`           | Mettre à jour plusieurs stocks |
| DELETE   | `/api/stock`                      | Supprimer un stock (IDs via body) |

---

### 📒 Gestion des listes de commande (Lists)

| Méthode | URL                              | Description                      |
|:---------|:---------------------------------|:---------------------------------|
| GET      | `/api/lists`                     | Liste de toutes les listes       |
| GET      | `/api/list/{ID_store}`            | Listes d'un magasin              |
| GET      | `/api/list/{ID_store}/{ID_list}`   | Détail d'une liste spécifique    |
| POST     | `/api/list`                       | Création d'une liste             |
| PUT      | `/api/list`                       | Mise à jour d'une liste          |
| DELETE   | `/api/list`                       | Suppression d'une liste          |

---

### 💬 Gestion des messages (Messages)

| Méthode | URL                              | Description                      |
|:---------|:---------------------------------|:---------------------------------|
| GET      | `/api/messages`                  | Liste de tous les messages       |
| GET      | `/api/messages/{ID_store}`        | Messages d'un magasin            |
| GET      | `/api/messages/{ID_store}/{ID_message}` | Détail d'un message             |
| POST     | `/api/message`                    | Création d'un message            |
| PUT      | `/api/message/{ID_message}`       | Mise à jour d'un message        |
| DELETE   | `/api/message/{ID_message}`       | Suppression d'un message         |

---

### 📆 Gestion des catégories activées (Categories Enable)

| Méthode | URL                              | Description                      |
|:---------|:---------------------------------|:---------------------------------|
| GET      | `/api/categoryEnable/{ID_store}`  | Catégories activées d'un magasin |
| POST     | `/api/categoryEnable/{ID_store}`  | Créer une catégorie activée     |
| PUT      | `/api/categoryEnable/{ID_store}`  | Mettre à jour une catégorie activée |
| DELETE   | `/api/categoryEnable`             | Supprimer une catégorie activée (IDs via body) |

> ✨ Toutes les routes (sauf login/register) sont **protégées par Sanctum**.

---

## 🚀 Déploiement CI/CD sur PlanetHoster

Relay-API est automatiquement déployé sur PlanetHoster à chaque push sur `master` via **GitHub Actions**.

> Configuration personnalisée avec connexion SSH (à travers port 5022) et installation automatique des dépendances Laravel.

## 👤 Auteur
**Développé par** Mathis Mercier
**Contact :** mthsmercier@gmail.com

