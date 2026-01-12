# 🔄 GUIDE GIT - REPRISE SUR NOUVELLE MACHINE

## 📥 ÉTAPE 1: INSTALLER GIT

### Windows
1. Télécharger: https://git-scm.com/download/win
2. Installer avec options par défaut
3. Vérifier: `git --version` dans PowerShell

### Configurer Git (obligatoire première fois)
```powershell
# Votre identité (visible dans les commits)
git config --global user.name "Votre Nom Complet"
git config --global user.email "votre-email@gmail.com"

# Vérifier
git config --list
```

---

## 🔐 ÉTAPE 2: CONNECTER GITHUB

### Option A: HTTPS avec Token (Recommandé - Plus simple)

#### 1. Créer un Personal Access Token sur GitHub
1. Aller sur https://github.com/settings/tokens
2. Cliquer **"Generate new token"** → **"Generate new token (classic)"**
3. Nom du token: `STORESUITE-DEV`
4. Expiration: `90 days` (ou `No expiration`)
5. Cocher les permissions:
   - ✅ `repo` (tout)
   - ✅ `workflow`
   - ✅ `write:packages`
6. Cliquer **"Generate token"**
7. **COPIER LE TOKEN IMMÉDIATEMENT** (vous ne le reverrez plus!)
   - Exemple: `ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

#### 2. Cloner le repository
```powershell
# Naviguer vers le dossier où vous voulez le projet
cd C:\xampp\htdocs

# Cloner (remplacer VOTRE-USERNAME par votre nom d'utilisateur GitHub)
git clone https://github.com/VOTRE-USERNAME/STORESuite.git

# Quand demandé:
# Username: votre-username-github
# Password: [coller le token généré]
```

#### 3. Sauvegarder les credentials (pour ne pas retaper à chaque fois)
```powershell
cd STORESuite

# Windows: Utiliser Credential Manager
git config --global credential.helper manager

# Ou spécifique au repo
git config credential.helper store
```

---

### Option B: SSH (Plus sécurisé - Recommandé pour long terme)

#### 1. Générer une clé SSH
```powershell
# Générer la clé (appuyez Enter pour tout accepter par défaut)
ssh-keygen -t ed25519 -C "votre-email@gmail.com"

# Si ed25519 ne marche pas, utiliser RSA
ssh-keygen -t rsa -b 4096 -C "votre-email@gmail.com"

# Fichiers créés dans C:\Users\VotreNom\.ssh\
# - id_ed25519 (clé privée - NE JAMAIS PARTAGER)
# - id_ed25519.pub (clé publique - à ajouter sur GitHub)
```

#### 2. Copier la clé publique
```powershell
# Afficher et copier le contenu
cat ~/.ssh/id_ed25519.pub

# Ou ouvrir avec notepad
notepad C:\Users\VotreNom\.ssh\id_ed25519.pub
```

#### 3. Ajouter la clé sur GitHub
1. Aller sur https://github.com/settings/keys
2. Cliquer **"New SSH key"**
3. Titre: `PC-DEV-2` (ou nom de votre machine)
4. Key type: `Authentication Key`
5. Coller la clé publique complète (commence par `ssh-ed25519` ou `ssh-rsa`)
6. Cliquer **"Add SSH key"**

#### 4. Tester la connexion
```powershell
# Tester (dire "yes" si demandé)
ssh -T git@github.com

# Résultat attendu:
# Hi VotreUsername! You've successfully authenticated...
```

#### 5. Cloner avec SSH
```powershell
cd C:\xampp\htdocs

# Cloner (remplacer par votre username)
git clone git@github.com:VOTRE-USERNAME/STORESuite.git
```

---

## 📡 ÉTAPE 3: PULL - RÉCUPÉRER LES COMMITS DISTANTS

```powershell
# Entrer dans le dossier du projet
cd C:\xampp\htdocs\STORESuite

# Vérifier l'état actuel
git status

# Vérifier quelle branche vous êtes
git branch

# Récupérer les derniers commits depuis GitHub
git pull origin main

# Si vous avez des modifications locales et qu'il y a conflit:
# Option 1: Garder vos modifications locales
git stash                    # Mettre de côté vos modifs
git pull origin main         # Récupérer les commits distants
git stash pop                # Réappliquer vos modifs

# Option 2: Écraser vos modifications locales
git reset --hard origin/main  # ⚠️ ATTENTION: Perd vos modifs locales!
```

---

## 📤 ÉTAPE 4: COMMIT & PUSH - ENVOYER VOS MODIFICATIONS

### Workflow complet

```powershell
# 1. Voir ce qui a changé
git status

# 2. Ajouter les fichiers modifiés
git add .                           # Ajouter TOUS les fichiers
# OU
git add fichier1.php fichier2.php   # Ajouter fichiers spécifiques

# 3. Vérifier ce qui sera commité
git status

# 4. Créer un commit avec message descriptif
git commit -m "Fix: Correction erreur 500 - Ajout fonctions manquantes dans database.php"

# 5. Envoyer vers GitHub
git push origin main
```

### Exemples de messages de commit
```bash
# Correction de bug
git commit -m "Fix: Correction erreur 500 sur login.php"

