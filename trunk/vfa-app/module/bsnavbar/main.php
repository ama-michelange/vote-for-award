<?php

class module_bsnavbar extends abstract_module
{

	public function _index()
	{
		$oView = $this->buildView();
		$oView->tLink = plugin_vfa_menu::buildNavBarMenu();
		return $oView;
	}

	public function _logout()
	{
		_root::getAuth()->logout();
	}

	public function buildView()
	{
		// _root::getLog()->log('AMA >>> module_authent._login()');
		// Vue par défaut
		$oView = new _view('bsnavbar::index');
		// Post ?
		if (_root::getRequest()->isPost()) {
			// Recup params
			$sLogin = _root::getParam('login');
			$sPass = sha1(_root::getParam('password'));
			// _root::getLog()->log('AMA >>> login = '.$sLogin.', pass = '.$sPass);
			// Recherche et vérifie "login/pass" dans la base
			$oUser = model_user::getInstance()->findByLoginAndCheckPass($sLogin, $sPass);
			// Connexion si utilisateur autorisé
			if (null != $oUser) {
				$oAccount = model_account::getInstance()->create($oUser);
				// _root::getLog()->log('AMA >>> $oAccount = '.$oAccount->getUser()->username);
				_root::getAuth()->connect($oAccount);
				_root::redirect('home_enable::index');
			} else {
				// TODO Passer une info du genre : $this->sUnknownLogin=$sLogin; pour indiquer au module parent une erreur
			}
		}
		return $oView;
	}
}
