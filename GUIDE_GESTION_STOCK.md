# 📦 GUIDE COMPLET - SYSTÈME DE GESTION DE STOCK MULTI-DÉPÔTS

**Date:** 15 janvier 2026  
**Auteur:** Emmanuel Kubiha  
**Système:** STORESUITE

---

## 🎯 VUE D'ENSEMBLE

Le système de gestion de stock permet de :
- Tracer tous les mouvements de produits
- Gérer plusieurs emplacements de stockage (dépôts)
- Suivre l'historique complet (qui, quand, quoi, où, pourquoi)
- Effectuer des transferts entre dépôts
- Générer des rapports et impressions

---

## 🗄️ BASE DE DONNÉES

### **Nouvelles Tables Créées**

#### 1. **`fournisseurs`** - Gestion des fournisseurs
```sql
Colonnes principales:
- id_fournisseur (PK)
- nom_fournisseur
- contact
- telephone
- email
- adresse
- est_actif
- date_creation
```

#### 2. **`depots`** - Emplacements de stockage
```sql
Colonnes principales:
- id_depot (PK)
- nom_depot (ex: "Magasin", "Dépôt A", "Entrepôt")
- description
- adresse
- est_principal (BOOLEAN - 1 seul dépôt principal)
- est_actif
- date_creation
```

**Note:** Par défaut, un dépôt "Magasin" (ID=1) est créé comme dépôt principal.

#### 3. **`stock_par_depot`** - Stock détaillé par emplacement
```sql
Colonnes principales:
- id_stock (PK)
- id_produit (FK -> produits)
- id_depot (FK -> depots)
- quantite (INT)
- date_modification (auto-update)

Index unique: (id_produit, id_depot)
```

**Fonctionnement:** Chaque produit peut avoir du stock dans plusieurs dépôts.

### **Tables Modifiées**

#### 4. **`mouvements_stock`** - Historique enrichi
```sql
Nouvelles colonnes ajoutées:
- id_depot_source (FK -> depots) - D'où vient le mouvement
- id_depot_destination (FK -> depots) - Où va le mouvement (pour transferts)
- id_fournisseur (FK -> fournisseurs) - Fournisseur concerné
- cout_unitaire (DECIMAL) - Coût par unité
- cout_total (DECIMAL) - Coût total du mouvement

Types de mouvements (type_mouvement):
- 'entree' : Réception de marchandise
- 'sortie' : Sortie (autre que vente)
- 'ajustement' : Correction de stock
- 'transfert' : Déplacement entre dépôts
- 'inventaire' : Comptage physique
- 'perte' : Casse, vol, péremption
```

#### 5. **`produits`** - Lien fournisseur
```sql
Nouvelle colonne:
- id_fournisseur_principal (FK -> fournisseurs) - Fournisseur par défaut
```

### **Vues SQL Créées**

#### **`vue_stock_global`** - Vue globale du stock
```sql
Affiche pour chaque produit:
- Quantité totale tous dépôts
- Quantité au magasin principal
- Quantité dans les autres dépôts
- Nombre de dépôts où le produit est stocké
```

#### **`vue_inventaire_complet`** - Inventaire détaillé
```sql
Vue complète incluant:
- Informations produit
- Catégorie
- Stock par emplacement
- Seuils d'alerte
```

#### **`vue_mouvements_stock_detail`** - Historique détaillé
```sql
Vue enrichie des mouvements avec:
- Nom du produit
- Nom des dépôts (source et destination)
- Nom de l'utilisateur
- Toutes les informations de traçabilité
```

### **Triggers Automatiques**

#### **`sync_stock_after_insert`** - Synchronisation automatique
```sql
AFTER INSERT sur stock_par_depot
→ Met à jour automatiquement produits.quantite_stock
```

#### **`sync_stock_after_update`** - Mise à jour automatique
```sql
AFTER UPDATE sur stock_par_depot
→ Recalcule produits.quantite_stock quand le stock change
```

#### **`sync_stock_after_delete`** - Suppression
```sql
AFTER DELETE sur stock_par_depot
→ Recalcule le stock total
```