# Nouvelle fonctionnalité
git commit -m "Feature: Ajout export Excel des ventes"

# Mise à jour documentation
git commit -m "Docs: Mise à jour guide de déploiement"

# Refactoring
git commit -m "Refactor: Nettoyage code protection_pages.php"

# Configuration
git commit -m "Config: Ajout fichier config.online.php pour production"
```

---

## 🔀 ÉTAPE 5: GÉRER LES CONFLITS

### Si conflit lors du pull
```powershell
git pull origin main
# Erreur: CONFLICT (content): Merge conflict in fichier.php

# 1. Ouvrir le fichier en conflit dans VS Code
# Le fichier contiendra:
# <<<<<<< HEAD
# Votre version locale
# =======
# Version distante (GitHub)
# >>>>>>> origin/main

# 2. Choisir quelle version garder (supprimer les marqueurs)

# 3. Marquer comme résolu
git add fichier.php

# 4. Finaliser le merge
git commit -m "Merge: Résolu conflit dans fichier.php"

# 5. Push
git push origin main
```

---

## 📊 COMMANDES GIT UTILES

```powershell
# Voir l'historique des commits
git log
git log --oneline              # Version courte
git log --graph --oneline      # Avec graphe

# Voir les différences
git diff                       # Modifications non staged
git diff --staged              # Modifications staged
git diff HEAD                  # Toutes les modifications

# Annuler des modifications
git checkout -- fichier.php    # Annuler modifs d'un fichier
git reset HEAD fichier.php     # Unstage un fichier

# Voir les branches
git branch                     # Branches locales
git branch -a                  # Toutes les branches

# Changer de branche
git checkout nom-branche
git checkout -b nouvelle-branche  # Créer et basculer

# Mettre à jour depuis GitHub sans merger
git fetch origin
git status                     # Voir si vous êtes en retard

# Voir l'URL du repository distant
git remote -v
```

---

## 🚫 FICHIERS À NE JAMAIS COMMITTER

### Créer/Mettre à jour `.gitignore`
```gitignore
# Fichiers de configuration avec credentials
config/config.php
config/config.online.php

# Uploads (images des produits/utilisateurs)
uploads/logos/*
uploads/produits/*
uploads/utilisateurs/*
!uploads/logos/.gitkeep
!uploads/produits/.gitkeep
!uploads/utilisateurs/.gitkeep

# Logs
*.log
logs/

# Cache
*.cache

# OS files
.DS_Store
Thumbs.db
desktop.ini

# IDE
.vscode/
.idea/
*.swp
*.swo

# Temporary files
tmp/
temp/
```

### Si vous avez déjà commité un fichier sensible
```powershell
# Supprimer du git (mais garder local)
git rm --cached config/config.php

# Commit
git commit -m "Remove: Suppression config.php du repository"

# Push
git push origin main
```

---

## 🔄 WORKFLOW QUOTIDIEN RECOMMANDÉ

```powershell
# Le matin (ou début de session)
cd C:\xampp\htdocs\STORESuite
git pull origin main           # Récupérer derniers commits

# Pendant le travail
# ... faire vos modifications ...

# Voir ce qui a changé régulièrement
git status

# À la fin de la session (ou toutes les heures)
git add .
git commit -m "Description des changements"
git push origin main

# Avant de quitter
git status                     # Vérifier qu'il n'y a rien en attente
```

---

## 🆘 PROBLÈMES COURANTS

### "fatal: not a git repository"
```powershell
# Vous n'êtes pas dans le bon dossier
cd C:\xampp\htdocs\STORESuite
```

### "Permission denied (publickey)"
```powershell
# Votre clé SSH n'est pas configurée
# Refaire l'étape 2 Option B
```

### "Your branch is behind 'origin/main'"
```powershell
# Vous devez pull
git pull origin main
```

### "Please commit your changes or stash them"
```powershell
# Vous avez des modifs non commitées
# Option 1: Commit
git add .
git commit -m "WIP: Travail en cours"

# Option 2: Stash (mettre de côté)
git stash
git pull origin main
git stash pop
```

### "Merge conflict"
```powershell
# Voir les fichiers en conflit
git status

# Ouvrir dans VS Code, résoudre manuellement
# Puis:
git add .
git commit -m "Merge: Résolu conflits"
git push origin main
```

---

## 📱 VÉRIFIER VOTRE CONFIGURATION

```powershell
# Afficher toute la config Git
git config --list

# Vérifier votre identité
git config user.name
git config user.email

# Vérifier les remotes
git remote -v

# Vérifier la branche actuelle
git branch

# Vérifier le statut
git status
```

---

## 🎯 CHECKLIST NOUVELLE MACHINE

- [ ] Git installé (`git --version`)
- [ ] Identité configurée (`git config user.name` et `user.email`)
- [ ] GitHub connecté (HTTPS token OU SSH key)
- [ ] Repository cloné dans `C:\xampp\htdocs\STORESuite`
- [ ] `.gitignore` présent et config.php dedans
- [ ] Test pull: `git pull origin main` fonctionne
- [ ] Test push: Créer fichier test, commit, push

---

**Prêt à coder!** 🚀

Pour toute question, consulter: https://git-scm.com/doc
