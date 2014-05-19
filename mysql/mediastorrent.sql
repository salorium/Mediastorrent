-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 05 Mai 2014 à 00:21
-- Version du serveur: 5.5.32-0ubuntu0.13.04.1
-- Version de PHP: 5.4.9-4ubuntu2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `mediastorrent`
--
CREATE DATABASE IF NOT EXISTS `mediastorrent`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci;
USE `mediastorrent`;

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE IF NOT EXISTS `amis` (
  `demandeur` VARCHAR(200)
              COLLATE utf8_unicode_ci NOT NULL,
  `login`     VARCHAR(200)
              COLLATE utf8_unicode_ci NOT NULL,
  `ok`        TINYINT(1)              NOT NULL,
  PRIMARY KEY (`demandeur`, `login`),
  KEY `demandeur` (`demandeur`),
  KEY `login` (`login`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `film`
--

CREATE TABLE IF NOT EXISTS `film` (
  `id`            VARCHAR(10)
                  COLLATE utf8_unicode_ci NOT NULL,
  `titre`         VARCHAR(200)
                  COLLATE utf8_unicode_ci NOT NULL,
  `titreoriginal` VARCHAR(200)
                  COLLATE utf8_unicode_ci NOT NULL,
  `acteurs`       VARCHAR(1000)
                  COLLATE utf8_unicode_ci NOT NULL,
  `realisateurs`  VARCHAR(1000)
                  COLLATE utf8_unicode_ci NOT NULL,
  `anneeprod`     VARCHAR(4)
                  COLLATE utf8_unicode_ci NOT NULL,
  `urlposter`     VARCHAR(1000)
                  COLLATE utf8_unicode_ci NOT NULL,
  `urlbackdrop`   VARCHAR(1000)
                  COLLATE utf8_unicode_ci NOT NULL,
  `infos`         TEXT
                  COLLATE utf8_unicode_ci NOT NULL,
  `idallocine`    VARCHAR(50)
                  COLLATE utf8_unicode_ci DEFAULT NULL,
  `idthemoviedb`  VARCHAR(50)
                  COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `titre` (`titre`),
  KEY `idallocine` (`idallocine`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `id`    VARCHAR(10)
          COLLATE utf8_unicode_ci NOT NULL,
  `label` VARCHAR(200)
          COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`, `label`),
  KEY `id` (`id`),
  KEY `label` (`label`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rtorrent`
--

CREATE TABLE IF NOT EXISTS `rtorrent` (
  `hostname` VARCHAR(300)
             COLLATE utf8_unicode_ci NOT NULL,
  `nom`      VARCHAR(200)
             COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nom`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rtorrents`
--

CREATE TABLE IF NOT EXISTS `rtorrents` (
  `nomrtorrent` VARCHAR(200)
                COLLATE utf8_unicode_ci NOT NULL,
  `login`       VARCHAR(200)
                COLLATE utf8_unicode_ci NOT NULL,
  `portscgi`    VARCHAR(10)
                COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nomrtorrent`, `login`),
  KEY `nomrtorrent` (`nomrtorrent`),
  KEY `login` (`login`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id`     VARCHAR(40)
           COLLATE utf8_unicode_ci NOT NULL,
  `donnee` LONGTEXT
           COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cronroot`
--

CREATE TABLE IF NOT EXISTS `cronroot` (
  `id`       VARCHAR(40)
             COLLATE utf8_unicode_ci NOT NULL,
  `donnee`   LONGTEXT
             COLLATE utf8_unicode_ci NOT NULL,
  `resultat` LONGTEXT
             COLLATE utf8_unicode_ci DEFAULT NULL,
  `nomrtorrent` VARCHAR(200)
                COLLATE utf8_unicode_ci NOT NULL,
  `fini`     TINYINT(1)              NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nomrtorrent` (`nomrtorrent`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `torrentfilm`
--

CREATE TABLE IF NOT EXISTS `torrentfilm` (
  `id`                VARCHAR(10)
                      COLLATE utf8_unicode_ci NOT NULL,
  `date`              TIMESTAMP               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `numfile`           VARCHAR(10)
                      COLLATE utf8_unicode_ci NOT NULL,
  `complementfichier` VARCHAR(1000)
                      COLLATE utf8_unicode_ci DEFAULT NULL,
  `idfilm`            VARCHAR(10)
                      COLLATE utf8_unicode_ci NOT NULL,
  `login`             VARCHAR(200)
                      COLLATE utf8_unicode_ci NOT NULL,
  `nomrtorrent`       VARCHAR(200)
                      COLLATE utf8_unicode_ci NOT NULL,
  `hashtorrent`       VARCHAR(40)
                      COLLATE utf8_unicode_ci NOT NULL,
  `clefunique`        VARCHAR(10)
                      COLLATE utf8_unicode_ci NOT NULL,
  `fini`              TINYINT(1)              NOT NULL,
  `mediainfo`         TEXT
                      COLLATE utf8_unicode_ci,
  `partageamis`       TINYINT(1)              NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idfilm` (`idfilm`),
  KEY `login` (`login`),
  KEY `hashtorrent` (`hashtorrent`),
  KEY `nomrtorrent` (`nomrtorrent`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `login`        VARCHAR(200)
                 COLLATE utf8_unicode_ci NOT NULL,
  `motdepasse`   VARCHAR(40)
                 COLLATE utf8_unicode_ci NOT NULL,
  `mail`         VARCHAR(200)
                 COLLATE utf8_unicode_ci NOT NULL,
  `role`         VARCHAR(250)
                 COLLATE utf8_unicode_ci NOT NULL,
  `keyconnexion` VARCHAR(40)
                 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`login`),
  UNIQUE KEY `mail` (`mail`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_unicode_ci;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `amis`
--
ALTER TABLE `amis`
ADD CONSTRAINT `amis_ibfk_1` FOREIGN KEY (`demandeur`) REFERENCES `utilisateur` (`login`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `amis_ibfk_2` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Contraintes pour la table `genre`
--
ALTER TABLE `genre`
ADD CONSTRAINT `genre_ibfk_1` FOREIGN KEY (`id`) REFERENCES `film` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Contraintes pour la table `rtorrents`
--
ALTER TABLE `rtorrents`
ADD CONSTRAINT `rtorrents_ibfk_1` FOREIGN KEY (`nomrtorrent`) REFERENCES `rtorrent` (`nom`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `rtorrents_ibfk_2` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `cronroot`
ADD CONSTRAINT `cronroot_ibfk_1` FOREIGN KEY (`nomrtorrent`) REFERENCES `rtorrent` (`nom`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Contraintes pour la table `torrentfilm`
--
ALTER TABLE `torrentfilm`
ADD CONSTRAINT `torrentfilm_ibfk_1` FOREIGN KEY (`idfilm`) REFERENCES `film` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `torrentfilm_ibfk_2` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `torrentfilm_ibfk_3` FOREIGN KEY (`nomrtorrent`) REFERENCES `rtorrent` (`nom`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
