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
		$toAllTitles = array();

		$toAwards = model_award::getInstance()->findAllCompleted(true);
		foreach ($toAwards as $oAward) {
			$toResults = model_vote_result::getInstance()->findAllByIdAward($oAward->getId());
			if (count($toResults) > 0) {
				for ($i = 0; $i < 3; $i++) {
					$oTitle = $toResults[$i]->findTitle();
					$oTitle->year = $oAward->year;
					if ($i == 0) {
						$oTitle->order = '1er';
					} else {
						$pos = $i + 1;
						$oTitle->order = $pos . 'ème';
					}
					$toAllTitles[] = $oTitle;
				}

			}
		}
		$toTitles = array();
		shuffle($toAllTitles);
		for ($i = 0; ($i < 10) && ($i < count($toAllTitles)); $i++) {
			$toTitles[] = $toAllTitles[$i];
		}

		$oView = new _view('default::index');
		$oView->toTitles = $toAllTitles;
		$this->oLayout->add('work', $oView);
	}

	public function after()
	{
		$this->oLayout->show();
	}
}
