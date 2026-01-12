<?php
/**
 * CONFIGURATION PRODUCTION - storesuite.shop
 * 
 * ⚠️ FICHIER PRÊT POUR DÉPLOIEMENT HOSTINGER
 * 
 * Instructions:
 * 1. Uploader ce fichier sur Hostinger dans le dossier config/
 * 2. Le renommer en config.php sur le serveur
 * 3. Vérifier les permissions: 644
 * 4. NE JAMAIS committer ce fichier sur Git!
 */

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// BASE DE DONNÉES HOSTINGER - storesuite.shop
// ============================================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'u783961849_storesuite');
define('DB_USER', 'u783961849_emmanuel');
define('DB_PASS', 'Hallelujah2018');
define('DB_CHARSET', 'utf8mb4');

// ============================================================================
// URLS ET CHEMINS
// ============================================================================
define('ROOT_PATH', dirname(__DIR__));

// URL de base du site
define('BASE_URL', 'https://storesuite.shop/');

define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('LOGO_PATH', UPLOAD_PATH . 'logos/');
define('PRODUCT_IMG_PATH', UPLOAD_PATH . 'produits/');
define('USER_IMG_PATH', UPLOAD_PATH . 'utilisateurs/');

// ============================================================================
// SÉCURITÉ
// ============================================================================
// Clé secrète unique pour cette installation
define('SECRET_KEY', 'F7k9mP2nX#wL4v@Q8rT$y5jB0hGc3fDe1AZ7bM4sJ6pY9w');

// Durée de validité de la session (2 heures)
define('SESSION_LIFETIME', 7200);

// Protection contre force brute
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_BLOCK_DURATION', 15); // minutes

// ============================================================================
// APPLICATION
// ============================================================================
define('APP_NAME', 'STORESUITE');
define('APP_VERSION', '2.0.0');

// Fuseau horaire
date_default_timezone_set('Africa/Lubumbashi');

// ============================================================================
// MODE PRODUCTION - Erreurs désactivées
// ============================================================================
define('DEBUG_MODE', false);

if (DEBUG_MODE === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ============================================================================
// NIVEAUX D'ACCÈS
// ============================================================================
define('NIVEAU_ADMIN', 1);
define('NIVEAU_VENDEUR', 2);

// ============================================================================
// STATUTS VENTES
// ============================================================================
define('VENTE_EN_COURS', 'en_cours');
define('VENTE_VALIDEE', 'validee');
define('VENTE_ANNULEE', 'annulee');

// ============================================================================
// TYPES MOUVEMENTS STOCK
// ============================================================================
define('MOUVEMENT_ENTREE', 'entree');
define('MOUVEMENT_SORTIE', 'sortie');
define('MOUVEMENT_AJUSTEMENT', 'ajustement');
define('MOUVEMENT_RETOUR', 'retour');

// ============================================================================
// CONFIGURATION UPLOADS
// ============================================================================
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// ============================================================================
// FONCTIONS UTILITAIRES
// ============================================================================

/**
 * Échappe les données HTML (prévention XSS)
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirige vers une URL
 */
function redirect($url, $code = 302) {
    header("Location: $url", true, $code);
    exit;
}

/**
 * Affiche une erreur fatale
 */
function die_error($message) {
    die("<div style='background:#f8d7da;color:#721c24;padding:20px;border:1px solid #f5c6cb;border-radius:5px;margin:20px;'>
        <strong>Erreur :</strong> $message
    </div>");
}

/**
 * Formate un montant avec devise
 */
function format_montant($montant, $devise = '$') {
    return number_format($montant, 2, ',', ' ') . ' ' . $devise;
}

/**
 * Formate une date
 */
function format_date($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

// ============================================================================
// INFORMATIONS DE CONNEXION
// ============================================================================
/*
 * SITE: https://storesuite.shop/
 * 
 * BASE DE DONNÉES:
 * - Nom: u783961849_storesuite
 * - User: u783961849_emmanuel
 * - Pass: Hallelujah2018
 * - Host: localhost
 * 
 * ACCÈS ADMIN PAR DÉFAUT:
 * - Login: admin
 * - Password: admin (À CHANGER après première connexion!)
 * 
 * STRUCTURE FICHIERS SUR SERVEUR:
 * public_html/
 * ├── .htaccess
 * ├── index.php
 * ├── login.php
 * ├── error_404.php
 * ├── error_500.php
 * ├── config/
 * │   ├── config.php (CE FICHIER renommé)
 * │   └── database.php
 * ├── ajax/
 * ├── assets/
 * └── uploads/ (permissions 755)
 *     ├── logos/
 *     ├── produits/
 *     └── utilisateurs/
 * 
 * TESTS À FAIRE APRÈS DÉPLOIEMENT:
 * 1. https://storesuite.shop/diagnostic_500.php
 * 2. https://storesuite.shop/login.php
 * 3. Connexion avec admin/admin
 * 4. Changer mot de passe admin
 * 5. Tester ajout produit
 * 6. Tester création vente
 * 
 * SÉCURITÉ POST-DÉPLOIEMENT:
 * - Supprimer diagnostic_500.php, debug_login.php, check_config.php
 * - Changer mot de passe admin
 * - Vérifier que DEBUG_MODE = false
 */
?>
