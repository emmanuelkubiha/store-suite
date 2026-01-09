<?php
/**
 * PAGE DE VENTE PROFESSIONNELLE - STORE SUITE
 * Interface moderne avec modification prix/quantité et TVA 16%
 */
require_once 'protection_pages.php';
$page_title = 'Nouvelle Vente';

// Récupération des produits actifs
$produits = db_fetch_all("
    SELECT p.*, c.nom_categorie
    FROM produits p
    LEFT JOIN categories c ON p.id_categorie = c.id_categorie
    WHERE p.est_actif = 1 AND p.quantite_stock > 0
    ORDER BY p.nom_produit ASC
");

// Récupération des clients
$clients = db_fetch_all("
    SELECT * FROM clients 
    WHERE est_actif = 1 
    ORDER BY nom_client ASC
");

include 'header.php';
?>

<style>
.product-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    border-color: <?php echo $couleur_primaire; ?>;
}

.product-card:active {
    transform: scale(0.98);
}

.cart-item {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
    margin-bottom: 0.5rem;
    border-radius: 8px;
}

.cart-total-box {
    background: linear-gradient(135deg, <?php echo $couleur_primaire; ?>, <?php echo $couleur_secondaire; ?>);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.price-input {
    width: 100px;
    font-weight: bold;
    color: <?php echo $couleur_primaire; ?>;
}

.qty-input {
    width: 70px;
    text-align: center;
    font-weight: bold;
}

.cart-section {
    position: sticky;
    top: 20px;
    z-index: 100;
}
</style>

<div class="container-xl">
    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="6" cy="19" r="2"/>
                        <circle cx="17" cy="19" r="2"/>
                        <path d="M17 17h-11v-14h-2"/>
                        <path d="M6 5l14 1l-1 7h-13"/>
                    </svg>
                    Nouvelle Vente
                </h2>
                <div class="text-muted mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12.01" y2="8"/><polyline points="11 12 12 12 12 16 13 16"/></svg>
                    Cliquez sur un produit pour l'ajouter au panier. Prix et quantités modifiables.
                </div>
            </div>
            <div class="col-auto">
                <a href="accueil.php" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <line x1="5" y1="12" x2="9" y2="16"/>
                        <line x1="5" y1="12" x2="9" y2="8"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                        Sélection des produits
                    </h3>
                    <div class="col-auto ms-auto">
                        <input type="text" class="form-control" id="searchProduct" placeholder="🔍 Rechercher...">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="productsList">
                        <?php foreach ($produits as $produit): ?>
                        <div class="col-md-4 col-sm-6 product-item" data-barcode="<?php echo e($produit['code_barre']); ?>">
                            <div class="product-card card" onclick="addToCart(<?php echo $produit['id_produit']; ?>, '<?php echo addslashes(e($produit['nom_produit'])); ?>', <?php echo $produit['prix_vente']; ?>, <?php echo $produit['quantite_stock']; ?>)" title="Cliquez pour ajouter au panier">
                                <div class="card-body text-center">
                                    <?php 
                                    $has_image = !empty($produit['image_produit']) && file_exists('uploads/produits/' . $produit['image_produit']);
                                    if ($has_image):
                                    ?>
                                    <div class="mb-2">
                                        <img src="uploads/produits/<?php echo e($produit['image_produit']); ?>" 
                                             alt="<?php echo e($produit['nom_produit']); ?>" 
                                             style="height: 80px; object-fit: contain;">
                                    </div>
                                    <?php else: ?>
                                    <div class="mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <h4 class="mb-1 fs-6 fw-bold"><?php echo e($produit['nom_produit']); ?></h4>
                                    <div class="text-muted small mb-2"><?php echo e($produit['nom_categorie'] ?? 'Sans catégorie'); ?></div>
                                    
                                    <?php if (!empty($produit['code_barre'])): ?>
                                    <div class="small text-monospace mb-2">
                                        <span class="badge bg-light text-dark">📦 <?php echo e($produit['code_barre']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-primary fs-5"><?php echo format_montant($produit['prix_vente'], $devise); ?></strong>
                                        <span class="badge <?php echo $produit['quantite_stock'] <= 10 ? 'bg-warning' : 'bg-success'; ?>">
                                            📦 <?php echo $produit['quantite_stock']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="cart-section">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-white mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
                            Panier (<span id="cartCount">0</span>)
                        </h3>
                        <div class="col-auto ms-auto">
                            <button class="btn btn-sm btn-danger" onclick="clearCart()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                Vider
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;" id="cartItems">
                        <div class="text-center p-5 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
                            <div>Panier vide</div>
                            <small>Cliquez sur un produit pour commencer</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                Client
                            </label>
                            <select class="form-select" id="clientSelect">
                                <option value="">🛒 Client Comptoir</option>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id_client']; ?>"><?php echo e($client['nom_client']); ?> - <?php echo e($client['telephone'] ?? 'N/A'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="cart-total-box mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total HT:</span>
                                <strong id="cartSubtotal">0 <?php echo $devise; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-2" style="border-bottom: 1px solid rgba(255,255,255,0.3);">
                                <span>TVA (16%):</span>
                                <strong id="cartTVA">0 <?php echo $devise; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fs-5">TOTAL TTC:</span>
                                <strong class="fs-2" id="cartTotal">0 <?php echo $devise; ?></strong>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" onclick="processSale()" id="btnProcessSale" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 11 12 14 20 6"/><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/></svg>
                                Valider la vente
                            </button>
                            <button class="btn btn-warning" onclick="generateProforma()" id="btnProforma" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><line x1="9" y1="7" x2="10" y2="7"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="13" y1="17" x2="15" y2="17"/></svg>
                                Facture Proforma
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Modal Ajouter au panier -->
<div class="modal fade" id="addToCartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalProductName">Produit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3" id="modalProductImage">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted" width="64" height="64" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Prix unitaire</label>
                    <div class="input-group">
                        <input type="number" class="form-control form-control-lg fw-bold" id="modalPrice" 
                               min="0" step="0.01" value="0">
                        <span class="input-group-text"><?php echo $devise; ?></span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Quantité</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary" id="btnDecreaseQty">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <input type="number" class="form-control form-control-lg text-center fw-bold" id="modalQuantity" 
                               min="1" value="1">
                        <button type="button" class="btn btn-outline-secondary" id="btnIncreaseQty">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>
                    <small class="text-muted" id="modalStockInfo">Stock disponible: 0</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Sous-total</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg fw-bold text-success" 
                               id="modalSubtotal" readonly value="0">
                        <span class="input-group-text"><?php echo $devise; ?></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success btn-lg" id="btnConfirmAdd" onclick="confirmAddToCart()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 11 12 14 20 6"/><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/></svg>
                    Ajouter au panier
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let currentModalProduct = null;
const TVA_RATE = 0.16; // 16%

// ===== ATTENDRE LE DOM AVANT DE CHARGER =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ vente.php script chargé');
    
    // ===== MODAL AJOUTER AU PANIER =====
    const addToCartModal = new bootstrap.Modal(document.getElementById('addToCartModal'));
    const modalPriceInput = document.getElementById('modalPrice');
    const modalQtyInput = document.getElementById('modalQuantity');
    const modalSubtotalInput = document.getElementById('modalSubtotal');
    const btnDecreaseQty = document.getElementById('btnDecreaseQty');
    const btnIncreaseQty = document.getElementById('btnIncreaseQty');
    
    window.addToCartModal = addToCartModal; // Accessible globalement
    window.modalPriceInput = modalPriceInput;
    window.modalQtyInput = modalQtyInput;
    window.modalSubtotalInput = modalSubtotalInput;
    window.btnDecreaseQty = btnDecreaseQty;
    window.btnIncreaseQty = btnIncreaseQty;

    btnDecreaseQty.addEventListener('click', () => {
        const val = Math.max(1, parseInt(modalQtyInput.value) - 1);
        modalQtyInput.value = val;
        window.updateModalSubtotal();
    });

    btnIncreaseQty.addEventListener('click', () => {
        const val = Math.min(currentModalProduct.stockMax, parseInt(modalQtyInput.value) + 1);
        modalQtyInput.value = val;
        window.updateModalSubtotal();
    });

    modalPriceInput.addEventListener('input', function() { window.updateModalSubtotal(); });
    modalQtyInput.addEventListener('input', function() { window.updateModalSubtotal(); });
});

function updateModalSubtotal() {
    const price = parseFloat(window.modalPriceInput.value) || 0;
    const qty = parseInt(window.modalQtyInput.value) || 0;
    const subtotal = price * qty;
    window.modalSubtotalInput.value = subtotal.toLocaleString('fr-FR', {minimumFractionDigits: 2});
}

function addToCart(id, nom, prix, stockMax) {
    console.log('addToCart appelée:', {id, nom, prix, stockMax});
    currentModalProduct = { id, nom, prix, stockMax };
    showAddToCartModal(id, nom, prix, stockMax);
}

function showAddToCartModal(id, nom, prix, stockMax) {
    console.log('showAddToCartModal appelée');
    currentModalProduct = { id, nom, prix, stockMax };
    
    document.getElementById('modalProductName').textContent = nom;
    document.getElementById('modalStockInfo').textContent = `📦 Stock disponible: ${stockMax}`;
    
    window.modalPriceInput.value = prix;
    window.modalQtyInput.value = 1;
    window.modalQtyInput.max = stockMax;
    window.btnIncreaseQty.disabled = stockMax <= 1;
    
    updateModalSubtotal();
    window.addToCartModal.show();
}

function confirmAddToCart() {
    if (!currentModalProduct) return;
    
    const price = parseFloat(window.modalPriceInput.value) || currentModalProduct.prix;
    const qty = parseInt(window.modalQtyInput.value) || 1;
    
    if (qty < 1) {
        showAlertModal({
            title: 'Quantité invalide',
            message: 'La quantité doit être au minimum 1',
            type: 'warning'
        });
        return;
    }
    
    if (qty > currentModalProduct.stockMax) {
        showAlertModal({
            title: 'Stock insuffisant',
            message: `Stock maximum: ${currentModalProduct.stockMax}`,
            type: 'warning'
        });
        return;
    }
    
    // Vérifier si le produit existe déjà dans le panier
    const existing = cart.find(item => item.id === currentModalProduct.id);
    if (existing) {
        existing.quantite += qty;
        if (existing.quantite > currentModalProduct.stockMax) {
            existing.quantite = currentModalProduct.stockMax;
        }
    } else {
        cart.push({
            id: currentModalProduct.id,
            nom: currentModalProduct.nom,
            prix: price,
            quantite: qty,
            stockMax: currentModalProduct.stockMax
        });
    }
    
    updateCart();
    window.addToCartModal.hide();
    
    showAlertModal({
        title: 'Succès',
        message: `${qty}x ${currentModalProduct.nom} ajoutés au panier`,
        type: 'success'
    });
}

function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const btnProcessSale = document.getElementById('btnProcessSale');
    const btnProforma = document.getElementById('btnProforma');
    const cartCount = document.getElementById('cartCount');
    
    cartCount.textContent = cart.length;
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="text-center p-5 text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
                <div>Panier vide</div>
                <small>Cliquez sur un produit pour commencer</small>
            </div>
        `;
        btnProcessSale.disabled = true;
        btnProforma.disabled = true;
        updateTotals(0);
        return;
    }
    
    btnProcessSale.disabled = false;
    btnProforma.disabled = false;
    
    let html = '';
    let subtotal = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.prix * item.quantite;
        subtotal += itemTotal;
        
        html += `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <strong class="text-dark" style="flex: 1;">${item.nom}</strong>
                    <button class="btn btn-sm btn-ghost-danger ms-2" onclick="removeFromCart(${index})" title="Retirer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="row g-2 align-items-center">
                    <div class="col-5">
                        <label class="form-label mb-0 small text-muted">Prix unitaire</label>
                        <input type="number" class="form-control form-control-sm price-input" value="${item.prix}" 
                               onchange="updatePrice(${index}, this.value)" min="0" step="0.01">
                    </div>
                    <div class="col-3">
                        <label class="form-label mb-0 small text-muted">Qté</label>
                        <input type="number" class="form-control form-control-sm qty-input" value="${item.quantite}" 
                               onchange="updateQuantityDirect(${index}, this.value)" min="1" max="${item.stockMax}">
                    </div>
                    <div class="col-4 text-end">
                        <label class="form-label mb-0 small text-muted">Total</label>
                        <div class="fw-bold text-primary">${itemTotal.toLocaleString('fr-FR', {minimumFractionDigits: 2})} <?php echo $devise; ?></div>
                    </div>
                </div>
                <div class="mt-1">
                    <div class="btn-group btn-group-sm w-100">
                        <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, -1)" ${item.quantite <= 1 ? 'disabled' : ''}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, 1)" ${item.quantite >= item.stockMax ? 'disabled' : ''}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItems.innerHTML = html;
    updateTotals(subtotal);
}

