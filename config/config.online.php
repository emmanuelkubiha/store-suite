<?php
/**
 * CONFIGURATION EN LIGNE (copie à utiliser sur shop.fosip-drc.org)
 * - Ne pas committer ce fichier avec des secrets.
 * - Quand vous déployez, remplacez config.php par ces valeurs ou incluez ce fichier.
 */

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base de données (hébergement mutualisé)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'fosip2610679_3lxbcd');
define('DB_USER', 'fosip2610679');
define('DB_PASS', 'mZ1-CDF**CC-TXh');
define('DB_CHARSET', 'utf8mb4');

// URLs et chemins
define('ROOT_PATH', dirname(__DIR__));
// Adapter si le projet est dans un sous-dossier, sinon laisser avec le / final
define('BASE_URL', 'https://shop.fosip-drc.org/');

define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('LOGO_PATH', UPLOAD_PATH . 'logos/');
define('PRODUCT_IMG_PATH', UPLOAD_PATH . 'produits/');
define('USER_IMG_PATH', UPLOAD_PATH . 'utilisateurs/');

// Sécurité
// Clé générée automatiquement pour la production
define('SECRET_KEY', 'F7k9mP2nX#wL4v@Q8rT$y5jB0hGc3fDe1AZ7bM4sJ6pY9w');
define('SESSION_LIFETIME', 7200);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_BLOCK_DURATION', 15);

// Application
define('APP_NAME', 'STORESUITE');
define('APP_VERSION', '2.0.0');

date_default_timezone_set('Africa/Lubumbashi');

// Logs/erreurs : laisser display_errors off en prod
if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Niveaux d'accès
define('NIVEAU_ADMIN', 1);
define('NIVEAU_VENDEUR', 2);

// Statuts ventes
define('VENTE_EN_COURS', 'en_cours');
define('VENTE_VALIDEE', 'validee');
define('VENTE_ANNULEE', 'annulee');

// Types mouvements stock
define('MOUVEMENT_ENTREE', 'entree');
define('MOUVEMENT_SORTIE', 'sortie');
define('MOUVEMENT_AJUSTEMENT', 'ajustement');
define('MOUVEMENT_RETOUR', 'retour');

// Uploads
define('MAX_FILE_SIZE', 5242880);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Helpers minimaux (copiés pour usage direct si ce fichier est standalone)
function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
function format_montant($montant, $devise = '$') { return number_format($montant, 2, ',', ' ') . ' ' . $devise; }
function redirect($url, $code = 302) { header("Location: $url", true, $code); exit; }
?>
