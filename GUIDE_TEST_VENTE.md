# GUIDE DE TEST - VENTE.PHP

## 🧪 CHECKLIST DE TEST

### ✅ Test 1: Affichage Page
- [ ] Accédez à `http://localhost/STORESuite/vente.php`
- [ ] Page charge sans erreur
- [ ] Panier affiche "Panier vide"
- [ ] Produits s'affichent avec images (si disponibles)
- [ ] Console JavaScript sans erreur (F12)

### ✅ Test 2: Recherche Produit
- [ ] Cliquez sur champ recherche
- [ ] Tapez un nom de produit → filtre en temps réel
- [ ] Tapez un code-barre (si produits en ont)
- [ ] Videz recherche → tous les produits reviennent
- [ ] **Raccourci:** Ctrl+F → focus sur recherche

### ✅ Test 3: Modal Ajout Panier
- [ ] Cliquez sur un produit
- [ ] Modal s'ouvre avec:
  - [ ] Nom produit affiché
  - [ ] Prix par défaut = prix_vente du produit
  - [ ] Quantité par défaut = 1
  - [ ] Stock disponible affiché
  - [ ] Sous-total calculé automatiquement
- [ ] Modifiez prix → sous-total recalcule
- [ ] Modifiez quantité → sous-total recalcule
- [ ] Cliquez +/- pour augmenter/diminuer quantité
- [ ] Tentez quantité > stock → erreur affichée
- [ ] Cliquez "Ajouter au panier"
- [ ] Message succès s'affiche
- [ ] Modal se ferme

### ✅ Test 4: Panier
- [ ] Produit apparaît dans le panier avec:
  - [ ] Nom exact
  - [ ] Prix unitaire modifié (si changé)
  - [ ] Quantité exacte
  - [ ] Sous-total correct
- [ ] Modifiez quantité inline → panier se recalcule
- [ ] Modifiez prix inline → panier se recalcule
- [ ] Cliquez X pour retirer produit
- [ ] Confirmation s'affiche
- [ ] Produit disparaît du panier

### ✅ Test 5: Calculs TVA
- [ ] Ajouter produit 100 au panier (1 unité)
- [ ] Vérifiez:
  - [ ] Sous-total HT = 100
  - [ ] TVA 16% = 16
  - [ ] Total TTC = 116
- [ ] Modifiez quantité à 2
- [ ] Vérifiez:
  - [ ] Sous-total HT = 200
  - [ ] TVA 16% = 32
  - [ ] Total TTC = 232
- [ ] Modifiez prix à 50
- [ ] Vérifiez:
  - [ ] Sous-total HT = 100
  - [ ] TVA 16% = 16
  - [ ] Total TTC = 116

### ✅ Test 6: Actions Panier
- [ ] Ajouter plusieurs produits
- [ ] Cliquez "Vider le panier"
- [ ] Confirmation affichée
- [ ] Panier se vide
- [ ] Message "Panier vide" revient
- [ ] Boutons "Valider" et "Facture Proforma" se désactivent

### ✅ Test 7: Sélection Client
- [ ] Sélectionnez "Client Comptoir" → panier fonctionne
- [ ] Sélectionnez un client → panier fonctionne
- [ ] Changez de client → panier reste intact

### ✅ Test 8: Validation Vente
- [ ] Ajouter 1 produit au panier (vérifiez le stock initial)
- [ ] Cliquez "Valider la vente" (F2)
- [ ] Confirmation affichée avec montant TTC
- [ ] Cliquez "Valider la vente"
- [ ] ⏳ Attendre réponse serveur
- [ ] Message succès avec numéro facture affiché
- [ ] Facture s'ouvre en nouvel onglet
- [ ] Panier se vide
- [ ] ✅ Vérifier en BD: vente créée avec bon montant_total, montant_ht, montant_tva

