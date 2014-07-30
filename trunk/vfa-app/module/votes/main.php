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
		$navBar->setTitle('Votes', new NavLink('votes', 'index'));
		$navBar->addChild(new BarButtons('left'));

		return $navBar;
	}

	public function _index()
	{
		if (count($this->toValidAwards) > 0) {
			$this->doVote();
		} else {

		}
	}

	private function doVote()
	{
		$oVote = $this->doVerifyAndBuild();

		$oView = new _view('votes::vote');
		$oView->oAward = $this->oAward;
		$oView->oVote = $oVote;

		$this->oLayout->add('work', $oView);
	}


	/**
	 * @return row_vote
	 */
	private function doVerifyAndBuild()
	{
		// Sélectionne le prix du bulletin de vote
		$this->selectAwardToVote();
		// Recherche du bulletin de vote
		$oVote = model_vote::getInstance()->findByUserIdAwardId($this->oUser->getId(), $this->oAward->getId());
		if ($oVote->isEmpty()) {
			// Création si non trouvé
			$this->saveNewVote($oVote);
		}
		// Recherche des titres sélectionnés pour le prix pour remplir le bulletin de vote détaillé
		$toVoteItems = array();
		$toTitles = $this->oAward->findTitles();
		foreach ($toTitles as $oTitle) {
			// Recherche du vote associé au titre
			$oVoteItem = model_vote_item::getInstance()->findByVoteIdTitleId($oVote->getId(), $oTitle->getId());
			$toVoteItems[$oTitle->getId()] = $oVoteItem;
			if ($oVoteItem->isEmpty()) {
				$oVoteItem->vote_id = $oVote->getId();
				$oVoteItem->title_id = $oTitle->getId();
			}
			$oVoteItem->setTitle($oTitle);
		}
		$oVote->setVoteItems($toVoteItems);

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
	}

	/**
	 * @param row_vote $poVote
	 * @return row_vote
	 */
	private function saveNewVote($poVote)
	{
		$poVote->award_id = $this->oAward->getId();
		$poVote->user_id = $this->oUser->getId();
		$poVote->created = plugin_vfa::dateTimeSgbd();
		$poVote->number = 0;
		$poVote->average = 0.0;
		$poVote->save();
	}

	/**
	 * @return row_vote
	 */
	private function save()
	{
		if (!_root::getRequest()->isPost()) {
			return null;
		}

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
			$oVote = $this->buildNewVote();
		} else {
			$oVote = $this->buildVote($voteId);
		}

		$oVote->modified = plugin_vfa::dateTimeSgbd();

//		// Copie la saisie dans un enregistrement
//		foreach ($oVoteModel->getListColumn() as $sColumn) {
//			if (in_array($sColumn, $oVoteModel->getIdTab())) {
//				continue;
//			}
//			if ((_root::getParam($sColumn, null) == null) && (null != $oVote->$sColumn)) {
//				$oVote->$sColumn = null;
//			} else {
//				$oVote->$sColumn = _root::getParam($sColumn, null);
//			}
//		}
//
//		if ($oVote->isValid()) {
//			$oVote->save();
//			$this->saveVoteAcl($oVote->getId(), $ptCompleteAclModules);
//			_root::redirect('votes::read', array('id' => $oVote->getId()));
//		}
		return $oVote;
	}


	/**
	 * @param $pVoteId int
	 * @return row_vote
	 */
	private function buildVote($pVoteId)
	{
		$oVote = model_vote::getInstance()->findById($pVoteId);
		return $oVote;
	}

	/**
	 * @param row_vote $poVote
	 * @return row_vote_item[]
	 */
	private function buildVoteItems($poVote)
	{
		$oVote = model_vote::getInstance()->findById($pVoteId);
		return $oVote;
	}


}

