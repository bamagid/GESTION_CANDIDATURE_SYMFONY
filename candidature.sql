-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 03 jan. 2024 à 23:20
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `candidature`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

DROP TABLE IF EXISTS `candidature`;
CREATE TABLE IF NOT EXISTS `candidature` (
  `id` int NOT NULL AUTO_INCREMENT,
  `statut_id` int NOT NULL,
  `user_id` int NOT NULL,
  `formation_id` int NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_E33BD3B8F6203804` (`statut_id`),
  KEY `IDX_E33BD3B8A76ED395` (`user_id`),
  KEY `IDX_E33BD3B85200282E` (`formation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidature`
--

INSERT INTO `candidature` (`id`, `statut_id`, `user_id`, `formation_id`, `created_at`) VALUES
(1, 3, 2, 2, '2023-12-29 11:43:38'),
(2, 2, 1, 1, '2023-12-29 11:43:38'),
(3, 2, 2, 2, '2024-01-01 22:11:17');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20231229164041', '2023-12-29 16:43:54', 103),
('DoctrineMigrations\\Version20231230145148', '2023-12-31 01:46:59', 23),
('DoctrineMigrations\\Version20231230161730', '2023-12-30 18:25:56', 138),
('DoctrineMigrations\\Version20240103152535', '2024-01-03 15:26:43', 137),
('DoctrineMigrations\\Version20240103183505', '2024-01-03 18:35:51', 79);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_cloture` datetime NOT NULL,
  `date_debut` datetime NOT NULL,
  `duree` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `is_fenced` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_404021BFA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `user_id`, `libelle`, `description`, `image`, `date_cloture`, `date_debut`, `duree`, `is_deleted`, `is_fenced`, `created_at`) VALUES
(1, 2, 'libelle', 'description', 'image', '2023-12-29 11:39:01', '2023-12-29 11:39:01', 'duree', 0, 1, '2023-12-29 11:39:01'),
(2, 2, 'libelle2', 'description2', 'image2', '2023-12-29 11:39:01', '2023-12-29 11:39:01', 'duree2', 0, 0, '2023-12-29 11:39:01');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `nom_role`) VALUES
(1, 'Admin'),
(2, 'Candidat');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

DROP TABLE IF EXISTS `statut`;
CREATE TABLE IF NOT EXISTS `statut` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`id`, `nom_statut`) VALUES
(1, 'En attente'),
(2, 'Accepter'),
(3, 'Refuser');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D649D60322AC` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `role_id`, `nom`, `prenom`, `adresse`, `telephone`, `email`, `password`, `created_at`) VALUES
(1, 2, 'Ba', 'Magid', 'Dakar', '78552220', 'magid@gmail.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 02:49:47'),
(2, 2, 'magid', 'Abdoul', 'Dakar', '78511222', 'magid1@gmail.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 02:49:47'),
(3, 2, 'Magid', 'Ba', 'Dakar', '7788522', 'mail12@gmail.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 18:01:25'),
(4, 2, 'Ba', 'Magnum', 'Dakar', '75582112', 'monmail@gmail.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 18:33:18'),
(5, 2, 'Ba', 'Magnum', 'Dakar', '75582112', 'monmail1@gmail.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 18:33:18'),
(6, 2, 'testhash', 'istrue?', 'listener', 'makeListener', 'mailevent@gmail.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 23:05:54'),
(7, 2, 'string', 'string', 'string', 'string', 'stringmail@gmail0.com', '$2y$13$k9cceCtk4vHSkdh83nH9EOQ.cDevC7jadulUihnrcZSnuTy3rUGee', '2023-12-29 23:11:17'),
(8, 2, 'modifier', 'modifier', 'modifier', 'modifier', 'modifier@email.com', '$2y$13$0fqtqXDbHy16KJVZl3ha2.syNaCXZkldUDQmyKEsackPtFGE6h2x2', '0000-00-00 00:00:00'),
(9, 2, 'ba', 'Magid', 'Dakar', '7755488', 'email@email.com', '$2y$13$dvl2LxtqoBS2nFjI1s3Ze.780gMb1ot76cEdltyOKZjtft04hLeRG', '2023-12-31 03:22:01'),
(11, 2, 'ba', 'Magid', 'Dakar', '7755488', 'email1@email.com', '$2y$13$lBWauctDGULKU4hEBHLEdeQxsT8CqaUYZAB1VF7pVNpokV5WGuy7u', '2023-12-31 03:23:56'),
(12, 2, 'ba', 'Magid', 'Dakar', '7755488', 'email12@email.com', '$2y$13$nIsPjRXv5Pfl8WQ9u.welOFCVEya.VjUqcp5Il5DnFJVXOG8tOKoi', '2023-12-31 03:38:47'),
(14, 2, 'ba', 'Magid', 'Dakar', '7755488', 'email@mail.com', '$2y$13$.1qRzN6gLP0ikAfdzkxaxOHYyvXaqp9MsnkCCSomjc.bwpcwdYlee', '2023-12-31 03:43:02'),
(15, 2, 'ba', 'Magid', 'Dakar', '7755488', 'magid@email.com', '$2y$13$5ooGiLiXg8h2KCpU4INQQOsfz43orqMoV9VBMGPuROKvLPzz/7GyS', '2024-01-03 15:42:52'),
(16, 2, 'ba', 'Magid', 'Dakar', '7755488', 'email1@mail.com', '$2y$13$yJVrjyMtYXeyU.PmC8p55uOSa0fUD5lD1xN277lxcEWzudQonWV5q', '2024-01-03 16:50:29'),
(18, 2, 'magid', 'ba', 'dakar', '77888522', 'magid@eemail.com', '$2y$13$wL5F/9qwiYDqucZoDVxRyOFTECmFo4nQsx0Hqm0L7Qk9CPcipkyNe', '2024-01-03 22:46:26');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `FK_E33BD3B85200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`),
  ADD CONSTRAINT `FK_E33BD3B8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_E33BD3B8F6203804` FOREIGN KEY (`statut_id`) REFERENCES `statut` (`id`);

--
-- Contraintes pour la table `formation`
--
ALTER TABLE `formation`
  ADD CONSTRAINT `FK_404021BFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
