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

	public function _amaTest()
	{
		$ac = new plugin_ActionItem('myModule', 'myAction');
		var_dump($ac);
		var_dump($ac->getNav());
		var_dump($ac->getLink());
		var_dump($ac->isPermit());
		
		$sep1 = new plugin_LabelItem();
		var_dump($sep1);
		
		$sep2 = plugin_LabelItem::buildSeparator();
		var_dump($sep2);
		var_dump($sep2->isSeparator());
		
		$item1 = new plugin_LabelItem('myLabel1', 'myIcon', 'myModule', 'myAction');
		var_dump($item1);
		var_dump($item1->getNav());
		var_dump($item1->getLink());
		var_dump($item1->isPermit());
		var_dump($item1->hasChildren());
		
		$item10 = plugin_LabelItem::build('myLabel', 'myIcon10', 'home_enable', 'index');
		var_dump($item10);
		$item11 = plugin_LabelItem::build('myLabel', 'myIcon11', 'home_enable', 'index');
		var_dump($item11);
		$item12 = plugin_LabelItem::build('myLabel', 'myIcon12', 'home_enable', 'index');
		var_dump($item12);
		
		$item2 = plugin_LabelItem::build('myLabel2', 'myIcon', 'home_enable', 'index');
		$item2->addChild(plugin_LabelItem::build('myLabelChild1', 'myIconChild1', 'home_enable', 'index'));
		$item2->addChild(plugin_LabelItem::build('myLabelChild2', 'myIconChild2', 'home_enable', 'index'));
		$item2->addChild(plugin_LabelItem::build('myLabelChild3', 'myIconChild3', 'AMAhome_enable', 'index'));
		$item2->addChild(plugin_LabelItem::build('myLabelChild4', 'myIconChild4', 'home_enable', 'index'));
		var_dump($item2);
		var_dump($item2->hasChildren());
		
		$items = new plugin_BarItems();
		$items->add($item1);
		$items->add($sep1);
		$items->add($item2);
		$items->add($sep2);
		$items->add($item10);
		$items->add($item11);
		$items->add($item12);
		
		var_dump($items);
		
		$oView = new _view('home_enable::ama');
		$this->oLayout->add('work', $oView);
	}

	public function after()
	{
		$this->oLayout->show();
	}
}
