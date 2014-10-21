-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 21 Octobre 2014 à 21:51
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2783 ;

--
-- Contenu de la table `vfa_authorizations`
--

INSERT INTO `vfa_authorizations` (`authorization_id`, `role_id`, `module`, `action`) VALUES
(1912, 11, 'selections', 'index'),
(2782, 7, 'votes', 'index'),
(1902, 11, 'nominees', 'create'),
(1903, 11, 'nominees', 'delete'),
(1904, 11, 'nominees', 'index'),
(1905, 11, 'nominees', 'list'),
(1906, 11, 'nominees', 'listThumbnail'),
(1911, 11, 'selections', 'delete'),
(1910, 11, 'selections', 'create'),
(1909, 11, 'nominees', 'update'),
(1908, 11, 'nominees', 'read'),
(1900, 11, 'docs', 'update'),
(2778, 12, 'users', 'read'),
(2777, 12, 'users', 'listResponsibleGroup'),
(2776, 12, 'users', 'listBoardGroup'),
(2775, 12, 'users', 'list'),
(2774, 12, 'users', 'index'),
(2773, 12, 'registred', 'listResponsible'),
(2772, 12, 'registred', 'listBoard'),
(2771, 12, 'registred', 'index'),
(2770, 12, 'nominees', 'read'),
(2769, 12, 'nominees', 'listThumbnailLarge'),
(2767, 12, 'nominees', 'list'),
(2768, 12, 'nominees', 'listThumbnail'),
(2502, 6, 'users', 'listBoardGroup'),
(2501, 6, 'selections', 'read'),
(2500, 6, 'selections', 'list'),
(2781, 7, 'home_enable', 'index'),
(2499, 6, 'selections', 'index'),
(2498, 6, 'registred', 'listBoardRegistred'),
(2497, 6, 'registred', 'listBoardGroup'),
(2496, 6, 'registred', 'index'),
(2495, 6, 'nominees', 'read'),
(2494, 6, 'nominees', 'listThumbnailLarge'),
(2493, 6, 'nominees', 'listThumbnail'),
(2492, 6, 'nominees', 'list'),
(2491, 6, 'nominees', 'index'),
(2487, 6, 'docs', 'listThumbnailLarge'),
(2488, 6, 'docs', 'read'),
(2489, 6, 'groups', 'index'),
(2739, 3, 'registred', 'index'),
(1907, 11, 'nominees', 'listThumbnailLarge'),
(1901, 11, 'home_enable', 'index'),
(1899, 11, 'docs', 'read'),
(1898, 11, 'docs', 'listThumbnailLarge'),
(1897, 11, 'docs', 'listThumbnail'),
(1896, 11, 'docs', 'list'),
(1895, 11, 'docs', 'index'),
(1894, 11, 'docs', 'delete'),
(1893, 11, 'docs', 'create'),
(1892, 11, 'awards', 'read'),
(1891, 11, 'awards', 'list'),
(1890, 11, 'awards', 'index'),
(1889, 11, 'accounts', 'update'),
(1888, 11, 'accounts', 'index'),
(1913, 11, 'selections', 'list'),
(1914, 11, 'selections', 'read'),
(1915, 11, 'selections', 'update'),
(2490, 6, 'groups', 'list'),
(2486, 6, 'docs', 'listThumbnail'),
(2485, 6, 'docs', 'list'),
(2484, 6, 'docs', 'index'),
(2765, 12, 'invitations', 'send'),
(2741, 3, 'users', 'read'),
(2780, 7, 'accounts', 'update'),
(2779, 7, 'accounts', 'index'),
(2740, 3, 'users', 'listMyGroup'),
(2738, 3, 'invitations', 'send'),
(2737, 3, 'invitations', 'read'),
(2736, 3, 'invitations', 'listReader'),
(2735, 3, 'invitations', 'invitReader'),
(2734, 3, 'invitations', 'index'),
(2483, 6, 'awards', 'read'),
(2482, 6, 'awards', 'list'),
(2481, 6, 'awards', 'index'),
(2480, 6, 'accounts', 'update'),
(2766, 12, 'nominees', 'index'),
(2764, 12, 'invitations', 'read'),
(2763, 12, 'invitations', 'listResponsible'),
(2762, 12, 'invitations', 'listBoard'),
(2761, 12, 'invitations', 'listAllMyInvitations'),
(2760, 12, 'invitations', 'invitResponsible'),
(2759, 12, 'invitations', 'invitBoard'),
(2758, 12, 'invitations', 'index'),
(2757, 12, 'invitations', 'delete'),
(2756, 12, 'home_enable', 'index'),
(2755, 12, 'groups', 'update'),
(2754, 12, 'groups', 'read'),
(2753, 12, 'groups', 'list'),
(2752, 12, 'groups', 'index'),
(2751, 12, 'groups', 'delete'),
(2750, 12, 'groups', 'create'),
(2749, 12, 'awards', 'update'),
(2748, 12, 'awards', 'read'),
(2747, 12, 'awards', 'list'),
(2746, 12, 'awards', 'index'),
(2742, 12, 'accounts', 'index'),
(2743, 12, 'accounts', 'update'),
(2744, 12, 'awards', 'create'),
(2731, 3, 'accounts', 'index'),
(2732, 3, 'accounts', 'update'),
(2479, 6, 'accounts', 'index'),
(2503, 6, 'users', 'read'),
(2733, 3, 'invitations', 'delete'),
(2745, 12, 'awards', 'delete');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `vfa_awards`
--