**Avantage:** Le champ `quantite_stock` dans `produits` reste toujours à jour automatiquement !

---

## 📁 FICHIERS CRÉÉS/MODIFIÉS

### **Pages Principales**

#### 1. **`mouvements_stock.php`**
**Rôle:** Page d'historique des mouvements  
**Accès:** Admin + Vendeur  
**Fonctionnalités:**
- Affichage de tous les mouvements
- Filtres avancés (type, produit, dépôt, période, utilisateur)
- Statistiques (30 derniers jours)
- Bouton "Nouveau Mouvement" (admin uniquement)
- Impression stylée
- Export (à venir)
- Pagination (50 mouvements par page)

**Éléments clés:**
```php
// Variable pour désactiver le loader
$skip_page_loader = true;

// Statistiques affichées
- Total mouvements (30j)
- Total entrées
- Total sorties
- Transferts effectués
```

#### 2. **`impression_mouvements.php`**
**Rôle:** Page d'impression professionnelle  
**Fonctionnalités:**
- Design professionnel avec logo
- Tableau stylé avec badges colorés
- Pied de page avec infos
- Bouton "Imprimer/PDF"
- Reprend tous les filtres de mouvements_stock.php

**Style:**
- En-tête avec logo et nom boutique
- Informations du rapport (date, période, filtres)
- Tableau avec alternance de couleurs
- Badges colorés par type de mouvement
- Optimisé pour l'impression

#### 3. **`rapports.php`** (modifié)
**Ajouts:**
Section "Rapports de Gestion de Stock" avec 4 cartes:
1. **Inventaire par dépôt** - Stock par emplacement
2. **Mouvements de stock** - Historique avec filtrage date
3. **Valeur du stock** (Admin) - Valorisation financière
4. **Alertes & Ruptures** - Produits en alerte

**Fonctionnalités:**
- Boutons Excel, PDF, Voir (modal)
- Modal de sélection de dépôt pour l'inventaire
- Intégration avec ajax/get_report.php

### **Endpoints AJAX**

#### 1. **`ajax/ajouter_mouvement.php`**
**Rôle:** Créer un nouveau mouvement de stock  
**Méthode:** POST  
**Accès:** Admin uniquement  
**Paramètres:**
```javascript
{
    type_mouvement: 'entree|sortie|ajustement|transfert|inventaire|perte',
    id_produit: int,
    id_depot_source: int,
    id_depot_destination: int (requis si transfert),
    quantite: int (positif),
    cout_unitaire: decimal (optionnel),
    date_mouvement: 'YYYY-MM-DD',
    notes: string
}
```

**Logique:**
1. Validation des données
2. Gestion des quantités négatives (sortie, perte)
3. Insertion dans `mouvements_stock`
4. Mise à jour de `stock_par_depot`
5. Pour transferts: met à jour 2 dépôts
6. Les triggers synchronisent `produits.quantite_stock`

#### 2. **`ajax/get_depots.php`**
**Rôle:** Liste des dépôts actifs  
**Méthode:** GET  
**Retour:** JSON avec tous les dépôts

#### 3. **`ajax/get_report.php`** (modifié)
**Ajouts:** 4 nouveaux types de rapports
- `inventaire_depot` - Avec paramètre id_depot (all ou ID spécifique)
- `mouvements_stock` - Historique filtré par dates
- `valeur_stock` - Valorisation par dépôt (admin)
- `alertes_stock` - Produits en alerte/rupture

### **Fichiers Database**

#### **`database/migration_gestion_stock_complete.sql`**
**Rôle:** Migration SQL complète  
**Contenu:**
- Création des 3 nouvelles tables
- Modification de mouvements_stock et produits
- Création des 3 vues
- Création des 3 triggers
- Insertion du dépôt par défaut "Magasin"

**Exécution:**
```bash
/Applications/MAMP/Library/bin/mysql80/bin/mysql -uroot -proot storesuite < database/migration_gestion_stock_complete.sql
```

---

## 🎮 COMMENT UTILISER LE SYSTÈME

### **1. Créer un Nouveau Mouvement de Stock**

