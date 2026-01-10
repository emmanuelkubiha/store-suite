<?php
// Page aide accessible à tous (connectés ou non)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('config/config.php');
require_once('config/database.php');

// Vérifier si connecté
$is_logged_in = false;
$user_id = null;
$user_name = null;
$user_niveau = 2;
$is_admin = false;
$nom_boutique = 'Store Suite';
$logo_boutique = '';
$devise = 'USD';
$couleur_primaire = '#3b82f6';
$couleur_secondaire = '#1e40af';
$page_title = 'Aide & À Propos';

// Si connecté, charger les vraies variables comme partout ailleurs
if (is_logged_in()) {
    $is_logged_in = true;
    
    // Mettre à jour timestamp activité
    $_SESSION['last_activity'] = time();
    
    // Récupérer les infos utilisateur
    $id_utilisateur = get_user_id();
    $user_data = get_user_by_id($id_utilisateur);
    
    if ($user_data) {
        // Utiliser exactement les mêmes variables que protection_pages.php
        $user_id = $user_data['id_utilisateur'];
        $user_name = $user_data['nom_complet'];
        $user_login = $user_data['login'];
        $user_email = $user_data['email'];
        $user_niveau = $user_data['niveau_acces'];
        $is_admin = ($user_niveau == NIVEAU_ADMIN);
        
        // Charger config système
        $config = get_system_config();
        if ($config) {
            $nom_boutique = $config['nom_boutique'];
            $logo_boutique = $config['logo'];
            $couleur_primaire = $config['couleur_primaire'];
            $couleur_secondaire = $config['couleur_secondaire'];
            $devise = $config['devise'];
        }
        
        // Compter les notifications et alertes produits
        $notifications_count = get_notifications_count();
        $products_alert_count = get_products_alert_count();
    }
}

?>

<?php
if ($is_logged_in) {
    // Connecté: utiliser header.php normal (identique à partout ailleurs)
    require_once('header.php');
} else {
    // Non-connecté: afficher un header simplifié
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo e($page_title); ?> - Store Suite</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-xl">
                <a class="navbar-brand fw-bold" href="index.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="<?php echo $couleur_primaire; ?>" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="6" cy="19" r="2"/>
                        <circle cx="17" cy="19" r="2"/>
                        <path d="M17 17h-11v-14h-2"/>
                        <path d="M6 5l14 1l-1 7h-13"/>
                    </svg>
                    Store Suite
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="ms-auto">
                        <a href="index.php" class="btn btn-outline-primary btn-sm">Se connecter</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="page-wrapper">
            <div class="container-xl">
    <?php
}

