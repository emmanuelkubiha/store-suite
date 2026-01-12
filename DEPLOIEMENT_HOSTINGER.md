# 🚀 GUIDE DÉPLOIEMENT HOSTINGER

## 📋 PRÉREQUIS

- ✅ Compte Hostinger actif
- ✅ Domaine shop.fosip-drc.org pointant vers Hostinger
- ✅ Accès au panneau de contrôle Hostinger (hPanel)
- ✅ Fichiers du projet prêts à uploader

---

## 🗄️ ÉTAPE 1: CRÉER LA BASE DE DONNÉES

### Via hPanel Hostinger

1. **Accéder à MySQL**
   - Connexion: https://hpanel.hostinger.com
   - Menu: `Bases de données` → `Bases de données MySQL`

2. **Créer nouvelle base de données**
   - Cliquer sur `CRÉER UNE NOUVELLE BASE DE DONNÉES`
   - Nom: `storesuite` (ou autre nom au choix)
   - Note: Le nom complet sera `u123456789_storesuite`

3. **Créer un utilisateur**
   - Username: `storesuite_user` (préfixe auto-ajouté)
   - Password: **[Générer mot de passe fort]** → Noter quelque part!
   - Cocher `Accorder tous les privilèges`

4. **Noter les informations**
   ```
   DB_HOST: localhost
   DB_NAME: u123456789_storesuite
   DB_USER: u123456789_storesuite_user
   DB_PASS: [votre mot de passe généré]
   ```

5. **Importer la base de données**
   - Cliquer sur `Gérer` à côté de la base créée
   - Aller dans `phpMyAdmin`
   - Onglet `Importer`
   - Choisir le fichier `storesuite_online.sql`
   - Cliquer `Exécuter`
   - ✅ Vérifier: 14 tables + 2 vues créées

---

## 📁 ÉTAPE 2: UPLOADER LES FICHIERS

### Option A: Gestionnaire de fichiers (Recommandé pour débutants)

1. **Accéder au gestionnaire**
   - hPanel → `Fichiers` → `Gestionnaire de fichiers`

2. **Naviguer vers le bon dossier**
   - Aller dans `public_html/` ou `domains/shop.fosip-drc.org/public_html/`

3. **Uploader les fichiers**
   - Créer un fichier ZIP du projet localement (sans `config/config.php`)
   - Uploader le ZIP via le gestionnaire
   - Extraire le ZIP dans `public_html/`

4. **Structure finale**
   ```
   public_html/
   ├── .htaccess
   ├── index.php
   ├── login.php
   ├── error_404.php
   ├── error_500.php
   ├── config/
   │   ├── config.php (à créer)
   │   └── database.php
   ├── ajax/
   ├── assets/
   ├── database/
   └── uploads/
       ├── logos/
       ├── produits/
       └── utilisateurs/
   ```

### Option B: FTP (Pour utilisateurs avancés)

1. **Récupérer les informations FTP**
   - hPanel → `Fichiers` → `Comptes FTP`
   - Hostname: `ftp.shop.fosip-drc.org`
   - Port: 21
   - Username: (votre username FTP)
   - Password: (votre password FTP)

2. **Utiliser FileZilla**
   - Télécharger: https://filezilla-project.org
   - Connexion avec les infos FTP
   - Uploader tous les fichiers dans `/public_html/`

---

## ⚙️ ÉTAPE 3: CONFIGURER LE FICHIER CONFIG.PHP

1. **Via gestionnaire de fichiers**
   - Aller dans `public_html/config/`
   - Copier `config.hostinger.php` → renommer en `config.php`

