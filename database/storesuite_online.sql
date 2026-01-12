-- STORESUITE - Dump ajusté pour hébergement mutualisé
-- Changements vs storesuite.sql :
-- 1) Colonne utilisateurs.password_hash (au lieu de mot_de_passe) pour matcher le code.
-- 2) Vues recréées sans DEFINER root, avec SQL SECURITY INVOKER (évite l'erreur "user specified as definer does not exist").
-- 3) Contenu inchangé sinon (tables, données de démo, triggers, indexes, contraintes).

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base : storesuite

-- --------------------------------------------------------
-- Table categories
CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(255) NOT NULL COMMENT 'Nom de la catégorie',
  `description` text DEFAULT NULL COMMENT 'Description de la catégorie',
  `icone` varchar(100) DEFAULT NULL COMMENT 'Icône ou classe CSS',
  `couleur` varchar(7) DEFAULT NULL COMMENT 'Couleur associée (format HEX)',
  `ordre_affichage` int(11) DEFAULT 0 COMMENT 'Ordre d''affichage',
  `est_actif` tinyint(1) DEFAULT 1 COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catégories de produits';

INSERT INTO `categories` (`id_categorie`, `nom_categorie`, `description`, `icone`, `couleur`, `ordre_affichage`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, 'Électronique', 'Téléphones, ordinateurs, accessoires', 'ti-device-laptop', '#3498db', 1, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(2, 'Électroménager', 'Réfrigérateurs, télévisions, cuisinières', 'ti-device-tv', '#e74c3c', 2, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(3, 'Meubles', 'Tables, chaises, armoires', 'ti-armchair', '#9b59b6', 3, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(4, 'Vêtements', 'Habits, chaussures, accessoires', 'ti-hanger', '#1abc9c', 4, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(5, 'Alimentation', 'Produits alimentaires', 'ti-shopping-cart', '#f39c12', 5, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48');

-- --------------------------------------------------------
-- Table clients
CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom_client` varchar(255) NOT NULL COMMENT 'Nom du client',
  `telephone` varchar(50) DEFAULT NULL COMMENT 'Téléphone',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email',
  `adresse` text DEFAULT NULL COMMENT 'Adresse complète',
  `type_client` enum('particulier','entreprise') DEFAULT 'particulier',
  `numero_fiscal` varchar(100) DEFAULT NULL COMMENT 'Numéro fiscal (pour entreprises)',
  `total_achats` decimal(15,2) DEFAULT 0.00 COMMENT 'Total des achats',
  `nombre_achats` int(11) DEFAULT 0 COMMENT 'Nombre d''achats',
  `date_dernier_achat` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL COMMENT 'Notes sur le client',
  `est_actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Base de données clients';

-- --------------------------------------------------------
-- Table configuration
CREATE TABLE `configuration` (
  `id_config` int(11) NOT NULL,
  `nom_boutique` varchar(255) NOT NULL COMMENT 'Nom de la boutique/entreprise',
  `slogan` varchar(255) DEFAULT NULL COMMENT 'Slogan ou description courte',
  `logo` varchar(255) DEFAULT NULL COMMENT 'Chemin vers le fichier logo',
  `couleur_primaire` varchar(7) DEFAULT '#e6e64c' COMMENT 'Couleur principale (format HEX)',
  `couleur_secondaire` varchar(7) DEFAULT '#556a94' COMMENT 'Couleur secondaire (format HEX)',
  `adresse` text DEFAULT NULL COMMENT 'Adresse complète de l''entreprise',
  `telephone` varchar(100) DEFAULT NULL COMMENT 'Numéro(s) de téléphone',
  `email` varchar(255) DEFAULT NULL COMMENT 'Adresse email',
  `site_web` varchar(255) DEFAULT NULL COMMENT 'Site web de l''entreprise',
  `num_registre_commerce` varchar(100) DEFAULT NULL COMMENT 'Numéro d''enregistrement (RCCM, etc.)',
  `num_impot` varchar(100) DEFAULT NULL COMMENT 'Numéro fiscal/TVA',
  `devise` varchar(10) DEFAULT '$' COMMENT 'Symbole de la devise utilisée',
  `taux_tva` decimal(5,2) DEFAULT 0.00 COMMENT 'Taux de TVA par défaut (%)',
  `fuseau_horaire` varchar(50) DEFAULT 'Africa/Lubumbashi' COMMENT 'Fuseau horaire',
  `langue` varchar(10) DEFAULT 'fr' COMMENT 'Langue du système (fr, en, etc.)',
  `est_configure` tinyint(1) DEFAULT 0 COMMENT '0=Non configuré, 1=Configuré',
  `date_configuration` datetime DEFAULT NULL COMMENT 'Date de première configuration',
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Paramètres globaux du système';

INSERT INTO `configuration` (`id_config`, `nom_boutique`, `slogan`, `logo`, `couleur_primaire`, `couleur_secondaire`, `adresse`, `telephone`, `email`, `site_web`, `num_registre_commerce`, `num_impot`, `devise`, `taux_tva`, `fuseau_horaire`, `langue`, `est_configure`, `date_configuration`, `date_modification`) VALUES
(1, 'CALEB SHOP', 'votre boutique d''excellence', 'uploads/logos/logo_boutique.png', '#206bc4', '#ffffff', '', '', '', 'https://www.test.com', 'RCCM/TEST/123', 'IMP-12345', 'CDF', 0.00, 'Africa/Lubumbashi', 'fr', 1, '2026-01-09 09:42:27', '2026-01-10 22:53:43');

-- --------------------------------------------------------
-- Table details_vente
CREATE TABLE `details_vente` (
  `id_detail` int(11) NOT NULL,
  `id_vente` int(11) NOT NULL COMMENT 'Référence à la vente',
  `id_produit` int(11) NOT NULL COMMENT 'Produit vendu',
  `nom_produit` varchar(255) NOT NULL COMMENT 'Nom du produit',
  `quantite` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantité vendue',
  `prix_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix unitaire',
  `prix_achat_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix achat',
  `prix_total` decimal(15,2) NOT NULL COMMENT 'Total ligne',
  `benefice_ligne` decimal(15,2) NOT NULL COMMENT 'Bénéfice ligne',
  `remise_ligne` decimal(15,2) DEFAULT 0.00 COMMENT 'Remise ligne',
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `details_vente` (`id_detail`, `id_vente`, `id_produit`, `nom_produit`, `quantite`, `prix_unitaire`, `prix_achat_unitaire`, `prix_total`, `benefice_ligne`, `remise_ligne`, `date_creation`) VALUES
(11, 11, 1, '', 1, 1200.00, 0.00, 0.00, 0.00, 0.00, '2026-01-10 12:18:06'),
(12, 12, 1, '', 1, 1200.00, 0.00, 0.00, 0.00, 0.00, '2026-01-11 19:58:32');

-- --------------------------------------------------------
-- Table logs_activites
CREATE TABLE `logs_activites` (
  `id_log` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `type_action` varchar(100) NOT NULL COMMENT 'Type d''action (connexion, vente, modification, etc.)',
  `description` text NOT NULL COMMENT 'Description détaillée de l''action',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Adresse IP',
  `user_agent` text DEFAULT NULL COMMENT 'Navigateur/Device',
  `donnees_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Données supplémentaires en JSON' CHECK (json_valid(`donnees_json`)),
  `date_action` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Journal des activités';

INSERT INTO `logs_activites` (`id_log`, `id_utilisateur`, `type_action`, `description`, `ip_address`, `user_agent`, `donnees_json`, `date_action`) VALUES
(1, 2, 'configuration_initiale', 'Configuration initiale du système effectuée', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"nom_boutique":"Ma Super Boutique Test","admin_login":"admin"}', '2026-01-09 09:33:05'),
(2, 3, 'configuration_initiale', 'Configuration initiale du système effectuée', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"nom_boutique":"CALEB SHOP","admin_login":"admin"}', '2026-01-09 09:42:27'),
(3, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 08:43:43'),
(4, 3, 'deconnexion', 'Déconnexion de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 09:07:31'),
(5, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 09:07:50'),
(6, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 17:23:31'),
(7, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0001 (1392 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"4","numero_facture":"FAC-20260109-0001","montant":1392}', '2026-01-09 19:49:49'),
(8, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0002 (1392 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"5","numero_facture":"FAC-20260109-0002","montant":1392}', '2026-01-09 19:50:24'),
(9, 3, 'VENTE_ANNULEE', 'Vente annulée: FAC-20260109-0002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":5,"numero_facture":"FAC-20260109-0002","montant":"1392.00"}', '2026-01-09 20:07:58'),
(10, 3, 'VENTE_SUPPRIMEE', 'Vente supprimée définitivement: FAC-20260109-0002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":5,"numero_facture":"FAC-20260109-0002","montant":"1392.00"}', '2026-01-09 20:19:35'),
(11, 3, 'VENTE_ANNULEE', 'Vente annulée: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":4,"numero_facture":"FAC-20260109-0001","montant":"1392.00"}', '2026-01-09 20:22:15'),
(12, 3, 'VENTE_SUPPRIMEE', 'Vente supprimée définitivement: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":4,"numero_facture":"FAC-20260109-0001","montant":"1392.00"}', '2026-01-09 20:22:21'),
(13, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0001 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"6","numero_facture":"FAC-20260109-0001","montant":1200}', '2026-01-09 20:24:50'),
(14, 3, 'VENTE_ANNULEE', 'Vente annulée: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":6,"numero_facture":"FAC-20260109-0001","montant":"1200.00"}', '2026-01-09 20:28:54'),
(15, 3, 'VENTE_SUPPRIMEE', 'Vente supprimée définitivement: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":6,"numero_facture":"FAC-20260109-0001","montant":"1200.00"}', '2026-01-09 20:29:05'),
(16, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0001 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"7","numero_facture":"FAC-20260109-0001","montant":1200}', '2026-01-09 20:29:28'),
(17, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0002 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"8","numero_facture":"FAC-20260109-0002","montant":1200}', '2026-01-09 20:53:25'),
(18, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 06:55:55'),
(19, 3, 'vente_restauree', 'Restauration de la vente FAC-20260109-0002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":8,"numero_facture":"FAC-20260109-0002"}', '2026-01-10 07:44:56'),
(20, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260110-0003 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"9","numero_facture":"FAC-20260110-0003","montant":1200}', '2026-01-10 07:53:49'),
(21, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260110-0004 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"10","numero_facture":"FAC-20260110-0004","montant":1200}', '2026-01-10 07:54:55'),
(22, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 08:18:09'),
(23, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '2026-01-10 08:25:52'),
(24, 3, 'REINIT', 'Suppression de toutes les ventes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"type":"ventes"}', '2026-01-10 09:41:08'),
(25, 3, 'REINIT', 'Suppression de tous les clients', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '{"type":"clients"}', '2026-01-10 09:45:06'),
(26, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 10:12:07'),
(27, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 10:31:30'),
(28, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260110-0001 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"11","numero_facture":"FAC-20260110-0001","montant":1200}', '2026-01-10 12:18:06'),
(29, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 19:42:16'),
(30, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 21:21:20'),
(31, 4, 'connexion', 'Connexion réussie de FEFE3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 21:21:36'),
(32, 4, 'deconnexion', 'Déconnexion de FEFE3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 23:46:42'),
(33, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 23:46:50'),
(34, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-11 19:11:44'),
(35, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-11 19:37:25'),
(36, 4, 'connexion', 'Connexion réussie de FEFE3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-11 19:37:29'),
(37, 4, 'VENTE', 'Nouvelle vente créée: FAC-20260111-0002 (1200 CDF)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{"id_vente":"12","numero_facture":"FAC-20260111-0002","montant":1200}', '2026-01-11 19:58:32');

-- --------------------------------------------------------
-- Table mouvements
CREATE TABLE `mouvements` (
  `id_mouvement` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','vente') NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(15,2) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL COMMENT 'Numéro facture ou bon',
  `motif` text DEFAULT NULL,
  `date_mouvement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table mouvements_stock
CREATE TABLE `mouvements_stock` (
  `id_mouvement` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL COMMENT 'Produit concerné',
  `type_mouvement` enum('entree','sortie','ajustement','retour') NOT NULL,
  `quantite` int(11) NOT NULL COMMENT 'Quantité du mouvement',
  `quantite_avant` int(11) NOT NULL COMMENT 'Stock avant le mouvement',
  `quantite_apres` int(11) NOT NULL COMMENT 'Stock après le mouvement',
  `id_vente` int(11) DEFAULT NULL COMMENT 'Référence vente si sortie',
  `id_utilisateur` int(11) NOT NULL COMMENT 'Utilisateur qui a fait l''opération',
  `motif` varchar(255) DEFAULT NULL COMMENT 'Raison du mouvement',
  `notes` text DEFAULT NULL,
  `date_mouvement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Historique des mouvements de stock';

INSERT INTO `mouvements_stock` (`id_mouvement`, `id_produit`, `type_mouvement`, `quantite`, `quantite_avant`, `quantite_apres`, `id_vente`, `id_utilisateur`, `motif`, `notes`, `date_mouvement`) VALUES
(9, 1, 'sortie', 1, 5, 4, NULL, 3, 'Vente FAC-20260109-0001', NULL, '2026-01-09 21:29:28'),
(10, 1, 'sortie', 1, 4, 3, NULL, 3, 'Vente FAC-20260109-0002', NULL, '2026-01-09 21:53:25'),
(11, 1, 'entree', 1, 4, 5, NULL, 3, 'Annulation vente - Facture FAC-20260109-0002', NULL, '2026-01-10 07:28:25'),
(12, 1, 'sortie', 1, 3, 2, NULL, 3, 'Vente FAC-20260110-0003', NULL, '2026-01-10 08:53:49'),
(13, 1, 'sortie', 1, 2, 1, NULL, 3, 'Vente FAC-20260110-0004', NULL, '2026-01-10 08:54:55'),
(14, 1, 'entree', 1, 2, 3, NULL, 3, 'Annulation vente - Facture FAC-20260109-0002', NULL, '2026-01-10 07:57:09'),
(15, 1, 'entree', 1, 3, 4, NULL, 3, 'Annulation vente - Facture FAC-20260110-0003', NULL, '2026-01-10 07:57:20'),
(16, 1, 'sortie', 1, 3, 2, NULL, 3, 'Vente FAC-20260110-0001', NULL, '2026-01-10 13:18:06'),
(17, 1, 'sortie', 1, 2, 1, NULL, 4, 'Vente FAC-20260111-0002', NULL, '2026-01-11 20:58:32');

-- --------------------------------------------------------
-- Table notifications
CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL,
  `type_notification` enum('stock_faible','stock_critique','rupture_stock','vente_importante','systeme') NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `id_produit` int(11) DEFAULT NULL COMMENT 'Produit concerné si applicable',
  `niveau_urgence` enum('info','avertissement','urgent') DEFAULT 'info',
  `est_lue` tinyint(1) DEFAULT 0 COMMENT '0=Non lue, 1=Lue',
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notifications système';

INSERT INTO `notifications` (`id_notification`, `type_notification`, `titre`, `message`, `id_produit`, `niveau_urgence`, `est_lue`, `date_creation`) VALUES
(1, 'systeme', 'Bienvenue !', 'Votre système de gestion de stock a été configuré avec succès. Vous pouvez maintenant commencer à ajouter vos produits et effectuer vos ventes.', NULL, 'info', 0, '2026-01-09 09:33:05'),
(2, 'systeme', 'Bienvenue !', 'Votre système de gestion de stock a été configuré avec succès. Vous pouvez maintenant commencer à ajouter vos produits et effectuer vos ventes.', NULL, 'info', 0, '2026-01-09 09:42:27');

-- --------------------------------------------------------
-- Table produits
CREATE TABLE `produits` (
  `id_produit` int(11) NOT NULL,
  `code_produit` varchar(100) DEFAULT NULL COMMENT 'Code/Référence unique du produit',
  `nom_produit` varchar(255) NOT NULL COMMENT 'Nom du produit',
  `description` text DEFAULT NULL COMMENT 'Description détaillée',
  `id_categorie` int(11) DEFAULT NULL COMMENT 'Catégorie du produit',
  `prix_achat` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Prix d''achat (VISIBLE ADMIN SEULEMENT)',
  `prix_vente` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Prix de vente recommandé',
  `prix_vente_min` decimal(15,2) DEFAULT NULL COMMENT 'Prix de vente minimum autorisé',
  `quantite_stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Quantité actuelle en stock',
  `seuil_alerte` int(11) DEFAULT 10 COMMENT 'Seuil pour alerte stock faible',
  `seuil_critique` int(11) DEFAULT 5 COMMENT 'Seuil critique (alerte rouge)',
  `unite_mesure` varchar(50) DEFAULT 'pièce' COMMENT 'Unité (pièce, kg, litre, etc.)',
  `image` varchar(255) DEFAULT NULL COMMENT 'Image du produit',
  `code_barre` varchar(100) DEFAULT NULL COMMENT 'Code-barres pour scanner',
  `emplacement` varchar(255) DEFAULT NULL COMMENT 'Emplacement dans le magasin',
  `date_entree` date DEFAULT NULL COMMENT 'Date dernière entrée en stock',
  `date_derniere_vente` datetime DEFAULT NULL COMMENT 'Date de la dernière vente',
  `nombre_ventes` int(11) DEFAULT 0 COMMENT 'Nombre total de ventes',
  `est_actif` tinyint(1) DEFAULT 1 COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Produits en stock avec gestion des alertes';

INSERT INTO `produits` (`id_produit`, `code_produit`, `nom_produit`, `description`, `id_categorie`, `prix_achat`, `prix_vente`, `prix_vente_min`, `quantite_stock`, `seuil_alerte`, `seuil_critique`, `unite_mesure`, `image`, `code_barre`, `emplacement`, `date_entree`, `date_derniere_vente`, `nombre_ventes`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, NULL, 'Television Samsung 32''', '', 1, 500.00, 1200.00, NULL, 1, 1, 5, 'pièce', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-01-09 10:24:34', '2026-01-11 18:58:32');

-- --------------------------------------------------------
-- Table utilisateurs (colonne password_hash au lieu de mot_de_passe)
CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom_complet` varchar(255) NOT NULL COMMENT 'Nom complet de l''utilisateur',
  `login` varchar(100) NOT NULL COMMENT 'Identifiant de connexion (unique)',
  `password_hash` varchar(255) NOT NULL COMMENT 'Mot de passe hashé (password_hash)',
  `email` varchar(255) DEFAULT NULL COMMENT 'Adresse email',
  `telephone` varchar(50) DEFAULT NULL COMMENT 'Numéro de téléphone',
  `niveau_acces` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin, 2=Vendeur',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Photo de profil',
  `est_actif` tinyint(1) DEFAULT 1 COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT current_timestamp() COMMENT 'Date de création du compte',
  `date_derniere_connexion` datetime DEFAULT NULL COMMENT 'Dernière connexion',
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Utilisateurs du système avec permissions';

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom_complet`, `login`, `password_hash`, `email`, `telephone`, `niveau_acces`, `photo`, `est_actif`, `date_creation`, `date_derniere_connexion`, `date_modification`) VALUES
(3, 'EMMANUEL BARAKA', 'admin', '$2y$10$qcv4m7Sf5FsYTzk5sfFfe.TtdxPMvI3d5o1e4iv44HI0i/5JbYASy', 'admin@exemple.com', NULL, 1, NULL, 1, '2026-01-09 09:42:27', '2026-01-11 20:11:44', '2026-01-11 18:11:44'),
(4, 'FEFE3', 'fefe', '$2y$10$Z1/QXucaQs2ToNgZJy1Qo.4/7sm5U0DFk6XnhZmEid8CHIiRsxy9O', '', NULL, 2, NULL, 1, '2026-01-10 19:50:24', '2026-01-11 20:37:29', '2026-01-11 18:37:29'),
(5, 'test4', 'test', '$2y$10$JQNoUt1ARFR3O7Vqm0Rgm.B6l/clQPbz2pQj3DYXthGql9EJ9SM6.', '', NULL, 2, NULL, 1, '2026-01-10 20:05:09', NULL, '2026-01-10 20:09:04'),
(6, 'FEFE3', 'fefe4', '$2y$10$A9b/JCv5khrvwEf4Jb8XcOm6twS2e2fa618q3lPNsmu3hxiQXlBZa', 'coordination@fosip-drc.org', NULL, 2, NULL, 1, '2026-01-10 21:10:34', NULL, '2026-01-10 20:20:48');

-- --------------------------------------------------------
-- Table ventes
CREATE TABLE `ventes` (
  `id_vente` int(11) NOT NULL,
  `numero_facture` varchar(50) NOT NULL COMMENT 'Numéro unique de la facture',
  `id_client` int(11) DEFAULT NULL COMMENT 'Client (NULL = vente comptoir)',
  `id_vendeur` int(11) NOT NULL COMMENT 'Vendeur/Caissier qui a effectué la vente',
  `montant_total` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Montant total de la vente',
  `montant_ht` decimal(10,2) DEFAULT 0.00,
  `montant_remise` decimal(15,2) DEFAULT 0.00 COMMENT 'Remise accordée',
  `montant_tva` decimal(15,2) DEFAULT 0.00 COMMENT 'Montant TVA',
  `montant_paye` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Montant payé par le client',
  `montant_rendu` decimal(15,2) DEFAULT 0.00 COMMENT 'Monnaie rendue',
  `mode_paiement` enum('especes','carte','mobile_money','cheque','credit') DEFAULT 'especes',
  `statut` enum('en_cours','validee','annulee') DEFAULT 'validee',
  `notes` text DEFAULT NULL COMMENT 'Notes ou observations',
  `date_vente` datetime DEFAULT current_timestamp() COMMENT 'Date et heure de la vente',
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='En-têtes des ventes (factures)';

INSERT INTO `ventes` (`id_vente`, `numero_facture`, `id_client`, `id_vendeur`, `montant_total`, `montant_ht`, `montant_remise`, `montant_tva`, `montant_paye`, `montant_rendu`, `mode_paiement`, `statut`, `notes`, `date_vente`, `date_modification`) VALUES
(11, 'FAC-20260110-0001', NULL, 3, 1200.00, 1034.48, 0.00, 165.52, 0.00, 0.00, 'especes', 'validee', '', '2026-01-10 13:18:06', '2026-01-10 11:18:06'),
(12, 'FAC-20260111-0002', NULL, 4, 1200.00, 1034.48, 0.00, 165.52, 0.00, 0.00, 'especes', 'validee', '', '2026-01-11 20:58:32', '2026-01-11 18:58:32');

DELIMITER $$
CREATE TRIGGER `before_vente_insert` BEFORE INSERT ON `ventes` FOR EACH ROW BEGIN
    IF NEW.numero_facture IS NULL OR NEW.numero_facture = '' THEN
        SET NEW.numero_facture = CONCAT('FAC', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD((SELECT COALESCE(MAX(id_vente), 0) + 1 FROM ventes), 6, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------
-- Table ventes_details
CREATE TABLE `ventes_details` (
  `id_detail` int(11) NOT NULL,
  `id_vente` int(11) NOT NULL COMMENT 'Référence à la vente',
  `id_produit` int(11) NOT NULL COMMENT 'Produit vendu',
  `nom_produit` varchar(255) NOT NULL COMMENT 'Nom du produit (copie pour historique)',
  `quantite` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantité vendue',
  `prix_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix unitaire de vente',
  `prix_achat_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix d''achat (pour calcul bénéfice)',
  `prix_total` decimal(15,2) NOT NULL COMMENT 'Prix total de la ligne (quantité × prix)',
  `benefice_ligne` decimal(15,2) NOT NULL COMMENT 'Bénéfice sur cette ligne',
  `remise_ligne` decimal(15,2) DEFAULT 0.00 COMMENT 'Remise sur cette ligne',
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Détails des ventes (lignes de factures)';

DELIMITER $$
CREATE TRIGGER `after_vente_detail_insert` AFTER INSERT ON `ventes_details` FOR EACH ROW BEGIN
    UPDATE produits 
    SET quantite_stock = quantite_stock - NEW.quantite,
        date_derniere_vente = NOW(),
        nombre_ventes = nombre_ventes + NEW.quantite
    WHERE id_produit = NEW.id_produit;
    IF (SELECT quantite_stock FROM produits WHERE id_produit = NEW.id_produit) <= 
       (SELECT seuil_critique FROM produits WHERE id_produit = NEW.id_produit) THEN
        INSERT INTO notifications (type_notification, titre, message, id_produit, niveau_urgence)
        SELECT 'stock_critique', 
               CONCAT('Stock critique: ', nom_produit),
               CONCAT('Le stock de ', nom_produit, ' est critique (', quantite_stock, ' restant)'),
               id_produit,
               'urgent'
        FROM produits WHERE id_produit = NEW.id_produit;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------
-- Doublures de vues (temp tables pour export phpMyAdmin)
CREATE TABLE `vue_produits_alertes` (
`id_produit` int(11),`nom_produit` varchar(255),`code_produit` varchar(100),`nom_categorie` varchar(255),`quantite_stock` int(11),`seuil_alerte` int(11),`seuil_critique` int(11),`niveau_alerte` varchar(8),`date_entree` date
);

CREATE TABLE `vue_statistiques_ventes` (
`date_vente` date,`nombre_ventes` bigint(21),`chiffre_affaires` decimal(37,2),`benefice_total` decimal(37,2),`vendeur` varchar(255)
);

-- --------------------------------------------------------
-- Vues (sans DEFINER root)
DROP TABLE IF EXISTS `vue_produits_alertes`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_produits_alertes` AS
SELECT p.id_produit AS id_produit, p.nom_produit AS nom_produit, p.code_produit AS code_produit, c.nom_categorie AS nom_categorie,
       p.quantite_stock AS quantite_stock, p.seuil_alerte AS seuil_alerte, p.seuil_critique AS seuil_critique,
       CASE WHEN p.quantite_stock = 0 THEN 'rupture'
            WHEN p.quantite_stock <= p.seuil_critique THEN 'critique'
            WHEN p.quantite_stock <= p.seuil_alerte THEN 'faible'
            ELSE 'normal' END AS niveau_alerte,
       p.date_entree AS date_entree
FROM produits p
LEFT JOIN categories c ON p.id_categorie = c.id_categorie
WHERE p.est_actif = 1 AND p.quantite_stock <= p.seuil_alerte;

DROP TABLE IF EXISTS `vue_statistiques_ventes`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_statistiques_ventes` AS
SELECT CAST(v.date_vente AS date) AS date_vente,
       COUNT(v.id_vente) AS nombre_ventes,
       SUM(v.montant_total) AS chiffre_affaires,
       SUM(vd.benefice_ligne) AS benefice_total,
       u.nom_complet AS vendeur
FROM ventes v
LEFT JOIN ventes_details vd ON v.id_vente = vd.id_vente
LEFT JOIN utilisateurs u ON v.id_vendeur = u.id_utilisateur
WHERE v.statut = 'validee'
GROUP BY CAST(v.date_vente AS date), u.id_utilisateur;

-- Index
ALTER TABLE `categories` ADD PRIMARY KEY (`id_categorie`), ADD KEY `idx_actif` (`est_actif`);
ALTER TABLE `clients` ADD PRIMARY KEY (`id_client`), ADD KEY `idx_telephone` (`telephone`);
ALTER TABLE `configuration` ADD PRIMARY KEY (`id_config`);
ALTER TABLE `details_vente` ADD PRIMARY KEY (`id_detail`), ADD KEY `idx_vente` (`id_vente`), ADD KEY `idx_produit` (`id_produit`);
ALTER TABLE `logs_activites` ADD PRIMARY KEY (`id_log`), ADD KEY `idx_utilisateur` (`id_utilisateur`), ADD KEY `idx_type` (`type_action`), ADD KEY `idx_date` (`date_action`);
ALTER TABLE `mouvements` ADD PRIMARY KEY (`id_mouvement`), ADD KEY `idx_produit` (`id_produit`), ADD KEY `idx_utilisateur` (`id_utilisateur`), ADD KEY `idx_date` (`date_mouvement`);
ALTER TABLE `mouvements_stock` ADD PRIMARY KEY (`id_mouvement`), ADD KEY `idx_produit` (`id_produit`), ADD KEY `idx_type` (`type_mouvement`), ADD KEY `idx_date` (`date_mouvement`), ADD KEY `fk_mouvement_utilisateur` (`id_utilisateur`);
ALTER TABLE `notifications` ADD PRIMARY KEY (`id_notification`), ADD KEY `idx_lue` (`est_lue`), ADD KEY `idx_type` (`type_notification`);
ALTER TABLE `produits` ADD PRIMARY KEY (`id_produit`), ADD UNIQUE KEY `code_produit` (`code_produit`), ADD KEY `idx_categorie` (`id_categorie`), ADD KEY `idx_stock` (`quantite_stock`), ADD KEY `idx_code` (`code_produit`), ADD KEY `idx_actif` (`est_actif`), ADD KEY `idx_produits_stock_actif` (`quantite_stock`,`est_actif`);
ALTER TABLE `utilisateurs` ADD PRIMARY KEY (`id_utilisateur`), ADD UNIQUE KEY `login` (`login`), ADD KEY `idx_login` (`login`), ADD KEY `idx_niveau` (`niveau_acces`);
ALTER TABLE `ventes` ADD PRIMARY KEY (`id_vente`), ADD UNIQUE KEY `numero_facture` (`numero_facture`), ADD UNIQUE KEY `unique_numero_facture` (`numero_facture`), ADD KEY `idx_client` (`id_client`), ADD KEY `idx_vendeur` (`id_vendeur`), ADD KEY `idx_date` (`date_vente`), ADD KEY `idx_statut` (`statut`), ADD KEY `idx_ventes_date_statut` (`date_vente`,`statut`);
ALTER TABLE `ventes_details` ADD PRIMARY KEY (`id_detail`), ADD KEY `idx_vente` (`id_vente`), ADD KEY `idx_produit` (`id_produit`);

-- Auto-incrément
ALTER TABLE `categories` MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `clients` MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `configuration` MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `details_vente` MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
ALTER TABLE `logs_activites` MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
ALTER TABLE `mouvements` MODIFY `id_mouvement` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mouvements_stock` MODIFY `id_mouvement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
ALTER TABLE `notifications` MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `produits` MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `utilisateurs` MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `ventes` MODIFY `id_vente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
ALTER TABLE `ventes_details` MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

-- Contraintes
ALTER TABLE `details_vente`
  ADD CONSTRAINT `fk_detail_produit_new` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_detail_vente_new` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`) ON DELETE CASCADE;
ALTER TABLE `mouvements`
  ADD CONSTRAINT `fk_mouvement_produit_new` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_mouvement_utilisateur_new` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);
ALTER TABLE `mouvements_stock`
  ADD CONSTRAINT `fk_mouvement_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_mouvement_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE SET NULL;
ALTER TABLE `ventes`
  ADD CONSTRAINT `fk_vente_client` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_vente_vendeur` FOREIGN KEY (`id_vendeur`) REFERENCES `utilisateurs` (`id_utilisateur`);
ALTER TABLE `ventes_details`
  ADD CONSTRAINT `fk_detail_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_detail_vente` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