**Accès:** Menu "Mouvements de stock" → Bouton "Nouveau Mouvement" (admin)

**Étapes:**
1. Cliquer sur "Nouveau Mouvement"
2. Sélectionner le **type de mouvement**:
   - **Entrée** : Réception de marchandise (ex: livraison fournisseur)
   - **Sortie** : Sortie autre que vente (ex: don, échantillon)
   - **Ajustement** : Correction de stock (ex: erreur de comptage)
   - **Transfert** : Déplacement entre dépôts
   - **Inventaire** : Résultat de comptage physique
   - **Perte** : Casse, vol, produit périmé

3. Sélectionner le **produit** concerné
4. Choisir le **dépôt source**
5. Si transfert: choisir le **dépôt destination**
6. Saisir la **quantité** (toujours positive)
7. Optionnel: Coût unitaire
8. Optionnel: Notes/Motif
9. Cliquer sur "Enregistrer"

**Exemple - Réception marchandise:**
```
Type: Entrée
Produit: Coca-Cola 1.5L
Dépôt source: Magasin
Quantité: 100
Coût unitaire: 500
Notes: Livraison fournisseur ABC
```

**Exemple - Transfert:**
```
Type: Transfert
Produit: Coca-Cola 1.5L
Dépôt source: Magasin
Dépôt destination: Dépôt A
Quantité: 20
Notes: Réapprovisionnement dépôt A
```

### **2. Consulter l'Historique**

**Accès:** Menu "Mouvements de stock"

**Filtres disponibles:**
- **Type** : Filtrer par type de mouvement
- **Produit** : Recherche par nom/code produit
- **Dépôt** : Mouvements concernant un dépôt
- **Période** : Date début → Date fin
- **Utilisateur** : Mouvements d'un utilisateur spécifique

**Actions:**
- **Imprimer** : Ouvre la page d'impression stylée
- **Exporter** : Export Excel (à venir)

### **3. Générer des Rapports**

**Accès:** Menu "Rapports" → Section "Rapports de Gestion de Stock"

#### **Rapport: Inventaire par Dépôt**
1. Cliquer sur "Voir"
2. Sélectionner un dépôt ou "Tous les dépôts"
3. Consulter le stock par emplacement
4. Export Excel/PDF disponible

#### **Rapport: Mouvements de Stock**
- Vue de l'historique avec date de début/fin
- Affiche les 100 derniers mouvements

#### **Rapport: Valeur du Stock** (Admin uniquement)
- Valorisation financière du stock
- Par dépôt
- Valeur achat vs valeur vente
- Marge potentielle

#### **Rapport: Alertes & Ruptures**
- Produits en rupture (quantité = 0)
- Produits en niveau critique
- Produits en alerte
- Détail par dépôt

### **4. Imprimer un Historique**

**Méthode:**
1. Appliquer les filtres souhaités sur mouvements_stock.php
2. Cliquer sur "Imprimer"
3. Une nouvelle fenêtre s'ouvre avec une mise en page professionnelle
4. Utiliser Ctrl+P / Cmd+P ou le bouton "Imprimer/PDF"
5. Choisir "Enregistrer en PDF" pour garder une copie

---

## 🔧 FONCTIONNALITÉS À VENIR

### **Prochaines étapes (Priorité HAUTE):**

1. **Onglet "Dépôts" dans listes.php**
   - CRUD complet des dépôts
   - Activation/Désactivation
   - Gestion du dépôt principal

2. **Modification du formulaire d'ajout de produit**
   - Sélection du fournisseur principal
   - Choix du dépôt initial
   - Quantité initiale par dépôt

3. **Exports Excel fonctionnels**
   - Modifier ajax/export_excel.php
   - Ajouter les 4 nouveaux types de rapports

4. **Templates PDF**
   - Modifier rapport_affichage.php
   - Génération PDF des rapports de stock

### **Améliorations futures:**

- Gestion des alertes automatiques (email/notification)
- Dashboard stock sur accueil.php
- Graphiques d'évolution du stock
- Prédictions de rupture
- Gestion des lots/numéros de série
- Code-barres pour mouvements rapides

---

## 📊 SCHÉMA DE FLUX