function updateTotals(subtotal) {
    const tva = subtotal * TVA_RATE;
    const total = subtotal + tva;
    
    document.getElementById('cartSubtotal').textContent = subtotal.toLocaleString('fr-FR', {minimumFractionDigits: 2}) + ' <?php echo $devise; ?>';
    document.getElementById('cartTVA').textContent = tva.toLocaleString('fr-FR', {minimumFractionDigits: 2}) + ' <?php echo $devise; ?>';
    document.getElementById('cartTotal').textContent = total.toLocaleString('fr-FR', {minimumFractionDigits: 2}) + ' <?php echo $devise; ?>';
}

function updateQuantity(index, delta) {
    const item = cart[index];
    const newQty = item.quantite + delta;
    if (newQty > 0 && newQty <= item.stockMax) {
        item.quantite = newQty;
        updateCart();
    }
}

function updateQuantityDirect(index, value) {
    const item = cart[index];
    const newQty = parseInt(value) || 1;
    if (newQty > 0 && newQty <= item.stockMax) {
        item.quantite = newQty;
        updateCart();
    } else if (newQty > item.stockMax) {
        showAlertModal({
            title: 'Stock insuffisant',
            message: `Stock maximum: ${item.stockMax}`,
            type: 'warning',
            icon: 'warning'
        });
        updateCart();
    }
}

