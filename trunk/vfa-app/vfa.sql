-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 07 Novembre 2013 à 21:33
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `vfa`
--

-- --------------------------------------------------------

--
-- Structure de la table `vfa_authorizations`
--

CREATE TABLE IF NOT EXISTS `vfa_authorizations` (
  `authorization_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module` varchar(15) NOT NULL,
  `action` varchar(15) NOT NULL,
  PRIMARY KEY (`authorization_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1565 ;

--
-- Contenu de la table `vfa_authorizations`
--

INSERT INTO `vfa_authorizations` (`authorization_id`, `role_id`, `module`, `action`) VALUES
(1564, 6, 'nominees', 'read'),
(1563, 6, 'nominees', 'listThumbnail'),
(1562, 6, 'nominees', 'listAwards'),
(1535, 8, 'users', 'read'),
(1534, 8, 'users', 'listByGroup'),
(1533, 8, 'users', 'list'),
(1532, 8, 'users', 'index'),
(200, 3, 'invitations', 'toRegistry'),
(199, 3, 'invitations', 'index'),
(159, 11, 'awards', 'index'),
(160, 11, 'awards', 'list'),
(161, 11, 'awards', 'read'),
(162, 11, 'docs', 'create'),
(163, 11, 'docs', 'delete'),
(164, 11, 'docs', 'index'),
(165, 11, 'docs', 'list'),
(166, 11, 'docs', 'read'),
(167, 11, 'docs', 'update'),
(168, 11, 'home_enable', 'index'),
(169, 11, 'nominees', 'create'),
(170, 11, 'nominees', 'delete'),
(171, 11, 'nominees', 'index'),
(172, 11, 'nominees', 'list'),
(173, 11, 'nominees', 'listAwards'),
(174, 11, 'nominees', 'listHead'),
(175, 11, 'nominees', 'listThumbnail'),
(176, 11, 'nominees', 'read'),
(177, 11, 'nominees', 'update'),
(178, 12, 'awards', 'create'),
(179, 12, 'awards', 'index'),
(180, 12, 'awards', 'list'),
(181, 12, 'awards', 'read'),
(182, 12, 'awards', 'update'),
(183, 12, 'groups', 'create'),
(184, 12, 'groups', 'delete'),
(185, 12, 'groups', 'index'),
(186, 12, 'groups', 'list'),
(187, 12, 'groups', 'read'),
(188, 12, 'groups', 'update'),
(189, 12, 'home_enable', 'index'),
(190, 12, 'nominees', 'index'),
(191, 12, 'nominees', 'list'),
(192, 12, 'nominees', 'listAwards'),
(193, 12, 'nominees', 'listHead'),
(194, 12, 'nominees', 'listThumbnail'),
(195, 12, 'nominees', 'read'),
(196, 12, 'users', 'index'),
(197, 12, 'users', 'list'),
(198, 12, 'users', 'listByGroup'),
(201, 3, 'users', 'index'),
(202, 3, 'users', 'listByGroup'),
(1561, 6, 'nominees', 'list'),
(1560, 6, 'invitations', 'responsible'),
(1559, 6, 'invitations', 'index'),
(1558, 6, 'invitations', 'free'),
(1557, 6, 'docs', 'read'),
(214, 7, 'home_enable', 'index'),
(1531, 8, 'users', 'delete'),
(1530, 8, 'nominees', 'update'),
(1529, 8, 'nominees', 'read'),
(1528, 8, 'nominees', 'listThumbnail'),
(1527, 8, 'nominees', 'listHead'),
(1526, 8, 'nominees', 'listAwards'),
(1525, 8, 'nominees', 'list'),
(1524, 8, 'nominees', 'index'),
(1523, 8, 'invitations', 'toRegistry'),
(1522, 8, 'invitations', 'responsible'),
(1521, 8, 'invitations', 'index'),
(1520, 8, 'invitations', 'free'),
(1519, 8, 'invitations', 'board'),
(1518, 8, 'home_enable', 'index'),
(1517, 8, 'groups', 'read'),
(1516, 8, 'groups', 'list'),
(1515, 8, 'groups', 'index'),
(1514, 8, 'docs', 'update'),
(1513, 8, 'docs', 'list'),
(1512, 8, 'docs', 'index'),
(1511, 8, 'docs', 'delete'),
(1510, 8, 'awards', 'update'),
(1509, 8, 'awards', 'index'),
(1508, 8, 'awards', 'delete'),
(1507, 8, 'awards', 'create');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_awards` (
  `award_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'PBD',
  PRIMARY KEY (`award_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Contenu de la table `vfa_awards`
--

INSERT INTO `vfa_awards` (`award_id`, `name`, `start_date`, `end_date`, `public`, `type`) VALUES
(36, '2000', '2013-07-16', '2013-08-06', 0, 'PSBD'),
(32, 'Alices 2013', '2013-01-01', '2013-06-01', 1, 'PBD'),
(33, '2013', '2013-06-01', '2013-06-30', 0, 'PSBD'),
(37, 'Test', '2013-10-11', '2013-11-16', 1, 'PBD');

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

INSERT INTO `vfa_award_titles` (`award_id`, `title_id`) VALUES
(33, 70),
(32, 69),
(32, 63),
(32, 61),
(32, 65),
(32, 68),
(32, 67),
(33, 62),
(32, 66),
(37, 59),
(33, 71),
(32, 60),
(37, 61),
(32, 62),
(33, 60),
(32, 59);

-- --------------------------------------------------------

--
-- Structure de la table `vfa_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_docs` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `proper_title` varchar(50) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `order_title` varchar(50) NOT NULL,
  `date_legal` date NOT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Contenu de la table `vfa_docs`
--

INSERT INTO `vfa_docs` (`doc_id`, `title`, `proper_title`, `number`, `image`, `url`, `order_title`, `date_legal`) VALUES
(3, 'Les Brigades du Temps', '1492, &agrave; l&#039;ouest rien de nouveau !', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_156748.jpg', 'http://www.bedetheque.com/album-156748-BD-1492-a-l-ouest-rien-de-nouveau.html', 'Brigades du Temps (Les)', '2013-06-13'),
(4, 'Batchalo', NULL, NULL, 'http://www.bedetheque.com/media/Couvertures/170778_c.jpg', 'http://www.bedetheque.com/serie-35021-BD-Batchalo.html', 'Batchalo', '2013-06-13'),
(5, 'Le Singe de Hartlepool', NULL, NULL, 'http://www.bedetheque.com/media/Couvertures/Couv_169020.jpg', 'http://www.bedetheque.com/serie-34702-BD-Singe-de-Hartlepool.html', 'Singe de Hartlepool (Le)', '2013-06-13'),
(6, 'Daytripper', NULL, NULL, 'http://www.bedetheque.com/media/Couvertures/Couv_160817.jpg', 'http://www.bedetheque.com/serie-33179-BD-Daytripper-VF.html', 'Daytripper', '2013-06-13'),
(7, 'Héraklès', 'Tome 1', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_164372.jpg', 'http://www.bedetheque.com/serie-33801-BD-Herakles.html', 'Héraklès', '2013-06-13'),
(8, 'Le loup des mers', NULL, NULL, 'http://www.bedetheque.com/media/Couvertures/Couv_174188.jpg', 'http://www.bedetheque.com/album-174188-BD-Le-loup-des-mers.html', 'Loup des mers (Le)', '2013-06-13'),
(9, 'Masqué', 'Anomalies', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_147335.jpg', 'http://www.bedetheque.com/serie-31017-BD-Masque.html', 'Masqué', '2013-06-13'),
(10, 'La mémoire de l&#039;eau', 'Première partie', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_160306.jpg', 'http://www.bedetheque.com/serie-33086-BD-Memoire-de-l-eau.html', 'Mémoire de l&#039;eau (La)', '2013-06-13'),
(11, 'La mémoire de l&#039;eau', 'Seconde partie', '2', 'http://www.bedetheque.com/media/Couvertures/Couv_164323.jpg', 'http://www.bedetheque.com/serie-33086-BD-Memoire-de-l-eau.html', 'Mémoire de l&#039;eau (La)', '2012-03-15'),
(12, 'Je mourrai pas gibier', NULL, NULL, 'http://www.bedetheque.com/media/Couvertures/JeMourraiPasGibier_82105.jpg', 'http://www.bedetheque.com/serie-19588-BD-Je-mourrai-pas-gibier.html', 'Je mourrai pas gibier', '2013-06-13'),
(13, 'La Page blanche', NULL, NULL, 'http://www.bedetheque.com/media/Couvertures/Couv_150505.jpg', 'http://www.bedetheque.com/serie-31489-BD-Page-blanche.html', 'Page blanche (La)', '2013-06-13'),
(25, 'La belle image', NULL, NULL, NULL, NULL, 'Belle image (La)', '2011-03-03'),
(51, 'Masqu&eacute;', NULL, '2', 'http://www.bedetheque.com/media/Couvertures/Couv_162595.jpg', NULL, 'Masqu&eacute;', '2013-06-16'),
(52, 'Masqué', 'Seconde partie sss ssss szddgf tffg fg fgfgfgf', '3', 'http://www.bedetheque.com/media/Couvertures/170778_c.jpg', NULL, 'Masqué', '2013-05-12'),
(53, 'Masqué', NULL, '4', 'http://www.bedetheque.com/media/Couvertures/Couv_156748.jpg', NULL, 'Masqué', '2013-06-16'),
(55, 'Typhon', NULL, '24', 'http://www.bedetheque.com/media/Couvertures/Couv_169022.jpg', NULL, 'Typhon', '2013-06-19'),
(56, 'La Page blanche', NULL, 'Bis', 's', NULL, 'Page blanche (La)', '2013-06-19'),
(57, 'Album de test (a&agrave;&acirc;&auml;e&eacute;&amp', 'a&agrave;&acirc;&auml;e&eacute;&egrave;&euml;&amp;', '999', NULL, NULL, 'Album de test (a&agrave;&acirc;&auml;e&eacute;&amp', '2013-07-14'),
(58, 'Gung ho', 'Brebis galeuses', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_197119.jpg', NULL, 'Gung ho', '2013-10-05');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `vfa_groups`
--

INSERT INTO `vfa_groups` (`group_id`, `group_name`, `type`) VALUES
(1, 'Gaulois', 'READER'),
(2, 'CE Bull', 'READER'),
(3, 'Romains', 'READER'),
(4, 'Schneider Electric', 'READER'),
(11, 'Comit&eacute; de s&eacute;lection', 'BOARD');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_invitations`
--

CREATE TABLE IF NOT EXISTS `vfa_invitations` (
  `invitation_id` int(11) NOT NULL AUTO_INCREMENT,
  `invitation_key` varchar(100) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `type` varchar(15) NOT NULL,
  `state` varchar(10) NOT NULL,
  `awards_ids` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Contenu de la table `vfa_invitations`
--

INSERT INTO `vfa_invitations` (`invitation_id`, `invitation_key`, `created_user_id`, `type`, `state`, `awards_ids`, `email`, `group_id`, `created_date`, `modified_date`) VALUES
(58, '00f36fdeaa5e46a8c9a8b8eafd77046cd98ea82b1382984140', 2, 'READER', 'OPEN', '37', 'aa@aa.com', 2, '2013-10-28 19:15:40', '2013-11-06 00:28:09'),
(59, '2b4639df572ed7bd2c2cc89d554a634e13455b1e1383166909', 2, 'READER', 'OPEN', '32', 'aa@aaaaaaa.com', 2, '2013-10-30 22:01:49', '2013-11-06 00:42:11'),
(60, '746e33eede09877491ac8e485d93af0293c5d28c1383178958', 2, 'READER', 'OPEN', '37,32', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@aa.com', 1, '2013-10-31 01:22:38', '2013-11-03 23:47:31'),
(61, '15dd7fb80630093dfb0031542459b1e1df44f5041383260861', 2, 'READER', 'REJECTED', '37,32', 'aa@aaaaaaa.com', 1, '2013-11-01 00:07:41', '2013-11-06 00:45:06'),
(62, '9af2d88005a5f1745c87e42ddda09ac91dacff651383339833', 2, 'BOARD', 'OPEN', '33', 'q@q.fr', 11, '2013-11-01 22:03:53', '2013-11-06 00:29:06'),
(63, 'e19faf0dac08a5076faab11870418540c1986a121383520562', 2, 'READER', 'OPEN', '37,32', 'sophie@dddd.dd', 1, '2013-11-04 00:16:02', NULL),
(65, '2d1c77464a709f39179e745bb820c4734843bbb21383693487', 2, 'RESPONSIBLE', 'OPEN', '32', 'aa@aa.com', 4, '2013-11-06 00:18:07', '2013-11-06 00:28:41');

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

--
-- Contenu de la table `vfa_roles`
--

INSERT INTO `vfa_roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'owner', NULL),
(3, 'resp_group', 'Responsable d&#039;un groupe d&#039;utilisateurs'),
(6, 'committee_member', 'Membre du comit&eacute; de s&eacute;lection'),
(7, 'voter', 'Electeur participant à un prix'),
(8, 'test', 'azerty sqg qsd qs qsg qs qsg qsg sdg z&#039;trelmsdk ùmelùoazeqs;:,d!qk sqsdf qsdjfk z efq sdfjqmlsk azzea sdf df gsdf gsd  sdfgsdfg sdf gsdf sdfsdfggg sdfsdfg sdfg sdf s ddfgdg'),
(11, 'bookseller', 'Le libraire'),
(12, 'organizer', 'L&#039;organisateur du prix');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

--
-- Contenu de la table `vfa_titles`
--

INSERT INTO `vfa_titles` (`title_id`, `title`, `numbers`, `order_title`) VALUES
(68, 'Masqué', '#1', 'Masqué#1'),
(69, 'La Page blanche', '', 'Page blanche (La)'),
(67, 'Héraklès', '#1', 'Héraklès#1'),
(65, 'Je mourrai pas gibier', '', 'Je mourrai pas gibier'),
(66, 'Le Singe de Hartlepool', '', 'Singe de Hartlepool (Le)'),
(63, 'La mémoire de l&#039;eau', '#1, #2', 'Mémoire de l&#039;eau (La)#1, #2'),
(59, 'Batchalo', '', 'Batchalo'),
(60, 'Les Brigades du temps', '#1', 'Brigades du temps (Les)T1'),
(61, 'Daytripper', '', 'Daytripper'),
(62, 'Le loup des mers', '', 'Loup des mers (Le)'),
(70, 'Gung ho', '#1', 'Gung ho#1'),
(71, 'Album de test (a&agrave;&acirc;&auml;e&eacute;&amp', '#999', 'Album de test (a&agrave;&acirc;&auml;e&eacute;&amp');

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

INSERT INTO `vfa_title_docs` (`title_id`, `doc_id`) VALUES
(67, 7),
(66, 5),
(65, 12),
(70, 58),
(62, 8),
(71, 57),
(61, 6),
(69, 13),
(60, 3),
(59, 4),
(63, 11),
(63, 10),
(68, 9);

-- --------------------------------------------------------

--
-- Structure de la table `vfa_users`
--

CREATE TABLE IF NOT EXISTS `vfa_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `vote` tinyint(4) DEFAULT '1',
  `birthyear` smallint(6) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `vfa_users`
--

INSERT INTO `vfa_users` (`user_id`, `login`, `password`, `first_name`, `last_name`, `email`, `vote`, `birthyear`, `gender`, `created_date`, `modified_date`) VALUES
(2, 'ama', 'd8e6e1405e607479c1ce78791f76a05cb6dc01fa', 'first', 'amdeus', 'michelange.anton@free.fr', NULL, 1955, 'M', '2012-10-28 21:27:29', '2013-11-01 22:03:08'),
(3, 'asterix', '35bc6c28bca27fbecc144761d32d09986876438f', 'asterix', 'asterix', 'asterix@bretagne.com', NULL, 1958, NULL, '2012-10-28 21:27:29', '2013-07-10 22:46:10'),
(4, 'obelix', 'c9e44795639f0b01c2d7ce02ea1ef83038e0d476', 'obelix', 'obelix', 'asterix@bretagne.com', NULL, NULL, NULL, '2012-10-28 21:27:29', '2013-07-09 23:39:18'),
(5, 'cesar', '8eee89c994b90ad49540aa5dcd839138c25e0c96', 'jules', 'cesar', 'cesar@rome.it', NULL, 1950, 'M', '2012-10-28 21:27:29', '2013-10-21 22:53:51'),
(6, 'octavus', '1275c3b44b3368a66436ee9dc4155f134636839d', 'octavus', 'octavus', 'octavus@rome.it', NULL, NULL, NULL, '2012-10-28 21:27:29', '2013-07-09 23:39:43'),
(18, 'cesario', '', 'cesario', 'cesario', 'cesario@rome.it', NULL, 1999, NULL, '0000-00-00 00:00:00', '2013-07-09 23:38:39'),
(31, 'aaaaa', '', NULL, 'aaaaa', 'aa@aa.com', NULL, 1999, 'M', '2013-07-10 00:38:18', '2013-10-30 21:45:42'),
(28, '123456789012345678901234567890', '', NULL, NULL, 'abcdfeghijabcdfeghijabcdfeghij@123456789.fr', NULL, NULL, 'M', NULL, '2013-10-21 23:55:27'),
(24, 'aaa', '', NULL, NULL, 'aa@aa.com', NULL, 1999, 'F', NULL, '2013-07-10 00:27:00'),
(27, 'dom', '', NULL, NULL, 'dom@bdfugue.fr', NULL, NULL, NULL, NULL, '2013-07-09 23:41:31'),
(29, 'aaaa', '', NULL, NULL, 'aa@aa.com', 1, NULL, NULL, NULL, NULL);

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
  `user_id` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_user_groups`
--

INSERT INTO `vfa_user_groups` (`group_id`, `user_id`, `type`) VALUES
(1, 2, NULL),
(1, 3, NULL),
(1, 4, NULL),
(3, 5, NULL),
(3, 6, NULL),
(4, 28, NULL),
(11, 2, NULL),
(4, 24, NULL),
(2, 2, NULL);

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
(11, 31),
(7, 24),
(8, 3),
(7, 5),
(3, 5),
(3, 24),
(6, 24);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