?>



        <!-- Titre et introduction -->
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Aide & À Propos - Store Suite</h2>
                    <p class="text-muted mt-2">Système de gestion de commerce de détail complet et intuitif</p>
                </div>
            </div>
        </div>

        <!-- Navigation par sections -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="nav nav-tabs border-0 flex-wrap" role="tablist">
                    <button class="nav-link active" id="tab-overview" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                        Vue d'ensemble
                    </button>
                    <button class="nav-link" id="tab-features" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab">
                        Fonctionnalités
                    </button>
                    <button class="nav-link" id="tab-sections" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">
                        Sections
                    </button>
                    <button class="nav-link" id="tab-howto" data-bs-toggle="tab" data-bs-target="#howto" type="button" role="tab">
                        Guides
                    </button>
                    <button class="nav-link" id="tab-about" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab">
                        À Propos
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenu des onglets -->
        <div class="tab-content">

            <!-- Vue d'ensemble -->
            <div class="tab-pane fade show active" id="overview">
                <!-- Hero Section -->
                <div class="card border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, <?php echo $couleur_primaire; ?> 0%, <?php echo $couleur_secondaire; ?> 100%); color: white;">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="card-title mb-3" style="font-size: 2rem; font-weight: 700;">Store Suite</h2>
                                <p class="card-text lead mb-0" style="font-size: 1.1rem; line-height: 1.6;">Votre solution complète de gestion de commerce de détail. Gérez ventes, stocks, clients et finances en un seul endroit, facilement et efficacement.</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="80" height="80" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.9;">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="6" cy="19" r="2"/>
                                    <circle cx="17" cy="19" r="2"/>
                                    <path d="M17 17h-11v-14h-2"/>
                                    <path d="M6 5l14 1l-1 7h-13"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trois Piliers -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100 hover-shadow" style="transition: all 0.3s ease; border-left: 4px solid <?php echo $couleur_primaire; ?>;">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="<?php echo $couleur_primaire; ?>" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="6" cy="19" r="2"/>
                                        <circle cx="17" cy="19" r="2"/>
                                        <path d="M17 17h-11v-14h-2"/>
                                        <path d="M6 5l14 1l-1 7h-13"/>
                                    </svg>
                                </div>
                                <h5 class="card-title text-center" style="color: <?php echo $couleur_primaire; ?>;">Gestion des Ventes</h5>
                                <p class="text-muted text-center small">Créez, validez et suivez vos ventes avec génération automatique de factures et calcul TVA intégré.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100 hover-shadow" style="transition: all 0.3s ease; border-left: 4px solid <?php echo $couleur_primaire; ?>;">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="<?php echo $couleur_primaire; ?>" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                                        <line x1="9" y1="6" x2="15" y2="6"/>
                                        <line x1="9" y1="12" x2="15" y2="12"/>
                                        <line x1="9" y1="18" x2="15" y2="18"/>
                                    </svg>
                                </div>
                                <h5 class="card-title text-center" style="color: <?php echo $couleur_primaire; ?>;">Gestion de Stock</h5>
                                <p class="text-muted text-center small">Suivi en temps réel des stocks avec alertes intelligentes pour les produits en quantité faible ou critique.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100 hover-shadow" style="transition: all 0.3s ease; border-left: 4px solid <?php echo $couleur_primaire; ?>;">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="<?php echo $couleur_primaire; ?>" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="3" y="12" width="6" height="8" rx="1"/>
                                        <rect x="9" y="8" width="6" height="12" rx="1"/>
                                        <rect x="15" y="4" width="6" height="16" rx="1"/>
                                    </svg>
                                </div>
                                <h5 class="card-title text-center" style="color: <?php echo $couleur_primaire; ?>;">Rapports &amp; Analyses</h5>
                                <p class="text-muted text-center small">Tableaux de bord complets, graphiques détaillés et exports pour analyser vos performances.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Caractéristiques clés -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3" style="color: <?php echo $couleur_primaire; ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
                                    </svg>
                                    Fonctionnalités Principales
                                </h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">✓ Gestion complète des ventes et facturations</li>
                                    <li class="mb-2">✓ Suivi intelligent du stock avec alertes</li>
                                    <li class="mb-2">✓ Base de données clients centralisée</li>
                                    <li class="mb-2">✓ Rapports détaillés et graphiques interactifs</li>
                                    <li class="mb-2">✓ Gestion des utilisateurs et permissions</li>
                                    <li class="mb-2">✓ Interface 100% française et intuitive</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3" style="color: <?php echo $couleur_primaire; ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                        <polyline points="12 7 12 12 16 14"/>
                                    </svg>
                                    Pourquoi Store Suite ?
                                </h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">⚡ Rapide et réactif, conçu pour les petits commerces</li>
                                    <li class="mb-2">📊 Analytiques en temps réel pour meilleures décisions</li>
                                    <li class="mb-2">🔒 Sécurisé avec gestion des droits d'accès</li>
                                    <li class="mb-2">💾 Sauvegarde et récupération fiables</li>
                                    <li class="mb-2">🎨 Design moderne et agréable à utiliser</li>
                                    <li class="mb-2">🌍 Localisé entièrement en français</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités -->
            <div class="tab-pane fade" id="features">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Fonctionnalités Principales</h3>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <h5 class="text-primary mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
                                                Gestion des Ventes
                                            </h5>
                                            <ul>
                                                <li>Créer et valider des ventes rapides</li>
                                                <li>Génération automatique de factures</li>
                                                <li>Suivi du statut des factures</li>
                                                <li>Calcul automatique TVA 16%</li>
                                                <li>Historique complet des ventes</li>
                                                <li>Modes de paiement multiples</li>
                                            </ul>
                                        </div>

                                        <div class="mb-4">
                                            <h5 class="text-primary mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="15" y2="6"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="18" x2="15" y2="18"/></svg>
                                                Produits &amp; Stock
                                            </h5>
                                            <p><span class="badge bg-warning">Admin uniquement</span></p>
                                            <ul>
                                                <li>Création de produits et catégories</li>
                                                <li>Suivi des quantités en stock</li>
                                                <li>Alertes de stock faible</li>
                                                <li>Mouvements de stock traçables</li>
                                                <li>Images produits personnalisées</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <h5 class="text-primary mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                                                Gestion Clients
                                            </h5>
                                            <ul>
                                                <li>Création et édition de clients</li>
                                                <li>Historique d'achat détaillé</li>
                                                <li>Statistiques clients</li>
                                                <li>Notes et commentaires clients</li>
                                                <li>Export de données clients</li>
                                            </ul>
                                        </div>

                                        <div class="mb-4">
                                            <h5 class="text-primary mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="12" width="6" height="8" rx="1"/><rect x="9" y="8" width="6" height="12" rx="1"/><rect x="15" y="4" width="6" height="16" rx="1"/></svg>
                                                Rapports &amp; Analytiques
                                            </h5>
                                            <ul>
                                                <li>Tableaux de bord temps réel</li>
                                                <li>Graphiques de chiffre d'affaires</li>
                                                <li>Rapports par période</li>
                                                <li>Export en Excel et PDF</li>
                                                <li>Analyse des ventes par produit</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Configuration &amp; Paramètres
                                        </h5>
                                        <p><span class="badge bg-warning">Admin uniquement</span></p>
                                        <ul>
                                            <li>Personnalisation du logo boutique</li>
                                            <li>Configuration des couleurs</li>
                                            <li>Paramètres de devise</li>
                                            <li>Gestion des utilisateurs et droits d'accès</li>
                                            <li>Sauvegarde et réinitialisation des données</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                                            Gestion Utilisateurs
                                        </h5>
                                        <p><span class="badge bg-warning">Admin uniquement</span></p>
                                        <ul>
                                            <li>Création de comptes utilisateurs</li>
                                            <li>Attribution de rôles (Admin, Vendeur)</li>
                                            <li>Suivi des connexions</li>
                                            <li>Gestion des permissions</li>
                                            <li>Historique des actions utilisateur</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sections disponibles -->
            <div class="tab-pane fade" id="sections">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Navigation et Sections</h3>
                                
                                <p class="text-muted mb-4">Voici les différentes sections de Store Suite et leur niveau d'accès.</p>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Section</th>
                                                <th>Description</th>
                                                <th>Accès</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/></svg>
                                                    <strong>Accueil</strong>
                                                </td>
                                                <td>Tableau de bord avec statistiques et graphiques</td>
                                                <td><span class="badge bg-success">Tous</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="6" cy="19" r="2"/><circle cx="17" cy="19" r="2"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
                                                    <strong>Vente</strong>
                                                </td>
                                                <td>Création et gestion des ventes et factures</td>
                                                <td><span class="badge bg-success">Tous</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="9.01" y2="12"/><line x1="13" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="9.01" y2="16"/><line x1="13" y1="16" x2="15" y2="16"/></svg>
                                                    <strong>Facture</strong>
                                                </td>
                                                <td>Consultation et gestion des factures</td>
                                                <td><span class="badge bg-success">Tous</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="15" y2="6"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="18" x2="15" y2="18"/></svg>
                                                    <strong>Produits &amp; Stock</strong>
                                                </td>
                                                <td>Gestion des produits, catégories et stocks</td>
                                                <td><span class="badge bg-warning">Admin uniquement</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="12" width="6" height="8" rx="1"/><rect x="9" y="8" width="6" height="12" rx="1"/><rect x="15" y="4" width="6" height="16" rx="1"/></svg>
                                                    <strong>Rapports</strong>
                                                </td>
                                                <td>Génération de rapports et analyses</td>
                                                <td><span class="badge bg-success">Tous</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="4" y="13" width="16" height="7" rx="1"/></svg>
                                                    <strong>Listes</strong>
                                                </td>
                                                <td>Gestion des clients et catégories</td>
                                                <td><span class="badge bg-warning">Admin uniquement</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/><path d="M12 12l8 -4.5"/><path d="M12 12l0 9"/><path d="M12 12l-8 -4.5"/></svg>
                                                    <strong>Administration</strong>
                                                </td>
                                                <td>Gestion des utilisateurs et configuration système</td>
                                                <td><span class="badge bg-warning">Admin uniquement</span></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                                                    <strong>Profil</strong>
                                                </td>
                                                <td>Gestion de votre profil utilisateur personnel</td>
                                                <td><span class="badge bg-success">Tous</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guides pratiques -->
            <div class="tab-pane fade" id="howto">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Guides Pratiques</h3>
                                
                                <div class="accordion" id="accordionGuides">
                                    
                                    <!-- Guide 1 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                                Comment créer une vente ?
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Cliquez sur <strong>Vente</strong> dans le menu principal</li>
                                                    <li>Sélectionnez ou créez un client</li>
                                                    <li>Recherchez et ajoutez les produits à vendre</li>
                                                    <li>Vérifiez les quantités et les prix</li>
                                                    <li>Sélectionnez le mode de paiement</li>
                                                    <li>Cliquez sur <strong>Valider la vente</strong></li>
                                                    <li>Une facture sera automatiquement générée et imprimée</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guide 2 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                                Comment imprimer une facture ?
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Allez dans <strong>Facture</strong> au menu</li>
                                                    <li>Cherchez la facture par numéro ou date</li>
                                                    <li>Cliquez sur <strong>Voir</strong> pour afficher la facture</li>
                                                    <li>Utilisez le bouton <strong>Imprimer</strong> de votre navigateur (Ctrl+P)</li>
                                                    <li>Choisissez votre imprimante et imprimez</li>
                                                </ol>
                                                <p class="text-muted mt-3"><small>La facture s'affichera au format prêt à imprimer avec toutes les informations légales.</small></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guide 3 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                                Comment ajouter un client ?
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Allez dans <strong>Listes</strong> (Admin uniquement)</li>
                                                    <li>Cliquez sur l'onglet <strong>Clients</strong></li>
                                                    <li>Cliquez sur <strong>Ajouter un client</strong></li>
                                                    <li>Remplissez les informations :
                                                        <ul>
                                                            <li>Nom complet</li>
                                                            <li>Numéro de téléphone</li>
                                                            <li>Email</li>
                                                            <li>Adresse</li>
                                                            <li>Type (Particulier ou Entreprise)</li>
                                                        </ul>
                                                    </li>
                                                    <li>Cliquez sur <strong>Enregistrer</strong></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guide 4 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                                Comment ajouter un produit ? (Admin)
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Allez dans <strong>Produits &amp; Stock</strong></li>
                                                    <li>Cliquez sur l'onglet <strong>Produits</strong></li>
                                                    <li>Cliquez sur <strong>Ajouter un produit</strong></li>
                                                    <li>Remplissez les informations essentielles :
                                                        <ul>
                                                            <li>Code produit (unique)</li>
                                                            <li>Nom produit</li>
                                                            <li>Catégorie</li>
                                                            <li>Prix d'achat</li>
                                                            <li>Prix de vente</li>
                                                            <li>Quantité en stock</li>
                                                        </ul>
                                                    </li>
                                                    <li>Ajoutez une image si désiré</li>
                                                    <li>Cliquez sur <strong>Enregistrer</strong></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guide 5 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFive">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                                Comment consulter les rapports ?
                                            </button>
                                        </h2>
                                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Allez dans <strong>Rapports</strong> au menu</li>
                                                    <li>Choisissez le type de rapport :
                                                        <ul>
                                                            <li>Chiffre d'affaires par période</li>
                                                            <li>Produits les plus vendus</li>
                                                            <li>Clients les plus actifs</li>
                                                            <li>Analyse de stock</li>
                                                        </ul>
                                                    </li>
                                                    <li>Sélectionnez la période (jour, semaine, mois, année)</li>
                                                    <li>Visualisez les graphiques et données</li>
                                                    <li>Exportez en Excel ou PDF si besoin</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guide 6 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSix">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                                Comment gérer mon profil ?
                                            </button>
                                        </h2>
                                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Cliquez sur votre avatar en haut à droite</li>
                                                    <li>Sélectionnez <strong>Mon Profil</strong></li>
                                                    <li>Vous pouvez modifier :
                                                        <ul>
                                                            <li>Votre nom complet</li>
                                                            <li>Votre email</li>
                                                            <li>Votre téléphone</li>
                                                            <li>Votre photo de profil</li>
                                                            <li>Votre mot de passe</li>
                                                        </ul>
                                                    </li>
                                                    <li>Cliquez sur <strong>Enregistrer</strong></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guide 7 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSeven">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">
                                                Comment configurer le système ? (Admin)
                                            </button>
                                        </h2>
                                        <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionGuides">
                                            <div class="accordion-body">
                                                <ol>
                                                    <li>Cliquez sur le menu <strong>Administration</strong></li>
                                                    <li>Sélectionnez <strong>Paramètres Généraux</strong></li>
                                                    <li>Vous pouvez configurer :
                                                        <ul>
                                                            <li><strong>Logo boutique</strong> - Téléchargez votre logo</li>
                                                            <li><strong>Nom boutique</strong> - Le nom qui apparaît partout</li>
                                                            <li><strong>Devise</strong> - USD, EUR, XOF, etc.</li>
                                                            <li><strong>Couleurs</strong> - Personnalisez les couleurs primaire et secondaire</li>
                                                        </ul>
                                                    </li>
                                                    <li>Cliquez sur <strong>Enregistrer</strong> pour valider</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- À propos -->
            <div class="tab-pane fade" id="about">
                <div class="row">
                    <!-- Informations Produit -->
                    <div class="col-lg-8 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title mb-4" style="color: <?php echo $couleur_primaire; ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="6" cy="19" r="2"/>
                                        <circle cx="17" cy="19" r="2"/>
                                        <path d="M17 17h-11v-14h-2"/>
                                        <path d="M6 5l14 1l-1 7h-13"/>
                                    </svg>
                                    À Propos de Store Suite
                                </h3>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h5 style="color: <?php echo $couleur_primaire; ?>;">Informations Produit</h5>
                                        <div class="list-group list-group-flush">
                                            <div class="list-group-item border-0 px-0 py-2">
                                                <small class="text-muted">Nom</small>
                                                <div><strong>Store Suite</strong></div>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2">
                                                <small class="text-muted">Type</small>
                                                <div><strong>Système de Gestion de Boutique</strong></div>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2">
                                                <small class="text-muted">Langue</small>
                                                <div><strong>Français (100%)</strong></div>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2">
                                                <small class="text-muted">Version</small>
                                                <div><strong>2.0</strong> <span class="badge bg-success ms-2">Stable</span></div>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2">
                                                <small class="text-muted">Interface</small>
                                                <div><strong>Responsive (Desktop, Tablet, Mobile)</strong></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <h5 style="color: <?php echo $couleur_primaire; ?>;">Caractéristiques</h5>
                                        <div class="list-group list-group-flush">
                                            <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 12 11 14 15 10" /></svg>
                                                <span>Gestion complète des ventes</span>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 12 11 14 15 10" /></svg>
                                                <span>Suivi du stock en temps réel</span>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 12 11 14 15 10" /></svg>
                                                <span>Rapports et analytiques avancés</span>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 12 11 14 15 10" /></svg>
                                                <span>Gestion des utilisateurs et droits</span>
                                            </div>
                                            <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 12 11 14 15 10" /></svg>
                                                <span>Facturation automatisée</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="alert alert-info border-0 rounded-lg">
                                    <div class="d-flex align-items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 mt-1 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9"/>
                                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                                            <polyline points="11 12 12 12 12 16 13 16"/>
                                        </svg>
                                        <div>
                                            <strong>Support :</strong> Pour toute question ou problème technique, veuillez contacter votre administrateur système.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Développeur / À Propos -->
                    <div class="col-lg-4 mb-4">
                        <!-- Card Développeur -->
                        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, <?php echo $couleur_primaire; ?> 0%, <?php echo $couleur_secondaire; ?> 100%); color: white;">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="64" height="64" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.9;">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="8" r="4"/>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                    </svg>
                                </div>
                                <h5 class="card-title mb-1">Créateur & Développeur</h5>
                                <p class="mb-3" style="font-size: 0.95rem; opacity: 0.95;">Emmanuel Baraka</p>
                                <div class="divider divider-light my-3"></div>
                                <p class="small mb-3" style="opacity: 0.9;">Passionné par l'innovation et la création de solutions logicielles pour les petits commerces.</p>
                                
                                <div class="mt-4">
                                    <a href="https://cd.linkedin.com/in/emmanuel-baraka" target="_blank" class="btn btn-light btn-sm w-100 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                                            <line x1="8" y1="9" x2="8" y2="15"/>
                                            <line x1="12" y1="9" x2="12" y2="15"/>
                                            <line x1="16" y1="9" x2="16" y2="15"/>
                                            <line x1="8" y1="9" x2="16" y2="9"/>
                                            <circle cx="6.5" cy="6.5" r="1.5"/>
                                        </svg>
                                        Visitez LinkedIn
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Copyright -->
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-body">
                                <h6 class="card-title" style="color: <?php echo $couleur_primaire; ?>;">Copyright & Licence</h6>
                                <p class="text-muted small mb-0">
                                    <strong>Store Suite v2.0</strong><br>
                                    © 2024 - 2026<br>
                                    Tous droits réservés<br><br>
                                    <em>Conçu et développé avec passion pour faciliter la gestion de votre commerce.</em>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php 
if ($is_logged_in) {
    // Connecté: utiliser footer normal (identique à partout ailleurs)
    require_once('footer.php');
} else {
    // Non-connecté: footer simplifié
    ?>
            </div>
        </div>
        <footer class="footer footer-transparent d-print-none border-top mt-auto" style="margin-top: auto;">
            <div class="container-xl">
                <div class="row align-items-center py-3">
                    <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                        <div class="text-muted small">
                            <a href="index.php" class="link-secondary text-decoration-none">Se connecter</a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3 mb-md-0">
                        <div class="text-muted small">
                            © <script>document.write(new Date().getFullYear());</script>
                            <a href="javascript:void(0)" class="link-secondary fw-semibold text-decoration-none">Store Suite</a>
                        </div>
                        <div class="text-muted small mt-1">
                            <a href="aide.php" class="link-secondary text-decoration-none">Aide & À Propos</a> | Tous droits réservés
                        </div>
                    </div>
                    <div class="col-md-4 text-center text-md-end">
                        <div class="text-muted small">
                            Version 2.0
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
    </html>
    <?php
}
?>
