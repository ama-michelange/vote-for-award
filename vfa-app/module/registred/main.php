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
				$navBar->setTitle('Lecteurs inscrits', new NavLink('registred', 'listReaderRegistred'));
				break;
			case 'listBoardGroup':
				$navBar->setTitle('Groupe du comité', new NavLink('registred', 'listBoardGroup'));
				break;
			case 'listBoardRegistred':
				$navBar->setTitle('Membres du comité', new NavLink('registred', 'listBoardRegistred'));
				break;
		}

		$this->buildMenuRegistred($navBar->getChild('left'), $oUserSession);
		$this->buildMenuGuests($navBar->getChild('left'), $oUserSession);
		$this->buildMenuGroups($navBar->getChild('left'), $oUserSession);

		return $navBar;
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	public static function buildMenuRegistred($pBar, $poUserSession)
	{
		$tValidReaderAwards = $poUserSession->getValidReaderAwards();
		$tValidBoardAwards = $poUserSession->getValidBoardAwards();

		$tItems = array();
		if (count($tValidReaderAwards) > 0) {
			$item = plugin_BsHtml::buildMenuItem('Lecteurs', new NavLink('registred', 'listReaderRegistred'));
			if ($item) {
				$tItems[] = $item;
			}
		}
		if (count($tValidBoardAwards) > 0) {
			$item = plugin_BsHtml::buildMenuItem('Comité', new NavLink('registred', 'listBoardRegistred'));
			if ($item) {
				$tItems[] = $item;
			}
		}

		switch (count($tItems)) {
			case 0:
				break;
			case 1:
				$tItems[0]->setLabel('Inscrits');
				$pBar->addChild($tItems[0]);
				break;
			default:
				$drop = new DropdownMenuItem('Inscrits');
				foreach ($tItems as $item) {
					$drop->addChild($item);
				}
				$pBar->addChild($drop);
				break;
		}
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	public static function buildMenuGroups($pBar, $poUserSession)
	{
		$oReaderGroup = $poUserSession->getReaderGroup();

		$tItems = array();
		$item = plugin_BsHtml::buildMenuItem($oReaderGroup->group_name, new NavLink('registred', 'listReaderGroup'));
		if ($item) {
			$tItems[] = $item;
		}
		$item = plugin_BsHtml::buildMenuItem('Comité de sélection', new NavLink('registred', 'listBoardGroup'));
		if ($item) {
			$tItems[] = $item;
		}

		switch (count($tItems)) {
			case 0:
				break;
			case 1:
				$pBar->addChild($tItems[0]);
				break;
			default:
				$drop = new DropdownMenuItem('Groupes');
				foreach ($tItems as $item) {
					$drop->addChild($item);
				}
				$pBar->addChild($drop);
				break;
		}
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	public static function buildMenuGuests($pBar, $poUserSession)
	{
		$oReaderGroup = $poUserSession->getReaderGroup();

		$tItems = array();
		$item = plugin_BsHtml::buildMenuItem($oReaderGroup->group_name, new NavLink('invitations', 'listReader'));
		if ($item) {
			$tItems[] = $item;
		}
		$item = plugin_BsHtml::buildMenuItem('Comité de sélection', new NavLink('invitations', 'listBoard'));
		if ($item) {
			$tItems[] = $item;
		}

		switch (count($tItems)) {
			case 0:
				break;
			case 1:
				$tItems[0]->setLabel('Invités');
				$pBar->addChild($tItems[0]);
				break;
			default:
				$drop = new DropdownMenuItem('Invités');
				foreach ($tItems as $item) {
					$drop->addChild($item);
				}
				$pBar->addChild($drop);
				break;
		}
	}

	public function _index()
	{
		// redirection vers la page par défaut
		_root::redirect('registred::listReaderGroup');
	}

	public function _listReaderGroup()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();

		$tUsers = model_user::getInstance()->findAllByGroupId($oReaderGroup->group_id);

		$oView = new _view('registred::listGroup');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oReaderGroup;

		$this->oLayout->add('work', $oView);
	}

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
			$tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($oReaderGroup->group_id, $oFirstAward->award_id, 'email');
		}
		$oView = new _view('registred::listRegistred');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oReaderGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;


		$this->oLayout->add('work', $oView);
	}

	public function _listBoardGroup()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oBoardGroup = $oUserSession->getBoardGroup();

		$tUsers = model_user::getInstance()->findAllByGroupId($oBoardGroup->group_id);

		$oView = new _view('registred::listGroup');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oBoardGroup;

		$this->oLayout->add('work', $oView);
	}

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


}
