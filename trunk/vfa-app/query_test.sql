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

-- ------------------------------------------------------------

--  vfa_groups
CREATE INDEX group_name ON vfa_groups (group_name(15));
CREATE INDEX group_role_id_name ON vfa_groups (role_id_default, group_name(15));

-- vfa_group_awards
ALTER TABLE vfa_group_awards ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
CREATE INDEX group_awards_group_id ON vfa_group_awards (group_id, award_id);
CREATE INDEX group_awards_award_id ON vfa_group_awards (award_id, group_id);

--  vfa_invitations
CREATE INDEX invitation_category_type ON vfa_invitations (category(5), type(5));
CREATE INDEX invitation_category_group ON vfa_invitations (category(5), group_id);

--  vfa_regin
CREATE INDEX regin_type_created_date ON vfa_regin (type(5), created_date);
CREATE INDEX regin_created_user_type_created_date ON vfa_regin (created_user_id, type(5), created_date);
CREATE INDEX regin_type_group_state ON vfa_regin (type(5), group_id, state(5), created_date);
CREATE INDEX regin_process_end__type_group_state ON vfa_regin (process_end, type(5), group_id, state(5), created_date);
CREATE INDEX regin_code ON vfa_regin (code(15));

-- vfa_regin_users
CREATE INDEX regin_users_regin_id ON vfa_regin_users (regin_id, user_id);
CREATE INDEX regin_users_user_id ON vfa_regin_users (user_id, regin_id);
CREATE INDEX regin_users_regin_id_created_date ON vfa_regin_users (regin_id, created_date);

--  vfa_roles
CREATE INDEX role_role_name ON vfa_roles (role_name(15));

--  vfa_selections
CREATE INDEX selection_year_name ON vfa_selections (year, name(20));

-- vfa_selection_titles
ALTER TABLE vfa_selection_titles ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
CREATE INDEX selection_titles_selection_id ON vfa_selection_titles (selection_id, title_id);
CREATE INDEX selection_titles_title_id ON vfa_selection_titles (title_id, selection_id);

--  vfa_titles
CREATE INDEX title_order_title ON vfa_titles (order_title(20));
CREATE INDEX title_title_numbers ON vfa_titles (title(15), numbers(5));

-- vfa_title_docs
ALTER TABLE vfa_title_docs ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
CREATE INDEX title_docs_title_id ON vfa_title_docs (title_id, doc_id);
CREATE INDEX title_docs_doc_id ON vfa_title_docs (doc_id, title_id);

-- vfa_users
CREATE INDEX user_last_first_name ON vfa_users (last_name(20), first_name(20));
CREATE INDEX user_login ON vfa_users (login(20));
CREATE INDEX user_email ON vfa_users (email(20));

-- vfa_user_awards
ALTER TABLE vfa_user_awards ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
CREATE INDEX user_awards_user_id ON vfa_user_awards (user_id, award_id);
CREATE INDEX user_awards_award_id ON vfa_user_awards (award_id, user_id);

-- vfa_user_groups
ALTER TABLE vfa_user_groups ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
CREATE INDEX user_groups_user_id ON vfa_user_groups (user_id, group_id);
CREATE INDEX user_groups_group_id ON vfa_user_groups (group_id, user_id);

-- vfa_user_roles
ALTER TABLE vfa_user_roles ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
CREATE INDEX user_roles_user_id ON vfa_user_roles (user_id, role_id);
CREATE INDEX user_roles_role_id ON vfa_user_roles (role_id, user_id);

--  vfa_votes
CREATE INDEX votes_user_award ON vfa_votes (user_id, award_id);
CREATE INDEX votes_award_modified ON vfa_votes (award_id, modified);
CREATE INDEX votes_award_user ON vfa_votes (award_id, user_id);

--  vfa_vote_items
CREATE INDEX vote_items_vote_title ON vfa_vote_items (vote_id, title_id);

--  vfa_vote_results
CREATE INDEX vote_results_award_title ON vfa_vote_results (award_id, title_id);
CREATE INDEX vote_results_award_modified ON vfa_vote_results (award_id, modified);

--  vfa_vote_stats
CREATE INDEX vote_stats_award_code ON vfa_vote_stats (award_id, code(10));


EXPLAIN
  SELECT *
    FROM vfa_users, vfa_user_roles, vfa_roles, vfa_user_awards
    WHERE ( vfa_user_roles.user_id = vfa_users.user_id )
    AND ( vfa_user_awards.user_id = vfa_users.user_id )
    AND ( vfa_user_roles.role_id = vfa_roles.role_id )
    AND (vfa_roles.role_name =  "board")
    AND (vfa_user_awards.award_id =41);

SELECT count(*) AS total FROM vfa_users, vfa_user_groups WHERE (vfa_user_groups.user_id = vfa_users.user_id) AND (vfa_user_groups.group_id = 3);

SELECT * FROM vfa_users, vfa_user_roles, vfa_roles, vfa_user_groups WHERE (vfa_user_roles.user_id = vfa_users.user_id)
  AND (vfa_user_groups.user_id = vfa_users.user_id) AND (vfa_user_roles.role_id = vfa_roles.role_id)
	AND (vfa_roles.role_name = "reader") AND (vfa_user_groups.group_id = 3) ORDER BY vfa_users.last_name, vfa_users.first_name;

SELECT * FROM vfa_votes WHERE award_id=41 ORDER BY modified DESC;

SELECT vfa_vote_items.title_id, count(*), sum(vfa_vote_items.score) FROM vfa_votes, vfa_vote_items
			WHERE (vfa_votes.award_id = 43 ) AND (vfa_votes.number >=  7 )
			AND (vfa_votes.vote_id = vfa_vote_items.vote_id) AND (vfa_vote_items.score > -1) GROUP BY vfa_vote_items.title_id;

SELECT * FROM vfa_vote_results WHERE award_id=43 ORDER BY average DESC;

SELECT * FROM vfa_vote_stats WHERE award_id=43 ORDER BY code;

SELECT * FROM vfa_titles WHERE title='Blue Note' AND numbers='#1';

SELECT * FROM vfa_titles, vfa_selection_titles WHERE (vfa_selection_titles.title_id = vfa_titles.title_id)
  AND (vfa_selection_titles.selection_id = 31) ORDER BY vfa_titles.order_title;