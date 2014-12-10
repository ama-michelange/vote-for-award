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
		$errorLogin = false;
		if (_root::getRequest()->isPost()) {
			$errorLogin = $this->doLogin();
		}

		$oView = new _view('default::index');
		$oView->toTitles = $this->searchWinnerTitlesInAwards(7, 13);
		$this->oLayout->add('work', $oView);

		$scriptView = new _view('default::script');
		$scriptView->errorLogin = $errorLogin;
		$this->oLayout->add('script', $scriptView);

	}

	private function doLogin()
	{
		$actionlogin = false;
		$action = _root::getParam('actionLogin');
		if (isset($action)) {
			$actionlogin = true;
			// Recup params
			$sLogin = _root::getParam('login');
			$sPass = plugin_vfa::cryptPassword(_root::getParam('password'));
			// Recherche et vérifie "login/pass" dans la base
			$oUser = model_user::getInstance()->findByLoginAndCheckPass($sLogin, $sPass);
			// Connexion si utilisateur autorisé
			if (null != $oUser) {
				$oUserSession = model_user_session::getInstance()->create($oUser);
				_root::getAuth()->connect($oUserSession);
				_root::redirect('home_enable::index');
			}
		}
		return $actionlogin;
	}

	/**
	 * Recherche les titres gagnants des prix terminés.
	 * @param int $pTop Recherche les x premiers de chaque prix
	 * @param int $pMax Nombre maximum de titres remontés
	 * @param bool $pShuffle Mélange les titres avant de les renvoyer
	 * @return array Le tableau des titres gagnants
	 */
	private function searchWinnerTitlesInAwards($pTop = 3, $pMax = 10, $pShuffle = true)
	{
		$toAllTitles = array();

		$toAwards = model_award::getInstance()->findAllCompleted(true);
		foreach ($toAwards as $oAward) {
			$toResults = model_vote_result::getInstance()->findAllByIdAward($oAward->getId());
			$nbResults = count($toResults);
			if ($nbResults > 0) {
				for ($i = 0; $i < $pTop; $i++) {
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
		if ($pShuffle) {
			shuffle($toAllTitles);
		}
		for ($i = 0; ($i < $pMax) && ($i < count($toAllTitles)); $i++) {
			$toTitles[] = $toAllTitles[$i];
		}
		return $toTitles;
	}

	public function after()
	{
		$this->oLayout->show();
	}
}
