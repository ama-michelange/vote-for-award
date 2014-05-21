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

		switch (_root::getAction()) {
			case 'listReaderGroup':
				$navBar->setTitle('Mon groupe', new NavLink('registred', 'listReaderGroup'));
				break;
			case 'listReaderRegistred':
				$navBar->setTitle('Inscrits', new NavLink('registred', 'listReaderRegistred'));
				break;
			case 'listBoardGroup':
				$navBar->setTitle('Comité', new NavLink('registred', 'listBoardGroup'));
				break;
			case 'listBoardRegistred':
				$navBar->setTitle('Inscrits', new NavLink('registred', 'listBoardRegistred'));
				break;
			default:
				$navBar->setTitle('Lecteur');
				break;
		}

		$this->buildMenuRegistred($navBar->getChild('left'), $oUserSession);
		module_invitations::buildMenuGuests($navBar->getChild('left'), $oUserSession);
		module_users::buildMenuUsersByGroup($navBar->getChild('left'), $oUserSession);


//		switch (_root::getAction()) {
//			case 'listReaderGroup':
//			case 'listReaderRegistred':
//			case 'listBoardGroup':
//			case 'listBoardRegistred':
//			case 'listAllUsers':
//				$this->buildMenuRegistred($navBar->getChild('left'), $oUserSession);
//				$this->buildMenuGuests($navBar->getChild('left'), $oUserSession);
//				module_users::buildMenuUsersByGroup($navBar->getChild('left'), $oUserSession);
//				break;
//			default:
//				module_users::buildMenuUsersByGroup($navBar->getChild('left'), $oUserSession);
//				break;
//		}


		return $navBar;
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	public static function buildMenuRegistred($pBar, $poUserSession)
	{
		$tItems = array();

		$tValidReaderAwards = $poUserSession->getValidReaderAwards();
		if (count($tValidReaderAwards) > 0) {
			$tItems[] = plugin_BsHtml::buildMenuItem($poUserSession->getReaderGroup()->toString(),
				new NavLink('registred', 'listReaderRegistred'));
			$tItems[] = plugin_BsHtml::buildMenuItem('Correspondants', new NavLink('registred', 'listResponsibleRegistred'));
		}
		$tValidBoardAwards = $poUserSession->getValidBoardAwards();
		if (count($tValidBoardAwards) > 0) {
			$tItems[] = plugin_BsHtml::buildMenuItem($poUserSession->getBoardGroup()->toString(),
				new NavLink('registred', 'listBoardRegistred'));
		}

		$pBar->addChild(plugin_BsHtml::buildDropdownMenuItem($tItems, 'Inscrits', 'Inscrits'));
	}


	public function _index()
	{
		// redirection vers la page par défaut
		_root::redirect('registred::listReaderRegistred');
	}

//	public function _listReaderGroup()
//	{
//		$invite = false;
//
//		/* @var $oUserSession row_user_session */
//		$oUserSession = _root::getAuth()->getUserSession();
//		$oReaderGroup = $oUserSession->getReaderGroup();
//		$tValidReaderAwards = $oUserSession->getValidReaderAwards();
//		if (count($tValidReaderAwards) > 0) {
//			$invite = true;
//		}
//
//		$tUsers = model_user::getInstance()->findAllByGroupId($oReaderGroup->group_id);
//
//		$oView = new _view('registred::listUsers');
//		$oView->tUsers = $tUsers;
//		$oView->oGroup = $oReaderGroup;
//		$oView->invite = $invite;
//
//		$this->oLayout->add('work', $oView);
//	}

	public function _listReaderRegistred()
	{
		$tUsers = null;
		$oFirstAward = null;
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oUserSession->getValidReaderAwards();
		if (count($tAwards) > 0) {
			$oFirstAward = $tAwards[0];
			$tUsers = model_user::getInstance()
				->findAllByGroupIdByAwardId($oReaderGroup->group_id, $oFirstAward->award_id, 'email');
		}
		$oView = new _view('registred::listRegistred');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oReaderGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;


		$this->oLayout->add('work', $oView);
	}

