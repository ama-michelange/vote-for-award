<?php

class module_default extends abstract_module
{

	public function before()
	{
		_root::startSession();
		plugin_vfa::loadI18n();

		if (_root::getAuth()->isConnected()) {
			_root::redirect('home_enable::index');
		}
		$this->oLayout = new _layout('tpl_bs_bar');
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
	}

	public function _index()
	{
		$oView = new _view('default::index');
		$this->oLayout->add('work', $oView);
	}

	public function _toto()
	{
		//_root::getLog()->log('ama_test_toto');

		/*
		$tParams = _root::getRequest()->getParams();
		$tItems = array();
		foreach ($tParams as $key => $value) {
			_root::getLog()->log('   '.$key.' => '.$value);
		}
		*/

		$mess = _root::getParam("MESSAGE_LOG");
		_root::getLog()->log($mess);
	}

	public function after()
	{
		$this->oLayout->show();
	}
}
