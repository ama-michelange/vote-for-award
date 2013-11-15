<?php

class module_home_enable extends abstract_module
{

	public function before()
	{
		_root::getAuth()->enable();
		$this->oLayout = new _layout('tpl_bs_bar');
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
	}

	public function _index()
	{
		$oView = new _view('home_enable::index');
		$this->oLayout->add('work', $oView);
	}

	public function after()
	{
		$this->oLayout->show();
	}
}
