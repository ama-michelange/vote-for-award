<?php

class module_results extends abstract_module
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
		$this->oReaderGroup = $oUserSession->getReaderGroup();
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
		$navBar->addChild(new Bar('right'));
		if (_root::getAction() == 'awardInProgress') {
			try {
				$navBar->setTitle('Sélection ' . $this->oAwardInProgress->year, new NavLink('results', 'awardInProgress'));
				$this->buildMenuAwardInProgress($navBar->getChild('right'));
			} catch (Exception $e) {
				// $this->oAwardInProgress n'existe pas ! Rien à faire
			}
		} else if (_root::getAction() == 'live') {
			$navBar->setTitle('Classement intermédiaire', new NavLink('results', 'live'));
			$this->buildMenuAwardLiveResults($navBar->getChild('left'));
		} else if (_root::getAction() == 'liveGroup') {
			$navBar->setTitle('Classement intermédiaire Groupe', new NavLink('results', 'liveGroup'));
			$this->buildMenuGroupLiveGroupResults($navBar->getChild('left'));
			$this->buildMenuAwardLiveGroupResults($navBar->getChild('left'));
		} else if (_root::getAction() == 'last') {
			$navBar->setTitle('Résultat du dernier prix', new NavLink('results', 'last'));
		} else if (_root::getAction() == 'archives') {
			$navBar->setTitle('Archives', new NavLink('results', 'archives'));
			$this->buildMenuAwardArchive($navBar);
		} else if (_root::getAction() == 'exportVotes') {
			$this->buildMenuAwardExport($navBar);
		}
		return $navBar;
	}

	/**
	 * @param Bar $pBar
	 */
	private function buildMenuAwardInProgress($pBar)
	{
		try {
			/* @var $oUserSession row_user_session */
			$oUserSession = _root::getAuth()->getUserSession();
			if ($oUserSession->isValidAward($this->oAwardInProgress->getId())) {
				$tParams = array('award_id' => $this->oAwardInProgress->getId());
				$pBar->addChild(plugin_BsHtml::buildButtonItem('Voter', new NavLink('votes', 'index', $tParams), 'glyphicon-leaf'));
			}
		} catch (Exception $e) {
			// $this->oAwardInProgress n'existe pas !
			// Rien à faire
		}
	}

	/**
	 * @param Bar $pBar
	 */
	private function buildMenuAwardLiveResults($pBar)
	{
		$tItems = array();
		$tAwards = model_award::getInstance()->findAllInProgress();
		foreach ($tAwards as $award) {
			if (plugin_vfa::TYPE_AWARD_BOARD == $award->type) {
				$tItems[] = plugin_BsHtml::buildSeparator();
			}
			$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
				new NavLink('results', 'live', array('award_id' => $award->award_id)));
		}
		$pBar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres classements', 'Autre classement', true));
	}

	/**
	 * @param Bar $pBar
	 */
	private function buildMenuAwardLiveGroupResults($pBar)
	{
		$groupId = _root::getParam('group_id');
		if ($groupId) {
			$tItems = array();
			$tAwards = model_award::getInstance()->findAllByGroupId($groupId);
			foreach ($tAwards as $award) {
				if (plugin_vfa::TYPE_AWARD_READER == $award->type) {
					$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
						new NavLink('results', 'liveGroup', array('group_id' => $groupId, 'award_id' => $award->award_id)));
				}
			}
			if (count($tItems) > 1) {
				$pBar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres classements', 'Autre classement', true));
			}
		}
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuGroupLiveGroupResults($pBar)
	{
		$oUserSession = _root::getAuth()->getUserSession();
		if ($oUserSession->isInRole(plugin_vfa::ROLE_OWNER) || $oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_BOOKSELLER)) {
			$tItems = array();
			$tGroups = model_group::getInstance()->findAll();
			foreach ($tGroups as $group) {
				if ($group->getId() != $this->oReaderGroup->getId()) {
					$tItems[] = plugin_BsHtml::buildMenuItem($group->toString(),
						new NavLink('results', 'liveGroup', array('group_id' => $group->group_id)));
				}
			}
			$pBar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres groupes', null, true, true));
		}
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuAwardArchive($pNavBar)
	{
		$tItems = array();
		$tAwards = model_award::getInstance()->findAllCompleted(true);
		foreach ($tAwards as $award) {
			$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
				new NavLink('results', 'archives', array('award_id' => $award->award_id)));
		}
		$pNavBar->getChild('left')->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres prix', 'Autre prix', true));
		$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Export',
			new NavLink('results', 'exportVotes', array('award_id' => $this->currentIdAward))));
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuAwardExport($pNavBar)
	{
		$pNavBar->setTitle('Export des votes', new NavLink('results', 'exportVotes', array('award_id' => _root::getParam('award_id'))));
		$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Archive',
			new NavLink('results', 'archives', array('award_id' => _root::getParam('award_id')))));
	}

	public function _index()
	{
	}

	public function _awardInProgress()
	{
		$oAward = $this->selectAwardInProgress();
		$toTitles = null;
		$this->oAwardInProgress = null;
		if (null != $oAward) {
			$toTitles = $oAward->findTitles();
			$this->oAwardInProgress = $oAward;
		}

		$oView = new _view('results::award_in_progress');
		$oView->oAward = $oAward;
		$oView->toTitles = $toTitles;
		$this->oLayout->add('work', $oView);
	}


	public function _live()
	{
		$toResults = null;
		$oAward = $this->selectAwardInProgress(_root::getParam('award_id'));
		if (null != $oAward) {
			$toResults = $this->calcAwardResults($oAward);
		}
		$oView = new _view('results::award_live');
		$oView->oAward = $oAward;
		$oView->toResults = $toResults;
		$this->oLayout->add('work', $oView);
	}

	public function _liveGroup()
	{
		$toResults = null;
		$oAward = $this->selectAwardInProgress(_root::getParam('award_id'));
		$oGroup = $this->selectGroup(_root::getParam('group_id'));
		if (null == $oGroup || $oGroup->isEmpty()) {
			$oAward = null;
		}
		if (null != $oAward) {
			$toResults = $this->calcAwardGroupResults($oAward, $oGroup);
		}
		$oView = new _view('results::award_live');
		$oView->oAward = $oAward;
		$oView->oGroup = $oGroup;
		$oView->toResults = $toResults;
		$this->oLayout->add('work', $oView);
	}

	public function _last()
	{
		$toResults = null;
		$oAward = $this->selectLastAwardCompleted();
		if (null != $oAward) {
			$toResults = $this->calcAwardResults($oAward);
			$toStats = model_vote_stat::getInstance()->findAllByIdAward($oAward->getId());
		}
		$oView = new _view('results::award_archive');
		$oView->oAward = $oAward;
		$oView->toResults = $toResults;
		$oView->toStats = $toStats;
		$this->oLayout->add('work', $oView);
	}

	public function _archives()
	{
		$toResults = null;
		$oAward = $this->selectArchiveAwardCompleted(_root::getParam('award_id'));
		if (null != $oAward) {
			$toResults = $this->calcAwardResults($oAward);
			$toStats = model_vote_stat::getInstance()->findAllByIdAward($oAward->getId());
		}
		$oView = new _view('results::award_archive');
		$oView->oAward = $oAward;
		$oView->toResults = $toResults;
		$oView->toStats = $toStats;
		$this->oLayout->add('work', $oView);

		// Memo du prix visualisé pour la construction du menu
		$this->currentIdAward = $oAward->getId();
	}

	public function _exportVotes()
	{
		$oAward = $this->selectArchiveAwardCompleted(_root::getParam('award_id'));
		if (null != $oAward) {
			$toTitles = $oAward->findTitles();
			$toVotes = model_vote::getInstance()->findAllByAwardIdOrderUser($oAward->getId());
			if (count($toVotes) == 0) {
				$toVotes = model_vote::getInstance()->findAllByAwardId($oAward->getId());
			}
		}
		$oView = new _view('results::award_export_votes');
		$oView->oAward = $oAward;
		$oView->toTitles = $toTitles;
		$oView->toVotes = $toVotes;
		$this->oLayout->add('work', $oView);
	}

	public function _recalcVotes()
	{
		$oAward = $this->selectArchiveAwardCompleted('43');
		if (null != $oAward) {
			$toTitles = $oAward->findTitles();
			$toVotes = model_vote::getInstance()->findAllByAwardIdOrderUser($oAward->getId());
			if (count($toVotes) == 0) {
				$toVotes = model_vote::getInstance()->findAllByAwardId($oAward->getId());
			}
		}

		foreach ($toVotes as $vote) {
			// Recherche les titres sélectionnés du prix pour remplir le bulletin de vote détaillé
			$toVoteItems = array();
			foreach ($toTitles as $oTitle) {
				// Recherche du vote associé au titre
				$oVoteItem = model_vote_item::getInstance()->findByVoteIdTitleId($vote->getId(), $oTitle->getId());
				$toVoteItems[$oTitle->getId()] = $oVoteItem;
				if ($oVoteItem->isEmpty()) {
					$oVoteItem->vote_id = $vote->getId();
					$oVoteItem->title_id = $oTitle->getId();
				}
				$oVoteItem->setTitle($oTitle);
			}
			$vote->setVoteItems($toVoteItems);
			$vote = $this->calcVote($vote);
			$this->saveVote($vote);
		}

		echo("TERMINE");
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
	 * @return void
	 */
	private function saveVote($poVote)
	{
		$poVote->save();
		$toVoteItems = $poVote->getVoteItems();
		foreach ($toVoteItems as $oVoteItem) {
			$oVoteItem->vote_id = $poVote->vote_id;
			if ($oVoteItem->vote_item_id) {
				$last = model_vote_item::getInstance()->findById($oVoteItem->getId());
				if (($oVoteItem->score != $last->score) || (strcmp($oVoteItem->comment, $last->comment) != 0)) {
					$oVoteItem->modified = plugin_vfa::dateTimeSgbd();
					$oVoteItem->update();
				}
			} else {
				$oVoteItem->created = plugin_vfa::dateTimeSgbd();
				$oVoteItem->modified = $oVoteItem->created;
				$oVoteItem->save();
			}
		}
	}

	/**
	 * Renvoie le groupe : par défaut le groupe de l'utilisateur sinon le groupe demandé.
	 * @param null $pGroupId string Si présent renvoie le groupe demandé
	 * @return null|row_group Le groupe de l'utilisateur ou le groupe demandé
	 */
	private function selectGroup($pGroupId = null)
	{
		$oGroup = $this->oReaderGroup;
		if (null != $pGroupId) {
			$oGroup = model_group::getInstance()->findById($pGroupId);
			if ((null != $oGroup) && ($oGroup->isEmpty())) {
				$oGroup = $this->oReaderGroup;
			}
		}
		return $oGroup;
	}

	/**
	 * Renvoie le prix en cours : par défaut le prix public sinon le prix demandé.
	 * @param null $pAwardId string Si présent renvoie le prix demandé si en cours
	 * @return null|row_award Le prix en cours ou null si non trouvé ou si non en cours
	 */
	public static function selectAwardInProgress($pAwardId = null)
	{
		$oAward = null;
		if (null != $pAwardId) {
			$oAward = model_award::getInstance()->findById($pAwardId);
			if ((null != $oAward) && ($oAward->isEmpty())) {
				$oAward = null;
			}
			if (null != $oAward) {
				$endDate = plugin_vfa::toDateFromSgbd($oAward->end_date);
				$today = plugin_vfa::today();
				if (false == plugin_vfa::beforeDate($today, $endDate)) {
					$oAward = null;
				}
			}
		}
		if (null == $oAward) {
			$tAwards = model_award::getInstance()->findAllInProgress();
			if ((null != $tAwards) && (count($tAwards) > 0)) {
				$oAward = $tAwards[0];
			}
		}
		return $oAward;
	}


	/**
	 * Renvoie le dernier prix public terminé.
	 * @return null|row_award
	 */
	public static function selectLastAwardCompleted()
	{
		$oAward = null;
		$tAwards = model_award::getInstance()->findAllCompleted(true);
		if ((null != $tAwards) && (count($tAwards) > 0)) {
			$oAward = $tAwards[0];
		}
		return $oAward;
	}

	/**
	 * Renvoie le prix terminé : par défaut l'avant dernier prix public terminé sinon le prix demandé.
	 * @param null $pAwardId string Si présent renvoie le prix demandé si terminé
	 * @return null|row_award Le prix terminé ou null si non trouvé ou si non terminé
	 */
	public static function selectArchiveAwardCompleted($pAwardId = null)
	{
		$oAward = null;
		if (null != $pAwardId) {
			$oAward = model_award::getInstance()->findById($pAwardId);
			if ((null != $oAward) && ($oAward->isEmpty())) {
				$oAward = null;
			}
			if (null != $oAward) {
				$endDate = plugin_vfa::toDateTime(plugin_vfa::toDateFromSgbd($oAward->end_date));
				$now = plugin_vfa::now();
				if (false == plugin_vfa::afterDateTime($now, $endDate)) {
					$oAward = null;
				}
			}
		}
		if (null == $oAward) {
			$tAwards = model_award::getInstance()->findAllCompleted(true);
			if ((null != $tAwards) && (count($tAwards) > 0)) {
				$index = 1;
				while (($index > 0) && (null == $oAward)) {
					if ($index < count($tAwards)) {
						$oAward = $tAwards[$index];
					}
					$index--;
				}
			}
		}
		return $oAward;
	}

	public function _calc()
	{
		$awardId = _root::getParam('award_id');
		if (null != $awardId) {
			$oAward = model_award::getInstance()->findById($awardId);
			if ((null != $oAward) && (false == $oAward->isEmpty())) {
				calcAwardResults($oAward);
			}
			_root::redirect('results::archives', array('award_id' => $awardId));
		}
		_root::redirect('results::archives');
	}

	/**
	 * @param $poAward row_award
	 * @return row_vote_result[]
	 */
	private function calcAwardResults($poAward)
	{
		$calc = true;

		// Vérifie si les résultats n'existe pas déjà
		$toResults = model_vote_result::getInstance()->findAllByIdAward($poAward->getId());
		if ((null != $toResults) && (count($toResults) > 0)) {
			// Vérifie s'il y a un vote plus récent que le dernier calcul des résultats du prix
			$oLastVote = model_vote::getInstance()->findLastModifiedByAwardId($poAward->getId());
			if (true == $oLastVote->isEmpty()) {
				$calc = false;
			} else {
				$LastVoteDatetime = plugin_vfa::toDateTimeFromSgbd($oLastVote->modified);
				$modified = plugin_vfa::toDateTimeFromSgbd($toResults[0]->modified);
				if (plugin_vfa::beforeDateTime($LastVoteDatetime, $modified)) {
					$calc = false;
				}
			}
		}
		// Calcule les résulats des votes du prix
		if (true == $calc) {
			$toCalcResults = model_vote_result::getInstance()->calcResultVotes($poAward);
			// Sauvegarde les résultats des votes
			$toResults = $this->mergeSaveResults($poAward, $toResults, $toCalcResults);
		}

		// Vérifie s'il faut calculer les stats
		$endDate = plugin_vfa::toDateTime(plugin_vfa::toDateFromSgbd($poAward->end_date));
		$now = plugin_vfa::now();
		// Si le prix est termminé
		if (true == plugin_vfa::afterDateTime($now, $endDate)) {
			// Recherche une stat du prix
			$oVoteStat = model_vote_stat::getInstance()->findByIdAwardCode($poAward->getId(), plugin_vfa::CODE_NB_REGISTRED);
			// Si pas de stat ou si le calcul vient d'être effectué
			if ((true == $oVoteStat->isEmpty()) || (true == $calc)) {
				// Calcul des stats
				$this->calcAwardStats($poAward);
			}
		}

		return $toResults;
	}

	/**
	 * @param $poAward row_award
	 * @param $poGroup row_group
	 * @return row_vote_result[]
	 */
	private function calcAwardGroupResults($poAward, $poGroup)
	{
		// Calcule les résulats des votes du prix
		$toResults = model_vote_result::getInstance()->calcResultGroupVotes($poAward, $poGroup);
		// Tri par moyenne descendante
		usort($toResults, array("row_vote_result", "cmpAverageDesc"));
		return $toResults;
	}

	/**
	 * @param $poAward row_award
	 * @return row_vote_stat[]
	 */
	private function calcAwardStats($poAward)
	{
		// Nombre de bulletin de votes
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_BALLOT;
		$oStat->num_int = model_vote::getInstance()->countAllBallots($poAward->getId());
		model_vote_stat::getInstance()->saveStat($oStat);

		// Nombre de bulletin de votes valides
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_BALLOT_VALID;
		$oStat->num_int = model_vote::getInstance()->countValidBallots($poAward->getId(), $poAward->type);
		model_vote_stat::getInstance()->saveStat($oStat);

		// Nombre de lecteurs inscrits au prix
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_REGISTRED;
		$oStat->num_int = model_award::getInstance()->countUser($poAward->getId());
		model_vote_stat::getInstance()->saveStat($oStat);

		// Nombre de groupes inscrits au prix
		$oStat = new row_vote_stat();
		$oStat->award_id = $poAward->getId();
		$oStat->code = plugin_vfa::CODE_NB_GROUP;
		$oStat->num_int = model_award::getInstance()->countGroup($poAward->getId());
		model_vote_stat::getInstance()->saveStat($oStat);
	}

	/**
	 * Sauvegarde les résultats des votes
	 * @param $poAward row_award
	 * @param $poOri row_vote_result[]
	 * @param $poNew row_vote_result[]
	 * @return row_vote_result[]
	 */
	private function mergeSaveResults($poAward, $poOri, $poNew)
	{
		$insert = true;
		if ((null != $poOri) && (count($poOri) > 0)) {
			if (count($poOri) == count($poNew)) {
				// Taille identique : mise à jour
				$insert = false;
				foreach ($poOri as $oResult) {
					$oNew = $this->find($oResult->title_id, $poNew);
					$oResult->score = $oNew->score;
					$oResult->number = $oNew->number;
					$oResult->average = $oNew->average;
					$oResult->modified = $oNew->modified;
					$oResult->update();
				}
			} else {
				// Tailles différentes, on supprime tout et on resauvegarde pour éviter les emmerdes
				foreach ($poOri as $oResult) {
					$oResult->delete();
				}
			}
		}
		// Sauvegarde si necessaire (insert)
		if (true == $insert) {
			foreach ($poNew as $oResult) {
				$oResult->save();
			}
		}
		// Relecture pour l'ordre
		$ret = model_vote_result::getInstance()->findAllByIdAward($poAward->getId());
		return $ret;
	}

	/**
	 * @param $pIdTitle int
	 * @param $ptoResults row_vote_result[]
	 * @return row_vote_result || null
	 */
	private function find($pIdTitle, $ptoResults)
	{
		$ret = null;
		$i = 0;
		while ((null == $ret) && ($i < count($ptoResults))) {
			$idTitle = $ptoResults[$i]->title_id;
			if ($idTitle == $pIdTitle) {
				$ret = $ptoResults[$i];
			}
			$i++;
		}
		return $ret;
	}

	public function _recup_file()
	{
		$tTitleIds = null;
		// Identifiant du prix
		$idPrix = 43;
		// Lit les lignes du fichier
		$lines = file('Alices-PrixBD-Resultats-2015-ama.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		// Converti les lignes de chaines en tableau
		$i = 0;
		$tBulletins = array();
		foreach ($lines as $line_num => $line) {
			if ($i == 0) {
				$tTitleIds = explode(';', $line);
			} else {
				$lineScores = explode(';', $line);
				$tBulletins[] = $lineScores;
			}
			$i++;
		}
		// Recherche le prochain identifiant d'utilisateur (id négatif car non inscrit)
		$minUserId = model_vote::getInstance()->minUserId();
		$nextUserId = -1;
		if ($minUserId <= $nextUserId) {
			$nextUserId = $minUserId - 1;
		}

		// Parcours tous les bulletins
		for ($iLine = 0; $iLine < count($tBulletins); $iLine++) {
			$lineScores = $tBulletins[$iLine];
			// Calcule les éléments du bulettin : nb vote et moyenne
			$cpt = 0;
			$sum = 0;
			for ($i = 0; $i < count($lineScores); $i++) {
				$len = strlen(trim($lineScores[$i]));
				// Vérifie qu'une valeur est présente
				if ($len > 0) {
					$cpt++;
					$sum += $lineScores[$i];
				} else {
					// Force une valeur négative comme dans le formulaire
					$lineScores[$i] = -1;
				}
			}
			$average = 0;
			if ($cpt > 0) {
				$average = $sum / $cpt;
			}
			// Création du vote pour l'utilisateur
			$oVote = new row_vote();
			$oVote->award_id = $idPrix;
			$oVote->user_id = $nextUserId;
			$oVote->number = $cpt;
			$oVote->average = $average;
			$oVote->created = plugin_vfa::dateTimeSgbd();
			$oVote->modified = $oVote->created;
			$oVote->save();

			for ($i = 0; $i < count($lineScores); $i++) {
				$note = $lineScores[$i];
				// Vérifie la note
				if (($note < -1) || ($note > 5)) {
					$note = -1;
				}
				// Création du vote pour le titre
				$oVoteItem = new row_vote_item();
				$oVoteItem->vote_id = $oVote->getId();
				$oVoteItem->title_id = $tTitleIds[$i];
				$oVoteItem->score = $note;
				$oVoteItem->created = plugin_vfa::dateTimeSgbd();
				$oVoteItem->modified = $oVoteItem->created;
				$oVoteItem->save();
			}
			// Utilisateur suivant
			$nextUserId--;
		}
		echo "<br />TERMINER\n";

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


		// Parcours tous les utilisateurs
		for ($iLine = 0; $iLine < count($tUsers); $iLine++) {
			$lineUser = $tUsers[$iLine];
			$oUser = $this->findOrCreateUser($lineUser, $pIdGroup, $pIdAward);

		}


//
//		// Parcours tous les bulletins
//		for ($iLine = 0; $iLine < count($tBulletins); $iLine++) {
//			$lineScores = $tBulletins[$iLine];
//			// Calcule les éléments du bulettin : nb vote et moyenne
//			$cpt = 0;
//			$sum = 0;
//			for ($i = 0; $i < count($lineScores); $i++) {
//				$len = strlen(trim($lineScores[$i]));
//				// Vérifie qu'une valeur est présente
//				if ($len > 0) {
//					$cpt++;
//					$sum += $lineScores[$i];
//				} else {
//					// Force une valeur négative comme dans le formulaire
//					$lineScores[$i] = -1;
//				}
//			}
//			$average = 0;
//			if ($cpt > 0) {
//				$average = $sum / $cpt;
//			}
//			// Création du vote pour l'utilisateur
//			$oVote = new row_vote();
//			$oVote->award_id = $idPrix;
//			$oVote->user_id = $nextUserId;
//			$oVote->number = $cpt;
//			$oVote->average = $average;
//			$oVote->created = plugin_vfa::dateTimeSgbd();
//			$oVote->modified = $oVote->created;
//			$oVote->save();
//
//			for ($i = 0; $i < count($lineScores); $i++) {
//				$note = $lineScores[$i];
//				// Vérifie la note
//				if (($note < -1) || ($note > 5)) {
//					$note = -1;
//				}
//				// Création du vote pour le titre
//				$oVoteItem = new row_vote_item();
//				$oVoteItem->vote_id = $oVote->getId();
//				$oVoteItem->title_id = $tTitleIds[$i];
//				$oVoteItem->score = $note;
//				$oVoteItem->created = plugin_vfa::dateTimeSgbd();
//				$oVoteItem->modified = $oVoteItem->created;
//				$oVoteItem->save();
//			}
//			// Utilisateur suivant
//			$nextUserId--;
//		}
		echo "<br />TERMINER\n";

	}

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
		echo "<br />";
		return $oUser;
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
		$aaa = _root::getLinkString("results::recup_file_uservote", $params);

		echo "<a class='btn btn-block btn-warning' href='$aaa'>Go</a>";
	}
}
