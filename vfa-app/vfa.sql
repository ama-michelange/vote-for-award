-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 21 Février 2015 à 14:49
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `vfa`
--
-- CREATE DATABASE IF NOT EXISTS `vfa` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- USE `vfa`;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_authorizations`
--

CREATE TABLE IF NOT EXISTS `vfa_authorizations` (
	`authorization_id` INT(11)     NOT NULL AUTO_INCREMENT,
	`role_id`          INT(11)     NOT NULL,
	`module`           VARCHAR(30) NOT NULL,
	`action`           VARCHAR(30) NOT NULL,
	PRIMARY KEY (`authorization_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 2937;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_awards` (
	`award_id`     INT(11)     NOT NULL AUTO_INCREMENT,
	`year`         INT(4)      NOT NULL,
	`name`         VARCHAR(50) NOT NULL,
	`start_date`   DATE        NOT NULL,
	`end_date`     DATE        NOT NULL,
	`public`       TINYINT(4)  NOT NULL DEFAULT '0',
	`type`         VARCHAR(10) NOT NULL DEFAULT 'PBD',
	`selection_id` INT(11)              DEFAULT NULL,
	PRIMARY KEY (`award_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 47;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_docs` (
	`doc_id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`title`        VARCHAR(50) NOT NULL,
	`proper_title` VARCHAR(80)          DEFAULT NULL,
	`number`       VARCHAR(20)          DEFAULT NULL,
	`image`        VARCHAR(255)         DEFAULT NULL,
	`url`          VARCHAR(255)         DEFAULT NULL,
	`order_title`  VARCHAR(50) NOT NULL,
	`date_legal`   DATE        NOT NULL,
	PRIMARY KEY (`doc_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 138;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_groups` (
	`group_id`        INT(11)      NOT NULL AUTO_INCREMENT,
	`group_name`      VARCHAR(100) NOT NULL,
	`role_id_default` INT(11)      NOT NULL,
	PRIMARY KEY (`group_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 27;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_group_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_group_awards` (
	`group_id` INT(11) NOT NULL,
	`award_id` INT(11) NOT NULL
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_invitations`
--

CREATE TABLE IF NOT EXISTS `vfa_invitations` (
	`invitation_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`invitation_key`  VARCHAR(100) NOT NULL,
	`created_user_id` INT(11)               DEFAULT NULL,
	`category`        VARCHAR(15)  NOT NULL,
	`type`            VARCHAR(15)  NOT NULL,
	`state`           VARCHAR(10)  NOT NULL,
	`awards_ids`      VARCHAR(50)           DEFAULT NULL,
	`email`           VARCHAR(100)          DEFAULT NULL,
	`user_id`         INT(11)               DEFAULT NULL,
	`group_id`        INT(11)               DEFAULT NULL,
	`created_date`    DATETIME     NOT NULL,
	`modified_date`   DATETIME              DEFAULT NULL,
	`ip`              VARCHAR(20)           DEFAULT NULL,
	`link_id`         INT(11)               DEFAULT NULL,
	`link_key`        VARCHAR(100)          DEFAULT NULL,
	PRIMARY KEY (`invitation_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 193;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_regin`
--

CREATE TABLE IF NOT EXISTS `vfa_regin` (
	`regin_id`        INT(11)     NOT NULL AUTO_INCREMENT,
	`type`            VARCHAR(15) NOT NULL,
	`code`            VARCHAR(20) NOT NULL,
	`state`           VARCHAR(10) NOT NULL,
	`created_user_id` INT(11)              DEFAULT NULL,
	`process`         VARCHAR(20)          DEFAULT NULL,
	`process_end`     DATE                 DEFAULT NULL,
	`process_options` VARCHAR(50)          DEFAULT NULL,
	`awards_ids`      VARCHAR(50)          DEFAULT NULL,
	`user_id`         INT(11)              DEFAULT NULL,
	`group_id`        INT(11)              DEFAULT NULL,
	`created_date`    DATETIME    NOT NULL,
	`modified_date`   DATETIME             DEFAULT NULL,
	PRIMARY KEY (`regin_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 21;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_regin_users`
--

CREATE TABLE IF NOT EXISTS `vfa_regin_users` (
	`regin_users_id` INT(11)  NOT NULL AUTO_INCREMENT,
	`regin_id`       INT(11)  NOT NULL,
	`user_id`        INT(11)  NOT NULL,
	`created_date`   DATETIME NOT NULL,
	PRIMARY KEY (`regin_users_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 10;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_roles`
--

CREATE TABLE IF NOT EXISTS `vfa_roles` (
	`role_id`     INT(11)     NOT NULL AUTO_INCREMENT,
	`role_name`   VARCHAR(20) NOT NULL,
	`description` VARCHAR(256)         DEFAULT NULL,
	PRIMARY KEY (`role_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 13;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_selections`
--

CREATE TABLE IF NOT EXISTS `vfa_selections` (
	`selection_id` INT(11)     NOT NULL AUTO_INCREMENT,
	`year`         INT(4)      NOT NULL,
	`name`         VARCHAR(50) NOT NULL,
	`create_date`  DATETIME             DEFAULT NULL,
	`type`         VARCHAR(10)          DEFAULT NULL,
	PRIMARY KEY (`selection_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 18;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_selection_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_selection_titles` (
	`selection_id` INT(11) NOT NULL,
	`title_id`     INT(11) NOT NULL
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_titles` (
	`title_id`    INT(11)     NOT NULL AUTO_INCREMENT,
	`title`       VARCHAR(50) NOT NULL,
	`numbers`     VARCHAR(50) NOT NULL,
	`order_title` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`title_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 192;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_title_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_title_docs` (
	`title_id` INT(11) NOT NULL,
	`doc_id`   INT(11) NOT NULL
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_users`
--

CREATE TABLE IF NOT EXISTS `vfa_users` (
	`user_id`       INT(11)      NOT NULL AUTO_INCREMENT,
	`login`         VARCHAR(100) NOT NULL,
	`password`      VARCHAR(50)  NOT NULL,
	`first_name`    VARCHAR(50)           DEFAULT NULL,
	`last_name`     VARCHAR(50)           DEFAULT NULL,
	`email`         VARCHAR(100)          DEFAULT NULL,
	`birthyear`     SMALLINT(6)           DEFAULT NULL,
	`gender`        VARCHAR(1)            DEFAULT NULL,
	`created_date`  DATETIME              DEFAULT NULL,
	`modified_date` DATETIME              DEFAULT NULL,
	PRIMARY KEY (`user_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 54;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_user_awards` (
	`user_id`  INT(11) NOT NULL,
	`award_id` INT(11) NOT NULL
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_user_groups` (
	`group_id` INT(11) NOT NULL,
	`user_id`  INT(11) NOT NULL
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_roles`
--

CREATE TABLE IF NOT EXISTS `vfa_user_roles` (
	`role_id` INT(11) NOT NULL,
	`user_id` INT(11) NOT NULL
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_votes`
--

CREATE TABLE IF NOT EXISTS `vfa_votes` (
	`vote_id`  INT(11)        NOT NULL AUTO_INCREMENT,
	`award_id` INT(11)        NOT NULL,
	`user_id`  INT(11)                 DEFAULT NULL,
	`number`   TINYINT(4)     NOT NULL,
	`average`  DECIMAL(10, 5) NOT NULL,
	`created`  DATETIME       NOT NULL,
	`modified` DATETIME       NOT NULL,
	PRIMARY KEY (`vote_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 2843;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_vote_items`
--

CREATE TABLE IF NOT EXISTS `vfa_vote_items` (
	`vote_item_id` INT(11)    NOT NULL AUTO_INCREMENT,
	`vote_id`      INT(11)    NOT NULL,
	`title_id`     INT(11)    NOT NULL,
	`score`        TINYINT(4) NOT NULL DEFAULT '-1',
	`comment`      TEXT,
	`created`      DATETIME   NOT NULL,
	`modified`     DATETIME   NOT NULL,
	PRIMARY KEY (`vote_item_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 38532;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_vote_results`
--

CREATE TABLE IF NOT EXISTS `vfa_vote_results` (
	`vote_result_id` INT(11)        NOT NULL AUTO_INCREMENT,
	`award_id`       INT(11)        NOT NULL,
	`title_id`       INT(11)        NOT NULL,
	`score`          INT(11)        NOT NULL,
	`number`         INT(11)        NOT NULL,
	`average`        DECIMAL(10, 5) NOT NULL,
	`created`        DATETIME       NOT NULL,
	`modified`       DATETIME       NOT NULL,
	PRIMARY KEY (`vote_result_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 105;

-- --------------------------------------------------------

--
-- Structure de la table `vfa_vote_stats`
--

CREATE TABLE IF NOT EXISTS `vfa_vote_stats` (
	`vote_stat_id` INT(11)     NOT NULL AUTO_INCREMENT,
	`award_id`     INT(11)              DEFAULT NULL,
	`code`         VARCHAR(20) NOT NULL,
	`num_int`      INT(11)              DEFAULT NULL,
	PRIMARY KEY (`vote_stat_id`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8
	AUTO_INCREMENT = 51;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
