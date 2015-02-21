-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 21 Février 2015 à 14:06
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `vfa`
--
CREATE DATABASE IF NOT EXISTS `vfa` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `vfa`;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_authorizations`
--

CREATE TABLE IF NOT EXISTS `vfa_authorizations` (
  `authorization_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  PRIMARY KEY (`authorization_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2937 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_awards` (
  `award_id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'PBD',
  `selection_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_docs` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `proper_title` varchar(80) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `order_title` varchar(50) NOT NULL,
  `date_legal` date NOT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `role_id_default` int(11) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_group_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_group_awards` (
  `group_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_invitations`
--

CREATE TABLE IF NOT EXISTS `vfa_invitations` (
  `invitation_id` int(11) NOT NULL AUTO_INCREMENT,
  `invitation_key` varchar(100) NOT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `category` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL,
  `state` varchar(10) NOT NULL,
  `awards_ids` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `link_key` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=193 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_regin`
--

CREATE TABLE IF NOT EXISTS `vfa_regin` (
  `regin_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(15) NOT NULL,
  `code` varchar(20) NOT NULL,
  `state` varchar(10) NOT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `process` varchar(20) DEFAULT NULL,
  `process_end` date DEFAULT NULL,
  `process_options` varchar(50) NOT NULL,
  `awards_ids` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`regin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_regin_users`
--

CREATE TABLE IF NOT EXISTS `vfa_regin_users` (
  `regin_users_id` int(11) NOT NULL AUTO_INCREMENT,
  `regin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`regin_users_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_roles`
--

CREATE TABLE IF NOT EXISTS `vfa_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_selections`
--

CREATE TABLE IF NOT EXISTS `vfa_selections` (
  `selection_id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`selection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_selection_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_selection_titles` (
  `selection_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_titles` (
  `title_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `numbers` varchar(50) NOT NULL,
  `order_title` varchar(50) NOT NULL,
  PRIMARY KEY (`title_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=192 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_title_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_title_docs` (
  `title_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_users`
--

CREATE TABLE IF NOT EXISTS `vfa_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthyear` smallint(6) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_user_awards` (
  `user_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_user_groups` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_roles`
--

CREATE TABLE IF NOT EXISTS `vfa_user_roles` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_votes`
--

CREATE TABLE IF NOT EXISTS `vfa_votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `number` tinyint(4) NOT NULL,
  `average` decimal(10,5) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2843 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_vote_items`
--

CREATE TABLE IF NOT EXISTS `vfa_vote_items` (
  `vote_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  `score` tinyint(4) NOT NULL DEFAULT '-1',
  `comment` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`vote_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38532 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_vote_results`
--

CREATE TABLE IF NOT EXISTS `vfa_vote_results` (
  `vote_result_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `average` decimal(10,5) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`vote_result_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_vote_stats`
--

CREATE TABLE IF NOT EXISTS `vfa_vote_stats` (
  `vote_stat_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_id` int(11) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `num_int` int(11) DEFAULT NULL,
  PRIMARY KEY (`vote_stat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
