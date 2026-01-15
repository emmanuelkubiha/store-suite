-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 15 jan. 2026 à 10:56
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `storesuite`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int NOT NULL,
  `nom_categorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom de la catégorie',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Description de la catégorie',
  `icone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Icône ou classe CSS',
  `couleur` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Couleur associée (format HEX)',
  `ordre_affichage` int DEFAULT '0' COMMENT 'Ordre d''affichage',
  `est_actif` tinyint(1) DEFAULT '1' COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catégories de produits';

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`, `description`, `icone`, `couleur`, `ordre_affichage`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, 'Électronique', 'Téléphones, ordinateurs, accessoires', 'ti-device-laptop', '#3498db', 1, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(2, 'Électroménager', 'Réfrigérateurs, télévisions, cuisinières', 'ti-device-tv', '#e74c3c', 2, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(3, 'Meubles', 'Tables, chaises, armoires', 'ti-armchair', '#9b59b6', 3, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(4, 'Vêtements', 'Habits, chaussures, accessoires', 'ti-hanger', '#1abc9c', 4, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48'),
(5, 'Alimentation', 'Produits alimentaires', 'ti-shopping-cart', '#f39c12', 5, 1, '2026-01-08 13:39:48', '2026-01-08 13:39:48');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int NOT NULL,
  `nom_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du client',
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Téléphone',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `adresse` text COLLATE utf8mb4_unicode_ci COMMENT 'Adresse complète',
  `type_client` enum('particulier','entreprise') COLLATE utf8mb4_unicode_ci DEFAULT 'particulier',
  `numero_fiscal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro fiscal (pour entreprises)',
  `total_achats` decimal(15,2) DEFAULT '0.00' COMMENT 'Total des achats',
  `nombre_achats` int DEFAULT '0' COMMENT 'Nombre d''achats',
  `date_dernier_achat` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes sur le client',
  `est_actif` tinyint(1) DEFAULT '1',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Base de données clients';

-- --------------------------------------------------------

--
-- Structure de la table `configuration`
--

CREATE TABLE `configuration` (
  `id_config` int NOT NULL,
  `nom_boutique` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom de la boutique/entreprise',
  `slogan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slogan ou description courte',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chemin vers le fichier logo',
  `couleur_primaire` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#e6e64c' COMMENT 'Couleur principale (format HEX)',
  `couleur_secondaire` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#556a94' COMMENT 'Couleur secondaire (format HEX)',
  `adresse` text COLLATE utf8mb4_unicode_ci COMMENT 'Adresse complète de l''entreprise',
  `telephone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro(s) de téléphone',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Adresse email',
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Site web de l''entreprise',
  `num_registre_commerce` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro d''enregistrement (RCCM, etc.)',
  `num_impot` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro fiscal/TVA',
  `devise` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '$' COMMENT 'Symbole de la devise utilisée',
  `taux_tva` decimal(5,2) DEFAULT '0.00' COMMENT 'Taux de TVA par défaut (%)',
  `fuseau_horaire` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Africa/Lubumbashi' COMMENT 'Fuseau horaire',
  `langue` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'fr' COMMENT 'Langue du système (fr, en, etc.)',
  `est_configure` tinyint(1) DEFAULT '0' COMMENT '0=Non configuré, 1=Configuré',
  `date_configuration` datetime DEFAULT NULL COMMENT 'Date de première configuration',
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Paramètres globaux du système';

--
-- Déchargement des données de la table `configuration`
-- (Vide - Configuration initiale via setup.php)
--

-- --------------------------------------------------------

--
-- Structure de la table `depots`
--

CREATE TABLE `depots` (
  `id_depot` int NOT NULL,
  `nom_depot` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du dépôt/emplacement',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Description de l''emplacement',
  `type_depot` enum('magasin','depot','entrepot','autre') COLLATE utf8mb4_unicode_ci DEFAULT 'depot' COMMENT 'Type d''emplacement',
  `adresse` text COLLATE utf8mb4_unicode_ci COMMENT 'Adresse physique',
  `responsable` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Responsable du dépôt',
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Téléphone du dépôt',
  `capacite` int DEFAULT NULL COMMENT 'Capacité maximale (unités)',
  `est_principal` tinyint(1) DEFAULT '0' COMMENT '1=Dépôt principal (Magasin)',
  `est_actif` tinyint(1) DEFAULT '1' COMMENT '0=Inactif, 1=Actif',
  `ordre_affichage` int DEFAULT '0',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Emplacements de stockage';

--
-- Déchargement des données de la table `depots`
--

INSERT INTO `depots` (`id_depot`, `nom_depot`, `description`, `type_depot`, `adresse`, `responsable`, `telephone`, `capacite`, `est_principal`, `est_actif`, `ordre_affichage`, `date_creation`, `date_modification`) VALUES
(1, 'Magasin', 'Emplacement principal de vente (par défaut)', 'magasin', NULL, NULL, NULL, NULL, 1, 1, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Structure de la table `details_vente`
--

CREATE TABLE `details_vente` (
  `id_detail` int NOT NULL,
  `id_vente` int NOT NULL COMMENT 'Référence à la vente',
  `id_produit` int NOT NULL COMMENT 'Produit vendu',
  `nom_produit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du produit',
  `quantite` int NOT NULL DEFAULT '1' COMMENT 'Quantité vendue',
  `prix_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix unitaire',
  `prix_achat_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix achat',
  `prix_total` decimal(15,2) NOT NULL COMMENT 'Total ligne',
  `benefice_ligne` decimal(15,2) NOT NULL COMMENT 'Bénéfice ligne',
  `remise_ligne` decimal(15,2) DEFAULT '0.00' COMMENT 'Remise ligne',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `details_vente`
