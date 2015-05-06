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
		$toResults = null;
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
			_root::redirect('results::index', array('award_id' => $awardId));
		}
		_root::redirect('results::index');
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
		$idPrix = 46;
		// Lit les lignes du fichier
		$lines = file('Alices-PrixBD-Resultats-2010.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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
		echo "<br />\n";

	}


}
