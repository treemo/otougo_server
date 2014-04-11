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
-- Structure de la table `marker_bicycle`
--

DROP TABLE IF EXISTS `marker_bicycle`;
CREATE TABLE IF NOT EXISTS `marker_bicycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `nb_available` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marker_id` (`marker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `marker_parking`
--

DROP TABLE IF EXISTS `marker_parking`;
CREATE TABLE IF NOT EXISTS `marker_parking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marker_id` int(11) NOT NULL,
  `nb_available` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marker_id` (`marker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
