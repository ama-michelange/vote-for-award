<?php

class module_registred extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		$this->oLayout = new _layout('tpl_bs_bar_context');
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->addChild(new Bar('left'));

		$navBar->setTitle('Inscrits', new NavLink('registred', _root::getAction()));

		$this->buildMenuRegistred($navBar->getChild('left'), $oUserSession);
//		module_invitations::buildMenuGuests($navBar->getChild('left'), $oUserSession);
//		module_users::buildMenuUsersByGroup($navBar->getChild('left'), $oUserSession);

		return $navBar;
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	public static function buildMenuRegistred($pBar, $poUserSession)
	{
		$tItems = array();

		$tItems[] = plugin_BsHtml::buildMenuItem($poUserSession->getReaderGroup()->toString(), new NavLink('registred', 'listReader'));
		$tItems[] = plugin_BsHtml::buildMenuItem('Correspondants', new NavLink('registred', 'listResponsible'));
		$tItems[] = plugin_BsHtml::buildMenuItem($poUserSession->getBoardGroup()->toString(), new NavLink('registred', 'listBoard'));

		$pBar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Autres inscrits', 'Inscrits'));
	}


	public function _index()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER)) {
			_root::getRequest()->setAction('listResponsible');
			$this->_listResponsible();
		} else {
			_root::getRequest()->setAction('listReader');
			$this->_listReader();
		}
	}


	public function _listReader()
	{
		$tUsers = array();
		$oFirstAward = null;
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oUserSession->getValidReaderAwards();
		if (count($tAwards) > 0) {
			$oFirstAward = $tAwards[0];
			$tUsers = model_user::getInstance()
				->findAllByGroupIdByAwardId($oReaderGroup->group_id, $oFirstAward->award_id);
		}
		$oView = new _view('registred::list');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oReaderGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;


		$this->oLayout->add('work', $oView);
	}

	public function _listBoard()
	{
		$tUsers = array();
		$oFirstAward = null;
		$tBoardGroups = model_group::getInstance()->findAllByRoleName(plugin_vfa::ROLE_BOARD);
		if (count($tBoardGroups) > 0) {
			$oBoardGroup = $tBoardGroups[0];
		}
		$tAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_BOARD);
		if (count($tAwards) > 0) {
			$oFirstAward = $tAwards[0];
			$tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($oBoardGroup->group_id, $oFirstAward->award_id, 'email');
		}

		$oView = new _view('registred::list');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oBoardGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;

		$oView->showGroup = true;

		$this->oLayout->add('work', $oView);
	}


	public function _listResponsible()
	{
		$tUsers = array();
		$oFirstAward = null;
		$tAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER);
		if (count($tAwards) > 0) {
			$oFirstAward = $tAwards[0];
			$tUsers = model_user::getInstance()->findAllByRoleNameByAwardId(plugin_vfa::ROLE_RESPONSIBLE, $oFirstAward->award_id, 'email');
		}
		$oView = new _view('registred::list');
		$oView->tUsers = $tUsers;
		$oTitleGroup = new row_group();
		$oTitleGroup->group_name = 'Correspondants';
		$oView->oGroup = $oTitleGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;

		$oView->showGroup = true;

		$this->oLayout->add('work', $oView);
	}

}
