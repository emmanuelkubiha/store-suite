<?php
/**
 * MES VENTES - Interface vendeur pour gérer ses propres ventes
 * Permet de consulter, annuler et réimprimer les factures
 */
require_once 'protection_pages.php';
$page_title = 'Mes Ventes';

// Filtres
$filter_date_debut = $_GET['date_debut'] ?? date('Y-m-d', strtotime('-30 days'));
$filter_date_fin = $_GET['date_fin'] ?? date('Y-m-d');
$filter_client = $_GET['client'] ?? '';
$filter_statut = $_GET['statut'] ?? '';
$filter_search = $_GET['search'] ?? '';

// Requête de base - vendeur voit uniquement SES ventes
$query = "
    SELECT v.*,
           c.nom_client,
           COUNT(dv.id_detail) as nb_articles
    FROM ventes v
    LEFT JOIN clients c ON v.id_client = c.id_client
    LEFT JOIN details_vente dv ON v.id_vente = dv.id_vente
    WHERE v.id_vendeur = ?
";
$params = [$user_id];

// Appliquer les filtres
if ($filter_date_debut) {
    $query .= " AND DATE(v.date_vente) >= ?";
    $params[] = $filter_date_debut;
}
if ($filter_date_fin) {
    $query .= " AND DATE(v.date_vente) <= ?";
    $params[] = $filter_date_fin;
}
if ($filter_client) {
    $query .= " AND v.id_client = ?";
    $params[] = $filter_client;
}
if ($filter_statut) {
    $query .= " AND v.statut = ?";
    $params[] = $filter_statut;
}
if ($filter_search) {
    $query .= " AND (v.numero_facture LIKE ? OR c.nom_client LIKE ?)";
    $params[] = "%$filter_search%";
    $params[] = "%$filter_search%";
}

$query .= " GROUP BY v.id_vente ORDER BY v.date_vente DESC";

$ventes = db_fetch_all($query, $params);

