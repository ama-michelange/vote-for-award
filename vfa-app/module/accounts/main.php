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

		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$oUser = $this->save();
		if (null == $oUser) {
			$oUser = $oUserSession->getUser();
		} else {
			$tMessage = $oUser->getMessages();
		}


		$oView = new _view('accounts::edit');
		$oView->oUser = $oUser;

		$tGroups = array();
		if (false == $oUserSession->getReaderGroup()->isEmpty()) {
			$tGroups[] = $oUserSession->getReaderGroup();
		}
		if (false == $oUserSession->getBoardGroup()->isEmpty()) {
			$tGroups[] = $oUserSession->getBoardGroup();
		}
		$oView->tGroups = $tGroups;

		$tValidAwards = $oUserSession->getValidReaderAwards();
		foreach ($oUserSession->getValidBoardAwards() as $award) {
			$tValidAwards[] = $award;
		}
		$oView->tValidAwards = $tValidAwards;
		$oView->tMessage = $tMessage;

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$oView->tSelectedYears = plugin_vfa::buildSelectedBirthYears($oUser->birthyear);

		$this->oLayout->add('work', $oView);

		$scriptView = new _view('accounts::scriptEdit');
		$this->oLayout->add('script', $scriptView);
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
			case 'saveLogin':
				return $this->saveLogin($oUser);
			case 'saveEmail':
				return $this->saveEmail($oUser);
			default:
				return $this->saveDefault($oUser);
		}
	}

	private function saveDefault($poUser)
	{
		// Copie la saisie dans un enregistrement
		$tColumns = array('last_name', 'first_name', 'birthyear', 'gender');
		foreach ($tColumns as $sColumn) {
			$param = _root::getParam($sColumn, null);
			if (0 == strlen($param)) {
				$param = null;
			}
			if ((null == $param) && (null != $poUser->$sColumn)) {
				$poUser->$sColumn = null;
			} else {
				$poUser->$sColumn = $param;
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
		// Vérifie la saisie du mot de passe
		$canSave = plugin_vfa::checkSavePassword($poUser, _root::getParam('newPassword'), _root::getParam('confirmPassword'));
		if (false == $canSave) {
			$poUser->openPassword = true;
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
		if ((null != $newLogin) && (strlen($newLogin) > 0)) {
			if ($newLogin != $poUser->login) {
				$oUserDoublon = model_user::getInstance()->findByLogin($newLogin);
				if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
					$poUser->login = $newLogin;
					$canSave = true;
				} else {
					$poUser->setMessages(array('newLogin' => array('doublon')));
					$poUser->newLogin = $newLogin;
					$poUser->openLogin = true;
				}
			}
		} else {
			$poUser->setMessages(array('newLogin' => array('NotEmpty')));
			$poUser->openLogin = true;
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
			$scriptView->text = '<p>Votre nouvel identifiant <strong>&laquo; ' . $poUser->login .
				' &raquo</strong> est enregistré.</p><p><br>N\'oubliez pas de l\'utiliser lors de votre prochaine connexion.';
			$this->oLayout->add('script', $scriptView);
		}
		return $poUser;
	}

	private function saveEmail($poUser)
	{
		$newEmail = _root::getParam('newEmail');
		if ($newEmail != $poUser->email) {
			$oldEmail = $poUser->email;
			$poUser->email = $newEmail;

			if ($poUser->isValid()) {
				// Sauvegarde
				$invit = $this->saveInvitation($poUser);
				// Envoi l'email
				$sent = $this->sendMail($invit);

				// Ajuste la popup et l'invitation en base en fonction de l'envoi du mail
				$scriptView = new _view('accounts::scriptSaved');
				$scriptView->title = "Changer mon adresse Email";
				if ($sent) {
					// popup
					$scriptView->text = '<p>Un message vient d\'être envoyé à l\'adresse <strong>' . $poUser->email . '</strong>.</p>' .
						'<p>Le lien contenu dans ce message vous permet de confirmer le changement d\'adresse.</p>' .
						'<p><br>Consultez votre messagerie dans les 48 heures !<br/>';
				} else {
					// popup
					$scriptView->text = '<p>Impossible d\'envoyer un message à l\'adresse <strong>' . $poUser->email . '</strong>.</p>' .
						'<p><br>Retentez dans un moment !<br/>';
				}
				$this->oLayout->add('script', $scriptView);
			} else {
				$tMess = $poUser->getMessages();
				if (array_key_exists('email', $tMess)) {
					$tNewMess = array();
					$tNewMess['newEmail'] = $tMess['email'];
					$poUser->setMessages($tNewMess);
					$poUser->newEmail = $newEmail;
					$poUser->openEmail = true;
				}
			}
			$poUser->email = $oldEmail;
		}
		return $poUser;
	}


	private function buildInvitationKey($poUser)
	{
		$s = plugin_vfa::CATEGORY_VALIDATE . plugin_vfa::TYPE_EMAIL . $poUser->user_id . $poUser->email;
		$sSha1 = sha1($s);
		$key = $sSha1 . time();
		return $key;
	}

	private function saveInvitation($poUser)
	{
		$oInvit = new row_invitation();

		// Remplissage de l'invit
		$oInvit->created_user_id = $poUser->user_id;
		$oInvit->invitation_key = $this->buildInvitationKey($poUser);
		$oInvit->state = plugin_vfa::STATE_OPEN;
		$oInvit->category = plugin_vfa::CATEGORY_VALIDATE;
		$oInvit->type = plugin_vfa::TYPE_EMAIL;
		$oInvit->email = $poUser->email;
		$oInvit->created_date = plugin_vfa::dateTimeSgbd();

		// Sauve en base
		$oInvit->save();

		return $oInvit;
	}

	private function sendMail($poInvitation)
	{
		$oMail = new plugin_email();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));
		$oMail->addTo($poInvitation->email);
//		$createdUser = $poInvitation->findCreatedUser();
//		$oMail->addCC($createdUser->email);
//		$oMail->setBcc(_root::getConfigVar('vfa-app.mail.from'));
		// Sujet
		$oMail->setSubject(plugin_vfa::buildTitleInvitation($poInvitation, true));
		// Prepare le body TXT
		$oViewTxt = new _view('accounts::mailTxt');
		$oViewTxt->oInvit = $poInvitation;
		$bodyTxt = $oViewTxt->show();
		// _root::getLog()->log($bodyTxt);
		$oMail->setBody($bodyTxt);

		// Envoi le mail
		$sent = plugin_vfa::sendEmail($oMail);
		if ($sent) {
			$poInvitation->state = plugin_vfa::STATE_SENT;
			$poInvitation->update();
		} else {
			$poInvitation->state = plugin_vfa::STATE_NOT_SENT;
			$poInvitation->update();
		}
		return $sent;
	}

}

