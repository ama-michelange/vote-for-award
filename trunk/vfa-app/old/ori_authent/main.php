<?php
Class module_authent extends abstract_module{


	public function _index(){
		$oView=new _view('authent::index');
		return $oView;
	}

	public function _login() {
		// _root::getLog()->log('AMA >>> module_authent._login()');
		// Vue par défaut
		$view = 'home_enable::index';
		// Post ?
		if(_root::getRequest()->isPost()) {
			// Recup params
			$sLogin=_root::getParam('login');
			$sPass=sha1(_root::getParam('password'));
				_root::getLog()->log('AMA >>> login = '.$sLogin.', pass = '.$sPass);
				_root::getLog()->log('AMA >>> cur-module = '._root::getParam('cur-module').', cur-action = '._root::getParam('cur-action'));
			
			// Recherche et vérifie "login/pass" dans la base
			$oUser = model_user::getInstance()->findByLoginAndCheckPass($sLogin, $sPass);
			// Connexion si utilisateur autorisé
			if (null != $oUser) {
				$oAccount = model_account::getInstance()->create($oUser);
				// _root::getLog()->log('AMA >>> $oAccount = '.$oAccount->getUser()->username);
				_root::getAuth()->connect($oAccount);
				 _root::getLog()->log('AMA >>> $oAccount = '._root::getAuth()->getAccount()->getUser()->username);
				 _root::getLog()->log('AMA >>> $isConnected = '._root::getAuth()->isConnected());
				//$view = 'authent::index';
				$view = _root::getParam('cur-module').'::'._root::getParam('cur-action');
			}
		}
		//return new _view($view);
		_root::redirect($view);
	}

	public function _logout(){
		_root::getAuth()->logout();
	}

}
