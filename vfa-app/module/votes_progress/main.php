<?php

class module_votes_progress extends abstract_module
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
		$this->oGroup = $oUserSession->getReaderGroup();
		$this->tAwards = $this->oGroup->findAwards();
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
		$param = null;
		$awardId = _root::getParam('award_id');
		if (null != $awardId) {
			$param = array('award_id' => $awardId);
		}
		$navBar->setTitle('Votes en cours', new NavLink('votes_progress', 'index', $param));
		$this->buildMenuAward($navBar);
		return $navBar;
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuAward($pNavBar)
	{
		$bar = $pNavBar->getChild('left');
		$tItems = array();
		foreach ($this->tAwards as $award) {
			if ($award->award_id != $this->oAward->award_id) {
				$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
					new NavLink('votes_progress', 'index', array('award_id' => $award->award_id)));
			}
		}
		$bar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Votes du groupe', null, true, true));


	}

	public function _index()
	{
		$this->oAward = null;
		$awardId = _root::getParam('award_id');
		if (null == $awardId) {
			if (count($this->tAwards) > 0) {
				$this->oAward = $this->tAwards[0];
			}
		} else {
			foreach ($this->tAwards as $oAward) {
				if ($oAward->getId() == $awardId) {
					$this->oAward = $oAward;
					break;
				}
			}
		}

		$oView = new _view('votes_progress::list');
		$oView->oAward = $this->oAward;
		$oView->oGroup = $this->oGroup;
		$oView->tUsers = $this->oGroup->findUsers();


		$this->oLayout->add('work', $oView);
	}

}

