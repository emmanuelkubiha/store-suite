<?php
/**
 * AJAX endpoint - Ajouter un nouveau mouvement de stock
 */
require_once __DIR__ . '/../protection_pages.php';
header('Content-Type: application/json');

// Seuls les admins peuvent créer des mouvements
if (!$is_admin) {
    echo json_encode(['success' => false, 'message' => 'Accès refusé. Seuls les administrateurs peuvent effectuer cette action.']);
    exit;
}

$response = ['success' => false, 'message' => ''];

// Debug log
error_log("=== AJOUTER MOUVEMENT DEBUG ===");
error_log("POST data: " . print_r($_POST, true));

try {
    // Validation
    $type_mouvement = $_POST['type_mouvement'] ?? '';
    $id_produit = $_POST['id_produit'] ?? 0;
    $id_depot_source = $_POST['id_depot_source'] ?? 0;
    $id_depot_destination = $_POST['id_depot_destination'] ?? null;
    
    // Pour les non-transferts, forcer id_depot_destination à NULL
    if ($type_mouvement !== 'transfert' && (empty($id_depot_destination) || $id_depot_destination === '')) {
        $id_depot_destination = null;
    }
    
    $quantite = $_POST['quantite'] ?? 0;
    $cout_unitaire = $_POST['cout_unitaire'] ?? null;
    $id_fournisseur = $_POST['id_fournisseur'] ?? null;
    
    // Forcer id_fournisseur et cout_unitaire à NULL si vides
    if (empty($id_fournisseur) || $id_fournisseur === '') {
        $id_fournisseur = null;
    }
    if (empty($cout_unitaire) || $cout_unitaire === '') {
        $cout_unitaire = null;
    }
    
    $notes = trim($_POST['motif'] ?? $_POST['notes'] ?? '');
    
    error_log("Type: $type_mouvement, Produit: $id_produit, Depot: $id_depot_source, Quantite: $quantite");
    
    if (empty($type_mouvement)) {
        throw new Exception('Type de mouvement requis');
    }
    
    if (empty($id_produit)) {
        throw new Exception('Produit requis');
    }
    
    if (empty($id_depot_source)) {
        throw new Exception('Dépôt source requis');
    }
    
    if ($quantite <= 0) {
        throw new Exception('Quantité invalide');
    }

    if ($notes === '') {
        throw new Exception('Motif/Commentaire requis');
    }
    
    // Pour les transferts, le dépôt destination est obligatoire
    if ($type_mouvement === 'transfert' && empty($id_depot_destination)) {
        throw new Exception('Dépôt de destination requis pour un transfert');
    }
    
    if ($type_mouvement === 'transfert' && $id_depot_source == $id_depot_destination) {
        throw new Exception('Le dépôt source et destination ne peuvent pas être identiques');
    }
    
    // Vérifier le stock disponible pour les opérations qui diminuent le stock
    if (in_array($type_mouvement, ['sortie', 'transfert', 'perte'])) {
        $stock_depot = db_fetch_one("
            SELECT quantite 
            FROM stock_par_depot 
            WHERE id_produit = ? AND id_depot = ?
        ", [$id_produit, $id_depot_source]);
        
        $stock_disponible = $stock_depot ? intval($stock_depot['quantite']) : 0;
        
        if ($quantite > $stock_disponible) {
            throw new Exception("Stock insuffisant dans ce dépôt ! Stock disponible : $stock_disponible");
        }
    }
    
    // Calcul du coût total
    $cout_total = null;
    if ($cout_unitaire !== null && $cout_unitaire !== '') {
        $cout_total = $cout_unitaire * $quantite;
    }
    
    // Récupérer la quantité avant le mouvement (stock actuel dans le dépôt source)
    $stock_avant = db_fetch_one("
        SELECT quantite 
        FROM stock_par_depot 
        WHERE id_produit = ? AND id_depot = ?
    ", [$id_produit, $id_depot_source]);
    
    $quantite_avant = $stock_avant ? intval($stock_avant['quantite']) : 0;
    
    // Calculer la quantité après selon le type de mouvement
    if ($type_mouvement === 'inventaire') {
        // Pour l'inventaire, la quantité saisie EST la nouvelle quantité totale
        $quantite_apres = $quantite;
        // Calculer le mouvement réel (différence)
        $quantite = $quantite_apres - $quantite_avant;
    } elseif ($type_mouvement === 'ajustement') {
        // Pour l'ajustement, on peut entrer directement la différence
        $quantite_apres = $quantite_avant + $quantite;
    } elseif (in_array($type_mouvement, ['sortie', 'perte'])) {
        // Pour les sorties et pertes : quantité négative
        $quantite = -abs($quantite);
        $quantite_apres = $quantite_avant + $quantite;
    } elseif ($type_mouvement === 'transfert') {
        // Pour les transferts : sortie du dépôt source
        $quantite_apres = $quantite_avant - $quantite;
        // On garde $quantite positive pour l'affichage, mais on l'utilise négativement pour le dépôt source
    } else {
        // Pour les entrées : quantité positive
        $quantite_apres = $quantite_avant + $quantite;
    }
    
    db_begin_transaction();
    
    // Insérer le mouvement avec NOW() pour la date/heure actuelle
    $sql_insert = "INSERT INTO mouvements_stock 
        (id_produit, type_mouvement, quantite, quantite_avant, quantite_apres, id_depot_source, id_depot_destination, 
         id_fournisseur, cout_unitaire, cout_total, date_mouvement, id_utilisateur, motif) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
    
    $id_mouvement = db_query($sql_insert, [
        $id_produit,
        $type_mouvement,
        $quantite,
        $quantite_avant,
        $quantite_apres,
        $id_depot_source,
        $id_depot_destination,
        $id_fournisseur,
        $cout_unitaire,
        $cout_total,
        $user_id,
        $notes
    ]);
    
    $id_mouvement = $pdo->lastInsertId();
    
    if (!$id_mouvement) {
        throw new Exception('Erreur lors de l\'enregistrement du mouvement');
    }
    
    // Pour les transferts, gérer les deux dépôts
    if ($type_mouvement === 'transfert') {
        // Retirer du dépôt source
        db_query("
            UPDATE stock_par_depot 
            SET quantite = quantite - ? 
            WHERE id_produit = ? AND id_depot = ?
        ", [abs($quantite), $id_produit, $id_depot_source]);
        
        // Vérifier si le produit existe déjà dans le dépôt destination
        $stock_dest = db_fetch_one("
            SELECT quantite FROM stock_par_depot 
            WHERE id_produit = ? AND id_depot = ?
        ", [$id_produit, $id_depot_destination]);
        
        if ($stock_dest) {
            // Ajouter au dépôt destination
            db_query("
                UPDATE stock_par_depot 
                SET quantite = quantite + ? 
                WHERE id_produit = ? AND id_depot = ?
            ", [abs($quantite), $id_produit, $id_depot_destination]);
        } else {
            // Créer l'entrée dans le dépôt destination
            db_insert('stock_par_depot', [
                'id_produit' => $id_produit,
                'id_depot' => $id_depot_destination,
                'quantite' => abs($quantite)
            ]);
        }
    } else {
        // Pour les autres types, mettre à jour le dépôt source
        $stock_existe = db_fetch_one("
            SELECT quantite FROM stock_par_depot 
            WHERE id_produit = ? AND id_depot = ?
        ", [$id_produit, $id_depot_source]);
        
        if ($stock_existe) {
            db_query("
                UPDATE stock_par_depot 
                SET quantite = quantite + ? 
                WHERE id_produit = ? AND id_depot = ?
            ", [$quantite, $id_produit, $id_depot_source]);
        } else {
            // Créer l'entrée si elle n'existe pas
            db_insert('stock_par_depot', [
                'id_produit' => $id_produit,
                'id_depot' => $id_depot_source,
                'quantite' => max(0, $quantite) // Pas de stock négatif
            ]);
        }
    }
    
    // Les triggers vont automatiquement mettre à jour produits.quantite_stock
    
    db_commit();
    
    $type_labels = [
        'entree' => 'Entrée',
        'sortie' => 'Sortie',
        'ajustement' => 'Ajustement',
        'transfert' => 'Transfert',
        'inventaire' => 'Inventaire',
        'perte' => 'Perte'
    ];
    
    $response = [
        'success' => true,
        'message' => 'Mouvement enregistré avec succès',
        'id_mouvement' => $id_mouvement
    ];
    
} catch (Exception $e) {
    if (db_in_transaction()) {
        db_rollback();
    }
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
