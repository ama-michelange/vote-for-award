<?php

class module_accounts extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_bar');
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
//		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));

		$this->oLayout->show();
	}

//	private function buildContextBar()
//	{
//		$navBar = plugin_BsHtml::buildNavBar();
//		$navBar->setTitle('Mon compte');
//		$navBar->addChild(new BarButtons('left'));
//
//		$bar = $navBar->getChild('left');
//		$bar->addChild(plugin_BsHtml::buildButtonItem('Liste par groupe', new NavLink('accounts', 'listByGroup'), 'glyphicon-list'));
//		plugin_BsContextBar::buildDefaultContextBar($navBar);
//		return $navBar;
//	}

	public function _index()
	{
		// Force l'action pour n'avoir qu'un seul test dans le menu contextuel
		_root::getRequest()->setAction('update');
		$this->_update();
	}


	public function _update()
	{
		$tMessage = null;
//		$oUserModel = new model_user();

		$oUser = $this->save();
		if (null == $oUser) {
			$oUser = _root::getAuth()->getUserSession()->getUser();
		} else {
			$tMessage = $oUser->getMessages();
		}


		$oView = new _view('accounts::edit');
		$oView->oUser = $oUser;
		$oView->tMessage = $tMessage;

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$alias = $oUser->alias;
		$email = $oUser->email;
		$oView->changeLogin = false;
		if (isset($alias) && isset($email)) {
			$oView->changeLogin = true;
		}

		$this->oLayout->add('work', $oView);
	}


	public function save()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oUser = new row_user();
			$oUser->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oUser;
		}

		$iId = _root::getParam('user_id');
		if ($iId == null) {
			return null;
		} else {
			$oUser = model_user::getInstance()->findById($iId);
		}
		$oUser->modified_date = plugin_vfa::dateTimeSgbd();

		switch (_root::getParam('submit')) {
			case 'savePassword':
				return $this->savePassword($oUser);
				break;
			case 'saveLogin':
				return $this->saveLogin($oUser);
				break;
			default:
				return $this->saveDefault($oUser);
		}
	}

	private function saveDefault($poUser)
	{
		// Copie la saisie dans un enregistrement
		$tColumns = array('alias', 'last_name', 'first_name', 'birthyear', 'gender');
		foreach ($tColumns as $sColumn) {
			if ((_root::getParam($sColumn, null) == null) && (null != $poUser->$sColumn)) {
				$poUser->$sColumn = null;
			} else {
				$poUser->$sColumn = _root::getParam($sColumn, null);
			}
		}

		if ($poUser->isValid()) {
			// Sauvegarde
			$poUser->save();
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($poUser);
			_root::getAuth()->setUserSession($oUserSession);
			// Prepare et Affiche la popup
			$scriptView = new _view('accounts::scriptSaved');
			$scriptView->text = 'Les données de votre compte sont enregistrées';
			$this->oLayout->add('script', $scriptView);
		}
		return $poUser;
	}

	private function savePassword($poUser)
	{
		// Gère la saisie du mot de passe
		$canSave = false;
		$newPassword = _root::getParam('newPassword');
		$confirmPassword = _root::getParam('confirmPassword');
		if (null != $newPassword) {
			$lenPassword = strlen($newPassword);
			if (($lenPassword < 7) OR ($lenPassword > 30)) {
				$poUser->openPassword = true;
				$poUser->newPassword = $newPassword;
				$poUser->confirmPassword = $confirmPassword;
				$poUser->setMessages(array('newPassword' => array('badSize')));
			} else {
				if ($newPassword === $confirmPassword) {
					$poUser->password = sha1($newPassword);
					$canSave = true;
				} else {
					$poUser->openPassword = true;
					$poUser->newPassword = $newPassword;
					$poUser->confirmPassword = $confirmPassword;
					$poUser->setMessages(array('newPassword' => array('isEqualKO'), 'confirmPassword' => array('isEqualKO')));
				}
			}
		}

		if (true == $canSave && $poUser->isValid()) {
			// Sauvegarde
			$poUser->save();
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($poUser);
			_root::getAuth()->setUserSession($oUserSession);
			// Prepare et Affiche la popup
			$scriptView = new _view('accounts::scriptSaved');
			$scriptView->text = 'Votre nouveau mot de passe est enregistré';
			$this->oLayout->add('script', $scriptView);
		}
		return $poUser;
	}

	private function saveLogin($poUser)
	{
		$canSave = false;
		$newLogin = _root::getParam('newLogin');
		if ($newLogin != $poUser->login) {
			$oUserDoublon = model_user::getInstance()->findByLogin($newLogin);
			if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
				$poUser->login = $newLogin;
				$canSave = true;
			} else {
				$poUser->setMessages(array('newLogin' => array('doublon')));
				$poUser->openLogin = true;
			}
		}

		if (true == $canSave && $poUser->isValid()) {
			// Sauvegarde
			$poUser->save();
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($poUser);
			_root::getAuth()->setUserSession($oUserSession);
			// Prepare et Affiche la popup
			$scriptView = new _view('accounts::scriptSaved');
			$scriptView->text = '<p>Votre nouvel identifiant <strong>' . $poUser->login .
				'</strong> est enregistré.</p><p><br>N\'oubliez de l\'utiliser lors de votre prochaine connexion.';
			$this->oLayout->add('script', $scriptView);
		}
		return $poUser;
	}


}

