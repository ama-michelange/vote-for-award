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
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->setTitle('Groupe', new NavLink('registred', 'listGroup'));
		$navBar->addChild(new BarButtons('left'));
//		plugin_BsContextBar::buildDefaultContextBar($navBar);
		return $navBar;
	}

	public function _index()
	{
		// redirection vers la page par défaut
		_root::redirect('registred::listGroup');
	}

	public function _listGroup()
	{
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();

		$tUsers = model_user::getInstance()->findAllByGroupId($oReaderGroup->group_id);

		$oView = new _view('registred::listGroup');
		$oView->tUsers = $tUsers;
		$oView->oGroup = $oReaderGroup;

		$this->oLayout->add('work', $oView);
	}

	public function _listRegistred()
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


}
