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

	public function _code()
	{
		$this->showViewGetCode(new row_registry());
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function showViewGetCode($poRegistry)
	{
		$oView = new _view('default::code');

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$oView->oRegistry = $poRegistry;
		$oView->tMessage = $poRegistry->getMessages();

		$this->oLayout->add('work', $oView);
	}

	public function _registry()
	{
		if (_root::getRequest()->isPost()) {
			switch (_root::getParam('action')) {
				case 'toGetCode':
					$this->postGetCode();
					break;
				case 'toAccount':
					$this->postAccount();
					break;
				case 'toIdentify':
					$this->postIdent();
					break;
				case 'submitForgottenPassword':
					$this->postForgottenPassword();
					break;
				default:
					_root::redirect('default::index');
					break;
			}
		} else {
			_root::redirect('default::index');
		}
	}

	private function postGetCode()
	{
		$nextView = false;
		$oRegistry = new row_registry();
		// on verifie que le token est valide
		if ($this->isValidToken($oRegistry)) {
			$oRegistry->action = _root::getParam('action', null);
			$oRegistry->code = _root::getParam('code', null);
			if ($oRegistry->isValid()) {
				$oRegin = model_regin::getInstance()->findByCode($oRegistry->code);
				if ($oRegin->isEmpty()) {
					$oRegistry->setMessages(array('code' => array('registry.code.unknown')));
				} else {
					if (false == module_regin::verifyReginValidity($oRegin)) {
						$oRegistry->setMessages(array('code' => array('registry.code.invalid')));
					} else {
						$nextView = true;
						$oRegistry->oRegin = $oRegin;
						$oRegistry->regin_id = $oRegin->regin_id;
					}
				}
			}
		}
		if ($nextView) {
			$this->initViewRegistry($oRegistry);
			$this->showViewRegistry($oRegistry);
		} else {
			$this->showViewGetCode($oRegistry);
		}
	}

	private function postAccount()
	{
		$oRegistry = $this->makeRegistryWithParams();
		if ($this->isValidToken($oRegistry)) {
			// Force l'ouverture du panel d'enregistrement du compte
			$oRegistry->openAccount = true;
			// Validation
			if ($oRegistry->isValid()) {
				// Doublon ?
				$oUserDoublon = model_user::getInstance()->findByLogin($oRegistry->login);
				if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
					if (plugin_vfa::checkSavePassword($oRegistry, _root::getParam('newPassword'), _root::getParam('confirmPassword'))) {
						$this->createUserReader($oRegistry);
						$this->sendMailCreateAccount($oRegistry);
						$this->doRegistry($oRegistry);
					}
				} else {
					$oRegistry->setMessages(array('login' => array('doublon')));
				}
			}
		}
		$this->showViewRegistry($oRegistry);
	}

	private function postIdent()
	{
		$oRegistry = $this->makeRegistryWithParams();
		if ($this->isValidToken($oRegistry)) {
			// Force l'ouverture du panel d'identification
			$oRegistry->openLogin = true;
		}
		$this->showViewRegistry($oRegistry);
	}

	private function postForgottenPassword()
	{
		$oRegistry = $this->makeRegistryWithParams();
		if ($this->isValidToken($oRegistry)) {
			// Force l'ouverture du panel de Mot de passe
			$oRegistry->openPassword = true;

			$oRegistry->oConnection = module_connection::doForgottenPassword();

			if (true == $oRegistry->oConnection->mailSent) {
				$oRegistry->openPassword = false;
			}
		}
		$this->showViewRegistry($oRegistry);
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function doRegistry($poRegistry)
	{
		switch ($poRegistry->oRegin->type) {
			case plugin_vfa::TYPE_READER:
				$this->doRegistryReader($poRegistry);
				break;
		}
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function doRegistryReader($poRegistry)
	{
		if (plugin_vfa::PROCESS_INTIME == $poRegistry->oRegin->process) {
			// Ajoute les prix à l'utilisateur
			$this->addAwardsToUser($poRegistry);
		} else {
			// Sauvegarde pour validation
			model_regin::getInstance()->saveReginUser($poRegistry->oRegin->getId(), $poRegistry->oUser->getId());
			$this->sendMailReginToValid($poRegistry);
		}
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function initViewRegistry($poRegistry)
	{
		// Reconstruit le texte
//		$this->makeTextConfirmation($poInvitation, $oConfirm);
		// Force les fermetures des panels
		$poRegistry->openAccount = false;
		$poRegistry->openLogin = false;
		$poRegistry->openPassword = false;

		if (!$poRegistry->oConnection) {
			$poRegistry->oConnection = new row_connection();
		}
		if (!$poRegistry->oRegin) {
			$poRegistry->oRegin = model_regin::getInstance()->findById($poRegistry->regin_id);
		}
	}


	/**
	 * Verifie le token
	 * @param row_registry $poRegistry
	 * @return bool
	 */
	private function isValidToken($poRegistry)
	{
		if (_root::getParam('token')) {
			$oPluginXsrf = new plugin_xsrf();
			if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
				$poRegistry->setMessages(array('token' => $oPluginXsrf->getMessage()));
				return false;
			}
		}
		return true;
	}

	private function makeRegistryWithParams()
	{
		$oRegistry = new row_registry();

		$oRegistry->action = _root::getParam('action');

		// Récupère les params cachés
		$oRegistry->regin_id = _root::getParam('regin_id');

		// Copie la saisie Identification
		$oRegistry->cf_login = _root::getParam('cf_login');
		$oRegistry->cf_password = _root::getParam('cf_password');

		// Copie la saisie Compte Utilisateur
		$oRegistry->login = _root::getParam('login');
		$oRegistry->email = _root::getParam('email');
		$oRegistry->confirmEmail = _root::getParam('confirmEmail');
		$oRegistry->newPassword = _root::getParam('newPassword');
		$oRegistry->confirmPassword = _root::getParam('confirmPassword');
		$oRegistry->last_name = _root::getParam('last_name');
		$oRegistry->first_name = _root::getParam('first_name');
		$oRegistry->birthyear = _root::getParam('birthyear');
		$oRegistry->gender = _root::getParam('gender');

		// Copie la saisie Mot de passe oublié
//		$oRegistry->user_id = _root::getParam('user_id');

		$this->initViewRegistry($oRegistry);

		return $oRegistry;
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function showViewRegistry($poRegistry)
	{
		$oView = new _view('default::registry');

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$oView->oRegistry = $poRegistry;
		$oView->oRegin = $poRegistry->oRegin;
		$oView->tMessage = $poRegistry->getMessages();

		$oView->oViewRegistryDetail = new _view('default::viewRegistryDetail');
		$oView->oViewRegistryDetail->oRegin = $poRegistry->oRegin;
		$oView->oViewRegistryDetail->oGroup = $poRegistry->oRegin->findGroup();
		$oView->oViewRegistryDetail->tAwards = $poRegistry->oRegin->findAwards();
		$oView->oViewRegistryDetail->oCreatedUser = $poRegistry->oRegin->findCreatedUser();

		$oView->oViewFormAccount = new _view('default::formAccount');
		$oView->oViewFormAccount->oRegistry = $poRegistry;
		$oView->oViewFormAccount->tMessage = $poRegistry->getMessages();
		$oView->oViewFormAccount->tSelectedYears = plugin_vfa::buildSelectedBirthYears($poRegistry->birthyear);
		$oView->oViewFormAccount->token = $oView->token;

		$oView->oViewFormIdent = new _view('default::formIdent');
		$oView->oViewFormIdent->oRegistry = $poRegistry;
		$oView->oViewFormIdent->tMessage = $poRegistry->getMessages();
		$oView->oViewFormIdent->token = $oView->token;

		// Force l'email déjà connu s'il existe
//		if (null == $oConfirm->oConnection->myEmail) {
//			$oConfirm->oConnection->myEmail = $oConfirm->email;
//		}
		$oView->oViewForgottenPassword = new _view('connection::formForgottenPassword');
		$oView->oViewForgottenPassword->tHidden = array('regin_id' => $poRegistry->regin_id, 'invitation_key' => $poRegistry->oRegin->code);
		$oView->oViewForgottenPassword->oConnection = $poRegistry->oConnection;
		$oView->oViewForgottenPassword->tMessage = $poRegistry->oConnection->getMessages();
		$oView->oViewForgottenPassword->token = $oView->token;

		$oView->oViewModalMessage = new _view('connection::modalMessage');
		$oView->oViewModalMessage->oConnection = $poRegistry->oConnection;
		$oView->oViewModalMessage->tMessage = $poRegistry->oConnection->getMessages();

		$this->oLayout->add('work', $oView);

		// Gestion de l'affichage du bon panel
		$scriptView = new _view('default::scriptRegistry');
		$this->oLayout->add('script', $scriptView);
		// Gestion de l'affichage de la boite modale
		$scriptView = new _view('connection::scriptForgottenPassword');
		$scriptView->oConnection = $poRegistry->oConnection;
		$this->oLayout->add('script', $scriptView);
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function createUserReader($poRegistry)
	{
		$oUser = new row_user();
		$oUser->created_date = plugin_vfa::dateTimeSgbd();
		$oUser->modified_date = $oUser->created_date;

		$oUser->login = $poRegistry->login;
		$oUser->email = $poRegistry->email;
		$oUser->password = $poRegistry->password;
		$oUser->last_name = $poRegistry->last_name;
		$oUser->first_name = $poRegistry->first_name;
		$oUser->birthyear = $poRegistry->birthyear;
		$oUser->gender = $poRegistry->gender;

		$tIdGroups = array($poRegistry->oRegin->group_id);
		$tIdRoles = array();
		$oRole = model_role::getInstance()->findByName(plugin_vfa::ROLE_READER);
		$tIdRoles[] = $oRole->getId();

		$oUser->save();
		model_user::getInstance()->saveUserRoles($oUser->getId(), $tIdRoles);
		model_user::getInstance()->saveUserGroups($oUser->getId(), $tIdGroups);

		$poRegistry->oUser = $oUser;
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function addAwardsToUser($poRegistry)
	{
		$tIdAwards = explode(',', $poRegistry->oRegin->awards_ids);
		model_user::getInstance()->saveUserAwards($poRegistry->oUser->getId(), $tIdAwards);
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function connectUser($poRegistry)
	{
		$oUserSession = model_user_session::getInstance()->create($poRegistry->oUser);
		_root::getAuth()->connect($oUserSession);
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

	private function sendMailCreateAccount($poRegistry)
	{
		$oMail = new plugin_mail();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));
		$oMail->addTo($poRegistry->oUser->email);
//		$createdUser = $poInvitation->findCreatedUser();
//		$oMail->addCC($createdUser->email);
		$oMail->setBcc(_root::getConfigVar('vfa-app.mail.from'));
		// Sujet
		$oMail->setSubject('Création d\'un compte sur ' . _root::getConfigVar('vfa-app.title'));
		// Prepare le body TXT
		$oViewTxt = new _view('default::mailCreateAccountTxt');
		$oViewTxt->oUser = $poRegistry->oUser;
		$bodyTxt = $oViewTxt->show();
//		 _root::getLog()->log($bodyTxt);
		$oMail->setBody($bodyTxt);
		// Envoi le mail
		$sent = plugin_vfa::sendEmail($oMail);
		return $sent;
	}

	private function sendMailReginToValid($poRegistry)
	{
		$oMail = new plugin_mail();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));
		$createdUser = $poRegistry->oRegin->findCreatedUser();
		$oMail->addTo($createdUser->email);
//		$oMail->addCC($poRegistry->oUser->email);
		$oMail->setBcc(_root::getConfigVar('vfa-app.mail.from'));

		$tAwards = $poRegistry->oRegin->findAwards();

		// Sujet
		$oMail->setSubject('[PrixBD' . $tAwards[0]->year . '] Inscription d\'un lecteur à valider');
		// Prepare le body TXT
		$oViewTxt = new _view('default::mailReginToValidateTxt');
		$oViewTxt->oUser = $poRegistry->oUser;
		$oViewTxt->tAwards = $tAwards;
		$bodyTxt = $oViewTxt->show();
//		 _root::getLog()->log($bodyTxt);
		$oMail->setBody($bodyTxt);
		// Envoi le mail
		$sent = plugin_vfa::sendEmail($oMail);
		return $sent;
	}

}
