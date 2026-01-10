# Store Suite - Documentation Technique

## Architecture Générale

### Stack Technologique
- **Langage** : PHP 8.0+
- **Base de données** : MySQL 5.7+ / MariaDB 10.3+
- **Framework UI** : Bootstrap 5.3.0
- **Serveur** : Apache (via XAMPP)
- **Charset** : UTF-8
- **API** : AJAX endpoints REST-style

### Architecture
- **Type** : Monolithique
- **Pattern** : Pages avec endpoints AJAX
- **ORM** : Aucun (PDO brut avec fonctions helpers)
- **Authentification** : Session PHP
- **Timezone** : Africa/Lubumbashi

---

## Schéma de Base de Données

### Tables Principales

#### `utilisateurs`
```sql
- id_utilisateur (INT, PK)
- nom_complet (VARCHAR 255)
- login (VARCHAR 100, UNIQUE)
- mot_de_passe (VARCHAR 255) -- hashé bcrypt
- email (VARCHAR 255)
- telephone (VARCHAR 50)
- niveau_acces (TINYINT) -- 1=Admin, 2=Vendeur
- photo (VARCHAR 255)
- est_actif (TINYINT)
- date_creation (DATETIME)
- date_derniere_connexion (DATETIME)
- date_modification (TIMESTAMP)
```

#### `categories`
```sql
- id_categorie (INT, PK)
- nom_categorie (VARCHAR 255)
- description (TEXT)
- icone (VARCHAR 100)
- couleur (VARCHAR 7) -- format HEX
- ordre_affichage (INT)
- est_actif (TINYINT)
- date_creation (DATETIME)
- date_modification (TIMESTAMP)
```

#### `produits`
```sql
- id_produit (INT, PK)
- code_produit (VARCHAR 100, UNIQUE)
- nom_produit (VARCHAR 255)
- description (TEXT)
- id_categorie (INT, FK)
- prix_achat (DECIMAL 15,2)
- prix_vente (DECIMAL 15,2)
- prix_vente_min (DECIMAL 15,2)
- quantite_stock (INT)
- seuil_alerte (INT)
- seuil_critique (INT)
- unite_mesure (VARCHAR 50)
- image (VARCHAR 255)
- code_barre (VARCHAR 100)
- emplacement (VARCHAR 255)
- date_entree (DATE)
- date_derniere_vente (DATETIME)
- nombre_ventes (INT)
- est_actif (TINYINT)
- date_creation (DATETIME)
- date_modification (TIMESTAMP)
```

#### `clients`
```sql
- id_client (INT, PK)
- nom_client (VARCHAR 255)
- telephone (VARCHAR 50)
- email (VARCHAR 255)
- adresse (TEXT)
- type_client (ENUM: particulier, entreprise)
- numero_fiscal (VARCHAR 100)
- total_achats (DECIMAL 15,2)
- nombre_achats (INT)
- date_dernier_achat (DATETIME)
- notes (TEXT)
- est_actif (TINYINT)
- date_creation (DATETIME)
- date_modification (TIMESTAMP)
```

#### `ventes`
```sql
- id_vente (INT, PK)
- numero_facture (VARCHAR 50, UNIQUE)
- id_client (INT, FK)
- id_vendeur (INT, FK)
- montant_total (DECIMAL 15,2)
- montant_remise (DECIMAL 15,2)
- montant_tva (DECIMAL 15,2) -- TVA 16%
- montant_paye (DECIMAL 15,2)
- montant_rendu (DECIMAL 15,2)
- mode_paiement (ENUM: especes, carte, mobile_money, cheque, credit)
- statut (ENUM: en_cours, validee, annulee)
- notes (TEXT)
- date_vente (DATETIME)
- date_modification (TIMESTAMP)
```

#### `ventes_details`
```sql
- id_detail (INT, PK)
- id_vente (INT, FK)
- id_produit (INT, FK)
- nom_produit (VARCHAR 255)
- quantite (INT)
- prix_unitaire (DECIMAL 15,2)
- prix_total (DECIMAL 15,2)
- date_creation (DATETIME)
```

