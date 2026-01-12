# 🚀 ÉTAT DU DÉPLOIEMENT - STORESUITE
**Date:** 12 janvier 2026  
**Statut:** Déploiement en cours - Site retourne erreur 500

---

## 📍 SITUATION ACTUELLE

### Environnement de production
- **Domaine:** https://shop.fosip-drc.org/
- **Hébergement:** Hébergement mutualisé
- **Structure:** `/public_html/shop.fosip-drc.org/` (tous les fichiers du projet)
- **Base de données:** MySQL importée et fonctionnelle

### ✅ Ce qui fonctionne
1. ✅ Base de données importée avec succès (14 tables + 2 vues)
2. ✅ Connexion MySQL opérationnelle (testé via diagnostic_500.php)
3. ✅ Tous les fichiers uploadés sur le serveur
4. ✅ Structure `uploads/` créée avec sous-dossiers (logos, produits, utilisateurs)
5. ✅ Fichiers critiques présents (header.php, footer.php, login.php, etc.)

### ❌ Problème actuel
- **Erreur 500** lors de l'accès à `https://shop.fosip-drc.org/`
- **Cause probable:** Fichier `config/config.php` mal configuré ou fonctions manquantes
- **Dernière étape:** Vérifier si `config.online.php` a été renommé en `config.php`

---

## 📂 FICHIERS CRITIQUES CRÉÉS POUR LE DÉPLOIEMENT

### 1. `database/storesuite_online.sql` ⭐
**Dump SQL corrigé pour le serveur de production**
- ✅ Colonne `password_hash` (au lieu de `mot_de_passe`)
- ✅ Vues sans `DEFINER=root@localhost` (avec `SQL SECURITY INVOKER`)
- ✅ 14 tables + 2 vues + triggers + données de démo
- ✅ Utilisateur admin créé (login: `admin`, password: hash de `admin`)

### 2. `config/config.online.php` ⭐⭐⭐
**Configuration pour le serveur de production**
```php
DB_HOST: 127.0.0.1
DB_NAME: fosip2610679_3lxbcd
DB_USER: fosip2610679
DB_PASS: mZ1-CDF**CC-TXh
BASE_URL: https://shop.fosip-drc.org/
SECRET_KEY: F7k9mP2nX#wL4v@Q8rT$y5jB0hGc3fDe1AZ7bM4sJ6pY9w
```
**⚠️ ACTION REQUISE:** Renommer en `config.php` sur le serveur!

### 3. `config/database.php` (mis à jour)
Fonctions ajoutées pour compatibilité:
- ✅ `is_logged_in()`
- ✅ `get_user_id()`
- ✅ `set_flash_message()` / `get_flash_message()`
- ✅ `redirect()`
- ✅ `generate_csrf_token()` / `verify_csrf_token()`
- ✅ `e()`, `format_montant()`, `format_date()`
- ✅ `die_error()`, `db_in_transaction()`

### 4. Scripts de diagnostic créés
- `diagnostic_500.php` - Test général (config, DB, uploads, fichiers)
- `diagnostic_remote.php` - Version simple sans emojis
- `test_pages.php` - Test chaque page (index, login, accueil)
- `debug_login.php` - Test fonctions login
- `check_config.php` - Vérifie config.php vs config.online.php

### 5. `DEPLOYMENT.md`
Guide complet pour 3 environnements (XAMPP local, serveur mutualisé, MAMP)

---

## 🔧 ACTIONS À FAIRE EN PRIORITÉ

### Sur le serveur (via FTP/cPanel)
1. **VÉRIFIER** si `config/config.php` existe
   - Si NON → Renommer `config.online.php` en `config.php`
   - Si OUI → Vérifier que les constantes sont correctes:
     ```php
     DB_HOST = 127.0.0.1
     DB_NAME = fosip2610679_3lxbcd
     DB_USER = fosip2610679
     DB_PASS = mZ1-CDF**CC-TXh
     BASE_URL = https://shop.fosip-drc.org/
     ```

2. **TESTER** via `https://shop.fosip-drc.org/check_config.php`
   - Cela indiquera si config.php est bien configuré

3. **TESTER** la connexion à `https://shop.fosip-drc.org/login.php`
   - Login: `admin`
   - Password: `admin`

4. **SI ERREUR 500 PERSISTE:**
   - Accéder à `https://shop.fosip-drc.org/debug_login.php`
   - Noter l'erreur exacte et la fonction qui échoue

---

## 🗄️ INFORMATIONS BASE DE DONNÉES

### Serveur MySQL
- **Host:** 127.0.0.1 (localhost sur hébergement mutualisé)
- **Database:** fosip2610679_3lxbcd
- **User:** fosip2610679
- **Password:** mZ1-CDF**CC-TXh
- **PHPMyAdmin:** https://mysql34.lwspanel.com/phpmyadmin

### Tables importantes
- `utilisateurs` - Colonne: `password_hash` (PAS `mot_de_passe`!)
- `ventes` - Colonnes: `montant_ht`, `montant_tva`, `montant_total`
- `configuration` - Ligne unique (id_config=1)
- `produits` - Table des produits avec gestion stock

### Comptes utilisateurs (dans la BD)
```sql
-- Admin (id=3)
login: admin
password: admin (hash: $2y$10$qcv4m7Sf5FsYTzk5sfFfe.TtdxPMvI3d5o1e4iv44HI0i/5JbYASy)
niveau_acces: 1 (ADMIN)

-- Vendeur (id=4)
login: fefe
password: [à définir]
niveau_acces: 2 (VENDEUR)
```

---

## 💻 COMMANDES GIT POUR NOUVELLE MACHINE