-- (Vide - Aucune vente initiale)
--

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id_fournisseur` int NOT NULL,
  `nom_fournisseur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du fournisseur',
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Personne de contact',
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro de téléphone',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Adresse email',
  `adresse` text COLLATE utf8mb4_unicode_ci COMMENT 'Adresse complète',
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pays',
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ville',
  `conditions_paiement` text COLLATE utf8mb4_unicode_ci COMMENT 'Conditions de paiement',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes diverses',
  `est_actif` tinyint(1) DEFAULT '1' COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fournisseurs de produits';

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id_fournisseur`, `nom_fournisseur`, `contact`, `telephone`, `email`, `adresse`, `pays`, `ville`, `conditions_paiement`, `notes`, `est_actif`, `date_creation`, `date_modification`) VALUES
(1, 'Fournisseur général', 'Divers', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-15 09:11:04', '2026-01-15 07:11:04');

-- --------------------------------------------------------

--
-- Structure de la table `logs_activites`
--

CREATE TABLE `logs_activites` (
  `id_log` int NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  `type_action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type d''action (connexion, vente, modification, etc.)',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description détaillée de l''action',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Adresse IP',
  `user_agent` text COLLATE utf8mb4_unicode_ci COMMENT 'Navigateur/Device',
  `donnees_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Données supplémentaires en JSON',
  `date_action` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Déchargement des données de la table `logs_activites`
--

INSERT INTO `logs_activites` (`id_log`, `id_utilisateur`, `type_action`, `description`, `ip_address`, `user_agent`, `donnees_json`, `date_action`) VALUES
(1, 2, 'configuration_initiale', 'Configuration initiale du système effectuée', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"nom_boutique\":\"Ma Super Boutique Test\",\"admin_login\":\"admin\"}', '2026-01-09 09:33:05'),
(2, 3, 'configuration_initiale', 'Configuration initiale du système effectuée', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"nom_boutique\":\"CALEB SHOP\",\"admin_login\":\"admin\"}', '2026-01-09 09:42:27'),
(3, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 08:43:43'),
(4, 3, 'deconnexion', 'Déconnexion de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 09:07:31'),
(5, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 09:07:50'),
(6, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-09 17:23:31'),
(7, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0001 (1392 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"4\",\"numero_facture\":\"FAC-20260109-0001\",\"montant\":1392}', '2026-01-09 19:49:49'),
(8, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0002 (1392 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"5\",\"numero_facture\":\"FAC-20260109-0002\",\"montant\":1392}', '2026-01-09 19:50:24'),
(9, 3, 'VENTE_ANNULEE', 'Vente annulée: FAC-20260109-0002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":5,\"numero_facture\":\"FAC-20260109-0002\",\"montant\":\"1392.00\"}', '2026-01-09 20:07:58'),
(10, 3, 'VENTE_SUPPRIMEE', 'Vente supprimée définitivement: FAC-20260109-0002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":5,\"numero_facture\":\"FAC-20260109-0002\",\"montant\":\"1392.00\"}', '2026-01-09 20:19:35'),
(11, 3, 'VENTE_ANNULEE', 'Vente annulée: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":4,\"numero_facture\":\"FAC-20260109-0001\",\"montant\":\"1392.00\"}', '2026-01-09 20:22:15'),
(12, 3, 'VENTE_SUPPRIMEE', 'Vente supprimée définitivement: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":4,\"numero_facture\":\"FAC-20260109-0001\",\"montant\":\"1392.00\"}', '2026-01-09 20:22:21'),
(13, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0001 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"6\",\"numero_facture\":\"FAC-20260109-0001\",\"montant\":1200}', '2026-01-09 20:24:50'),
(14, 3, 'VENTE_ANNULEE', 'Vente annulée: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":6,\"numero_facture\":\"FAC-20260109-0001\",\"montant\":\"1200.00\"}', '2026-01-09 20:28:54'),
(15, 3, 'VENTE_SUPPRIMEE', 'Vente supprimée définitivement: FAC-20260109-0001', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":6,\"numero_facture\":\"FAC-20260109-0001\",\"montant\":\"1200.00\"}', '2026-01-09 20:29:05'),
(16, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0001 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"7\",\"numero_facture\":\"FAC-20260109-0001\",\"montant\":1200}', '2026-01-09 20:29:28'),
(17, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260109-0002 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"8\",\"numero_facture\":\"FAC-20260109-0002\",\"montant\":1200}', '2026-01-09 20:53:25'),
(18, 3, 'connexion', 'Connexion réussie de Emmanuel K', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 06:55:55'),
(19, 3, 'vente_restauree', 'Restauration de la vente FAC-20260109-0002', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":8,\"numero_facture\":\"FAC-20260109-0002\"}', '2026-01-10 07:44:56'),
(20, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260110-0003 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"9\",\"numero_facture\":\"FAC-20260110-0003\",\"montant\":1200}', '2026-01-10 07:53:49'),
(21, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260110-0004 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"10\",\"numero_facture\":\"FAC-20260110-0004\",\"montant\":1200}', '2026-01-10 07:54:55'),
(22, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 08:18:09'),
(23, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '2026-01-10 08:25:52'),
(24, 3, 'REINIT', 'Suppression de toutes les ventes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"type\":\"ventes\"}', '2026-01-10 09:41:08'),
(25, 3, 'REINIT', 'Suppression de tous les clients', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '{\"type\":\"clients\"}', '2026-01-10 09:45:06'),
(26, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 10:12:07'),
(27, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 10:31:30'),
(28, 3, 'VENTE', 'Nouvelle vente créée: FAC-20260110-0001 (1200 $)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"11\",\"numero_facture\":\"FAC-20260110-0001\",\"montant\":1200}', '2026-01-10 12:18:06'),
(29, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 19:42:16'),
(30, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 21:21:20'),
(31, 4, 'connexion', 'Connexion réussie de FEFE3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 21:21:36'),
(32, 4, 'deconnexion', 'Déconnexion de FEFE3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 23:46:42'),
(33, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-10 23:46:50'),
(34, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-11 19:11:44'),
(35, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-11 19:37:25'),
(36, 4, 'connexion', 'Connexion réussie de FEFE3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-11 19:37:29'),
(37, 4, 'VENTE', 'Nouvelle vente créée: FAC-20260111-0002 (1200 CDF)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"id_vente\":\"12\",\"numero_facture\":\"FAC-20260111-0002\",\"montant\":1200}', '2026-01-11 19:58:32'),
(38, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 12:59:20'),
(39, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 12:59:29'),
(40, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 12:59:45'),
(41, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 12:59:53'),
(42, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:01:04'),
(43, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:01:12'),
(44, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:01:26'),
(45, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:02:48'),
(46, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:02:53'),
(47, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:03:11'),
(48, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : fefe', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:03:34'),
(49, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin@exemple.com', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:06:35'),
(50, NULL, 'connexion_echouee', 'Tentative de connexion échouée pour l\'utilisateur : admin@exemple.com', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:06:45'),
(51, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:07:39'),
(52, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:08:56'),
(53, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:10:52'),
(54, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 13:11:08'),
(55, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 15:33:41'),
(56, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 12:04:34'),
(57, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 12:04:40'),
(58, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 12:10:26'),
(59, 3, 'deconnexion', 'Déconnexion de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 12:10:31'),
(60, 3, 'connexion', 'Connexion réussie de EMMANUEL BARAKA', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-15 08:46:27');

-- --------------------------------------------------------

--
-- Structure de la table `mouvements`
--

CREATE TABLE `mouvements` (
  `id_mouvement` int NOT NULL,
  `id_produit` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `type_mouvement` enum('entree','sortie','ajustement','vente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(15,2) DEFAULT NULL,
  `reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro facture ou bon',
  `motif` text COLLATE utf8mb4_unicode_ci,
  `date_mouvement` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stock`
--

CREATE TABLE `mouvements_stock` (
  `id_mouvement` int NOT NULL,
  `id_produit` int NOT NULL COMMENT 'Produit concerné',
  `id_depot_source` int DEFAULT NULL COMMENT 'Dépôt source (pour transferts)',
  `id_depot_destination` int DEFAULT NULL COMMENT 'Dépôt destination',
  `id_fournisseur` int DEFAULT NULL COMMENT 'Fournisseur (pour entrées)',
  `type_mouvement` enum('entree','sortie','ajustement','retour','transfert','inventaire','perte') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int NOT NULL COMMENT 'Quantité du mouvement',
  `quantite_avant` int NOT NULL COMMENT 'Stock avant le mouvement',
  `quantite_apres` int NOT NULL COMMENT 'Stock après le mouvement',
  `cout_unitaire` decimal(15,2) DEFAULT NULL COMMENT 'Coût unitaire d''achat',
  `cout_total` decimal(15,2) DEFAULT NULL COMMENT 'Coût total du mouvement',
  `id_vente` int DEFAULT NULL COMMENT 'Référence vente si sortie',
  `id_utilisateur` int NOT NULL COMMENT 'Utilisateur qui a fait l''opération',
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Raison du mouvement',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `date_mouvement` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Historique des mouvements de stock';

--
-- Déchargement des données de la table `mouvements_stock`
-- (Vide - Aucun mouvement initial)
--

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` int NOT NULL,
  `type_notification` enum('stock_faible','stock_critique','rupture_stock','vente_importante','systeme') COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_produit` int DEFAULT NULL COMMENT 'Produit concerné si applicable',
  `niveau_urgence` enum('info','avertissement','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `est_lue` tinyint(1) DEFAULT '0' COMMENT '0=Non lue, 1=Lue',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notifications système';

--
-- Déchargement des données de la table `notifications`
-- (Vide - Aucune notification initiale)
--

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int NOT NULL,
  `code_produit` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code/Référence unique du produit',
  `nom_produit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du produit',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Description détaillée',
  `id_categorie` int DEFAULT NULL COMMENT 'Catégorie du produit',
  `id_fournisseur_principal` int DEFAULT NULL COMMENT 'Fournisseur principal du produit',
  `prix_achat` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Prix d''achat (VISIBLE ADMIN SEULEMENT)',
  `prix_vente` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Prix de vente recommandé',
  `prix_vente_min` decimal(15,2) DEFAULT NULL COMMENT 'Prix de vente minimum autorisé',
  `quantite_stock` int NOT NULL DEFAULT '0' COMMENT 'Quantité actuelle en stock',
  `seuil_alerte` int DEFAULT '10' COMMENT 'Seuil pour alerte stock faible',
  `seuil_critique` int DEFAULT '5' COMMENT 'Seuil critique (alerte rouge)',
  `unite_mesure` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'pièce' COMMENT 'Unité (pièce, kg, litre, etc.)',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Image du produit',
  `code_barre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code-barres pour scanner',
  `emplacement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Emplacement dans le magasin',
  `date_entree` date DEFAULT NULL COMMENT 'Date dernière entrée en stock',
  `date_derniere_vente` datetime DEFAULT NULL COMMENT 'Date de la dernière vente',
  `nombre_ventes` int DEFAULT '0' COMMENT 'Nombre total de ventes',
  `est_actif` tinyint(1) DEFAULT '1' COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Produits en stock avec gestion des alertes';

