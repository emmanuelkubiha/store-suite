# DEPLOYEMENT STORESUITE

Ce guide explique comment configurer l'application en local (XAMPP), sur un hébergement mutualisé (vos accès fournis) et sur Mac avec MAMP, sans modifier immédiatement les fichiers sensibles. Les valeurs ci-dessous sont à renseigner dans `config/config.php` avant mise en ligne.

## 1) Fichiers à ajuster
- `config/config.php`
  - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_CHARSET`
  - `BASE_URL` (URL publique incluant le dossier racine, avec / final)
  - `SECRET_KEY` (générer une nouvelle clé aléatoire en production)
  - `SESSION_LIFETIME` (conserver 7200s ou ajuster selon la politique de sécurité)
- `config/database.php`
  - Ne pas modifier : il consomme uniquement les constantes ci-dessus.

## 2) Pré-requis communs
- PHP 8.0+ avec `pdo_mysql` activé.
- Base MySQL importée : exécuter `database/storesuite.sql` puis `database/migration_tva.sql`.
- Droits d'écriture sur `uploads/` (logos, produits, utilisateurs).
- Fuseau horaire utilisé : `Africa/Lubumbashi` (déjà défini dans `config.php`).

## 3) Configuration par environnement

### A. Local Windows (XAMPP)
- Host: `localhost`
- Port MySQL: 3306 (défaut XAMPP)
- DB: `storesuite`
- User: `root`
- Pass: (vide par défaut)
- `BASE_URL`: `http://localhost/STORESuite/`
- Action : importer `database/storesuite.sql` puis `database/migration_tva.sql` via phpMyAdmin local.

### B. Hébergement mutualisé (vos accès fournis)
- Host: `127.0.0.1`
- DB: `fosip2610679_3lxbcd`
- User: `fosip2610679`
- Pass: `mZ1-CDF**CC-TXh`
- `BASE_URL`: renseigner l'URL publique exacte (ex: `https://votredomaine.com/` ou `https://votredomaine.com/STORESuite/`).
- Actions :
  1. Importer `database/storesuite.sql` puis `database/migration_tva.sql` via https://mysql34.lwspanel.com/phpmyadmin.
  2. Mettre à jour `config/config.php` avec les valeurs ci-dessus.
  3. Générer une nouvelle `SECRET_KEY` (32+ caractères alphanumériques).
  4. S'assurer que `uploads/` est inscriptible (chmod 755 ou 775 selon l'hébergeur).

### C. Mac (MAMP)
- Host: `localhost`
- Port MySQL MAMP: 8889
- DB: créer `storesuite` (ou autre nom et l'ajuster dans `DB_NAME`)
- User: `root`
- Pass: `root`
- `BASE_URL`: `http://localhost:8888/STORESuite/` (port Apache MAMP par défaut)
- Actions :
  1. Importer `database/storesuite.sql` puis `database/migration_tva.sql` dans la base choisie.
  2. Mettre `DB_HOST` à `localhost` et ajouter `;port=8889` dans le DSN si vous modifiez `database.php` (optionnel). Plus simple : changer `DB_HOST` en `localhost:8889` dans `config.php` (suffit pour PDO).
  3. Adapter `BASE_URL` avec le port 8888.

## 4) Étapes de déploiement (résumé)
1. Créer la base de données sur la cible.
2. Importer `database/storesuite.sql` puis `database/migration_tva.sql`.
3. Copier le code du dossier `STORESuite/` sur le serveur (respecter la casse du dossier dans l'URL).
4. Éditer `config/config.php` avec les valeurs de l'environnement.
5. Générer et mettre une nouvelle `SECRET_KEY` en production.
6. Vérifier les permissions d'écriture sur `uploads/` et sous-dossiers.
7. Tester l'authentification, une vente (TVA 16%) et l'impression de facture.

## 5) Points de sécurité
- Ne pas committer `config/config.php` avec des identifiants réels.
- Mettre `display_errors` à off (déjà le cas si `DEBUG_MODE` n'est pas défini à true).
- Forcer HTTPS sur le domaine final (via `.htaccess` si nécessaire).
- Changer les mots de passe fournis après déploiement initial.

## 6) Checklists rapides
- **BDD importée** : oui / non
- **Config DB mise à jour** : oui / non
- **BASE_URL ajustée** : oui / non
- **SECRET_KEY régénérée** : oui / non
- **uploads/ inscriptible** : oui / non
- **Tests vente + facture** : oui / non

---
Rappel : `config/database.php` utilise uniquement les constantes de `config/config.php`. Mettez à jour ces constantes par environnement, sans toucher le reste du code. Pensez à régénérer la clé secrète et à ne pas versionner les secrets.