### 1. Configurer Git (première fois)
```bash
# Configurer identité
git config --global user.name "Votre Nom"
git config --global user.email "votre-email@example.com"

# Vérifier la configuration
git config --list
```

### 2. Connecter GitHub (authentification)
```bash
# Option A: HTTPS avec Personal Access Token (recommandé)
# 1. Créer un token sur GitHub:
#    Settings → Developer settings → Personal access tokens → Generate new token
#    Cocher: repo, workflow, write:packages

# 2. Cloner le repo
git clone https://github.com/VOTRE-USERNAME/STORESuite.git
# Entrer username + token comme password

# Option B: SSH (plus sécurisé)
# 1. Générer clé SSH
ssh-keygen -t ed25519 -C "votre-email@example.com"

# 2. Copier la clé publique
cat ~/.ssh/id_ed25519.pub

# 3. Ajouter sur GitHub:
#    Settings → SSH and GPG keys → New SSH key

# 4. Tester connexion
ssh -T git@github.com

# 5. Cloner
git clone git@github.com:VOTRE-USERNAME/STORESuite.git
```

### 3. Pull des derniers commits
```bash
cd STORESuite

# Récupérer les derniers commits
git pull origin main

# Si conflit, voir les fichiers
git status

# Résoudre conflits manuellement, puis
git add .
git commit -m "Résolu conflits"
git push origin main
```

### 4. Push de nouveaux changements
```bash
# Voir les modifications
git status

# Ajouter tous les fichiers modifiés
git add .

# OU ajouter fichiers spécifiques
git add config/database.php
git add database/storesuite_online.sql

# Commit avec message
git commit -m "Fix: Ajout fonctions manquantes dans database.php pour déploiement"

# Push vers GitHub
git push origin main
```

### 5. Fichiers à NE PAS committer (dans .gitignore)
```
config/config.php
config/config.online.php
uploads/logos/*
uploads/produits/*
uploads/utilisateurs/*
*.log
```

---

## 📋 CHECKLIST DÉPLOIEMENT

### Préparation locale
- [x] Base de données corrigée (storesuite_online.sql)
- [x] Config serveur créé (config.online.php)
- [x] Fonctions manquantes ajoutées (database.php)
- [x] Scripts de diagnostic créés
- [ ] **TODO:** Tester sur XAMPP local que tout fonctionne

### Sur le serveur
- [x] Fichiers uploadés dans `/public_html/shop.fosip-drc.org/`
- [x] Base de données importée (fosip2610679_3lxbcd)
- [x] Dossiers uploads créés (logos, produits, utilisateurs)
- [ ] **TODO:** Vérifier config.php existe et est correct
- [ ] **TODO:** Tester login.php
- [ ] **TODO:** Vérifier permissions fichiers (755 pour dossiers, 644 pour fichiers)

### Tests finaux
- [ ] Page de login accessible (https://shop.fosip-drc.org/login.php)
- [ ] Connexion admin fonctionne
- [ ] Tableau de bord s'affiche
- [ ] Ajout produit fonctionne
- [ ] Vente fonctionne
- [ ] Impression facture fonctionne

---

## 🔍 DIAGNOSTIC RAPIDE

Si erreur 500, suivre cet ordre:
1. `https://shop.fosip-drc.org/check_config.php` → Vérifie config.php
2. `https://shop.fosip-drc.org/diagnostic_500.php` → Teste tout (DB, fichiers, etc.)
3. `https://shop.fosip-drc.org/debug_login.php` → Teste fonctions login
4. `https://shop.fosip-drc.org/test_pages.php` → Teste chaque page

Chaque script affiche exactement où est le problème.

---

## 📞 INFORMATIONS DE CONNEXION

### Local (XAMPP)
- URL: http://localhost/STORESuite/
- DB: localhost, root, (vide), storesuite

### Production (Serveur)
- URL: https://shop.fosip-drc.org/
- DB: 127.0.0.1, fosip2610679, mZ1-CDF**CC-TXh, fosip2610679_3lxbcd
- FTP: [À compléter]
- cPanel: [À compléter]

---

## 🐛 PROBLÈMES RÉSOLUS

1. ✅ **Vues SQL avec DEFINER error** → Recréé avec `SQL SECURITY INVOKER`
2. ✅ **Colonne password vs password_hash** → Renommé dans SQL dump
3. ✅ **Fonctions manquantes** → Ajouté dans database.php:
   - is_logged_in(), get_user_id()
   - set_flash_message(), get_flash_message()
   - redirect(), generate_csrf_token(), verify_csrf_token()
   - e(), format_montant(), format_date()
4. ✅ **SECRET_KEY manquant** → Généré et ajouté dans config.online.php

---

## 📝 NOTES IMPORTANTES

1. **Ne JAMAIS committer** `config/config.php` sur Git (contient credentials)
2. **Toujours utiliser** `config.online.php` comme template pour production
3. **Session vars:** Le système utilise `$_SESSION['id_utilisateur']` (PAS `user_id`)
4. **BASE_URL:** Doit finir par `/` (slash obligatoire)
5. **TVA:** Calculée à 16% sur tous les montants

---

## 🎯 OBJECTIF FINAL

Site fonctionnel sur https://shop.fosip-drc.org/ avec:
- ✅ Login admin/vendeur
- ✅ Gestion produits
- ✅ Gestion clients
- ✅ Point de vente
- ✅ Facturation
- ✅ Rapports

---

**Dernière mise à jour:** 12 janvier 2026  
**Prochaine étape:** Vérifier et corriger config.php sur le serveur, puis tester login