// Récupérer les clients du vendeur
$clients = db_fetch_all("
    SELECT DISTINCT c.*
    FROM clients c
    JOIN ventes v ON c.id_client = v.id_client
    WHERE v.id_vendeur = ?
    ORDER BY c.nom_client
", [$user_id]);

// Statistiques
$stats = db_fetch_one("
    SELECT 
        COUNT(DISTINCT v.id_vente) as total_ventes,
        COALESCE(SUM(CASE WHEN DATE(v.date_vente) = CURDATE() THEN v.montant_total ELSE 0 END), 0) as ca_today,
        COALESCE(SUM(v.montant_total), 0) as ca_total,
        COALESCE(SUM(v.montant_remise), 0) as total_remises
    FROM ventes v
    WHERE v.id_vendeur = ?
", [$user_id]);

include 'header.php';
?>

<style>
.stat-card {
    border-left: 4px solid <?php echo $couleur_primaire; ?>;
    transition: all 0.3s;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
</style>

<div class="container-xl">
    <!-- Header -->
    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Mes Ventes
                </h2>
                <div class="text-muted mt-1">Gérez vos ventes, annulez et réimprimez vos factures</div>
            </div>
            <div class="col-auto">
                <a href="vente.php" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Nouvelle Vente
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="<?php echo $couleur_primaire; ?>" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Total Ventes</div>
                            <div class="h3 mb-0"><?php echo $stats['total_ventes']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="<?php echo $couleur_secondaire; ?>" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">CA Aujourd'hui</div>
                            <div class="h3 mb-0"><?php echo format_montant($stats['ca_today'], $devise); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">CA Total</div>
                            <div class="h3 mb-0"><?php echo format_montant($stats['ca_total'], $devise); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffc107" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div class="text-muted small">Remises</div>
                            <div class="h3 mb-0"><?php echo format_montant($stats['total_remises'], $devise); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date" class="form-control" name="date_debut" value="<?php echo e($filter_date_debut); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" class="form-control" name="date_fin" value="<?php echo e($filter_date_fin); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Client</label>
                    <select class="form-select" name="client">
                        <option value="">Tous</option>
                        <?php foreach($clients as $c): ?>
                        <option value="<?php echo $c['id_client']; ?>" <?php echo $filter_client == $c['id_client'] ? 'selected' : ''; ?>>
                            <?php echo e($c['nom_client']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="statut">
                        <option value="">Tous</option>
                        <option value="validee" <?php echo $filter_statut == 'validee' ? 'selected' : ''; ?>>Validée</option>
                        <option value="annulee" <?php echo $filter_statut == 'annulee' ? 'selected' : ''; ?>>Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Recherche</label>
                    <input type="text" class="form-control" name="search" placeholder="N° facture..." value="<?php echo e($filter_search); ?>">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="mes_ventes.php" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📋 Liste de vos ventes (<?php echo count($ventes); ?>)</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Articles</th>
                            <th>Montant TTC</th>
                            <th>Paiement</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventes)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">Aucune vente trouvée</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($ventes as $vente): ?>
                        <tr>
                            <td><strong><?php echo e($vente['numero_facture']); ?></strong></td>
                            <td><small><?php echo date('d/m/Y H:i', strtotime($vente['date_vente'])); ?></small></td>
                            <td><?php echo e($vente['nom_client'] ?: 'Vente comptoir'); ?></td>
                            <td><span class="badge bg-info"><?php echo $vente['nb_articles']; ?></span></td>
                            <td><strong><?php echo format_montant($vente['montant_total'], $devise); ?></strong></td>
                            <td>
                                <?php
                                $mode_badges = ['especes' => 'success', 'carte' => 'primary', 'mobile_money' => 'warning', 'cheque' => 'info', 'credit' => 'secondary'];
                                $mode_labels = ['especes' => 'Espèces', 'carte' => 'Carte', 'mobile_money' => 'Mobile Money', 'cheque' => 'Chèque', 'credit' => 'Crédit'];
                                $badge_class = $mode_badges[$vente['mode_paiement']] ?? 'secondary';
                                $mode_label = $mode_labels[$vente['mode_paiement']] ?? $vente['mode_paiement'];
                                ?>
                                <span class="badge bg-<?php echo $badge_class; ?>"><?php echo $mode_label; ?></span>
                            </td>
                            <td>
                                <?php
                                $statut_badges = ['validee' => 'success', 'annulee' => 'danger'];
                                $statut_labels = ['validee' => 'Validée', 'annulee' => 'Annulée'];
                                $badge_class = $statut_badges[$vente['statut']] ?? 'secondary';
                                $statut_label = $statut_labels[$vente['statut']] ?? $vente['statut'];
                                ?>
                                <span class="badge bg-<?php echo $badge_class; ?>"><?php echo $statut_label; ?></span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="facture_impression_v2.php?id=<?php echo $vente['id_vente']; ?>" 
                                       target="_blank"
                                       class="btn btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Imprimer facture">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                            <rect x="6" y="14" width="12" height="8"></rect>
                                        </svg>
                                    </a>
                                    <?php if ($vente['statut'] == 'validee'): ?>
                                    <button type="button" class="btn btn-outline-danger btn-cancel-vente" 
                                            data-id="<?php echo $vente['id_vente']; ?>"
                                            data-numero="<?php echo e($vente['numero_facture']); ?>"
                                            data-bs-toggle="tooltip" title="Annuler la vente">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                    <?php if ($vente['statut'] == 'annulee' && $is_admin): ?>
                                    <button type="button" class="btn btn-outline-danger btn-delete-vente" 
                                            data-id="<?php echo $vente['id_vente']; ?>"
                                            data-numero="<?php echo e($vente['numero_facture']); ?>"
                                            data-bs-toggle="tooltip" title="Supprimer définitivement (Admin)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Annulation de vente
    document.querySelectorAll('.btn-cancel-vente').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const numero = this.dataset.numero;
            
            if (typeof showConfirmModal === 'function') {
                showConfirmModal({
                    title: 'Annuler la vente',
                    message: `Êtes-vous sûr de vouloir annuler la vente ${numero} ? Le stock sera restauré.`,
                    onConfirm: () => cancelVente(id)
                });
            } else {
                if (confirm(`Annuler la vente ${numero} ? Le stock sera restauré.`)) {
                    cancelVente(id);
                }
            }
        });
    });
    
    // Suppression définitive (admin)
    document.querySelectorAll('.btn-delete-vente').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const numero = this.dataset.numero;
            
            console.log('🗑️ Bouton supprimer cliqué:', {id, numero});
            
            // Utiliser confirm() pour être sûr que ça marche
            const confirmed = confirm(`⚠️ ATTENTION ⚠️\n\nVoulez-vous vraiment SUPPRIMER définitivement la vente ${numero} ?\n\nCette action est IRRÉVERSIBLE !`);
            console.log('👤 Admin a confirmé suppression:', confirmed);
            
            if (confirmed) {
                deleteVente(id);
            }
        });
    });
    
    function cancelVente(id) {
        console.log('🎯 cancelVente appelée avec id:', id);
        
        fetch('ajax/cancel_vente.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({id_vente: id})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (typeof showAlertModal === 'function') {
                    showAlertModal({
                        title: 'Succès',
                        message: data.message,
                        type: 'success',
                        onClose: () => location.reload()
                    });
                } else {
                    alert(data.message);
                    location.reload();
                }
            } else {
                if (typeof showAlertModal === 'function') {
                    showAlertModal({
                        title: 'Erreur',
                        message: data.message,
                        type: 'error'
                    });
                } else {
                    alert('Erreur: ' + data.message);
                }
            }
        })
        .catch(e => {
            console.error(e);
            if (typeof showAlertModal === 'function') {
                showAlertModal({
                    title: 'Erreur',
                    message: 'Erreur de connexion: ' + e.message,
                    type: 'error'
                });
            } else {
                alert('Erreur de connexion');
            }
        });
    }
    
    function deleteVente(id) {
        console.log('🗑️ deleteVente appelée avec id:', id);
        
        fetch('ajax/delete_vente.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({id_vente: id})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Suppression réussie');
                if (typeof showAlertModal === 'function') {
                    showAlertModal({
                        title: 'Succès',
                        message: data.message,
                        type: 'success'
                    });
                } else {
                    alert(data.message);
                }
                // Recharger après 1 seconde
                setTimeout(() => {
                    console.log('🔄 Rechargement page...');
                    location.reload();
                }, 1000);
            } else {
                console.error('❌ Erreur suppression:', data.message);
                if (typeof showAlertModal === 'function') {
                    showAlertModal({
                        title: 'Erreur',
                        message: data.message,
                        type: 'error'
                    });
                } else {
                    alert('Erreur: ' + data.message);
                }
            }
        })
        .catch(e => {
            console.error('❌ Erreur fetch:', e);
            if (typeof showAlertModal === 'function') {
                showAlertModal({
                    title: 'Erreur',
                    message: 'Erreur de connexion: ' + e.message,
                    type: 'error'
                });
            } else {
                alert('Erreur de connexion: ' + e.message);
            }
        });
    }
});
</script>

<?php include 'footer.php'; ?>