--
-- Déchargement des données de la table `produits`
-- (Vide - Aucun produit initial)
--

-- --------------------------------------------------------

--
-- Structure de la table `stock_par_depot`
--

CREATE TABLE `stock_par_depot` (
  `id_stock` int NOT NULL,
  `id_produit` int NOT NULL COMMENT 'Référence au produit',
  `id_depot` int NOT NULL COMMENT 'Référence au dépôt',
  `quantite` int NOT NULL DEFAULT '0' COMMENT 'Quantité dans ce dépôt',
  `seuil_alerte` int DEFAULT '10' COMMENT 'Seuil d''alerte pour ce dépôt',
  `date_derniere_maj` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stock par emplacement (multi-localisation)';

--
-- Déchargement des données de la table `stock_par_depot`
-- (Vide - Aucun stock initial)
--

--
-- Déclencheurs `stock_par_depot`
--
DELIMITER $$
CREATE TRIGGER `after_stock_par_depot_delete` AFTER DELETE ON `stock_par_depot` FOR EACH ROW BEGIN
    -- Recalculer le stock total du produit
    UPDATE produits 
    SET quantite_stock = (
        SELECT COALESCE(SUM(quantite), 0) 
        FROM stock_par_depot 
        WHERE id_produit = OLD.id_produit
    )
    WHERE id_produit = OLD.id_produit;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_stock_par_depot_insert` AFTER INSERT ON `stock_par_depot` FOR EACH ROW BEGIN
    -- Recalculer le stock total du produit
    UPDATE produits 
    SET quantite_stock = (
        SELECT COALESCE(SUM(quantite), 0) 
        FROM stock_par_depot 
        WHERE id_produit = NEW.id_produit
    )
    WHERE id_produit = NEW.id_produit;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_stock_par_depot_update` AFTER UPDATE ON `stock_par_depot` FOR EACH ROW BEGIN
    -- Recalculer le stock total du produit
    UPDATE produits 
    SET quantite_stock = (
        SELECT COALESCE(SUM(quantite), 0) 
        FROM stock_par_depot 
        WHERE id_produit = NEW.id_produit
    )
    WHERE id_produit = NEW.id_produit;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int NOT NULL,
  `nom_complet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom complet de l''utilisateur',
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identifiant de connexion (unique)',
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mot de passe hashé (password_hash)',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Adresse email',
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro de téléphone',
  `niveau_acces` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1=Admin, 2=Vendeur',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Photo de profil',
  `est_actif` tinyint(1) DEFAULT '1' COMMENT '0=Inactif, 1=Actif',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création du compte',
  `date_derniere_connexion` datetime DEFAULT NULL COMMENT 'Dernière connexion',
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Utilisateurs du système avec permissions';

--
-- Déchargement des données de la table `utilisateurs`
-- (Vide - Utilisateur admin créé lors du setup initial)
--

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id_vente` int NOT NULL,
  `numero_facture` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Numéro unique de la facture',
  `id_client` int DEFAULT NULL COMMENT 'Client (NULL = vente comptoir)',
  `id_vendeur` int NOT NULL COMMENT 'Vendeur/Caissier qui a effectué la vente',
  `montant_total` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Montant total de la vente',
  `montant_ht` decimal(10,2) DEFAULT '0.00',
  `montant_remise` decimal(15,2) DEFAULT '0.00' COMMENT 'Remise accordée',
  `montant_tva` decimal(15,2) DEFAULT '0.00' COMMENT 'Montant TVA',
  `montant_paye` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Montant payé par le client',
  `montant_rendu` decimal(15,2) DEFAULT '0.00' COMMENT 'Monnaie rendue',
  `mode_paiement` enum('especes','carte','mobile_money','cheque','credit') COLLATE utf8mb4_unicode_ci DEFAULT 'especes',
  `statut` enum('en_cours','validee','annulee') COLLATE utf8mb4_unicode_ci DEFAULT 'validee',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes ou observations',
  `date_vente` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Date et heure de la vente',
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='En-têtes des ventes (factures)';

--
-- Déchargement des données de la table `ventes`
-- (Vide - Aucune vente initiale)
--

--
-- Déclencheurs `ventes`
--
DELIMITER $$
CREATE TRIGGER `before_vente_insert` BEFORE INSERT ON `ventes` FOR EACH ROW BEGIN
    IF NEW.numero_facture IS NULL OR NEW.numero_facture = '' THEN
        SET NEW.numero_facture = CONCAT('FAC', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD((SELECT COALESCE(MAX(id_vente), 0) + 1 FROM ventes), 6, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `ventes_details`
--

CREATE TABLE `ventes_details` (
  `id_detail` int NOT NULL,
  `id_vente` int NOT NULL COMMENT 'Référence à la vente',
  `id_produit` int NOT NULL COMMENT 'Produit vendu',
  `nom_produit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du produit (copie pour historique)',
  `quantite` int NOT NULL DEFAULT '1' COMMENT 'Quantité vendue',
  `prix_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix unitaire de vente',
  `prix_achat_unitaire` decimal(15,2) NOT NULL COMMENT 'Prix d''achat (pour calcul bénéfice)',
  `prix_total` decimal(15,2) NOT NULL COMMENT 'Prix total de la ligne (quantité × prix)',
  `benefice_ligne` decimal(15,2) NOT NULL COMMENT 'Bénéfice sur cette ligne',
  `remise_ligne` decimal(15,2) DEFAULT '0.00' COMMENT 'Remise sur cette ligne',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Détails des ventes (lignes de factures)';

--
-- Déclencheurs `ventes_details`
--
DELIMITER $$
CREATE TRIGGER `after_vente_detail_insert` AFTER INSERT ON `ventes_details` FOR EACH ROW BEGIN
    -- Diminuer le stock du produit
    UPDATE produits 
    SET quantite_stock = quantite_stock - NEW.quantite,
        date_derniere_vente = NOW(),
        nombre_ventes = nombre_ventes + NEW.quantite
    WHERE id_produit = NEW.id_produit;
    
    -- Créer une notification si stock faible
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

--
-- Doublure de structure pour la vue `vue_inventaire_complet`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_inventaire_complet` (
`code_produit` varchar(100)
,`id_depot` int
,`id_produit` int
,`nom_categorie` varchar(255)
,`nom_depot` varchar(255)
,`nom_produit` varchar(255)
,`prix_achat` decimal(15,2)
,`prix_vente` decimal(15,2)
,`quantite` int
,`seuil_alerte` int
,`statut_depot` varchar(7)
,`type_depot` enum('magasin','depot','entrepot','autre')
,`unite_mesure` varchar(50)
,`valeur_stock_achat` decimal(25,2)
,`valeur_stock_vente` decimal(25,2)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_mouvements_stock_detail`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_mouvements_stock_detail` (
`code_produit` varchar(100)
,`cout_total` decimal(15,2)
,`cout_unitaire` decimal(15,2)
,`date_mouvement` datetime
,`depot_destination` varchar(255)
,`depot_source` varchar(255)
,`id_mouvement` int
,`id_produit` int
,`motif` varchar(255)
,`nom_fournisseur` varchar(255)
,`nom_produit` varchar(255)
,`notes` text
,`numero_facture` varchar(50)
,`quantite` int
,`quantite_apres` int
,`quantite_avant` int
,`type_mouvement` enum('entree','sortie','ajustement','retour','transfert','inventaire','perte')
,`type_mouvement_libelle` varchar(14)
,`unite_mesure` varchar(50)
,`utilisateur` varchar(255)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_produits_alertes`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_produits_alertes` (
`code_produit` varchar(100)
,`date_entree` date
,`id_produit` int
,`niveau_alerte` varchar(8)
,`nom_categorie` varchar(255)
,`nom_produit` varchar(255)
,`quantite_stock` int
,`seuil_alerte` int
,`seuil_critique` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_statistiques_ventes`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_statistiques_ventes` (
`benefice_total` decimal(37,2)
,`chiffre_affaires` decimal(37,2)
,`date_vente` date
,`nombre_ventes` bigint
,`vendeur` varchar(255)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_stock_global`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_stock_global` (
`code_produit` varchar(100)
,`est_actif` tinyint(1)
,`id_categorie` int
,`id_produit` int
,`nom_categorie` varchar(255)
,`nom_produit` varchar(255)
,`prix_achat` decimal(15,2)
,`prix_vente` decimal(15,2)
,`seuil_alerte` int
,`seuil_critique` int
,`statut_stock` varchar(8)
,`stock_total` decimal(32,0)
,`unite_mesure` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure de la vue `vue_inventaire_complet`
--
DROP TABLE IF EXISTS `vue_inventaire_complet`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_inventaire_complet`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`code_produit` AS `code_produit`, `p`.`nom_produit` AS `nom_produit`, `c`.`nom_categorie` AS `nom_categorie`, `d`.`id_depot` AS `id_depot`, `d`.`nom_depot` AS `nom_depot`, `d`.`type_depot` AS `type_depot`, `spd`.`quantite` AS `quantite`, `p`.`prix_achat` AS `prix_achat`, `p`.`prix_vente` AS `prix_vente`, (`spd`.`quantite` * `p`.`prix_achat`) AS `valeur_stock_achat`, (`spd`.`quantite` * `p`.`prix_vente`) AS `valeur_stock_vente`, `p`.`unite_mesure` AS `unite_mesure`, `spd`.`seuil_alerte` AS `seuil_alerte`, (case when (`spd`.`quantite` = 0) then 'rupture' when (`spd`.`quantite` <= `spd`.`seuil_alerte`) then 'alerte' else 'normal' end) AS `statut_depot` FROM (((`produits` `p` join `stock_par_depot` `spd` on((`p`.`id_produit` = `spd`.`id_produit`))) join `depots` `d` on((`spd`.`id_depot` = `d`.`id_depot`))) left join `categories` `c` on((`p`.`id_categorie` = `c`.`id_categorie`))) WHERE ((`p`.`est_actif` = 1) AND (`d`.`est_actif` = 1)) ORDER BY `p`.`nom_produit` ASC, `d`.`ordre_affichage` ASC, `d`.`nom_depot` ASC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_mouvements_stock_detail`
--
DROP TABLE IF EXISTS `vue_mouvements_stock_detail`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_mouvements_stock_detail`  AS SELECT `m`.`id_mouvement` AS `id_mouvement`, `m`.`type_mouvement` AS `type_mouvement`, `m`.`date_mouvement` AS `date_mouvement`, `p`.`id_produit` AS `id_produit`, `p`.`code_produit` AS `code_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`unite_mesure` AS `unite_mesure`, `m`.`quantite` AS `quantite`, `m`.`quantite_avant` AS `quantite_avant`, `m`.`quantite_apres` AS `quantite_apres`, `ds`.`nom_depot` AS `depot_source`, `dd`.`nom_depot` AS `depot_destination`, `f`.`nom_fournisseur` AS `nom_fournisseur`, `m`.`cout_unitaire` AS `cout_unitaire`, `m`.`cout_total` AS `cout_total`, `m`.`motif` AS `motif`, `m`.`notes` AS `notes`, `u`.`nom_complet` AS `utilisateur`, `v`.`numero_facture` AS `numero_facture`, (case `m`.`type_mouvement` when 'entree' then 'Entrée stock' when 'sortie' then 'Sortie (Vente)' when 'transfert' then 'Transfert' when 'ajustement' then 'Ajustement' when 'inventaire' then 'Inventaire' when 'perte' then 'Perte/Casse' when 'retour' then 'Retour' else 'Autre' end) AS `type_mouvement_libelle` FROM ((((((`mouvements_stock` `m` join `produits` `p` on((`m`.`id_produit` = `p`.`id_produit`))) left join `depots` `ds` on((`m`.`id_depot_source` = `ds`.`id_depot`))) left join `depots` `dd` on((`m`.`id_depot_destination` = `dd`.`id_depot`))) left join `fournisseurs` `f` on((`m`.`id_fournisseur` = `f`.`id_fournisseur`))) left join `utilisateurs` `u` on((`m`.`id_utilisateur` = `u`.`id_utilisateur`))) left join `ventes` `v` on((`m`.`id_vente` = `v`.`id_vente`))) ORDER BY `m`.`date_mouvement` DESC, `m`.`id_mouvement` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_produits_alertes`
--
DROP TABLE IF EXISTS `vue_produits_alertes`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_produits_alertes`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`code_produit` AS `code_produit`, `c`.`nom_categorie` AS `nom_categorie`, `p`.`quantite_stock` AS `quantite_stock`, `p`.`seuil_alerte` AS `seuil_alerte`, `p`.`seuil_critique` AS `seuil_critique`, (case when (`p`.`quantite_stock` = 0) then 'rupture' when (`p`.`quantite_stock` <= `p`.`seuil_critique`) then 'critique' when (`p`.`quantite_stock` <= `p`.`seuil_alerte`) then 'faible' else 'normal' end) AS `niveau_alerte`, `p`.`date_entree` AS `date_entree` FROM (`produits` `p` left join `categories` `c` on((`p`.`id_categorie` = `c`.`id_categorie`))) WHERE ((`p`.`est_actif` = 1) AND (`p`.`quantite_stock` <= `p`.`seuil_alerte`)) ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_statistiques_ventes`
--
DROP TABLE IF EXISTS `vue_statistiques_ventes`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_statistiques_ventes`  AS SELECT cast(`v`.`date_vente` as date) AS `date_vente`, count(`v`.`id_vente`) AS `nombre_ventes`, sum(`v`.`montant_total`) AS `chiffre_affaires`, sum(`vd`.`benefice_ligne`) AS `benefice_total`, `u`.`nom_complet` AS `vendeur` FROM ((`ventes` `v` left join `ventes_details` `vd` on((`v`.`id_vente` = `vd`.`id_vente`))) left join `utilisateurs` `u` on((`v`.`id_vendeur` = `u`.`id_utilisateur`))) WHERE (`v`.`statut` = 'validee') GROUP BY cast(`v`.`date_vente` as date), `u`.`id_utilisateur` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_stock_global`
--
DROP TABLE IF EXISTS `vue_stock_global`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vue_stock_global`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`code_produit` AS `code_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`id_categorie` AS `id_categorie`, `c`.`nom_categorie` AS `nom_categorie`, `p`.`prix_achat` AS `prix_achat`, `p`.`prix_vente` AS `prix_vente`, coalesce(sum(`spd`.`quantite`),0) AS `stock_total`, `p`.`seuil_alerte` AS `seuil_alerte`, `p`.`seuil_critique` AS `seuil_critique`, `p`.`unite_mesure` AS `unite_mesure`, `p`.`est_actif` AS `est_actif`, (case when (coalesce(sum(`spd`.`quantite`),0) = 0) then 'rupture' when (coalesce(sum(`spd`.`quantite`),0) <= `p`.`seuil_critique`) then 'critique' when (coalesce(sum(`spd`.`quantite`),0) <= `p`.`seuil_alerte`) then 'alerte' else 'normal' end) AS `statut_stock` FROM ((`produits` `p` left join `categories` `c` on((`p`.`id_categorie` = `c`.`id_categorie`))) left join `stock_par_depot` `spd` on((`p`.`id_produit` = `spd`.`id_produit`))) WHERE (`p`.`est_actif` = 1) GROUP BY `p`.`id_produit`, `p`.`code_produit`, `p`.`nom_produit`, `p`.`id_categorie`, `c`.`nom_categorie`, `p`.`prix_achat`, `p`.`prix_vente`, `p`.`seuil_alerte`, `p`.`seuil_critique`, `p`.`unite_mesure`, `p`.`est_actif` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`),
  ADD KEY `idx_actif` (`est_actif`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `idx_telephone` (`telephone`);

--
-- Index pour la table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`id_config`);

--
-- Index pour la table `depots`
--
ALTER TABLE `depots`
  ADD PRIMARY KEY (`id_depot`),
  ADD UNIQUE KEY `unique_nom_depot` (`nom_depot`),
  ADD KEY `idx_est_actif` (`est_actif`),
  ADD KEY `idx_est_principal` (`est_principal`);

--
-- Index pour la table `details_vente`
--
ALTER TABLE `details_vente`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_vente` (`id_vente`),
  ADD KEY `idx_produit` (`id_produit`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id_fournisseur`),
  ADD KEY `idx_nom_fournisseur` (`nom_fournisseur`),
  ADD KEY `idx_est_actif` (`est_actif`);

--
-- Index pour la table `logs_activites`
--
ALTER TABLE `logs_activites`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_type` (`type_action`),
  ADD KEY `idx_date` (`date_action`);

--
-- Index pour la table `mouvements`
--
ALTER TABLE `mouvements`
  ADD PRIMARY KEY (`id_mouvement`),
  ADD KEY `idx_produit` (`id_produit`),
  ADD KEY `idx_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_date` (`date_mouvement`);

--
-- Index pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD PRIMARY KEY (`id_mouvement`),
  ADD KEY `idx_produit` (`id_produit`),
  ADD KEY `idx_type` (`type_mouvement`),
  ADD KEY `idx_date` (`date_mouvement`),
  ADD KEY `fk_mouvement_utilisateur` (`id_utilisateur`),
  ADD KEY `idx_depot_source` (`id_depot_source`),
  ADD KEY `idx_depot_destination` (`id_depot_destination`),
  ADD KEY `idx_fournisseur` (`id_fournisseur`),
  ADD KEY `idx_type_mouvement` (`type_mouvement`),
  ADD KEY `idx_date_mouvement` (`date_mouvement`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `idx_lue` (`est_lue`),
  ADD KEY `idx_type` (`type_notification`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `code_produit` (`code_produit`),
  ADD KEY `idx_categorie` (`id_categorie`),
  ADD KEY `idx_stock` (`quantite_stock`),
  ADD KEY `idx_code` (`code_produit`),
  ADD KEY `idx_actif` (`est_actif`),
  ADD KEY `idx_produits_stock_actif` (`quantite_stock`,`est_actif`),
  ADD KEY `idx_fournisseur_principal` (`id_fournisseur_principal`);

--
-- Index pour la table `stock_par_depot`
--
ALTER TABLE `stock_par_depot`
  ADD PRIMARY KEY (`id_stock`),
  ADD UNIQUE KEY `unique_produit_depot` (`id_produit`,`id_depot`),
  ADD KEY `idx_id_produit` (`id_produit`),
  ADD KEY `idx_id_depot` (`id_depot`),
  ADD KEY `idx_quantite` (`quantite`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `idx_login` (`login`),
  ADD KEY `idx_niveau` (`niveau_acces`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id_vente`),
  ADD UNIQUE KEY `numero_facture` (`numero_facture`),
  ADD UNIQUE KEY `unique_numero_facture` (`numero_facture`),
  ADD KEY `idx_client` (`id_client`),
  ADD KEY `idx_vendeur` (`id_vendeur`),
  ADD KEY `idx_date` (`date_vente`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_ventes_date_statut` (`date_vente`,`statut`);

--
-- Index pour la table `ventes_details`
--
ALTER TABLE `ventes_details`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_vente` (`id_vente`),
  ADD KEY `idx_produit` (`id_produit`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `configuration`
--
ALTER TABLE `configuration`
  MODIFY `id_config` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `depots`
--
ALTER TABLE `depots`
  MODIFY `id_depot` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `details_vente`
--
ALTER TABLE `details_vente`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id_fournisseur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `logs_activites`
--
ALTER TABLE `logs_activites`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `mouvements`
--
ALTER TABLE `mouvements`
  MODIFY `id_mouvement` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  MODIFY `id_mouvement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `stock_par_depot`
--
ALTER TABLE `stock_par_depot`
  MODIFY `id_stock` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id_vente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `ventes_details`
--
ALTER TABLE `ventes_details`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `details_vente`
--
ALTER TABLE `details_vente`
  ADD CONSTRAINT `fk_detail_produit_new` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_detail_vente_new` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mouvements`
--
ALTER TABLE `mouvements`
  ADD CONSTRAINT `fk_mouvement_produit_new` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_mouvement_utilisateur_new` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD CONSTRAINT `fk_mouvement_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_mouvement_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE SET NULL;

--
-- Contraintes pour la table `stock_par_depot`
--
ALTER TABLE `stock_par_depot`
  ADD CONSTRAINT `fk_stock_depot` FOREIGN KEY (`id_depot`) REFERENCES `depots` (`id_depot`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stock_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD CONSTRAINT `fk_vente_client` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_vente_vendeur` FOREIGN KEY (`id_vendeur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `ventes_details`
--
ALTER TABLE `ventes_details`
  ADD CONSTRAINT `fk_detail_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `fk_detail_vente` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id_vente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