function updatePrice(index, value) {
    const item = cart[index];
    const newPrice = parseFloat(value) || 0;
    if (newPrice >= 0) {
        item.prix = newPrice;
        updateCart();
    }
}

function removeFromCart(index) {
    showConfirmModal({
        title: 'Retirer du panier',
        message: `Retirer ${cart[index].nom} du panier ?`,
        icon: 'warning',
        type: 'warning',
        confirmText: 'Oui, retirer',
        cancelText: 'Non'
    }).then(confirmed => {
        if (confirmed) {
            cart.splice(index, 1);
            updateCart();
        }
    });
}

function clearCart() {
    if (cart.length === 0) return;
    
    showConfirmModal({
        title: 'Vider le panier',
        message: 'Êtes-vous sûr de vouloir vider le panier ?',
        icon: 'warning',
        type: 'danger',
        confirmText: 'Oui, vider',
        cancelText: 'Annuler'
    }).then(confirmed => {
        if (confirmed) {
            cart = [];
            updateCart();
        }
    });
}

function processSale() {
    if (cart.length === 0) return;
    
    showConfirmModal({
        title: 'Confirmer la vente',
        message: `Confirmer la vente pour un montant total de ${document.getElementById('cartTotal').textContent} ?`,
        icon: 'info',
        type: 'success',
        confirmText: 'Valider la vente',
        cancelText: 'Annuler'
    }).then(confirmed => {
        if (!confirmed) return;
        
        const formData = new FormData();
        formData.append('id_client', document.getElementById('clientSelect').value || null);
        formData.append('cart', JSON.stringify(cart));
        
        fetch('ajax/process_vente.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showAlertModal({
                    title: 'Vente réussie !',
                    message: data.message,
                    type: 'success',
                    icon: 'success'
                }).then(() => {
                    if (data.id_vente) {
                        window.open('facture.php?id=' + data.id_vente, '_blank');
                    }
                    cart = [];
                    updateCart();
                });
            } else {
                showAlertModal({
                    title: 'Erreur',
                    message: data.message,
                    type: 'danger',
                    icon: 'danger'
                });
            }
        })
        .catch(e => {
            showAlertModal({
                title: 'Erreur',
                message: 'Erreur de connexion: ' + e,
                type: 'danger',
                icon: 'danger'
            });
        });
    });
}

