-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 03 nov. 2025 à 06:37
-- Version du serveur : 10.11.13-MariaDB-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dm_ria_2025`
--

-- --------------------------------------------------------

--
-- Structure de la table `Illustration`
--

CREATE TABLE `Illustration` (
  `ID` int(11) NOT NULL,
  `Image` varchar(1000) DEFAULT NULL,
  `Logo` varchar(250) DEFAULT NULL,
  `Texte` varchar(250) DEFAULT NULL,
  `Dimension_QR` int(11) DEFAULT NULL,
  `Dimension_IX` int(11) DEFAULT NULL,
  `Dimension_IY` int(11) DEFAULT NULL,
  `P_Texte` int(11) DEFAULT NULL,
  `P_QR` int(11) DEFAULT NULL,
  `Createur` int(11) NOT NULL,
  `complement` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Illustration`
--

INSERT INTO `Illustration` (`ID`, `Image`, `Logo`, `Texte`, `Dimension_QR`, `Dimension_IX`, `Dimension_IY`, `P_Texte`, `P_QR`, `Createur`, `complement`) VALUES
(6, 'https://img.freepik.com/photos-gratuite/composition-livre-livre-ouvert_23-2147690555.jpg?semt=ais_hybrid&w=740&q=80', '', 'Read the F****ng manual', 10, 600, 600, 1, 1, 13, ''),
(7, 'https://securinglaravel.com/content/images/size/w1200/2024/09/Securing-Laravel---Arrow-Frame--1-.png', '', 'Never Trust User Input', 10, 1000, 700, 3, 1, 13, ''),
(8, 'https://media.licdn.com/dms/image/v2/D5612AQFvIu8uQaM8hQ/article-cover_image-shrink_720_1280/article-cover_image-shrink_720_1280/0/1739334807217?e=2147483647&v=beta&t=wL5JMOwQgUeP2t_6mL6KyThcrDnpeNXteIzwWhtD-4s', '', 'Build Done', 10, 1400, 800, 3, 1, 13, '');

-- --------------------------------------------------------

--
-- Structure de la table `Page`
--

CREATE TABLE `Page` (
  `ID` int(11) NOT NULL,
  `ID_Illustration` int(11) DEFAULT NULL,
  `Texte` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

CREATE TABLE `Personne` (
  `ID` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `Code` varchar(50) NOT NULL,
  `Professeur` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Personne`
--

INSERT INTO `Personne` (`ID`, `Nom`, `Prenom`, `Code`, `Professeur`) VALUES
(10, 'Berquier', 'Raphael', '123', 0),
(12, 'Maes', 'Adrien', 'a', 0),
(13, 'Bourdeaud\'huy', 'Thomas', 'abc', 1);

-- --------------------------------------------------------

--
-- Structure de la table `Recevoir`
--

CREATE TABLE `Recevoir` (
  `ID` int(11) NOT NULL,
  `ID_Illustration` int(11) NOT NULL,
  `ID_Personne` int(11) NOT NULL,
  `DateReception` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Recevoir`
--

INSERT INTO `Recevoir` (`ID`, `ID_Illustration`, `ID_Personne`, `DateReception`) VALUES
(9, 8, 10, '2025-11-03'),
(10, 8, 13, '2025-11-03');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Illustration`
--
ALTER TABLE `Illustration`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Createur` (`Createur`);

--
-- Index pour la table `Page`
--
ALTER TABLE `Page`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID_Illustration` (`ID_Illustration`);

--
-- Index pour la table `Personne`
--
ALTER TABLE `Personne`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `Recevoir`
--
ALTER TABLE `Recevoir`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Illustration` (`ID_Illustration`),
  ADD KEY `ID_Personne` (`ID_Personne`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Illustration`
--
ALTER TABLE `Illustration`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `Page`
--
ALTER TABLE `Page`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Personne`
--
ALTER TABLE `Personne`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `Recevoir`
--
ALTER TABLE `Recevoir`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Illustration`
--
ALTER TABLE `Illustration`
  ADD CONSTRAINT `Illustration_ibfk_1` FOREIGN KEY (`Createur`) REFERENCES `Personne` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Page`
--
ALTER TABLE `Page`
  ADD CONSTRAINT `Page_ibfk_1` FOREIGN KEY (`ID_Illustration`) REFERENCES `Illustration` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Recevoir`
--
ALTER TABLE `Recevoir`
  ADD CONSTRAINT `Recevoir_ibfk_1` FOREIGN KEY (`ID_Illustration`) REFERENCES `Illustration` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Recevoir_ibfk_2` FOREIGN KEY (`ID_Personne`) REFERENCES `Personne` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
