<?php

class module_connection extends abstract_module
{

	public function _index()
	{
		_root::getLog()->log('AMA >>> module_connection._index : '._root::getParam('action'));
		$oView = new _view('connection::index');
		// Post ?
		if (_root::getRequest()->isPost()) {
			if (_root::getParam('submitLogin')) {
				$this->doLogin();
			} elseif ('submitForgottenPassword' == _root::getParam('action')) {
				$this->doForgottenPassword();
			}
		}
		return $oView;
	}

	public function _logout()
	{
		_root::getAuth()->logout();
	}

	private function doLogin()
	{
		// Recup params
		$sLogin = _root::getParam('login');
		$sPass = sha1(_root::getParam('password'));
		// _root::getLog()->log('AMA >>> login = '.$sLogin.', pass = '.$sPass);
		// Recherche et vérifie "login/pass" dans la base
		$oUser = model_user::getInstance()->findByLoginAndCheckPass($sLogin, $sPass);
		// Connexion si utilisateur autorisé
		if (null != $oUser) {
			$oUserSession = model_user_session::getInstance()->create($oUser);
			// _root::getLog()->log('AMA >>> $oUserSession = '.$oUserSession->getUser()->login);
			_root::getAuth()->connect($oUserSession);
			_root::redirect('home_enable::index');
		} else {
			// TODO Passer une info du genre : $this->sUnknownLogin=$sLogin; pour indiquer au module parent une erreur
		}
	}
	public function _forgottenPassword()
	{
		_root::getLog()->log('AMA >>> module_connection._forgottenPassword : '._root::getParam('action'));
		$oView = new _view('connection::forgottenPassword');
		// Post ?
		if (_root::getRequest()->isPost()) {
			if ('submitForgottenPassword' == _root::getParam('action')) {
				$this->doForgottenPassword();
			}
		}
		return $oView;
	}

	private function doForgottenPassword()
	{
		_root::getLog()->log('AMA >>> module_connection.doForgottenPassword');

	}
}