#### `mouvements_stock`
```sql
- id_mouvement (INT, PK)
- id_produit (INT, FK)
- type_mouvement (ENUM: entree, sortie, ajustement)
- quantite (INT)
- raison (VARCHAR 255)
- reference (VARCHAR 100)
- utilisateur_id (INT, FK)
- date_mouvement (DATETIME)
- notes (TEXT)
```

#### `configuration`
```sql
- id_config (INT, PK)
- nom_boutique (VARCHAR 255)
- adresse_boutique (TEXT)
- telephone_boutique (VARCHAR 50)
- email_boutique (VARCHAR 255)
- logo_boutique (VARCHAR 255)
- devise (VARCHAR 10)
- couleur_primaire (VARCHAR 7)
- couleur_secondaire (VARCHAR 7)
- est_configure (TINYINT)
- date_creation (DATETIME)
- date_modification (TIMESTAMP)
```

---

## Structure PHP

### Dossiers Clés

```
STORESuite/
├── ajax/                      # Endpoints AJAX
│   ├── categories.php
│   ├── clients.php
│   ├── produits.php
│   ├── utilisateurs.php
│   ├── valider_vente.php
│   ├── export_excel.php
│   ├── export_pdf.php
│   └── reinitialiser_donnees.php
├── config/
│   ├── config.php            # Constantes (paths, DB, timeouts)
│   └── database.php          # Connexion PDO + helpers
├── database/
│   └── storesuite.sql        # Schéma complet
├── uploads/
│   ├── logos/
│   ├── produits/
│   └── utilisateurs/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── modals.js
│       └── loader.js
├── header.php                # Navigation commune
├── footer.php                # Pied de page commune
├── protection_pages.php      # Middleware d'auth
├── index.php                 # Redirection
├── accueil.php              # Dashboard
├── vente.php                # Création ventes
├── facture.php              # Consultation factures
├── listes.php               # Gestion données (admin)
├── rapports.php             # Rapports et exports
├── parametres.php           # Configuration système
├── utilisateurs.php         # Gestion utilisateurs (admin)
├── profil.php               # Profil utilisateur
├── aide.php                 # Documentation utilisateur
└── setup.php                # Installation initiale
```

### Fonctions Helpers PDO (config/database.php)

```php
// Requête préparée avec résultat
db_query($sql, $params = [])

// Récupérer une ligne
db_fetch_one($sql, $params = [])

// Récupérer toutes les lignes
db_fetch_all($sql, $params = [])

// Exécuter une requête (INSERT, UPDATE, DELETE)
db_execute($sql, $params = [])

// Compter les lignes
db_count($table, $where = '', $params = [])

// Insérer
db_insert($table, $data)

// Mettre à jour
db_update($table, $data, $where, $whereParams = [])

// Supprimer
db_delete($table, $where, $params = [])

// Transactions
db_begin_transaction()
db_commit()
db_rollback()
db_in_transaction()
```

### Variables Globales (protection_pages.php)

```php
$user_id              // ID utilisateur actuel
$user_name            // Nom complet utilisateur
$user_niveau          // Niveau accès (1=Admin, 2=Vendeur)
$is_admin             // Boolean

$nom_boutique         // Config: nom boutique
$logo_boutique        // Config: logo
$devise               // Config: devise
$couleur_primaire     // Config: couleur primaire
$couleur_secondaire   // Config: couleur secondaire
```

---

## Sécurité

### Authentification
- **Stockage** : bcrypt (password_hash/password_verify)
- **Session** : PHP_SESSION, 2h timeout
- **Protection** : Middleware via protection_pages.php

### Injection SQL
- **Prévention** : Requêtes préparées PDO partout
- **Paramètres** : Bindés via ? ou :param

### XSS (Cross-Site Scripting)
- **Prévention** : Fonction `e()` pour échappement HTML
- **Utilisation** : `echo e($user_input);`

### CSRF
- À implémenter si nécessaire (tokens de session)

---

## Configuration

