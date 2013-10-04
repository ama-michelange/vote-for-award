<?php
Class module_bsheader extends abstract_module{

	public function _index(){
		$oView = new _view('bsheader::index');
		$oView->sTitle=$this->buildTitle();
		return $oView;
	}


	public function buildTitle(){

		switch (_root::getModule()) {
			case 'awards':
				$sTitle = 'Prix';
				break;
			case 'docs':
				$sTitle = 'Albums';
				break;
			case 'groups':
				$sTitle = 'Groupes';
				break;
			case 'titles':
				$sTitle = 'Titres';
				break;
			default:
				$sTitle= _root::getModule();
				break;
		}
		return $sTitle;
	}

}
