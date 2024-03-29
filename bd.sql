-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Mer 18 Mai 2022 à 12:48
-- Version du serveur :  5.7.29
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `in21b20284`
--
CREATE DATABASE IF NOT EXISTS `in21b20284` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `in21b20284`;

-- --------------------------------------------------------

--
-- Structure de la table `Depense`
--

CREATE TABLE `Depense` (
  `did` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `montant` int(11) DEFAULT NULL,
  `libelle` varchar(50) DEFAULT NULL,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `tag` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Depense`
--

INSERT INTO `Depense` (`did`, `date`, `montant`, `libelle`, `gid`, `uid`, `tag`) VALUES
(4, '2022-04-08', 10, 'Glaces', 4, 3, 'Loisir'),
(10, '2022-04-08', 50, 'Restaurant', 4, 3, 'Manger'),
(13, '2022-05-04', 10, 'Parc d\'attraction', 4, 5, 'Loisir'),
(14, '2022-05-04', 50, 'Cinema', 4, 7, 'Loisir'),
(15, '2022-05-04', 5, 'Gauffres', 4, 8, 'Manger');

-- --------------------------------------------------------

--
-- Structure de la table `Facture`
--

CREATE TABLE `Facture` (
  `fid` int(11) NOT NULL,
  `scan` varchar(100) NOT NULL,
  `did` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Facture`
--

INSERT INTO `Facture` (`fid`, `scan`, `did`) VALUES
(8, 'uploads/modele-facture-fr-pur-750px.png', 4);

-- --------------------------------------------------------

--
-- Structure de la table `Groupe`
--

CREATE TABLE `Groupe` (
  `gid` int(11) NOT NULL,
  `nom` text,
  `devise` varchar(20) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Groupe`
--

INSERT INTO `Groupe` (`gid`, `nom`, `devise`, `uid`) VALUES
(4, 'Mon super groupe', 'euro', 3),
(6, 'test', 'euro', 5);

-- --------------------------------------------------------

--
-- Structure de la table `Participer`
--

CREATE TABLE `Participer` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `estConfirme` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Participer`
--

INSERT INTO `Participer` (`uid`, `gid`, `estConfirme`) VALUES
(3, 4, 1),
(5, 4, 1),
(5, 6, 1),
(7, 4, 1),
(8, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `uid` int(11) NOT NULL,
  `courriel` varchar(256) NOT NULL,
  `nom` text,
  `prenom` text,
  `motPasse` text,
  `estActif` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`uid`, `courriel`, `nom`, `prenom`, `motPasse`, `estActif`) VALUES
(3, 'f.detiffe@student.helmo.be', 'Detiffe', 'Florian', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 1),
(5, 'fdetiffe@gmail.com', 'Baguette', 'Flo', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 1),
(7, 'naiko4264@yahoo.com', 'Youtube', 'Naiko', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 1),
(8, 'emmadilandro@yahoo.com', 'Dilandro', 'Emma', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 1);

-- --------------------------------------------------------

--
-- Structure de la table `Versement`
--

CREATE TABLE `Versement` (
  `vid` int(11) NOT NULL,
  `uidVerseur` int(11) NOT NULL,
  `uidBenefi` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `montant` float NOT NULL,
  `estConfirme` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Depense`
--
ALTER TABLE `Depense`
  ADD PRIMARY KEY (`did`),
  ADD UNIQUE KEY `Depense_did_uindex` (`did`),
  ADD KEY `Depense_gid_uindex` (`gid`) USING BTREE,
  ADD KEY `Depense_uid_uindex` (`uid`) USING BTREE;

--
-- Index pour la table `Facture`
--
ALTER TABLE `Facture`
  ADD PRIMARY KEY (`fid`),
  ADD UNIQUE KEY `Facture_fid_uindex` (`fid`),
  ADD KEY `Facture_Depense_did_fk` (`did`);

--
-- Index pour la table `Groupe`
--
ALTER TABLE `Groupe`
  ADD PRIMARY KEY (`gid`),
  ADD UNIQUE KEY `Groupe_gid_uindex` (`gid`),
  ADD UNIQUE KEY `Groupe_uid_uindex` (`uid`);

--
-- Index pour la table `Participer`
--
ALTER TABLE `Participer`
  ADD UNIQUE KEY `pid` (`uid`,`gid`),
  ADD KEY `foreign_gid` (`gid`);

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `Utilisateur_courriel_uindex` (`courriel`);

--
-- Index pour la table `Versement`
--
ALTER TABLE `Versement`
  ADD PRIMARY KEY (`vid`),
  ADD KEY `uidVerseur` (`uidVerseur`),
  ADD KEY `uidBeneficiaire` (`uidBenefi`),
  ADD KEY `gidVersement` (`gid`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Depense`
--
ALTER TABLE `Depense`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `Facture`
--
ALTER TABLE `Facture`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `Groupe`
--
ALTER TABLE `Groupe`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `Versement`
--
ALTER TABLE `Versement`
  MODIFY `vid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Depense`
--
ALTER TABLE `Depense`
  ADD CONSTRAINT `dgid` FOREIGN KEY (`gid`) REFERENCES `Groupe` (`gid`),
  ADD CONSTRAINT `duid` FOREIGN KEY (`uid`) REFERENCES `Utilisateur` (`uid`);

--
-- Contraintes pour la table `Facture`
--
ALTER TABLE `Facture`
  ADD CONSTRAINT `Facture_Depense_did_fk` FOREIGN KEY (`did`) REFERENCES `Depense` (`did`);

--
-- Contraintes pour la table `Groupe`
--
ALTER TABLE `Groupe`
  ADD CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `Utilisateur` (`uid`);

--
-- Contraintes pour la table `Participer`
--
ALTER TABLE `Participer`
  ADD CONSTRAINT `Participer_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `Groupe` (`gid`),
  ADD CONSTRAINT `Participer_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `Utilisateur` (`uid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
