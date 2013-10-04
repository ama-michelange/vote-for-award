<?php
Class module_menu extends abstract_module{

	public function _index(){

		$tLink=array(
			'Vote' => 'home::index',
			'Compte' => 'home::index',
		);

		if (_root::getACL()->isInRole('owner')) {
			$tLink['Utilisateurs']='users::list';
			$tLink['Roles']='roles::list';
			$tLink['Groupe']='groups::list';
		}
		$tLink['Inscriptions']='registrations::list';
		$tLink['Prix']='awards::list';
		$tLink['Titres']='titles::list';
		$tLink['Albums']='docs::list';


		$oView = new _view('menu::index');
		$oView->tLink=$tLink;

		return $oView;
	}
}
