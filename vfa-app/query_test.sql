--
-- Base de données: `vfa`
--

-- --------------------------------------------------------


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

-- --------------------------------------------------------

--
-- Structure de la table `vfa_selection_titles`
--

CREATE TABLE IF NOT EXISTS `vfa_selection_titles` (
  `selection_id` int(11) NOT NULL,
  `title_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
-- Structure de la table `vfa_title_docs`
--

CREATE TABLE IF NOT EXISTS `vfa_title_docs` (
  `title_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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


--
--
--

SELECT * FROM vfa_votes
WHERE (vfa_votes.award_id = 38) AND (vfa_votes.number > 3);

SELECT * FROM vfa_votes, vfa_vote_items
WHERE
  (vfa_votes.award_id = 38)
  AND (vfa_votes.number > 3)
  AND (vfa_votes.vote_id = vfa_vote_items.vote_id);


SELECT vfa_vote_items.vote_id, vfa_vote_items.title_id, vfa_vote_items.vote_item_id, vfa_vote_items.score
FROM vfa_votes, vfa_vote_items
WHERE
  (vfa_votes.award_id = 38)
  AND (vfa_votes.number > 3)
  AND (vfa_votes.vote_id = vfa_vote_items.vote_id)
  AND (vfa_vote_items.score > -1);

SELECT vfa_vote_items.title_id, count(*), sum(vfa_vote_items.score)
FROM vfa_votes, vfa_vote_items
WHERE
  (vfa_votes.award_id = 38)
  AND (vfa_votes.number > 3)
  AND (vfa_votes.vote_id = vfa_vote_items.vote_id)
  AND (vfa_vote_items.score > -1)
GROUP BY vfa_vote_items.title_id;


SELECT count(*) FROM vfa_vote_items;
SELECT count(*), sum(score) FROM vfa_vote_items;



SELECT *
FROM vfa_selections as s, vfa_selection_titles as st, vfa_titles as t
WHERE s.selection_id = st.selection_id
      AND st.title_id = t.title_id
ORDER BY s.selection_id ASC, t.order_title ASC;


SELECT * FROM vfa_votes WHERE modified >= DATE("2014-11-08");


-- Création d'un index
CREATE INDEX RoleId_Module_Action ON vfa_authorizations (role_id,module(10,action(10));

-- Suppression d'un index
ALTER TABLE vfa_authorizations DROP INDEX RoleId_Module_Action;


SELECT count(*) FROM vfa_votes WHERE (award_id = 43) ;

SELECT * FROM vfa_votes, vfa_user_groups
WHERE
  (vfa_votes.award_id = 43)
  AND (vfa_user_groups.group_id = 1)
  AND (vfa_user_groups.user_id = vfa_votes.user_id);