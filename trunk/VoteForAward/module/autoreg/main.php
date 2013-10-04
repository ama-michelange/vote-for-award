<?php
class module_autoreg extends abstract_module{
	public function before(){
		_root::startSession();
		plugin_vfa::loadI18n();
		
		$this->oLayout=new _layout('tpl_bs_bar');
		//$this->oLayout->addModule('bsnavbar','bsnavbar::index');

	}
	public function _index(){
		$oView=new _view('autoreg::index');
		$this->oLayout->add('work', $oView);
	}

	public function after(){
		$this->oLayout->show();
	}
}