INSERT INTO `vfa_awards` (`award_id`, `year`, `name`, `start_date`, `end_date`, `public`, `type`, `selection_id`) VALUES
(36, 2000, 'Test', '2013-07-16', '2013-08-06', 0, 'PSBD', NULL),
(32, 2013, 'Alices', '2013-01-01', '2013-06-01', 1, 'PBD', NULL),
(33, 2014, 'Ama', '2013-06-01', '2013-06-30', 0, 'PSBD', 6),
(37, 2010, 'Test', '2013-10-11', '2013-11-16', 1, 'PBD', 8),
(38, 2014, 'Testa', '2014-02-24', '2014-08-01', 1, 'PBD', 6),
(39, 2001, 'Testa', '2014-03-15', '2014-04-01', 0, 'PBD', NULL),
(40, 2014, 'Une BD', '2014-04-01', '2014-04-30', 0, 'PBD', 10),
(41, 2015, 'Alices', '2014-01-01', '2014-10-31', 0, 'PSBD', 11);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Contenu de la table `vfa_docs`
--

INSERT INTO `vfa_docs` (`doc_id`, `title`, `proper_title`, `number`, `image`, `url`, `order_title`, `date_legal`) VALUES
(3, 'Les Brigades du Temps', '1492, à l&#039;ouest rien de nouveau !', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_156748.jpg', 'http://www.bedetheque.com/album-156748-BD-1492-a-l-ouest-rien-de-nouveau.html', 'Brigades du Temps (Les)', '2013-06-13'),
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
(51, 'Masqué', NULL, '2', 'http://www.bedetheque.com/media/Couvertures/Couv_162595.jpg', NULL, 'Masqué', '2013-06-16'),
(52, 'Masqué', 'Seconde partie sss ssss szddgf tffg fg fgfgfgf', '3', 'http://www.bedetheque.com/media/Couvertures/170778_c.jpg', NULL, 'Masqué', '2013-05-12'),
(53, 'Masqué', NULL, '4', 'http://www.bedetheque.com/media/Couvertures/Couv_156748.jpg', NULL, 'Masqué', '2013-06-16'),
(55, 'Typhon', NULL, '24', 'http://www.bedetheque.com/media/Couvertures/Couv_169022.jpg', NULL, 'Typhon', '2013-06-19'),
(56, 'La Page blanche', NULL, 'Bis', 's', NULL, 'Page blanche (La)', '2013-06-19'),
(57, 'Album de Test d&#039;appel mlkjsdf qsdmflk jqsdmfl', 'aàâäeéèë&amp;', '999', NULL, NULL, 'Album de Test d&#039;appel mlkjsdf qsdmflk jqsdmfl', '2013-07-14'),
(58, 'Gung ho', 'Brebis galeuses', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_197119.jpg', NULL, 'Gung ho', '2013-10-05'),
(59, 'Paci', 'Bacalan', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_210982.jpg', 'http://www.bedetheque.com/BD-Paci-Tome-1-Bacalan-210982.html', 'Paci', '2014-04-26'),
(60, 'Metropolis', NULL, '1', 'http://www.bedetheque.com/media/Couvertures/Couv_203342.jpg', 'http://www.bedetheque.com/BD-Metropolis-Delcourt-Tome-1-Metropolis-1-203342.html', 'Metropolis', '2014-04-26'),
(61, 'Hedge Fund', 'Des hommes d&#039;argent', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_202369.jpg', 'http://www.bedetheque.com/BD-Hedge-Fund-Tome-1-Des-hommes-d-argent-202369.html', 'Hedge Fund', '2014-04-26'),
(62, 'Vor', 'Un voleur dans la loi', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_213139.jpg', 'http://www.bedetheque.com/BD-Vor-Tome-1-Un-voleur-dans-la-loi-213139.html', 'Vor', '2014-04-27'),
(63, 'Rock &amp; Stone', NULL, '1', 'http://www.bedetheque.com/media/Couvertures/Couv_203104.jpg', 'http://www.bedetheque.com/BD-Rock-Stone-Tome-1-203104.html', 'Rock &amp; Stone', '2014-04-27'),
(64, 'Le Soufflevent', 'New Pearl - Alexandrie', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_203390.jpg', 'http://www.bedetheque.com/BD-Soufflevent-Tome-1-New-Pearl-Alexandrie-203390.html', 'Soufflevent (Le)', '2014-04-27'),
(65, 'Les Campbell', 'Inferno', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_203188.jpg', 'http://www.bedetheque.com/BD-Campbell-Tome-1-Inferno-203188.html', 'Campbell (Les)', '2014-04-27'),
(66, 'Maggy Garrisson', 'Fais un sourire, Maggy', '1', 'http://www.bedetheque.com/media/Couvertures/Couv_211495.jpg', 'http://www.bedetheque.com/BD-Maggy-Garrisson-Tome-1-Fais-un-sourire-Maggy-211495.html', 'Maggy Garrisson', '2014-04-27');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_groups`
--

CREATE TABLE IF NOT EXISTS `vfa_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `role_id_default` int(11) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `vfa_groups`
--

INSERT INTO `vfa_groups` (`group_id`, `group_name`, `role_id_default`) VALUES
(1, 'Gaulois', 7),
(2, 'CE Bull', 7),
(3, 'Romains', 7),
(4, 'Schneider Electric', 7),
(11, 'Comité de sélection', 6),
(24, 'Lecteurs Test', 7);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=182 ;

--
-- Contenu de la table `vfa_invitations`
--

INSERT INTO `vfa_invitations` (`invitation_id`, `invitation_key`, `created_user_id`, `category`, `type`, `state`, `awards_ids`, `email`, `user_id`, `group_id`, `created_date`, `modified_date`, `ip`, `link_id`, `link_key`) VALUES
(178, '692752023a93dbfb5043be0b4cd20e0d2ca7745d1406147919', 5, 'MODIFY', 'password', 'SENT', NULL, 'cesar@rome.it', NULL, NULL, '2014-07-23 22:38:39', '2014-07-23 22:38:39', '127.0.0.1', NULL, NULL),
(122, '312603a41860b02ccea3ece60bad1b5c9d3c4ccc1402184307', 32, 'INVITATION', 'board', 'ACCEPTED', '41', 'octavus@rome.it', NULL, 11, '2014-06-08 01:38:27', '2014-06-08 01:39:50', '', NULL, NULL),
(136, 'a9e5335064bf5681f09a13124c3e32f2572b2bb21404678416', 32, 'INVITATION', 'responsible', 'SENT', '38', 'schneider@electric.com', NULL, 4, '2014-07-06 22:26:56', '2014-07-06 23:51:25', '', NULL, NULL),
(137, '632a935dcb0796d65b4ffdd06f5595a4b01804111404681587', 32, 'INVITATION', 'responsible', 'SENT', '38', 'pierre.vannier@bull.net', NULL, 2, '2014-07-06 23:19:47', '2014-07-06 23:19:47', '', NULL, NULL),
(133, '1c13eed1f81f5d790815bc922318c4058c6a9ebe1402260215', 32, 'INVITATION', 'board', 'OPEN', '41', 'se5@se.com', NULL, 11, '2014-06-08 22:43:35', NULL, '', NULL, NULL),
(132, '1a813fb25fd58ed50b8b2a942573393b669f6b211402260132', 32, 'INVITATION', 'board', 'OPEN', '41', 'se4@se.com', NULL, 11, '2014-06-08 22:42:12', NULL, '', NULL, NULL),
(130, 'dbcedb1e2b3ad2ec469f246de5c00f8deb4b99c51402259921', 32, 'INVITATION', 'board', 'SENT', '41', 'se2@se.com', NULL, 11, '2014-06-08 22:38:41', '2014-07-06 23:27:48', '', NULL, NULL),
(131, '0c136847c01f80d49f9fb5937e229870f6023e341402260016', 32, 'INVITATION', 'board', 'OPEN', '41', 'se3@se.com', NULL, 11, '2014-06-08 22:40:16', NULL, '', NULL, NULL),
(129, '863861d8edcc50ecb650a42b30b066df9c455f951402257595', 32, 'INVITATION', 'responsible', 'ACCEPTED', '38', 'se1@se.com', NULL, 4, '2014-06-08 21:59:55', '2014-06-08 22:36:48', '', NULL, NULL),
(138, '7623584cd823ce103a198be7ad08b8313c3f1a551404682363', 32, 'INVITATION', 'board', 'ACCEPTED', '41', 'kiki.rosberg@fia.net', NULL, 11, '2014-07-06 23:32:43', '2014-07-06 23:36:22', '', NULL, NULL),
(139, 'e2f57314e7e6e1be687038c05e63b892f7a97f051404684050', 32, 'INVITATION', 'responsible', 'ACCEPTED', '38', 'kiki.rosberg@fia.net', NULL, 24, '2014-07-07 00:00:50', '2014-07-07 00:01:33', '', NULL, NULL),
(110, 'd7a24092e11266ccdcc62d1470776dd4e9d9de8a1401144939', 2, 'INVITATION', 'responsible', 'SENT', '38', 'michelange.anton@free.fr', NULL, 1, '2014-05-27 00:55:39', NULL, '', NULL, NULL),
(80, '4ce88ef99a4f583405ed7aa01a9812308d9bdbef1388018503', 2, 'INVITATION', 'board', 'OPEN', '33', 'zorro@californie.com', NULL, 11, '2013-12-26 01:41:43', NULL, '', NULL, NULL),
(111, '5e6c0713dcaf1564f71950c1bbe64452d2bc7b541401145101', 2, 'INVITATION', 'responsible', 'ACCEPTED', '38', 'ama@bull.net', NULL, 2, '2014-05-27 00:58:21', '2014-06-06 01:20:57', '', NULL, NULL),
(119, '1a7cdd2b54fcde4414ca946b09d6b784c7ee9d5a1401396135', 5, 'INVITATION', 'reader', 'ACCEPTED', '38', 'octavus@rome.it', NULL, 3, '2014-05-29 22:42:15', '2014-06-08 01:19:47', '', NULL, NULL),
(118, '9e4067aa7a93b1a5f8ea6e2d0fc91688666ed6571401395991', 5, 'INVITATION', 'reader', 'ACCEPTED', '38', 'cesario@rome.it', NULL, 3, '2014-05-29 22:39:51', '2014-06-08 01:17:48', '', NULL, NULL),
(120, 'ab7716eb4234b71404d5a9046f807881ff1c8bcf1401491646', 5, 'INVITATION', 'reader', 'SENT', '38', 'aquarius@rome.it', NULL, 3, '2014-05-31 01:14:06', '2014-05-31 01:20:31', '', NULL, NULL),
(121, 'b7fbda2e361d8385be5534ea68b68daa76807ca71401492003', 5, 'INVITATION', 'reader', 'SENT', '38', 'bobinus@rome.it', NULL, 3, '2014-05-31 01:20:03', '2014-05-31 01:21:05', '', NULL, NULL),
(181, 'dfb3f661162bdda64858b25e5840dcdcb035d1311406156396', NULL, 'MODIFY', 'password', 'SENT', NULL, 'asterix@bretagne.com', NULL, NULL, '2014-07-24 00:59:56', '2014-07-24 00:59:56', '127.0.0.1', 136, 'a9e5335064bf5681f09a13124c3e32f2572b2bb21404678416');

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
(3, 'responsible', 'Responsable d&#039;un groupe d&#039;utilisateurs'),
(6, 'board', 'Membre du comité de sélection'),
(7, 'reader', 'Lecteur participant à un prix'),
(11, 'bookseller', 'Le libraire'),
(12, 'organizer', 'L&#039;organisateur du prix');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `vfa_selections`
--

INSERT INTO `vfa_selections` (`selection_id`, `year`, `name`, `create_date`, `type`) VALUES
(6, 2014, 'Zorro', NULL, NULL),
(7, 2013, 'Amadeus', NULL, NULL),
(8, 2010, 'Magic', NULL, NULL),
(9, 2014, 'Une BD sans prix', NULL, NULL),
(10, 2014, 'Une BD dans un PRIX', NULL, NULL),
(11, 2015, 'Présélection Alices', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vfa_selection_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_selection_titles` (
  `selection_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_selection_titles`
--

INSERT INTO `vfa_selection_titles` (`selection_id`, `title_id`) VALUES
(6, 87),
(6, 86),
(6, 84),
(6, 83),
(8, 88),
(6, 82),
(9, 89),
(10, 90),
(11, 91),
(11, 92),
(11, 93);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

--
-- Contenu de la table `vfa_titles`
--

INSERT INTO `vfa_titles` (`title_id`, `title`, `numbers`, `order_title`) VALUES
(92, 'Paci', '#1', 'Paci#1'),
(91, 'Hedge Fund', '#1', 'Hedge Fund#1'),
(90, 'Gung ho', '#1', 'Gung ho#1'),
(89, 'La Page blanche', '', 'Page blanche (La)'),
(88, 'Les Brigades du Temps', '#1', 'Brigades du Temps (Les)#1'),
(85, 'Gung ho', '#1', 'Gung ho#1'),
(86, 'Le loup des mers', '', 'Loup des mers (Le)'),
(87, 'Les Brigades du Temps', '#1', 'Brigades du Temps (Les)#1'),
(84, 'La mémoire de l&#039;eau', '#1, #2', 'Mémoire de l&#039;eau (La)#1, #2'),
(83, 'Album de Test d&#039;appel mlkjsdf qsdmflk jqsdmfl', '#999', 'Album de Test d&#039;appel mlkjsdf qsdmflk jqsdmfl'),
(82, 'Batchalo', '', 'Batchalo'),
(93, 'Metropolis', '#1', 'Metropolis#1');

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
(93, 60),
(92, 59),
(91, 61),
(90, 58),
(89, 13),
(86, 8),
(88, 3),
(87, 3),
(85, 58),
(84, 11),
(84, 10),
(83, 57),
(82, 4);

-- --------------------------------------------------------

--
-- Structure de la table `vfa_users`
--

CREATE TABLE IF NOT EXISTS `vfa_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthyear` smallint(6) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Contenu de la table `vfa_users`
--

INSERT INTO `vfa_users` (`user_id`, `login`, `password`, `first_name`, `last_name`, `email`, `birthyear`, `gender`, `created_date`, `modified_date`) VALUES
(2, 'ama', 'd8e6e1405e607479c1ce78791f76a05cb6dc01fa', 'first', 'amadeus', 'michelange.anton@free.fr', 1955, 'M', '2012-10-28 21:27:29', '2014-05-03 02:35:22'),
(3, 'asterix', '35bc6c28bca27fbecc144761d32d09986876438f', 'Asterix', 'Le gaulois', 'asterix@bretagne.com', 1961, 'M', '2012-10-28 21:27:29', '2014-05-15 23:04:46'),
(4, 'obelix', 'c9e44795639f0b01c2d7ce02ea1ef83038e0d476', 'obelix', 'obelix', 'asterix@bretagne.com', NULL, NULL, '2012-10-28 21:27:29', '2014-05-15 00:55:51'),
(5, 'cesar', '8eee89c994b90ad49540aa5dcd839138c25e0c96', 'Jules', 'César', 'cesar@rome.it', 1950, 'M', '2012-10-28 21:27:29', '2014-06-03 21:46:52'),
(6, 'octavus', '1275c3b44b3368a66436ee9dc4155f134636839d', 'octavus', 'octavus', 'octavus@rome.it', NULL, NULL, '2012-10-28 21:27:29', '2014-06-08 01:39:50'),
(18, 'cesario', 'e36e9a3b488538fd5fa62b45f3beecb3f22196d6', 'cesario', 'cesario', 'cesar@rome.it', 1999, NULL, '2013-12-03 08:39:17', '2014-06-08 01:17:48'),
(28, '123456789012345678901234567890', 'b8489c3d1018dc378c6f2c1bf5bd8c69b16290e2', '3456789012', '2345678901', 'abcdfeghijabcdfeghijabcdfeghij@123456789.fr', 1989, 'M', NULL, '2014-04-29 01:21:45'),
(24, 'aaaaaaa', 'e93b4e3c464ffd51732fbd6ded717e9efda28aad', 'Prénom', 'Nom', 'aaaA@aaa.com', 1991, 'M', NULL, '2014-05-15 22:58:33'),
(27, 'dom', '', NULL, NULL, 'dom@bdfugue.fr', NULL, NULL, NULL, '2014-06-08 00:56:43'),
(32, 'organizer', 'd47297fa68d80bcdb6bdd6c8eae893de538dcac1', 'Bertrand', 'Le Costumier', 'organizer@organizer.com', NULL, NULL, '2014-05-01 00:56:38', '2014-06-08 22:09:15'),
(33, 'ama@bull.net', 'b8489c3d1018dc378c6f2c1bf5bd8c69b16290e2', 'a', 'a', 'ama@bull.net', NULL, NULL, '2014-06-06 00:57:39', '2014-06-06 00:57:39'),
(34, 'ama2@bull.net', 'dbaa30de22b1129ec140a188fc3c06a6af8e9f1f', NULL, NULL, 'ama@bull.net', NULL, NULL, '2014-06-06 01:20:57', '2014-06-06 01:20:57'),
(38, 'sophie@dddd.dd', '0ff52c5e5fb3e26ff648c02f921cd534be6f8a48', NULL, NULL, 'sophie@dddd.dd', NULL, NULL, '2014-06-07 00:54:35', '2014-06-07 00:54:35'),
(39, 'se1@se.com', 'b8489c3d1018dc378c6f2c1bf5bd8c69b16290e2', NULL, NULL, 'se1@se.com', NULL, NULL, '2014-06-08 22:36:48', '2014-06-08 22:36:48'),
(40, 'rosberg', 'c6c03501a905371b9b6baa6bed695066c62fd60d', 'Kiki', 'Rosberg', 'kiki.rosberg@fia.net', NULL, NULL, '2014-07-06 23:36:22', '2014-07-07 00:01:33');

-- --------------------------------------------------------

--
-- Structure de la table `vfa_user_awards`
--

CREATE TABLE IF NOT EXISTS `vfa_user_awards` (
  `user_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `vfa_user_awards`
--

INSERT INTO `vfa_user_awards` (`user_id`, `award_id`) VALUES
(2, 38),
(5, 38),
(33, 38),
(34, 38),
(38, 32),
(38, 37),
(18, 38),
(6, 38),
(6, 41),
(39, 38),
(40, 41),
(40, 38);

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
(3, 18),
(1, 3),
(1, 4),
(3, 5),
(3, 6),
(4, 28),
(11, 2),
(2, 2),
(4, 24),
(11, 24),
(11, 5),
(2, 33),
(11, 32),
(2, 34),
(1, 38),
(11, 6),
(4, 39),
(11, 40),
(24, 40);

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
(6, 2),
(7, 28),
(7, 33),
(3, 3),
(3, 5),
(7, 5),
(3, 33),
(7, 24),
(6, 24),
(1, 2),
(7, 2),
(7, 6),
(12, 32),
(7, 3),
(6, 5),
(7, 4),
(6, 32),
(7, 18),
(7, 34),
(3, 34),
(7, 38),
(11, 27),
(6, 6),
(3, 39),
(7, 39),
(6, 40),
(3, 40),
(7, 40);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `vfa_votes`
--

INSERT INTO `vfa_votes` (`vote_id`, `award_id`, `user_id`, `number`, `average`, `created`, `modified`) VALUES
(7, 41, 6, 2, '1.50000', '2014-10-20 00:22:32', '2014-10-20 00:59:11');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `vfa_vote_items`
--

INSERT INTO `vfa_vote_items` (`vote_item_id`, `vote_id`, `title_id`, `score`, `comment`, `created`, `modified`) VALUES
(13, 7, 91, 2, NULL, '2014-10-20 00:22:32', '2014-10-20 00:50:35'),
(14, 7, 93, 1, NULL, '2014-10-20 00:22:32', '2014-10-20 00:59:11'),
(15, 7, 92, -1, NULL, '2014-10-20 00:22:32', '2014-10-20 00:22:32');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
