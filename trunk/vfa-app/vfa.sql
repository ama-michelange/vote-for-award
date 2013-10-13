-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 30 Octobre 2012 à 23:45
-- Version du serveur: 5.1.36
-- Version de PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `vfa`
--

-- --------------------------------------------------------

--
-- Structure de la table `vfa_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_awards` (
  `award_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `vfa_awards`
--


-- --------------------------------------------------------

--
-- Structure de la table `vfa_award_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_award_titles` (
  `award_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_award_titles`
--


-- --------------------------------------------------------

--
-- Structure de la table `vfa_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_docs` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `serial_title` varchar(50) DEFAULT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `vfa_docs`
--


-- --------------------------------------------------------

--
-- Structure de la table `vfa_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `vfa_groups`
--

INSERT INTO `vfa_groups` (`group_id`, `group_name`) VALUES
(1, 'gaulois'),
(2, 'bull'),
(3, 'romain'),
(4, 'schneider electric');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_roles`
--

CREATE TABLE IF NOT EXISTS `vfa_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `vfa_roles`
--

INSERT INTO `vfa_roles` (`role_id`, `role_name`) VALUES
(1, 'owner'),
(2, 'award_admin'),
(3, 'group_admin');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_titles` (
  `title_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`title_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `vfa_titles`
--


-- --------------------------------------------------------

--
-- Structure de la table `vfa_title_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_title_docs` (
  `title_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_title_docs`
--


-- --------------------------------------------------------

--
-- Structure de la table `vfa_users`
--

CREATE TABLE IF NOT EXISTS `vfa_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `vote` tinyint(4) DEFAULT '1',
  `create_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `vfa_users`
--

INSERT INTO `vfa_users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `email`, `vote`, `create_date`, `modified_date`) VALUES
(2, 'ama', 'd8e6e1405e607479c1ce78791f76a05cb6dc01fa', 'ama', 'ama', 'ama@gmail.com', 1, '2012-10-28 21:27:29', NULL),
(3, 'asterix', '35bc6c28bca27fbecc144761d32d09986876438f', 'asterix', 'asterix', NULL, 1, '2012-10-28 21:27:29', NULL),
(4, 'obelix', 'c9e44795639f0b01c2d7ce02ea1ef83038e0d476', 'obelix', 'obelix', NULL, 0, '2012-10-28 21:27:29', NULL),
(5, 'cesar', '8eee89c994b90ad49540aa5dcd839138c25e0c96', 'jules', 'cesar', NULL, 0, '2012-10-28 21:27:29', NULL),
(6, 'octavus', '1275c3b44b3368a66436ee9dc4155f134636839d', 'octavus', 'octavus', NULL, 0, '2012-10-28 21:27:29', NULL),
(11, 'az&eacute;z', 'az&eacute;z', 'az&eacute;z', 'az&eacute;z', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_user_groups` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_user_groups`
--

INSERT INTO `vfa_user_groups` (`group_id`, `user_id`) VALUES
(2, 2),
(1, 3),
(1, 4),
(3, 5),
(3, 6);

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_roles`
--

CREATE TABLE IF NOT EXISTS `vfa_user_roles` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_user_roles`
--

INSERT INTO `vfa_user_roles` (`role_id`, `user_id`) VALUES
(1, 2),
(2, 3),
(3, 3),
(3, 5);
