<?php

class module_help extends abstract_module
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

		$navBar->setTitle('Aide');
		$navBar->addChild(new Bar('left'));
		$navBar->addChild(new BarButtons('right'));


		$this->buildMenuRight($navBar);
		return $navBar;
	}

	/**
	 * @param NavBar $pNavBar
	 */
	public function buildMenuRight($pNavBar)
	{
	}

	public function _registryGlobalProcess()
	{
		$oView = new _view('help::registryGlobalProcess');
		$this->oLayout->add('work', $oView);
	}

	public function _registryGroup()
	{
		$oView = new _view('help::registryGroup');
		$this->oLayout->add('work', $oView);
	}

	public function _registryReader()
	{
		$oView = new _view('help::registryReader');
		$this->oLayout->add('work', $oView);
	}
}
