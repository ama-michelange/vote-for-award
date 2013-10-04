<?php
class module_home extends abstract_module{

	public function before(){
		_root::getACL()->enable();
		$this->oLayout=new _layout('template_default');
		$this->oLayout->addModule('authent','authent::index');
		$this->oLayout->addModule('menu','menu::index');
	}


	public function _index() {
		$oView=new _view('home::index');
		$this->oLayout->add('work', $oView);
	}

	public function after(){
		$this->oLayout->show();
	}
}