2. **Éditer config.php**
   - Clic droit → Modifier
   - Remplir les informations de base de données:
   
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'u123456789_storesuite');          // Votre nom complet de BD
   define('DB_USER', 'u123456789_storesuite_user');     // Votre utilisateur BD
   define('DB_PASS', 'VOTRE_MOT_DE_PASSE');             // Le password noté à l'étape 1
   
   define('BASE_URL', 'https://shop.fosip-drc.org/');   // Votre URL
   ```

3. **Vérifier les permissions**
   - config.php: `644`
   - uploads/: `755`

---

## 🔒 ÉTAPE 4: ACTIVER SSL (HTTPS)

1. **Aller dans SSL/TLS**
   - hPanel → `Sécurité` → `SSL`

2. **Activer SSL gratuit**
   - Hostinger fournit Let's Encrypt gratuit
   - Cliquer sur `Installer SSL` pour shop.fosip-drc.org
   - Attendre 1-2 minutes pour activation

3. **Forcer HTTPS (optionnel)**
   - Décommenter les lignes dans `.htaccess`:
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

---

## 🔧 ÉTAPE 5: CONFIGURATION PHP

1. **Vérifier version PHP**
   - hPanel → `Avancé` → `Configuration PHP`
   - Version recommandée: **PHP 8.0** ou supérieur
   - Changer si nécessaire

2. **Paramètres PHP (si besoin)**
   - `upload_max_filesize`: 10M
   - `post_max_size`: 10M
   - `max_execution_time`: 300
   - `memory_limit`: 256M

---

## ✅ ÉTAPE 6: TESTER LE SITE

### Tests de diagnostic

1. **Test connexion base de données**
   ```
   https://shop.fosip-drc.org/diagnostic_500.php
   ```
   - ✅ Config chargé
   - ✅ Connexion MySQL OK
   - ✅ Tables présentes

2. **Test page login**
   ```
   https://shop.fosip-drc.org/login.php
   ```
   - Page de login doit s'afficher
   - Tester connexion:
     - Login: `admin`
     - Password: `admin`

3. **Test erreurs**
   ```
   https://shop.fosip-drc.org/page-inexistante
   ```
   - Page 404 personnalisée doit s'afficher

4. **Test tableau de bord**
   - Après login, accès au tableau de bord
   - Vérifier statistiques
   - Tester ajout produit

---

## 🐛 RÉSOLUTION DE PROBLÈMES

### Erreur 500 - Serveur interne

**Causes possibles:**
1. config.php mal configuré
2. Permissions incorrectes
3. .htaccess incompatible

**Solutions:**
```bash
# Via gestionnaire de fichiers, vérifier:
- config/config.php existe et contient les bonnes infos BD
- uploads/ a permission 755
- Renommer .htaccess temporairement pour tester
```

### Erreur "Cannot connect to database"

**Solutions:**
```php
# Dans config.php, essayer:
define('DB_HOST', 'localhost');  // ou '127.0.0.1'

# Vérifier dans phpMyAdmin que:
- La base existe
- L'utilisateur a les privilèges
```

### Page blanche

**Solutions:**
1. Activer affichage erreurs temporairement:
   ```php
   // Dans config.php temporairement
   define('DEBUG_MODE', true);
   ```

2. Vérifier les logs:
   - hPanel → `Avancé` → `Logs d'erreurs`

### Uploads ne fonctionnent pas

**Solutions:**
```bash
# Vérifier que le dossier uploads/ existe avec:
uploads/
  ├── logos/
  ├── produits/
  └── utilisateurs/

# Permissions: 755 pour tous
```

### .htaccess cause erreur 500

**Solution temporaire:**
```bash
# Renommer .htaccess en .htaccess.bak
# Tester le site
# Si ça marche, problème dans .htaccess
# Ajouter les règles une par une pour trouver le problème
```

---

## 📝 CHECKLIST FINALE

- [ ] Base de données créée sur Hostinger
- [ ] Fichier SQL importé (14 tables + 2 vues)
- [ ] Tous les fichiers uploadés dans public_html/
- [ ] config.php créé avec les bonnes credentials
- [ ] Permissions correctes (755 pour dossiers, 644 pour fichiers)
- [ ] SSL activé (HTTPS)
- [ ] PHP version 8.0+
- [ ] .htaccess présent à la racine
- [ ] Test diagnostic_500.php → ✅
- [ ] Test login.php → ✅
- [ ] Connexion admin fonctionne
- [ ] Tableau de bord accessible
- [ ] Test ajout produit
- [ ] Test création vente

---

## 🔐 SÉCURITÉ POST-DÉPLOIEMENT

1. **Supprimer les fichiers de diagnostic**
   ```bash
   - diagnostic_500.php
   - debug_login.php
   - check_config.php
   - test_pages.php
   ```

2. **Changer le mot de passe admin**
   - Via interface: Paramètres → Utilisateurs
   - Mettre un mot de passe fort

3. **Désactiver DEBUG_MODE**
   ```php
   // Dans config.php
   define('DEBUG_MODE', false);
   ```

4. **Sauvegardes automatiques**
   - hPanel → `Fichiers` → `Sauvegardes`
   - Activer sauvegardes automatiques

---

## 📞 INFORMATIONS UTILES

### Accès Hostinger
- Panel: https://hpanel.hostinger.com
- phpMyAdmin: Via hPanel → Bases de données → Gérer
- FTP: ftp.shop.fosip-drc.org:21

### Support Hostinger
- Chat: 24/7 disponible dans hPanel
- Email: support@hostinger.com
- Tutoriels: https://support.hostinger.com

### Documentation projet
- Guide complet: `ETAT_DEPLOIEMENT_12_JAN.md`
- Guide Git: `GUIDE_GIT_NOUVELLE_MACHINE.md`

---

**Dernière mise à jour:** 12 janvier 2026  
**Prochaine étape:** Envoyer les nouvelles credentials Hostinger pour mise à jour du config
