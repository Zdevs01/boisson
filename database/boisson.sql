-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 10 fév. 2025 à 13:19
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boisson`
--

-- --------------------------------------------------------

--
-- Structure de la table `approvisionnement`
--

CREATE TABLE `approvisionnement` (
  `id` int(11) NOT NULL,
  `nom_personne` varchar(100) NOT NULL,
  `numero_telephone` varchar(15) NOT NULL,
  `somme` decimal(10,2) NOT NULL,
  `motif` varchar(900) NOT NULL,
  `reste` decimal(10,2) DEFAULT 0.00,
  `date_prevue_livraison` date NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `livre` varchar(900) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `back_order_list`
--

CREATE TABLE `back_order_list` (
  `id` int(30) NOT NULL,
  `receiving_id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `bo_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bordereau_livraison`
--

CREATE TABLE `bordereau_livraison` (
  `id` int(11) NOT NULL,
  `nombre_produits` int(11) NOT NULL,
  `tonnage` decimal(10,2) NOT NULL,
  `montant_total` decimal(15,2) NOT NULL,
  `nom_fournisseur` varchar(255) NOT NULL,
  `telephone_fournisseur` varchar(15) NOT NULL,
  `adresse_fournisseur` varchar(255) DEFAULT NULL,
  `date_livraison` date NOT NULL,
  `details` text DEFAULT NULL,
  `reference_bordereau` varchar(100) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_mise_a_jour` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bo_items`
--

CREATE TABLE `bo_items` (
  `bo_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chauffeur`
--

CREATE TABLE `chauffeur` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `numero_telephone` varchar(15) NOT NULL,
  `deuxieme_numero` varchar(15) DEFAULT NULL,
  `type_engin` varchar(50) NOT NULL,
  `matricule_vehicule` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `total_kg` float NOT NULL,
  `montant_total` float NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande_produits`
--

CREATE TABLE `commande_produits` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite_kg` float NOT NULL,
  `prix_unitaire` float NOT NULL,
  `sous_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `creancier`
--

CREATE TABLE `creancier` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `numero_telephone` varchar(20) NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `avance` decimal(15,2) DEFAULT 0.00,
  `motif` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `id` int(11) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `statut` enum('actif','licencie') DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employes_presences`
--

CREATE TABLE `employes_presences` (
  `id` int(11) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `date_jour` date NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `heure_depart_pause` time DEFAULT NULL,
  `heure_retour_pause` time DEFAULT NULL,
  `retard` enum('non','oui') DEFAULT 'non',
  `absence` enum('non','oui') DEFAULT 'non',
  `pause_longue` enum('non','oui') DEFAULT 'non',
  `heures_supp` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gestion_presence`
--

CREATE TABLE `gestion_presence` (
  `id` int(11) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `date_jour` date NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `heure_depart_pause` time DEFAULT NULL,
  `heure_retour_pause` time DEFAULT NULL,
  `retard` enum('non','oui') DEFAULT 'non',
  `absence` enum('non','oui') DEFAULT 'non',
  `pause_longue` enum('non','oui') DEFAULT 'non',
  `statut` enum('actif','licencie') DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `item_list`
--

CREATE TABLE `item_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `category` varchar(900) NOT NULL,
  `typ` varchar(400) NOT NULL,
  `description` text NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `cost` float NOT NULL DEFAULT 0,
  `stock` varchar(900) NOT NULL,
  `cost1` float NOT NULL DEFAULT 0,
  `qte` int(250) NOT NULL,
  `nbrs` float NOT NULL DEFAULT 0,
  `poid` float NOT NULL DEFAULT 0,
  `benef` float NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `item_list`
--

INSERT INTO `item_list` (`id`, `name`, `category`, `typ`, `description`, `supplier_id`, `cost`, `stock`, `cost1`, `qte`, `nbrs`, `poid`, `benef`, `status`, `date_created`, `date_updated`) VALUES
(92, 'coca ', 'Jus', 'littre', 'ras', 11, 150, '100', 100, 100, 0, 0, 0, 1, '2025-02-10 13:12:35', '2025-02-10 13:30:23');

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `statut` enum('actif','licencie') DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id`, `nom_complet`, `statut`, `date_creation`) VALUES
(1, 'durane', 'actif', '2024-11-29 15:49:18');

-- --------------------------------------------------------

--
-- Structure de la table `po_items`
--

CREATE TABLE `po_items` (
  `po_id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL,
  `total` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

CREATE TABLE `presence` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `date_jour` date NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `heure_depart_pause` time DEFAULT NULL,
  `heure_retour_pause` time DEFAULT NULL,
  `retard` enum('non','oui') DEFAULT 'non',
  `absence` enum('non','oui') DEFAULT 'non',
  `pause_longue` enum('non','oui') DEFAULT 'non'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

CREATE TABLE `presences` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) NOT NULL,
  `date_jour` date NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `heure_depart_pause` time DEFAULT NULL,
  `heure_retour_pause` time DEFAULT NULL,
  `retard` enum('non','oui') DEFAULT 'non',
  `absence` enum('non','oui') DEFAULT 'non',
  `pause_longue` enum('non','oui') DEFAULT 'non'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `production_list`
--

CREATE TABLE `production_list` (
  `id` int(30) NOT NULL,
  `production_code` varchar(50) NOT NULL,
  `client` text DEFAULT NULL,
  `num` varchar(500) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `prix` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_amount` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `poids_sac` float NOT NULL,
  `quantite_kg` float NOT NULL,
  `prix_achat` float NOT NULL,
  `prix_vente` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `purchase_order_list`
--

CREATE TABLE `purchase_order_list` (
  `id` int(30) NOT NULL,
  `po_code` varchar(50) NOT NULL,
  `client` varchar(900) NOT NULL,
  `num` int(200) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `produit` varchar(900) NOT NULL,
  `prix` float NOT NULL DEFAULT 0,
  `amount` float NOT NULL,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = partially received, 2 =received',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `receiving_list`
--

CREATE TABLE `receiving_list` (
  `id` int(30) NOT NULL,
  `form_id` int(30) NOT NULL,
  `from_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=PO ,2 = BO',
  `amount` float NOT NULL DEFAULT 0,
  `discount_perc` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax_perc` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `stock_ids` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `return_list`
--

CREATE TABLE `return_list` (
  `id` int(30) NOT NULL,
  `return_code` varchar(50) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sales_list`
--

CREATE TABLE `sales_list` (
  `id` int(30) NOT NULL,
  `sales_code` varchar(50) NOT NULL,
  `client` text DEFAULT NULL,
  `num` varchar(500) NOT NULL,
  `ventetype` varchar(900) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `prix` float NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `stock_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_amount` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock_list`
--

CREATE TABLE `stock_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` float NOT NULL DEFAULT 0,
  `quantity2` float NOT NULL DEFAULT 0,
  `unit` varchar(250) DEFAULT NULL,
  `unit2` varchar(900) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `price2` float NOT NULL DEFAULT 0,
  `typ` varchar(900) NOT NULL,
  `prix` float NOT NULL DEFAULT 0,
  `total` float NOT NULL DEFAULT current_timestamp(),
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=IN , 2=OUT',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `supplier_list`
--

CREATE TABLE `supplier_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `category` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `cperson` text NOT NULL,
  `contact` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `supplier_list`
--

INSERT INTO `supplier_list` (`id`, `name`, `category`, `address`, `cperson`, `contact`, `status`, `date_created`, `date_updated`) VALUES
(11, 'RAS', '', 'RAS', 'RAS', '693481655', 1, '2025-02-10 13:04:39', '2025-02-10 13:04:39');

-- --------------------------------------------------------

--
-- Structure de la table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'DrinkFlow'),
(6, 'short_name', 'DrinkFlow'),
(11, 'logo', 'uploads/logo-1739190834.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1739190838.png'),
(15, 'content', 'Array');

-- --------------------------------------------------------

--
-- Structure de la table `typ`
--

CREATE TABLE `typ` (
  `id` int(200) NOT NULL,
  `typ` varchar(900) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1635556826', NULL, 1, '2021-01-20 14:02:37', '2021-10-30 09:20:26'),
(21, 'tester-beter', NULL, 'tester-beter', 'tester-beter', 'a045b60be781286b25d9cd7a24fe5c4c', 'uploads/avatar-21.png?v=1739188681', NULL, 1, '2025-02-10 12:57:59', '2025-02-10 12:58:01');

-- --------------------------------------------------------

--
-- Structure de la table `user_meta`
--

CREATE TABLE `user_meta` (
  `user_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `versement_bancaire`
--

CREATE TABLE `versement_bancaire` (
  `id` int(11) NOT NULL,
  `nom_banque` varchar(255) NOT NULL,
  `numero_compte` varchar(50) NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `date_versement` date NOT NULL,
  `mode_paiement` enum('Chèque','Virement','Espèces','Autre') NOT NULL,
  `description` text DEFAULT NULL,
  `reference_versement` varchar(100) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_mise_a_jour` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `versement_fournisseur`
--

CREATE TABLE `versement_fournisseur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_versement` date NOT NULL,
  `mode_paiement` enum('Espèces','Chèque','Virement','Autre') NOT NULL,
  `description` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_mise_a_jour` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `versement_journalier`
--

CREATE TABLE `versement_journalier` (
  `id` int(11) NOT NULL,
  `montant_total` decimal(15,2) NOT NULL,
  `recouvrement` decimal(15,2) NOT NULL,
  `approvisionnement` decimal(15,2) NOT NULL,
  `depense` float NOT NULL DEFAULT 0,
  `nombre_transactions` int(11) NOT NULL,
  `date_versement` date NOT NULL,
  `details` text DEFAULT NULL,
  `responsable` varchar(255) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_mise_a_jour` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pourcentage_10` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `approvisionnement`
--
ALTER TABLE `approvisionnement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `back_order_list`
--
ALTER TABLE `back_order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `receiving_id` (`receiving_id`);

--
-- Index pour la table `bordereau_livraison`
--
ALTER TABLE `bordereau_livraison`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bo_items`
--
ALTER TABLE `bo_items`
  ADD KEY `item_id` (`item_id`),
  ADD KEY `bo_id` (`bo_id`);

--
-- Index pour la table `chauffeur`
--
ALTER TABLE `chauffeur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande_produits`
--
ALTER TABLE `commande_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `creancier`
--
ALTER TABLE `creancier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `employes_presences`
--
ALTER TABLE `employes_presences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_complet` (`nom_complet`,`date_jour`);

--
-- Index pour la table `gestion_presence`
--
ALTER TABLE `gestion_presence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_complet` (`nom_complet`,`date_jour`);

--
-- Index pour la table `item_list`
--
ALTER TABLE `item_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `po_items`
--
ALTER TABLE `po_items`
  ADD KEY `po_id` (`po_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `presence`
--
ALTER TABLE `presence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personnel_id` (`personnel_id`);

--
-- Index pour la table `presences`
--
ALTER TABLE `presences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employe_id` (`employe_id`,`date_jour`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Index pour la table `receiving_list`
--
ALTER TABLE `receiving_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `return_list`
--
ALTER TABLE `return_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Index pour la table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `sales_list`
--
ALTER TABLE `sales_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock_list`
--
ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `typ`
--
ALTER TABLE `typ`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_meta`
--
ALTER TABLE `user_meta`
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `versement_bancaire`
--
ALTER TABLE `versement_bancaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `versement_fournisseur`
--
ALTER TABLE `versement_fournisseur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `versement_journalier`
--
ALTER TABLE `versement_journalier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `approvisionnement`
--
ALTER TABLE `approvisionnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `back_order_list`
--
ALTER TABLE `back_order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `bordereau_livraison`
--
ALTER TABLE `bordereau_livraison`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `chauffeur`
--
ALTER TABLE `chauffeur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande_produits`
--
ALTER TABLE `commande_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `creancier`
--
ALTER TABLE `creancier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `employes_presences`
--
ALTER TABLE `employes_presences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `gestion_presence`
--
ALTER TABLE `gestion_presence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `item_list`
--
ALTER TABLE `item_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `presence`
--
ALTER TABLE `presence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `presences`
--
ALTER TABLE `presences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `receiving_list`
--
ALTER TABLE `receiving_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `return_list`
--
ALTER TABLE `return_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sales_list`
--
ALTER TABLE `sales_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=489;

--
-- AUTO_INCREMENT pour la table `stock_list`
--
ALTER TABLE `stock_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1161;

--
-- AUTO_INCREMENT pour la table `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `typ`
--
ALTER TABLE `typ`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `versement_bancaire`
--
ALTER TABLE `versement_bancaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `versement_fournisseur`
--
ALTER TABLE `versement_fournisseur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `versement_journalier`
--
ALTER TABLE `versement_journalier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `back_order_list`
--
ALTER TABLE `back_order_list`
  ADD CONSTRAINT `back_order_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_order_list_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `purchase_order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `back_order_list_ibfk_3` FOREIGN KEY (`receiving_id`) REFERENCES `receiving_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bo_items`
--
ALTER TABLE `bo_items`
  ADD CONSTRAINT `bo_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bo_items_ibfk_2` FOREIGN KEY (`bo_id`) REFERENCES `back_order_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande_produits`
--
ALTER TABLE `commande_produits`
  ADD CONSTRAINT `commande_produits_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id`),
  ADD CONSTRAINT `commande_produits_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `item_list`
--
ALTER TABLE `item_list`
  ADD CONSTRAINT `item_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `po_items_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_order_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `po_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `presence`
--
ALTER TABLE `presence`
  ADD CONSTRAINT `presence_ibfk_1` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `presences`
--
ALTER TABLE `presences`
  ADD CONSTRAINT `presences_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD CONSTRAINT `purchase_order_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `return_list`
--
ALTER TABLE `return_list`
  ADD CONSTRAINT `return_list_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`);

--
-- Contraintes pour la table `stock_list`
--
ALTER TABLE `stock_list`
  ADD CONSTRAINT `stock_list_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
