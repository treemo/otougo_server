-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 06 Avril 2014 à 18:33
-- Version du serveur: 5.5.31
-- Version de PHP: 5.4.4-14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `otougo`
--
-- Fonctions
--
SET GLOBAL log_bin_trust_function_creators  = 1;
DELIMITER |
DROP FUNCTION IF EXISTS get_distance_metres|
CREATE FUNCTION get_distance_metres (lat1 DOUBLE, lng1 DOUBLE, lat2 DOUBLE, lng2 DOUBLE) RETURNS DOUBLE
BEGIN
    DECLARE rlo1 DOUBLE;
    DECLARE rla1 DOUBLE;
    DECLARE rlo2 DOUBLE;
    DECLARE rla2 DOUBLE;
    DECLARE dlo DOUBLE;
    DECLARE dla DOUBLE;
    DECLARE a DOUBLE;
 
    SET rlo1 = RADIANS(lng1);
    SET rla1 = RADIANS(lat1);
    SET rlo2 = RADIANS(lng2);
    SET rla2 = RADIANS(lat2);
    SET dlo = (rlo2 - rlo1) / 2;
    SET dla = (rla2 - rla1) / 2;
    SET a = SIN(dla) * SIN(dla) + COS(rla1) * COS(rla2) * SIN(dlo) * SIN(dlo);
    RETURN (6378137 * 2 * ATAN2(SQRT(a), SQRT(1 - a)));
END|
DELIMITER;

-- --------------------------------------------------------

--
-- Structure de la table `marker`
--

DROP TABLE IF EXISTS `marker`;
CREATE TABLE IF NOT EXISTS `marker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(1024) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `name` varchar(1024) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`(512),`latitude`,`longitude`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `marker`
--

DROP TABLE IF EXISTS `marker`;
CREATE TABLE IF NOT EXISTS `marker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(1024) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `name` varchar(1024) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`(512),`latitude`,`longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `marker_carpark`
--

DROP TABLE IF EXISTS `marker_carpark`;
CREATE TABLE IF NOT EXISTS `marker_carpark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `nb_available` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marker_id` (`marker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `marker_common`
--

DROP TABLE IF EXISTS `marker_common`;
CREATE TABLE IF NOT EXISTS `marker_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `last_station` int(11) DEFAULT NULL,
  `type` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marker_id` (`marker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `marker_cycle`
--

DROP TABLE IF EXISTS `marker_cycle`;
CREATE TABLE IF NOT EXISTS `marker_cycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `nb_available` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marker_id` (`marker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `marker_schedule`
--

DROP TABLE IF EXISTS `marker_schedule`;
CREATE TABLE IF NOT EXISTS `marker_schedule` (
  `marker_id` int(11) NOT NULL,
  `day` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `stat_use_get_marke`
--

DROP TABLE IF EXISTS `stat_use_get_marker`;
CREATE TABLE IF NOT EXISTS `stat_use_get_marker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `latitude` float NOT NULL,
  `longitude` int(11) NOT NULL,
  `distance` int(11) NOT NULL,
  `timeUpdate` int(11) NOT NULL,
  `filtre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `stat_use_get_marker_info`
--

DROP TABLE IF EXISTS `stat_use_get_marker_info`;
CREATE TABLE IF NOT EXISTS `stat_use_get_marker_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `marker_id` int(11) NOT NULL,
  `type` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

