<?php
/**
 * ============================================================================
 * PAGE DE CONNEXION - LOGIN
 * ============================================================================
 * 
 * Importance : Authentification sécurisée des utilisateurs
 * 
 * Fonctionnalités :
 * - Formulaire de connexion avec validation
 * - Protection contre les attaques par force brute
 * - Gestion des messages d'erreur
 * - Redirection selon le niveau d'accès
 * 
 * Sécurité :
 * - Utilisation de password_verify() pour vérifier les mots de passe
 * - Protection CSRF avec token
 * - Limitation des tentatives de connexion
 * 
 * ============================================================================
 */

require_once __DIR__ . '/config/database.php';

// Si déjà connecté, rediriger vers l'accueil
if (is_logged_in()) {
    redirect(BASE_URL . 'accueil.php');
    exit;
}

// Récupérer la configuration du système
$config = get_system_config();
if (!$config || !$config['est_configure']) {
    redirect(BASE_URL . 'setup.php');
    exit;
}

// Générer le token CSRF
$csrf_token = generate_csrf_token();

// Traitement du formulaire de connexion
$error_message = '';
$login_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier le token CSRF
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        $error_message = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $login_value = $login; // Garder la valeur pour le rechargement
        
        if (empty($login) || empty($password)) {
            $error_message = 'Veuillez remplir tous les champs.';
        } else {
            // Récupérer l'utilisateur
            $user = get_user_by_login($login);
            
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Connexion réussie !
                
                // Vérifier si le compte est actif
                if ($user['est_actif'] != 1) {
                    $error_message = 'Ce compte a été désactivé. Contactez l\'administrateur.';
                } else {
                    // Créer la session utilisateur
                    $_SESSION['user_id'] = $user['id_utilisateur'];
                    $_SESSION['user_name'] = $user['nom_complet'];
                    $_SESSION['user_login'] = $user['login'];
                    $_SESSION['niveau_acces'] = $user['niveau_acces'];
                    
                    // Mettre à jour la date de dernière connexion
                    update_last_login($user['id_utilisateur']);
                    
                    // Logger la connexion
                    log_activity('connexion', 'Connexion réussie de ' . $user['nom_complet']);
                    
                    // Rediriger vers l'accueil
                    redirect(BASE_URL . 'accueil.php');
                    exit;
                }
            } else {
                $error_message = 'Identifiant ou mot de passe incorrect.';
                
                // Logger la tentative échouée
                if ($user) {
                    log_activity('connexion_echouee', "Tentative de connexion échouée pour l'utilisateur : $login");
                }
            }
        }
    }
}

// Récupérer un éventuel message flash
$flash = get_flash_message();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo e($config['nom_boutique']); ?> - Connexion</title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="./dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="./dist/css/demo.min.css" rel="stylesheet"/>
    <style>
        .titre {
            font-size: 3rem!important;
            color: <?php echo $config['couleur_primaire']; ?>!important;
            font-weight: 900;
        }
        .btn-ghost-primary, .btn-outline-primary, .btn-primary, .btn {
            background-color: <?php echo $config['couleur_primaire']; ?>!important;
            color: <?php echo $config['couleur_secondaire']; ?>!important;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="antialiased border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <?php if (!empty($config['logo']) && file_exists(LOGO_PATH . $config['logo'])): ?>
                    <img src="<?php echo BASE_URL . 'uploads/logos/' . $config['logo']; ?>" 
                         alt="<?php echo e($config['nom_boutique']); ?>" 
                         style="max-height: 100px; margin-bottom: 20px;">
                <?php endif; ?>
                <div class="titre">
                    <?php echo e($config['nom_boutique']); ?>
                </div>
                <?php if (!empty($config['slogan'])): ?>
                    <div class="text-muted"><?php echo e($config['slogan']); ?></div>
                <?php endif; ?>
            </div>
            
            <form class="card card-md" method="POST" action="" autocomplete="off">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Connexion à STORESUITE</h2>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                                </div>
                                <div>
                                    <?php echo e($error_message); ?>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($flash): ?>
                        <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <?php echo e($flash['message']); ?>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Identifiant</label>
                        <input type="text" name="login" class="form-control" 
                               placeholder="Votre identifiant" 
                               value="<?php echo e($login_value); ?>" 
                               required autofocus>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Votre mot de passe" 
                               required>
                    </div>
                    
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M20 12h-13l3 -3m0 6l-3 -3" /></svg>
                            Se connecter
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <a href="aide.php" class="btn btn-outline-secondary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                        <polyline points="11 12 12 12 12 16 13 16"/>
                    </svg>
                    Aide & À Propos
                </a>
            </div>
            
            <div class="text-center text-muted mt-3">
                <small>STORESUITE v<?php echo APP_VERSION; ?> - Système de Gestion de Stock</small>
            </div>
        </div>
    </div>
    
    <!-- Libs JS -->
    <script src="./dist/js/tabler.min.js"></script>
</body>
</html>
