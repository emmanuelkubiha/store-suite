# 🚀 DÉPLOIEMENT RAPIDE - storesuite.shop

## ✅ INFORMATIONS PRÉ-REMPLIES

```
Site: https://storesuite.shop/
Base de données: u783961849_storesuite
Utilisateur BD: u783961849_emmanuel
Mot de passe BD: Hallelujah2018
Host BD: localhost
```

---

## 📋 ÉTAPES DE DÉPLOIEMENT

### 1️⃣ IMPORTER LA BASE DE DONNÉES

1. Connectez-vous à **phpMyAdmin** sur Hostinger
   - Via hPanel → Bases de données → Gérer `u783961849_storesuite`

2. Importer le fichier SQL
   - Onglet **Importer**
   - Choisir: `database/storesuite_online.sql`
   - Cliquer **Exécuter**
   - ✅ Vérifier: 14 tables + 2 vues créées

---

### 2️⃣ UPLOADER LES FICHIERS

**Via Gestionnaire de fichiers Hostinger:**

1. Aller dans **public_html/** (ou **domains/storesuite.shop/public_html/**)

2. Uploader TOUS les fichiers du projet SAUF:
   - ❌ `config/config.php` (local)
   - ❌ Fichiers `.md` (documentation)
   - ❌ Dossier `.git/`

3. Structure finale:
```
public_html/
├── .htaccess                    ← Important!
├── index.php
├── login.php
├── accueil.php
├── error_404.php
├── error_500.php
├── config/
│   ├── config.storesuite.php    ← À renommer!
│   └── database.php
├── ajax/
├── assets/
├── database/
└── uploads/
    ├── logos/
    ├── produits/
    └── utilisateurs/
```

---

### 3️⃣ RENOMMER LE FICHIER CONFIG

**IMPORTANT:** Dans le dossier `config/`

1. Supprimer `config.php` s'il existe
2. Renommer `config.storesuite.php` → `config.php`
3. Vérifier permissions: **644**

---

### 4️⃣ VÉRIFIER LES PERMISSIONS

**Via gestionnaire de fichiers:**

- Dossiers: **755**
  - `uploads/`
  - `uploads/logos/`
  - `uploads/produits/`
  - `uploads/utilisateurs/`

- Fichiers PHP: **644**
  - `config/config.php`
  - Tous les autres `.php`

---

### 5️⃣ ACTIVER SSL (HTTPS)

1. hPanel → **Sécurité** → **SSL**
2. Vérifier que SSL est actif pour `storesuite.shop`
3. Si non actif, cliquer **Installer SSL** (gratuit Let's Encrypt)
4. Attendre 2-3 minutes

---

### 6️⃣ TESTER LE SITE

#### Test 1: Diagnostic général
```
https://storesuite.shop/diagnostic_500.php
```

**Résultat attendu:**
```
✓ config/config.php EXISTE
✓ CHARGE
  DB_HOST: localhost
  DB_NAME: u783961849_storesuite
✓ CONNEXION OK
✓ 14 TABLES
```

#### Test 2: Page de login
```
https://storesuite.shop/login.php
```

- ✅ Page de login s'affiche
- ✅ Pas d'erreur 500

#### Test 3: Connexion admin
```
Login: admin
Password: admin
```

- ✅ Connexion réussie
- ✅ Redirection vers tableau de bord

#### Test 4: Page d'erreur 404
```
https://storesuite.shop/page-inexistante
```

- ✅ Page 404 personnalisée s'affiche

---

## 🔧 SI ERREUR 500

### Vérifier dans cet ordre:

1. **config.php existe?**
   ```
   https://storesuite.shop/check_config.php
   ```

2. **Permissions correctes?**
   - uploads/ → 755
   - config/config.php → 644

3. **.htaccess présent?**
   - Doit être à la racine de public_html/
   - Vérifier qu'il n'est pas renommé `.htaccess.txt`

4. **Version PHP**
   - hPanel → Avancé → Configuration PHP
   - Mettre **PHP 8.0** ou **8.1**

5. **Voir les logs**
   - hPanel → Avancé → Logs d'erreurs
   - Chercher les erreurs récentes

---

## 🔐 SÉCURITÉ POST-DÉPLOIEMENT

### 1. Supprimer les fichiers de test

Via gestionnaire de fichiers, **supprimer:**
```
❌ diagnostic_500.php
❌ debug_login.php
❌ check_config.php
❌ test_pages.php
```

### 2. Changer le mot de passe admin

1. Se connecter: https://storesuite.shop/login.php
2. Menu: **Paramètres** → **Utilisateurs**
3. Modifier l'utilisateur **admin**
4. Nouveau mot de passe **fort** (12+ caractères)

### 3. Vérifier DEBUG_MODE

Dans `config/config.php`, vérifier:
```php
define('DEBUG_MODE', false);  // ← DOIT être false!
```

---

## ✅ CHECKLIST FINALE

- [ ] Base de données `u783961849_storesuite` créée
- [ ] SQL importé (14 tables visibles dans phpMyAdmin)
- [ ] Tous les fichiers uploadés dans public_html/
- [ ] `config.storesuite.php` renommé en `config.php`
- [ ] Permissions uploads/ = 755
- [ ] SSL activé (site accessible en HTTPS)
- [ ] Test diagnostic_500.php → ✅
- [ ] Login admin/admin fonctionne
- [ ] Mot de passe admin changé
- [ ] Fichiers de test supprimés
- [ ] DEBUG_MODE = false

---

## 🎯 URLS IMPORTANTES

```
Site principal:     https://storesuite.shop/
Page login:         https://storesuite.shop/login.php
Tableau de bord:    https://storesuite.shop/accueil.php
Point de vente:     https://storesuite.shop/vente_professionnel.php

Panel Hostinger:    https://hpanel.hostinger.com
phpMyAdmin:         Via hPanel → Bases de données
```

---

## 📞 IDENTIFIANTS

### Base de données
```
Host:     localhost
Database: u783961849_storesuite
User:     u783961849_emmanuel
Pass:     Hallelujah2018
```

### Application (par défaut)
```
Login:    admin
Password: admin (À CHANGER!)
```

---

## 🆘 SUPPORT

**Si problème persistant:**

1. Prendre screenshot de l'erreur
2. Copier contenu de diagnostic_500.php
3. Vérifier logs erreurs dans hPanel
4. Chat support Hostinger 24/7

---

**Temps estimé de déploiement:** 15-20 minutes  
**Dernière mise à jour:** 12 janvier 2026

🚀 **Bon déploiement!**
