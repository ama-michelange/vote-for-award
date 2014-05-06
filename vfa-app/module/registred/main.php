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
//		$oReaderGroup = $oUserSession->getReaderGroup();
		$tValidReaderAwards = $oUserSession->getValidReaderAwards();
		$tValidBoardAwards = $oUserSession->getValidBoardAwards();

		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->addChild(new Bar('left'));

		$item = plugin_BsHtml::buildMenuItem('Invités', new NavLink('invitations', 'index'));
		if ($item) {
			$navBar->getChild('left')->addChild($item);
		}

		$item = null;
		$item2 = null;
		switch (_root::getAction()) {
			case 'listReaderGroup':
				$navBar->setTitle('Mon groupe', new NavLink('registred', 'listReaderGroup'));
				if (count($tValidReaderAwards) > 0) {
					$item = plugin_BsHtml::buildMenuItem('Inscrits', new NavLink('registred', 'listReaderRegistred'));
				}
				$item2 = plugin_BsHtml::buildMenuItem('Comité', new NavLink('registred', 'listBoardGroup'));
				break;
			case 'listReaderRegistred':
				$navBar->setTitle('Inscrits', new NavLink('registred', 'listReaderRegistred'));
				$item = plugin_BsHtml::buildMenuItem('Mon groupe', new NavLink('registred', 'listReaderGroup'));
				$item2 = plugin_BsHtml::buildMenuItem('Comité', new NavLink('registred', 'listBoardGroup'));
				break;
			case 'listBoardGroup':
				$navBar->setTitle('Comité', new NavLink('registred', 'listBoardGroup'));
				if (count($tValidBoardAwards) > 0) {
					$item = plugin_BsHtml::buildMenuItem('Inscrits comité', new NavLink('registred', 'listBoardRegistred'));
				}
				$item2 = plugin_BsHtml::buildMenuItem('Mon groupe', new NavLink('registred', 'listReaderGroup'));
				break;
			case 'listBoardRegistred':
				$navBar->setTitle('Inscrits', new NavLink('registred', 'listBoardRegistred'));
				$item = plugin_BsHtml::buildMenuItem('Comité', new NavLink('registred', 'listBoardGroup'));
				$item2 = plugin_BsHtml::buildMenuItem('Mon groupe', new NavLink('registred', 'listReaderGroup'));
				break;
		}
		if ($item) {
			$navBar->getChild('left')->addChild($item);
		}
		if ($item2) {
			$navBar->getChild('left')->addChild($item2);
		}
		return $navBar;
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
			$tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($oReaderGroup->group_id, $oFirstAward->award_id);
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
			$tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($oBoardGroup->group_id, $oFirstAward->award_id);
		}
		$oView = new _view('registred::listRegistred');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oBoardGroup;
		$oView->oFirstAward = $oFirstAward;
		$oView->tAwards = $tAwards;


		$this->oLayout->add('work', $oView);
	}


}