function generateProforma() {
    if (cart.length === 0) {
        showAlertModal({
            title: 'Panier vide',
            message: 'Ajoutez des produits au panier avant de générer un proforma',
            type: 'warning',
            icon: 'warning'
        });
        return;
    }
    
    // Créer un formulaire et le soumettre vers proforma.php
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'proforma.php';
    form.target = '_blank';
    
    // Ajouter les items du panier
    cart.forEach((item, index) => {
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = `cart_items[${index}][id]`;
        inputId.value = item.id;
        form.appendChild(inputId);
        
        const inputNom = document.createElement('input');
        inputNom.type = 'hidden';
        inputNom.name = `cart_items[${index}][nom]`;
        inputNom.value = item.nom;
        form.appendChild(inputNom);
        
        const inputPrix = document.createElement('input');
        inputPrix.type = 'hidden';
        inputPrix.name = `cart_items[${index}][prix]`;
        inputPrix.value = item.prix;
        form.appendChild(inputPrix);
        
        const inputQte = document.createElement('input');
        inputQte.type = 'hidden';
        inputQte.name = `cart_items[${index}][quantite]`;
        inputQte.value = item.quantite;
        form.appendChild(inputQte);
    });
    
    // Calculer et ajouter les totaux
    const subtotal = cart.reduce((sum, item) => sum + (item.prix * item.quantite), 0);
    const tva = subtotal * TVA_RATE;
    const total = subtotal + tva;
    
    const inputSubtotal = document.createElement('input');
    inputSubtotal.type = 'hidden';
    inputSubtotal.name = 'subtotal';
    inputSubtotal.value = subtotal;
    form.appendChild(inputSubtotal);
    
    const inputTVA = document.createElement('input');
    inputTVA.type = 'hidden';
    inputTVA.name = 'tva';
    inputTVA.value = tva;
    form.appendChild(inputTVA);
    
    const inputTotal = document.createElement('input');
    inputTotal.type = 'hidden';
    inputTotal.name = 'total';
    inputTotal.value = total;
    form.appendChild(inputTotal);
    
    // Ajouter le client
    const clientSelect = document.getElementById('clientSelect');
    const inputClientId = document.createElement('input');
    inputClientId.type = 'hidden';
    inputClientId.name = 'id_client';
    inputClientId.value = clientSelect.value;
    form.appendChild(inputClientId);
    
    const inputClientName = document.createElement('input');
    inputClientName.type = 'hidden';
    inputClientName.name = 'client_name';
    inputClientName.value = clientSelect.options[clientSelect.selectedIndex].text;
    form.appendChild(inputClientName);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Recherche de produits (nom + code-barre)
const searchInput = document.getElementById('searchProduct');
let searchTimeout;

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const search = this.value.toLowerCase().trim();
    
    searchTimeout = setTimeout(() => {
        document.querySelectorAll('.product-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            const code = item.getAttribute('data-barcode') || '';
            const matches = text.includes(search) || code.includes(search);
            item.style.display = matches ? '' : 'none';
        });
        
        // Compter les résultats
        const visible = document.querySelectorAll('.product-item[style=""]').length + 
                       document.querySelectorAll('.product-item:not([style])').length;
        const shown = document.querySelectorAll('.product-item:not([style="display: none;"])').length;
        
        if (search && shown === 0) {
            console.log('Aucun produit trouvé pour: ' + search);
        }
    }, 300);
});

// Focus sur la recherche avec Ctrl+F
searchInput.addEventListener('focus', function() {
    this.select();
});

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    // Ctrl+F = Focus sur recherche
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchProduct').focus();
    }
    // F2 = Valider la vente
    if (e.key === 'F2' && cart.length > 0) {
        e.preventDefault();
        processSale();
    }
    // F3 = Vider le panier
    if (e.key === 'F3') {
        e.preventDefault();
        clearCart();
    }
    // Échap = Focus sur recherche
    if (e.key === 'Escape' && document.activeElement !== document.getElementById('searchProduct')) {
        document.getElementById('searchProduct').focus();
    }
});

console.log('✅ Système de vente moderne chargé - TVA 16% incluse');
</script>

<?php include 'footer.php'; ?>