### ✅ Test 9: Facture Impression
- [ ] Facture affiche:
  - [ ] Nom boutique et logo
  - [ ] N° facture
  - [ ] Date/heure
  - [ ] Nom client (ou "Vente comptoir")
  - [ ] Vendeur connecté
  - [ ] Tous les articles
  - [ ] Montant HT
  - [ ] TVA 16% (en chiffre)
  - [ ] Montant TTC en évidence
  - [ ] Mode paiement
- [ ] Cliquez "Imprimer"
- [ ] Dialog impression s'ouvre
- [ ] Aperçu affiche bien la facture
- [ ] Annulez impression
- [ ] Cliquez "Fermer"
- [ ] Onglet se ferme

### ✅ Test 10: Base de Données
```sql
-- Vérifier la vente créée
SELECT * FROM ventes WHERE numero_facture LIKE 'FAC-%' ORDER BY id_vente DESC LIMIT 1;

-- Vérifier les détails
SELECT * FROM details_vente WHERE id_vente = [ID_VENTE_CRÉÉE];

-- Vérifier les mouvements
SELECT * FROM mouvements_stock WHERE id_vente = [ID_VENTE_CRÉÉE] AND type_mouvement = 'sortie';

-- Vérifier le stock mis à jour
SELECT quantite_stock FROM produits WHERE id_produit = [ID_PRODUIT_VENDU];
```

### ✅ Test 11: Erreurs
- [ ] Tentez vendre plus que le stock → erreur affichée
- [ ] Tentez valider sans produits → bouton désactivé
- [ ] Tentez changements prix négatifs → rejeté
- [ ] Tentez quantité 0 → rejeté

### ✅ Test 12: Raccourcis Clavier
- [ ] **Ctrl+F** → recherche se focus
- [ ] **F2** (panier rempli) → valide vente
- [ ] **F3** → vide panier
- [ ] **Échap** → recherche se focus

## 📊 VÉRIFICATIONS BD CRITIQUES

Après chaque vente validée:

```sql
-- 1. Vente créée avec bons montants
SELECT * FROM ventes WHERE numero_facture = 'FAC-20260109-XXXX';
-- Vérifier: montant_ht, montant_tva (ht*0.16), montant_total (ht+tva)

-- 2. Détails de vente créés
SELECT d.*, p.nom_produit 
FROM details_vente d 
JOIN produits p ON d.id_produit = p.id_produit 
WHERE d.id_vente = [ID];
-- Vérifier: quantité exacte, prix_unitaire exact

-- 3. Mouvements enregistrés
SELECT * FROM mouvements_stock 
WHERE id_vente = [ID] AND type_mouvement = 'sortie';
-- Vérifier: quantité = quantité vendue, motif = numéro facture

-- 4. Stock mis à jour
SELECT quantite_stock FROM produits WHERE id_produit = [ID];
-- Vérifier: stock réduit de la quantité vendue
```

## 🐛 DÉPANNAGE

| Problème | Solution |
|----------|----------|
| Modal n'apparaît pas | Vérifier console (F12) - bootstrap.js chargé? |
| TVA incorrecte | Vérifier formule: TVA = HT × 0.16 |
| Stock pas mis à jour | Vérifier process_vente.php execute bien UPDATE |
| Facture vierge | Vérifier details_vente créés |
| Erreur "Panier vide" | Vérifier JSON stringification du panier |

## 🎯 CAS D'USAGE À TESTER

1. **Vente simple:** 1 produit, 1 unité
2. **Vente multiple:** 3+ produits, quantités variables
3. **Vente avec remise de prix:** modifier le prix avant ajout
4. **Vente comptoir:** sans client
5. **Vente à client:** avec sélection client
6. **Vente proforma:** sans validation
7. **Annulation panier:** vider et recommencer

## ✅ APRÈS TOUS LES TESTS

- [ ] Commit Git final
- [ ] Documenter tout bug trouvé
- [ ] Production ready!

---

Bonne chance! 🚀
