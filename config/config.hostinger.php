<?php
/**
 * CONFIGURATION HOSTINGER - shop.fosip-drc.org
 * 
 * Instructions:
 * 1. Remplir les informations de base de données Hostinger
 * 2. Renommer ce fichier en config.php
 * 3. Le placer dans le dossier config/ sur le serveur
 * 4. NE JAMAIS committer sur Git avec les vrais credentials
 */

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// BASE DE DONNÉES HOSTINGER - À REMPLIR
// ============================================================================
define('DB_HOST', 'localhost');                    // Généralement localhost sur Hostinger
define('DB_NAME', 'VOTRE_NOM_BASE_DONNEES');      // Nom de la base de données
define('DB_USER', 'VOTRE_UTILISATEUR_BD');         // Nom d'utilisateur MySQL
define('DB_PASS', 'VOTRE_MOT_DE_PASSE_BD');        // Mot de passe MySQL
define('DB_CHARSET', 'utf8mb4');

// ============================================================================
// URLS ET CHEMINS
// ============================================================================
define('ROOT_PATH', dirname(__DIR__));

// URL de base - ADAPTER selon votre configuration Hostinger
// Si à la racine du domaine: https://shop.fosip-drc.org/
// Si dans un sous-dossier: https://shop.fosip-drc.org/storesuite/
define('BASE_URL', 'https://shop.fosip-drc.org/');

define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('LOGO_PATH', UPLOAD_PATH . 'logos/');
define('PRODUCT_IMG_PATH', UPLOAD_PATH . 'produits/');
define('USER_IMG_PATH', UPLOAD_PATH . 'utilisateurs/');

// ============================================================================
// SÉCURITÉ
// ============================================================================
// Clé secrète unique - NE JAMAIS PARTAGER
// Générer une nouvelle avec: bin2hex(random_bytes(32))
define('SECRET_KEY', 'F7k9mP2nX#wL4v@Q8rT$y5jB0hGc3fDe1AZ7bM4sJ6pY9w');

// Durée de validité de la session (2 heures = 7200 secondes)
define('SESSION_LIFETIME', 7200);

// Tentatives de connexion
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
// MODE DEBUG - TOUJOURS OFF EN PRODUCTION!
// ============================================================================
// En production (Hostinger), toujours laisser à false
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
// FONCTIONS UTILITAIRES (définies ici pour standalone)
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
// NOTES IMPORTANTES POUR HOSTINGER
// ============================================================================
/*
 * 1. EMPLACEMENT DES FICHIERS:
 *    - Les fichiers doivent être dans public_html/ ou domains/shop.fosip-drc.org/public_html/
 *    
 * 2. BASE DE DONNÉES:
 *    - Créer la base via le panneau Hostinger (Bases de données MySQL)
 *    - Noter le nom exact de la base, utilisateur et mot de passe
 *    - Importer storesuite_online.sql via phpMyAdmin
 *    
 * 3. PERMISSIONS FICHIERS:
 *    - Dossiers: 755
 *    - Fichiers PHP: 644
 *    - uploads/: 755 (avec sous-dossiers logos, produits, utilisateurs)
 *    
 * 4. .htaccess:
 *    - Le fichier .htaccess doit être à la racine du projet
 *    - Vérifier qu'il est bien uploadé (fichier caché)
 *    
 * 5. SSL/HTTPS:
 *    - Hostinger fournit SSL gratuit
 *    - Activer dans le panneau: Avancé → SSL
 *    - BASE_URL doit commencer par https://
 *    
 * 6. PHP VERSION:
 *    - Recommandé: PHP 8.0 ou supérieur
 *    - Changer dans: Avancé → Configuration PHP
 */
?>
