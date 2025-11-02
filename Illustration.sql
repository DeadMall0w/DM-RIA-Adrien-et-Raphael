-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 02 nov. 2025 à 13:44
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
-- Base de données : `DM_RIA_2025`
--

-- --------------------------------------------------------

--
-- Structure de la table `Illustration`
--

CREATE TABLE `Illustration` (
  `ID` int(11) NOT NULL,
  `Image` varchar(250) DEFAULT NULL,
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
(2, 'image', 'logo', 'texte', 4, 5, 3, 1, 3, 4, 'bla bla  ');

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Illustration`
--
ALTER TABLE `Illustration`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Illustration`
--
ALTER TABLE `Illustration`
  ADD CONSTRAINT `Illustration_ibfk_1` FOREIGN KEY (`Createur`) REFERENCES `Personne` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
