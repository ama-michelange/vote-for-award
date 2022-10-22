<?php

class module_votes extends abstract_module
{
	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_bar_context');


		// Récupère les infos de l'utilisateur connecté
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();


		$this->oUser = $oUserSession->getUser();
		$this->toValidAwards = $oUserSession->getValidAwards();
		$this->toValidBoardAwards = 0;
		$this->toValidReaderAwards = 0;
		if (count($this->toValidAwards) > 0) {
			$this->toValidBoardAwards = $oUserSession->getValidBoardAwards();
			$this->toValidReaderAwards = $oUserSession->getValidReaderAwards();
		}
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->addChild(new Bar('left'));
//		$navBar->setTitle('Voter', new NavLink('votes', 'index'), null, "myBrand");
		$navBar->setTitle('Voter', new Link('#', null), null, "myBrand");
		$this->buildMenuAwardToVote($navBar);
		return $navBar;
	}

	public function _index()
	{
		if (count($this->toValidAwards) > 0) {
			$this->doVote();
		} else {
			$oView = new _view('votes::noVote');
			$this->oLayout->add('work', $oView);
		}
	}

	private function doVote()
	{
		$oVote = $this->doVerifyRequest();

		$oView = new _view('votes::vote');
		$oView->oAward = $this->oAward;
		$oView->oVote = $oVote;
		$oView->tMessage = $oVote->getMessages();
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);

		// Ajout du javascript
		$scriptView = new _view('votes::scriptJs');
		$this->oLayout->add('script', $scriptView);
	}


	/**
	 * @return row_vote
	 */
	private function doVerifyRequest()
	{
		$oVote = null;
		if (_root::getRequest()->isPost()) {
			$oVote = $this->doVerifyPost();
		}
		if (null == $oVote) {
			// Construit le bulletin de vote complet d'un prix de l'utilisateur courant
			$oVote = $this->buildBulletinVote();
		}
		return $oVote;
	}

	/**
	 * Construit le bulletin de vote complet d'un prix de l'utilisateur courant.
	 * Le prix est automatiquement sélectionné si son ID ne fait pas parti des paramètre de la requête.
	 * @return row_vote
	 */
	private function buildBulletinVote()
	{
		// Sélectionne le prix correspondant au bulletin de vote
		$this->selectAwardToVote();
		// Recherche du bulletin de vote
		$oVote = $this->findBulletinVote($this->oUser->getId(), $this->oAward->getId());
		// Recherche les titres sélectionnés du prix pour remplir le bulletin de vote détaillé
		$toVoteItems = array();
		$toTitles = $this->oAward->findTitles();
		foreach ($toTitles as $oTitle) {
			// Recherche du vote associé au titre
			$oVoteItem = model_vote_item::getInstance()->findByVoteIdTitleId($oVote->getId(), $oTitle->getId());
			$toVoteItems[$oTitle->getId()] = $oVoteItem;
			if ($oVoteItem->isEmpty()) {
				// Pas de vote associé au titre, attribution des identifiants et du score "non noté"
				$oVoteItem->vote_id = $oVote->getId();
				$oVoteItem->title_id = $oTitle->getId();
				$oVoteItem->score = -1;
			}
			$oVoteItem->setTitle($oTitle);
		}
		$oVote->setVoteItems($toVoteItems);
		return $this->sortBulletinVote($oVote);
	}

	private function sortBulletinVote($pVote)
	{
		$oVote = $pVote;
		$toVoteItems = $oVote->getVoteItems();
		uasort($toVoteItems, array('self', 'compareVoteItem'));
		$oVote->setVoteItems($toVoteItems);
		return $oVote;
	}

	private static function compareVoteItem($a, $b)
	{
		if (($a->score == -1) && ($b->score > -1)) {
			return -1;
		}
		if (($a->score > -1) && ($b->score == -1)) {
			return 1;
		}
		return self::compareTitle($a->getTitle(), $b->getTitle());
	}

	private static function compareTitle($a, $b)
	{
		if ($a->order_title > $b->order_title) {
			return 1;
		}
		if ($a->order_title < $b->order_title) {
			return -1;
		}
		return 0;
	}

	/**
	 * Recherche du bulletin de vote d'un prix pour un utilisateur.
	 * Si non trouvé, initialise un bulletin de base.
	 *
	 * @param $pIdUser
	 * @param $pIdAward
	 * @return row_vote
	 */
	private function findBulletinVote($pIdUser, $pIdAward)
	{
		$oVote = model_vote::getInstance()->findByUserIdAwardId($pIdUser, $pIdAward);
		if ($oVote->isEmpty()) {
			// Initialisation si non trouvé
			$oVote->award_id = $this->oAward->getId();
			$oVote->user_id = $this->oUser->getId();
		}
		return $oVote;
	}

	/**
	 * Sélectionne le prix du bulletin de vote : soit avec le paramètre donné, soit parmi les prix valides de l'utilisateur en cours.
	 */
	private function selectAwardToVote()
	{
		$awardId = _root::getParam('award_id');
		if (null == $awardId) {
			if (count($this->toValidReaderAwards) > 0) {
				$t = $this->toValidReaderAwards;
				$this->oAward = $t[0];
			} elseif (count($this->toValidBoardAwards) > 0) {
				$t = $this->toValidBoardAwards;
				$this->oAward = $t[0];
			}
		} else {
			foreach ($this->toValidAwards as $oAward) {
				if ($oAward->getId() == $awardId) {
					$this->oAward = $oAward;
					break;
				}
			}
		}
		// Le paramètre 'award_id' est introuvable ou non valide
		if (null == $this->oAward) {
			_root::redirect('default::index');
		}
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuAwardToVote($pNavBar)
	{
		$bar = $pNavBar->getChild('left');
		$tItems = array();
		if ($this->toValidReaderAwards && (count($this->toValidReaderAwards) > 0)) {
			$t = $this->toValidReaderAwards;
			foreach ($t as $award) {
				if ($award->award_id != $this->oAward->award_id) {
					$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
						new NavLink('votes', 'index', array('award_id' => $award->award_id)));
				}
			}
		}
		if ($this->toValidBoardAwards && count($this->toValidBoardAwards) > 0) {
			$t = $this->toValidBoardAwards;
			$tItems[] = plugin_BsHtml::buildSeparator();
			foreach ($t as $award) {
				if ($award->award_id != $this->oAward->award_id) {
					$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
						new NavLink('votes', 'index', array('award_id' => $award->award_id)));
				}
			}
		}
		$bar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres bulletins', 'Autre bulletin', true));

		$bar = new BarButtons('right');
		$pNavBar->addChild($bar);
		$btn = new ButtonItem('Enregistrer', null, 'glyphicon-save', true, 'btnSave', true);
		$bar->addChild($btn);
	}

	/**
	 * @return row_vote
	 */
	private function doVerifyPost()
	{
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oVote = new row_vote();
			$oVote->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oVote;
		}

		// Nouveau bulletin de vote ?
		$voteId = _root::getParam('vote_id', null);
		if ($voteId == null) {
			$oVote = new row_vote();
			$oVote->award_id = _root::getParam('award_id', null);
			$oVote->user_id = _root::getParam('user_id', null);
			$oVote->created = plugin_vfa::dateTimeSgbd();
			$oVote->modified = $oVote->created;
			$oVote->number = 0;
			$oVote->average = 0.0;
		} else {
			$oVote = model_vote::getInstance()->findById($voteId);
			$oVote->modified = plugin_vfa::dateTimeSgbd();
		}

		// Extrait les paramètres de la requête et les convertit en row_vote_item
		$toVoteItems = $this->extractParamsToVoteItems();
		$oVote->setVoteItems($toVoteItems);
		// Calcule le nombre de vote valide
		$oVote = $this->calcVote($oVote);
		// Sauvegarde
		$this->saveVote($oVote);

		return null;
	}

	/**
	 * @return row_vote_item[]
	 */
	private function extractParamsToVoteItems()
	{
		// extraction
		$tParams = _root::getRequest()->getParams();
		$tItems = array();
		foreach ($tParams as $key => $value) {
			$exKey = explode("_", $key);
			if (($exKey[0] == 'no') || ($exKey[0] == 'co')) {
				$idTitle = $exKey[1];
				$idVoteItem = $exKey[2];
				if (isset($tItems[$idTitle])) {
					$tDetail = $tItems[$idTitle];
				} else {
					$tDetail = array();
				}
				$tDetail['title_id'] = $idTitle;
				$tDetail['vote_item_id'] = $idVoteItem;
				if ($exKey[0] == 'no') {
					$tDetail['score'] = $value;
				}
				if ($exKey[0] == 'co') {
					$tDetail['comment'] = $value;
				}
				$tItems[$idTitle] = $tDetail;
			}
		}

		// conversion
		$toVoteItems = array();
		foreach ($tItems as $key => $values) {
			$oVoteItem = new row_vote_item();
			$id = $values['vote_item_id'];
			if (strlen($id) > 0) {
				$oVoteItem->vote_item_id = intval($values['vote_item_id']);
			}
			$oVoteItem->title_id = intval($values['title_id']);
			$oVoteItem->score = intval($values['score']);
			$oVoteItem->comment = $values['comment'];
			$toVoteItems[] = $oVoteItem;
		}
		return $toVoteItems;
	}

	/**
	 * @param $poVote row_vote
	 * @return row_vote
	 */
	private function calcVote($poVote)
	{
		$nb = 0;
		$sum = 0.0;
		$toVoteItems = $poVote->getVoteItems();
		foreach ($toVoteItems as $oVoteItem) {
			if ($oVoteItem->score > -1) {
				$nb++;
				$sum += $oVoteItem->score;
			}
		}
		$poVote->number = $nb;
		if ($nb) {
			$poVote->average = $sum / $nb;
		} else {
			$poVote->average = 0;
		}
		return $poVote;
	}

	/**
	 * @param $poVote row_vote
	 * @return boolean Vrai si une des notes a été créée ou modifiée
	 */
	private function saveVote($poVote)
	{
		$modify = false;
		$poVote->save();
		$toVoteItems = $poVote->getVoteItems();
		foreach ($toVoteItems as $oVoteItem) {
			$oVoteItem->vote_id = $poVote->vote_id;
			if ($oVoteItem->vote_item_id) {
				$last = model_vote_item::getInstance()->findById($oVoteItem->getId());
				if (($oVoteItem->score != $last->score) || (strcmp($oVoteItem->comment, $last->comment) != 0)) {
					$oVoteItem->modified = plugin_vfa::dateTimeSgbd();
					$oVoteItem->update();
					$modify = true;
				}
			} else {
				$oVoteItem->created = plugin_vfa::dateTimeSgbd();
				$oVoteItem->modified = $oVoteItem->created;
				$oVoteItem->save();
				$modify = true;
			}
		}
		return $modify;
	}

	public function _recup_file_uservote()
	{
		$go = _root::getParam('go');
		if ($go == "__go_go__") {
			$this->recuperer_file_uservote('Alices-StmGre-votes-2016-ama.csv', 41, 50);
		} else {
			$this->voir_file_uservote('Alices-StmGre-votes-2016-ama.csv', 41, 50);
		}
	}

	private function recuperer_file_uservote($pFilename, $pIdGroup, $pIdAward)
	{
		$tLineOne = null;
		// Identifiant du prix
		$idPrix = $pIdAward;
		// Lit les lignes du fichier
		$lines = file($pFilename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		// Converti les lignes de chaines en tableau
		$i = 0;
		$tTitleIds = null;
		$tBulletins = array();
		$tUsers = array();
		foreach ($lines as $line_num => $line) {
			if ($i == 0) {
				$tLineOne = explode(';', $line);
				$tTitleIds = array_slice($tLineOne, 4);
			} else {
				$lineScores = explode(';', $line);
				$tBulletins[] = array_slice($lineScores, 4);
				$tUsers[] = array_slice($lineScores, 0, 4);
			}
			$i++;
		}

		// Parcours tous les utilisateurs et bulletins
		for ($iLine = 0; $iLine < count($tUsers); $iLine++) {
			$lineUser = $tUsers[$iLine];
			$oUser = $this->findOrCreateUser($lineUser, $pIdGroup, $pIdAward);
			$oVote = $this->findOrBuildVote($oUser, $pIdAward);
			$oVote = $this->fillVote($oVote, $tTitleIds, $tBulletins[$iLine]);
			// Sauvegarde
			if ($this->saveVote($oVote)) {
				echo "<span style='padding-left: 20px;'>MODIFY</span>";
			}
			echo "<br />";
		}


		$oAward = model_award::getInstance()->findById($pIdAward);
		$this->calcAwardStats($oAward);

		echo "<br />TERMINER\n";
	}

	/**
	 * @param $poAward row_award
	 * @return row_vote_stat[]
	 */
	private function calcAwardStats($poAward)
	{
		echo "Statistiques<br />";

		// Nombre de bulletin de votes
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_BALLOT;
		$oStat->num_int = model_vote::getInstance()->countAllBallots($poAward->getId());
		$nb = $oStat->num_int;
		echo "Nombre de bulletins = $nb<br />";
		model_vote_stat::getInstance()->saveStat($oStat);

		// Nombre de bulletin de votes valides
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_BALLOT_VALID;
		$oStat->num_int = model_vote::getInstance()->countValidBallots($poAward->getId(), $poAward->type, $this->oAward->getCategory());
		$nb = $oStat->num_int;
		echo "Nombre de bulletins valides = $nb<br />";
		model_vote_stat::getInstance()->saveStat($oStat);

		// Nombre de lecteurs inscrits au prix
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_REGISTRED;
		$oStat->num_int = model_award::getInstance()->countUser($poAward->getId());
		$nb = $oStat->num_int;
		echo "Nombre d'utilisateurs = $nb<br />";
		model_vote_stat::getInstance()->saveStat($oStat);

		// Nombre de groupes inscrits au prix
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_GROUP;
		$oStat->num_int = model_award::getInstance()->countGroup($poAward->getId());
		$nb = $oStat->num_int;
		echo "Nombre de groupes = $nb<br />";
		model_vote_stat::getInstance()->saveStat($oStat);
	}

	/**
	 * @param row_vote $poVote
	 * @param int[] $ptTitleIds
	 * @param int[] $ptLineScores
	 */
	private function fillVote($poVote, $ptTitleIds, $ptLineScores)
	{
		$lineNotes = array();
		for ($i = 0; $i < count($ptLineScores); $i++) {
			$len = strlen(trim($ptLineScores[$i]));
			// Vérifie qu'une valeur est présente
			if ($len > 0) {
				$lineNotes[] = $ptLineScores[$i];
			} else {
				// Force une valeur négative comme dans le formulaire
				$lineNotes[] = -1;
			}
			if ($i == 0) {
				echo "<span style='padding-left: 20px;'>Notes : </span>";
			} else {
				echo ", ";
			}
			echo "$lineNotes[$i] ";
		}

		echo "<br />";
		echo "<span style='padding-left: 20px;'></span>";
		$toVoteItemsSrc = $poVote->getVoteItems();
		$toVoteItemsDst = array();

		for ($i = 0; $i < count($ptTitleIds); $i++) {
			if ($i > 0) {
				echo ", ";
			}
			echo "$ptTitleIds[$i] = $lineNotes[$i]";
			$oVoteItem = $this->searchVoteItem($toVoteItemsSrc, $ptTitleIds[$i]);
			if (null == $oVoteItem) {
				$oVoteItem = new row_vote_item();
				$oVoteItem->title_id = $ptTitleIds[$i];
			}
			$oVoteItem->score = $lineNotes[$i];
			$toVoteItemsDst[] = $oVoteItem;
		}
		$poVote->setVoteItems($toVoteItemsDst);
		// Calcule le nombre de vote valide
		$this->calcVote($poVote);

		$nb = $poVote->number;
		$ave = $poVote->average;
		echo "<span style='padding-left: 20px;'>Nombre : $nb</span>";
		echo "<span style='padding-left: 20px;'>Moyenne : $ave</span>";

		echo "<br />";
		return $poVote;
	}

	/**
	 * @param row_vote_item[] $ptoVoteItems
	 * @param $pTitleId
	 * @return row_vote_item or null
	 */
	private function searchVoteItem($ptoVoteItems, $pTitleId)
	{
		if (null != $ptoVoteItems) {
			foreach ($ptoVoteItems as $oVoteItem) {
				if ($oVoteItem->title_id == $pTitleId) {
					return $oVoteItem;
				}
			}
		}
		return null;
	}

	/**
	 * @param array $pLineUser
	 * @param int $pIdGroup
	 * @param int $pIdAward
	 * @return row_user
	 */
	private function findOrCreateUser($pLineUser, $pIdGroup, $pIdAward)
	{
		$oUser = model_user::getInstance()->findByLogin($pLineUser[2]);
		if ($oUser->isEmpty()) {
			$oUser = new row_user();
			$oUser->created_date = plugin_vfa::dateTimeSgbd();
			$oUser->login = $pLineUser[2];
			$oUser->last_name = $pLineUser[0];
			$oUser->first_name = $pLineUser[1];
			$oUser->email = $pLineUser[3];

			$oGroup = model_group::getInstance()->findById($pIdGroup);
			$tUserGroups = array();
			$tUserGroups[] = $oGroup->group_id;
			$tUserRoles = array();
			$tUserRoles[] = $oGroup->role_id_default;
			$tUserAwards = array();
			$tUserAwards[] = $pIdAward;

			$oUser->save();
			model_user::getInstance()->saveUserRoles($oUser->user_id, $tUserRoles);
			model_user::getInstance()->saveUserGroups($oUser->user_id, $tUserGroups);
			model_user::getInstance()->saveUserAwards($oUser->user_id, $tUserAwards);

			$aa = $oUser->toString();
			echo "CREATE : $aa<br />";

		} else {
			$aa = $oUser->toString();
			echo "Found : $aa<br />";
		}
//		echo "<br />";
		return $oUser;
	}

	/**
	 * @param row_user $poUser
	 * @param int $pIdAward
	 * @return row_vote
	 */
	private function findOrBuildVote($poUser, $pIdAward)
	{
		$oVote = model_vote::getInstance()->findByUserIdAwardId($poUser->getId(), $pIdAward);
		if ($oVote->isEmpty()) {
			echo "<span style='padding-left: 20px;'>Pas de bulletin dispo</span><br/>";
			$oVote = new row_vote();
			$oVote->award_id = $pIdAward;
			$oVote->user_id = $poUser->getId();
			$oVote->created = plugin_vfa::dateTimeSgbd();
			$oVote->modified = $oVote->created;
			$oVote->number = 0;
			$oVote->average = 0.0;
		} else {
			$oVote->modified = plugin_vfa::dateTimeSgbd();
			$oVote->setVoteItems($oVote->findVoteItems());
		}
		return $oVote;
	}

	private function voir_file_uservote($pFilename, $pIdGroup, $pIdAward)
	{
		$tLineOne = null;
		// Identifiant du prix
		$idPrix = $pIdAward;
		// Lit les lignes du fichier
		$lines = file($pFilename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		// Converti les lignes de chaines en tableau
		$i = 0;
		$tTitleIds = null;
		$tBulletins = array();
		$tUsers = array();
		foreach ($lines as $line_num => $line) {
			if ($i == 0) {
				$tLineOne = explode(';', $line);
				$tTitleIds = array_slice($tLineOne, 4);
			} else {
				$lineScores = explode(';', $line);
				$tBulletins[] = array_slice($lineScores, 4);
				$tUsers[] = array_slice($lineScores, 0, 4);
			}
			$i++;
		}

		$oPrix = model_award::getInstance()->findById($pIdAward);
		$prixString = $oPrix->toString();
		echo "<h1>$prixString</h1>";

		$oGroupe = model_group::getInstance()->findById($pIdGroup);
		$groupeString = $oGroupe->toString();

		echo "<table class='table table-striped table-hover'>";
		echo "<tr>";
		echo "<td>ID</td>";
		echo "<td>Titre</td>";
		echo "</tr>";
		for ($iLine = 0; $iLine < count($tTitleIds); $iLine++) {
			echo "<tr>";
			echo "<td>$tTitleIds[$iLine]</td>";
			$oTitre = model_title::getInstance()->findById($tTitleIds[$iLine]);
			echo "<td>$oTitre->title</td>";
			echo "</tr>";
		}
		echo "</table>";

		echo "<table class='table table-striped table-hover'>";
		echo "<tr>";
		echo "<th></th>";
		echo "<th colspan='17'>Bulletins des utilisateur du groupe $groupeString</th>";
		echo "</tr>";

		echo "<tr>";
		echo "<th></th>";
		echo "<th>Nom</th>";
		echo "<th>Prenom</th>";
		echo "<th>Login</th>";
		echo "<th>Email</th>";
		for ($iLine = 0; $iLine < count($tTitleIds); $iLine++) {
			echo "<th>$tTitleIds[$iLine]</th>";
		}
		echo "</tr>";

		for ($iLine = 0; $iLine < count($tUsers); $iLine++) {
			echo "<tr>";
			echo "<td>$iLine</td>";
			$lineUser = $tUsers[$iLine];
			for ($i = 0; $i < count($lineUser); $i++) {
				if ($i == 2) {
					$oUser = model_user::getInstance()->findByLogin($lineUser[$i]);
					if ($oUser->isEmpty()) {
						echo "<td>$lineUser[$i]</td>";
					} else {
						echo "<td class='success'>$lineUser[$i]</td>";
					}
				} else {
					echo "<td>$lineUser[$i]</td>";
				}
			}
			$lineScores = $tBulletins[$iLine];
			for ($i = 0; $i < count($lineScores); $i++) {
				echo "<td>$lineScores[$i]</td>";
			}
			echo "</tr>";
		}
		echo "</table>";

		$params = array();
		$params['go'] = "__go_go__";
		$aaa = _root::getLinkString("votes::recup_file_uservote", $params);

		echo "<a class='btn btn-block btn-warning' href='$aaa'>Go</a>";
	}

}