### config/config.php

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'storesuite');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', 'http://localhost/STORESuite/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('LOGO_PATH', __DIR__ . '/../uploads/logos/');

define('MAX_FILE_SIZE', 5242880); // 5MB
define('SESSION_LIFETIME', 7200); // 2h
define('TIMEZONE', 'Africa/Lubumbashi');
```

---

## Flux de Données

### Cycle d'une Vente

1. **Créer vente** (vente.php)
   - Sélectionner client
   - Ajouter produits au panier
   - Calculer TVA 16%
   
2. **Valider vente** (ajax/valider_vente.php)
   - Vérifier stock
   - Générer numéro facture
   - Insérer dans `ventes` et `ventes_details`
   - Décrémenter stock
   - Créer mouvement stock

3. **Afficher facture** (facture.php)
   - Charger données de `ventes` + `ventes_details`
   - Formater avec logo et infos boutique

4. **Imprimer/PDF** (facture_impression.php)
   - CSS media query @print
   - Convertir en PDF via navigateur

---

## Gestion de Stock

### Workflow Produits

```
Ajouter produit (listes.php)
    ↓
db_insert('produits', $data)
    ↓
Créer mouvement_stock (type=entree)
    ↓
Vendre produit (valider_vente.php)
    ↓
db_update('produits', quantite_stock-=qty)
    ↓
Créer mouvement_stock (type=sortie)
```

### Alertes Stock

- **Seuil alerte** : Warning (jaune)
- **Seuil critique** : Danger (rouge)
- Affichage dans Accueil et Rapports

---

## Calcul Financier

### TVA
```
TVA = 16% du montant HT
Montant TTC = Montant HT + (Montant HT × 0.16)
```

### Mode Paiement
- Espèces
- Carte bancaire
- Mobile Money
- Chèque
- Crédit client

---

## Localisation

### Français
- Tous les libellés, messages, menus en français
- Dates formatées : jj/mm/yyyy
- Devises dynamiques (USD, EUR, XOF, etc.)

### Timezone
- Défaut : Africa/Lubumbashi
- Configurable dans config.php

---

## Extensibilité

### Ajouter un Utilisateur
```php
db_insert('utilisateurs', [
    'nom_complet' => 'Jean Dupont',
    'login' => 'jdupont',
    'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT),
    'email' => 'jean@example.com',
    'niveau_acces' => 2, // Vendeur
    'est_actif' => 1
]);
```

### Ajouter une Catégorie
```php
db_insert('categories', [
    'nom_categorie' => 'Électronique',
    'description' => 'Produits électroniques',
    'couleur' => '#3498db',
    'est_actif' => 1
]);
```

### Ajouter un Produit
```php
db_insert('produits', [
    'code_produit' => 'PROD001',
    'nom_produit' => 'Ordinateur Portable',
    'id_categorie' => 1,
    'prix_achat' => 500.00,
    'prix_vente' => 750.00,
    'quantite_stock' => 10,
    'est_actif' => 1
]);
```

---

## Performance

### Optimisations
- Index sur colonnes fréquemment interrogées
- Cache de configuration en session
- Pagination des listes
- Compression CSS/JS minifiés

### Requêtes Critiques
- Ventes : groupées par date/client
- Stock : requêtes preparées avec IN
- Produits alertes : vue vs requête

---

## Maintenance

### Sauvegardes
- Exporter BD via phpMyAdmin
- Dossier uploads/ régulièrement sauvegardé

### Logs
- Audit: table `activite_logs` (à implémenter)
- Erreurs: /logs/ (à créer)

### Réinitialisation
- Admin → Paramètres → Zone de Réinitialisation
- Options : ventes, produits, clients, utilisateurs, complet

---

## Roadmap

- [ ] Tableau de bord avancé (KPIs)
- [ ] Gestion fournisseurs
- [ ] Commandes client
- [ ] Intégration paiement (API)
- [ ] Export comptable
- [ ] Mobile app
- [ ] API REST complète

---

## Support

Documentation utilisateur : aide.php
Problèmes techniques : README.md (ce fichier)