//	public function _listBoardGroup()
//	{
//		/* @var $oUserSession row_user_session */
//		$oUserSession = _root::getAuth()->getUserSession();
//		$oBoardGroup = $oUserSession->getBoardGroup();
//
//		$tUsers = model_user::getInstance()->findAllByGroupId($oBoardGroup->group_id);
//
//		$oView = new _view('registred::listUsers');
//		$oView->tUsers = $tUsers;
//		$oView->oGroup = $oBoardGroup;
//		$oView->invite = _root::getACL()->permit('invitations::board');
//
//		$this->oLayout->add('work', $oView);
//	}

	public function _listBoardRegistred()
	{
		$tUsers = null;
		$oFirstAward = null;
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oBoardGroup = $oUserSession->getBoardGroup();
		$tAwards = $oUserSession->getValidBoardAwards();
		if (count($tAwards) > 0) {
			$oFirstAward = $tAwards[0];
			$tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($oBoardGroup->group_id, $oFirstAward->award_id, 'email');
		}
		$oView = new _view('registred::listRegistred');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oBoardGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;


		$this->oLayout->add('work', $oView);
	}

	public function _listResponsibleGroup()
	{
		$tUsers = model_user::getInstance()->findAllByRoleName(plugin_vfa::ROLE_RESPONSIBLE);

		$oView = new _view('registred::listUsers');
		$oView->tUsers = $tUsers;
		$oTitleGroup = new row_group();
		$oTitleGroup->group_name = 'Correspondants';
		$oView->oGroup = $oTitleGroup;
		$oView->invite = _root::getACL()->permit('invitations::responsible');

		$this->oLayout->add('work', $oView);
	}

	public function _listResponsibleRegistred()
	{
		$tUsers = null;
		$oFirstAward = null;
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$tAwards = $oUserSession->getValidReaderAwards();
		if (count($tAwards) > 0) {
			$oFirstAward = $tAwards[0];
			$tUsers = model_user::getInstance()->findAllByRoleNameByAwardId(plugin_vfa::ROLE_RESPONSIBLE, $oFirstAward->award_id, 'email');
		}
		$oView = new _view('registred::listRegistred');
		$oView->tUsers = $tUsers;
		$oTitleGroup = new row_group();
		$oTitleGroup->group_name = 'Correspondants';
		$oView->oGroup = $oTitleGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;

		$this->oLayout->add('work', $oView);
	}

	public function _listAllGroups()
	{
		$tGroups = model_group::getInstance()->findAll();

		$oView = new _view('registred::listGroups');
		$oView->tGroups = $tGroups;

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('registred::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oUserModel = new model_user();
		$oUser = $oUserModel->findById(_root::getParam('id'));

		$oView = new _view('registred::show');
		$oView->oUser = $oUser;

		$tRoleNames = $oUser->findRoleNames();
		$responsibleRole = false;
		if (isset($tRoleNames[plugin_vfa::ROLE_RESPONSIBLE])) {
			$responsibleRole = true;
		}
		$oView->responsibleRole = $responsibleRole;

		$boardRole = false;
		if (isset($tRoleNames[plugin_vfa::ROLE_BOARD])) {
			$boardRole = true;
		}
		$oView->boardRole = $boardRole;

		$oView->oReaderGroup = $oUser->findGroupByRoleName(plugin_vfa::ROLE_READER);
		$oView->oBoardGroup = $oUser->findGroupByRoleName(plugin_vfa::ROLE_BOARD);

		$toValidAwards = model_award::getInstance()->findAllValidByUserId($oUser->user_id);
		$toValidReaderAwards = array();
		$toValidBoardAwards = array();
		foreach ($toValidAwards as $oAward) {
			switch ($oAward->type) {
				case plugin_vfa::TYPE_AWARD_BOARD:
					$toValidBoardAwards[] = $oAward;
					break;
				case plugin_vfa::TYPE_AWARD_READER:
					$toValidReaderAwards[] = $oAward;
					break;
			}
		}
		$oView->toValidReaderAwards = $toValidReaderAwards;
		$oView->toValidBoardAwards = $toValidBoardAwards;

		return $oView;
	}


}
