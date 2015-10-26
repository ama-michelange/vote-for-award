<?php

class module_votes_detail extends abstract_module
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

		$groupId = _root::getParam('group_id');
		if (null != $groupId) {
			$theGroup = model_group::getInstance()->findById($groupId);
			if (false == $theGroup->isEmpty()) {
				$this->oGroup = $theGroup;
			}
		}
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
//		$param = null;
//		$awardId = _root::getParam('award_id');
//		if (null != $awardId) {
//			$param = array('award_id' => $awardId);
//		}
//		$navBar->setTitle('Votes en cours', new NavLink('votes_detail', 'index', $param));
		$navBar->setTitle('Détail des votes', new NavLink('votes_detail', 'index'));
		$this->buildMenuAward($navBar);
		$this->buildMenuGroup($navBar);
		return $navBar;
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuAward($pNavBar)
	{
		if ($this->oAward) {
			$bar = $pNavBar->getChild('left');
			$tItems = array();
			foreach ($this->tAwards as $award) {
				if ($award->award_id != $this->oAward->award_id) {
					$tItems[] = plugin_BsHtml::buildMenuItem($award->toString(),
						new NavLink('votes_detail', 'index', array('award_id' => $award->award_id, 'group_id' => $award->group_id)));
				}
			}
			$bar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres prix', null, true, true));
		}
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildMenuGroup($pNavBar)
	{
		$bar = $pNavBar->getChild('left');
		$tItems = array();
		$tGroups = model_group::getInstance()->findAll();
		foreach ($tGroups as $group) {
			if ($group->getId() != $this->oGroup->getId()) {
				$tItems[] = plugin_BsHtml::buildMenuItem($group->toString(),
					new NavLink('votes_detail', 'otherGroup', array('group_id' => $group->group_id)));
			}
		}
		$bar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres groupes', null, true, true));
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

		$oView = new _view('votes_detail::list');
		$oView->oAward = $this->oAward;
		$oView->oGroup = $this->oGroup;
		if ($this->oAward) {
			$oView->tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($this->oGroup->getId(), $this->oAward->getId());
		}
		$this->oLayout->add('work', $oView);
	}

	public function _otherGroup()
	{
		$this->_index();
	}
}