### **Flux: Créer un Mouvement**

```
Utilisateur (Admin)
    ↓
mouvements_stock.php
    ↓ (Clic "Nouveau Mouvement")
Modal de saisie
    ↓ (Submit form)
ajax/ajouter_mouvement.php
    ↓
1. Validation données
2. INSERT dans mouvements_stock
3. UPDATE/INSERT dans stock_par_depot
    ↓
Triggers MySQL (automatique)
    ↓
UPDATE produits.quantite_stock
    ↓
Retour JSON success
    ↓
Rechargement de la page
```

### **Flux: Synchronisation Stock**

```
Action sur stock_par_depot
    ↓
Trigger: sync_stock_after_insert/update/delete
    ↓
Calcul: SUM(quantite) GROUP BY id_produit
    ↓
UPDATE produits.quantite_stock = nouvelle_valeur
    ↓
Stock global à jour !
```

---

## ⚠️ POINTS IMPORTANTS

### **Règles de Gestion**

1. **Quantités toujours positives dans le formulaire**
   - Le système applique le signe selon le type
   - Entrées/Ajustements positifs : quantité > 0
   - Sorties/Pertes : quantité < 0

2. **Un seul dépôt principal**
   - Généralement "Magasin" ou "Point de vente"
   - Ne peut pas être désactivé

3. **Transferts**
   - Requièrent dépôt source ET destination
   - Ne peuvent pas être vers le même dépôt
   - Mettent à jour 2 emplacements simultanément

4. **Triggers automatiques**
   - NE PAS modifier manuellement `produits.quantite_stock`
   - Toujours passer par `stock_par_depot`
   - Le système se synchronise automatiquement

5. **Traçabilité complète**
   - Chaque mouvement enregistre l'utilisateur
   - Date/heure automatique
   - Notes pour justification

### **Sécurité**

- Seuls les **admins** peuvent créer des mouvements
- Tous les utilisateurs peuvent consulter l'historique
- Pas de suppression de mouvements (traçabilité)
- Transactions SQL pour garantir la cohérence

### **Performance**

- Index sur les FK (id_produit, id_depot, etc.)
- Vues SQL pré-calculées
- Pagination sur l'historique
- Limite de 500 mouvements à l'impression

---

## 🆘 DÉPANNAGE

### **Le loader tourne indéfiniment**
**Solution:** Ajouter `$skip_page_loader = true;` avant `require_once('header.php');`

### **Le stock ne se met pas à jour**
**Vérification:**
1. Les triggers existent : `SHOW TRIGGERS LIKE 'stock_par_depot';`
2. Le stock_par_depot est bien rempli
3. Tester manuellement : `SELECT SUM(quantite) FROM stock_par_depot WHERE id_produit = X`

### **Erreur "Dépôt introuvable"**
**Solution:** Vérifier que le dépôt par défaut existe :
```sql
SELECT * FROM depots WHERE id_depot = 1;
```
Si absent, réexécuter la migration.

### **Les rapports ne s'affichent pas**
**Vérification:**
1. ajax/get_report.php accessible
2. ajax/get_depots.php retourne des données
3. Console navigateur pour erreurs JS

---

## 📝 NOTES DE DÉVELOPPEMENT

**Conventions de code:**
- Noms de variables en français
- Fonctions helper de database.php utilisées partout
- Tous les formulaires utilisent showAlertModal() (pas de alert())
- Protection CSRF à ajouter dans une version future

**Architecture:**
- Approche MVC simplifiée
- Séparation logique/présentation
- Réutilisation des composants (header/footer)
- Modal Bootstrap 5

**Base de données:**
- UTF-8 partout
- InnoDB (support transactions)
- Clés étrangères avec CASCADE
- Timestamps automatiques

---

## 📞 SUPPORT

Pour toute question ou problème:
- Consulter ce guide en premier
- Vérifier SESSION_TRAVAIL.md pour problèmes connus
- Examiner les logs dans la console navigateur
- Tester les endpoints AJAX individuellement

---

**Fin du guide - Version 1.0**  
**Dernière mise à jour:** 15 janvier 2026
