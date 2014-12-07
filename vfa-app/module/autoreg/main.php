<?php

class module_autoreg extends abstract_module
{
	private $_oInvitation = null;

	public function before()
	{
		_root::startSession();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_base');
	}

	public function after()
	{
		$this->oLayout->show();
	}

	public function _index()
	{
		$oInvitation = model_invitation::getInstance()->findById(_root::getParam('id'));

		if (true == $this->isInvitationParamsValid($oInvitation)) {
			$this->dispatch($oInvitation);
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	private function dispatch($poInvitation)
	{
		switch ($poInvitation->category) {
			case plugin_vfa::CATEGORY_INVITATION:
				$this->doInvitation($poInvitation);
				break;
			case plugin_vfa::CATEGORY_VALIDATE:
				switch ($poInvitation->type) {
					case plugin_vfa::TYPE_EMAIL:
						$this->doValidateEmail($poInvitation);
						break;
				}
				break;
			case plugin_vfa::CATEGORY_MODIFY:
				switch ($poInvitation->type) {
					case plugin_vfa::TYPE_PASSWORD:
						$this->doLostPassword($poInvitation);
						break;
				}
				break;
		}
	}

	private function doLostPassword($poInvitation)
	{
		// Compare le datetime courant avec celle de création
		// Si dépassement de 48h, message d'erreur
		$dt = new plugin_datetime($poInvitation->modified_date, 'Y-m-d H:i:s');
		$dt->addDay(2);
		if (plugin_vfa::beforeDateTime(plugin_vfa::now(), $dt)) {
			// OK : modification du mot de passe
			// mais pour quel utilisateur ?
			$tUsers = model_user::getInstance()->findAllByEmail($poInvitation->email);
			$nbFound = count($tUsers);
			if (0 == $nbFound) {
				// Aucun utilisateur : KO
				$this->doInvalidAccess($poInvitation);
			} else {
				$this->doPostLostPassword($poInvitation, $tUsers);
			}
		} else {
			// KO
			$this->doInvalidAccess($poInvitation);
		}
	}

	private function doInvalidAccess($poInvitation)
	{
		$oConfirm = new row_confirm_invitation();
		$oConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
		$oConfirm->textInvit = 'Cet accès n\'est plus valide !';

		$oView = new _view('autoreg::ko');
		$oView->oConfirm = $oConfirm;

		$this->oLayout->add('work', $oView);

		// Supprime l'invit
		model_invitation::getInstance()->delete($poInvitation);
	}


	public function _toLostPassword()
	{
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('invitation_id'));
		$this->_oInvitation = $oInvitation;
		if (true == $this->isInvitationParamsValid($oInvitation)) {
			$this->doPostLostPassword($oInvitation);
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	private function doPostLostPassword($poInvitation, $ptUsers = null)
	{
		if (_root::getRequest()->isPost()) {
			$oConfirm = doVerifyPostLostPassword($poInvitation);
		} else {
			$oConfirm = $this->makeConfirmWithParams($poInvitation);
		}
//		$oConfirm = new row_confirm_invitation();
//		$oConfirm->invitation_id = $poInvitation->invitation_id;
//		$oConfirm->invitation_key = $poInvitation->invitation_key;
//		$oConfirm->email = $poInvitation->email;
		if (null == $ptUsers) {
			$oConfirm->tUsers = model_user::getInstance()->findAllByEmail($poInvitation->email);
		} else {
			$oConfirm->tUsers = $ptUsers;
		}

		$oView = new _view('autoreg::lostPassword');
		$oView->oConfirm = $oConfirm;
		$oView->tMessage = $oConfirm->getMessages();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}


	private function doVerifyPostLostPassword($poInvitation)
	{
		// Récupère les params
		$oConfirm = $this->makeConfirmWithParams($poInvitation);

		// Verifie le token
		if (_root::getParam('token')) {
			$oPluginXsrf = new plugin_xsrf();
			if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
				$oConfirm->setMessages(array('token' => $oPluginXsrf->getMessage()));
				return $oConfirm;
			}
		}
		return $oConfirm;
	}

	private function doValidateEmail($poInvitation)
	{
		// Compare le datetime courant avec celle de création
		// Si dépassement de 48h, message d'erreur
		$dt = new plugin_datetime($poInvitation->created_date, 'Y-m-d H:i:s');
		$dt->addDay(2);
		if (plugin_vfa::beforeDateTime(plugin_vfa::now(), $dt)) {
			// OK : changement d'adresse Email
			$oConfirm = new row_confirm_invitation();
			$oConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
			$oConfirm->textInvit = 'Votre nouvelle adresse <strong>' . $poInvitation->email . '</strong> est validée.';

			$oView = new _view('autoreg::ok');
			$oView->oConfirm = $oConfirm;

			$this->oLayout->add('work', $oView);

			// MAJ Email Utilisateur
			$oUser = model_user::getInstance()->findById($poInvitation->created_user_id);
			if (false == $oUser->isEmpty()) {
				$oUser->email = $poInvitation->email;
				model_user::getInstance()->update($oUser);
				if (_root::getAuth()->isConnected()) {
					// Met à jour la session
					$oUserSession = _root::getAuth()->getUserSession();
					$oUserSession->setUser($oUser);
					_root::getAuth()->setUserSession($oUserSession);
				}
			}
		} else {
			// KO
			$oConfirm = new row_confirm_invitation();
			$oConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
			$oConfirm->textInvit = 'Cet accès n\'est plus valide ! Recommencez votre changement d\'adresse et validez-le plus rapidement.';

			$oView = new _view('autoreg::ko');
			$oView->oConfirm = $oConfirm;

			$this->oLayout->add('work', $oView);

		}
		// Supprime l'invit
		model_invitation::getInstance()->delete($poInvitation);
	}

	private function doInvitation($poInvitation)
	{
		if (true == $this->isValidInvitation($poInvitation)) {
			$oConfirm = new row_confirm_invitation();
			$oConfirm->invitation_id = _root::getParam('id');
			$oConfirm->invitation_key = _root::getParam('key');
			$this->makeTextInvitation($poInvitation, $oConfirm);

			$oView = new _view('autoreg::invitation');
			$oView->oConfirm = $oConfirm;

			$this->oLayout->add('work', $oView);
		}
	}

	private function isValidInvitation($poInvitation)
	{
		$ok = true;
		if (plugin_vfa::STATE_ACCEPTED == $poInvitation->state) {
			$textInvit = 'Cet accès n\'est plus valide ! L\'invitation a déjà été acceptée.';
			$ok = false;
		} elseif (false == $this->isValidGroupInvitation($poInvitation)) {
			$textInvit = 'Cet accès n\'est pas valide ! Le groupe de l\'invitation n\'existe pas.';
			$ok = false;
		} elseif (false == $this->isValidAwardInvitation($poInvitation)) {
			$textInvit = 'Cet accès n\'est pas valide !<p>Le prix de l\'invitation est terminé.</p>';
			$ok = false;
		}

		if (false == $ok) {
			$oConfirm = new row_confirm_invitation();
			$oConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
			$oConfirm->textInvit = $textInvit;

			$oView = new _view('autoreg::ko');
			$oView->oConfirm = $oConfirm;

			$this->oLayout->add('work', $oView);
		}
		return $ok;
	}

	private function isValidGroupInvitation($poInvitation)
	{
		$oGroup = $poInvitation->findGroup();
		if ($oGroup->isEmpty()) {
			// Supprime l'invit
			model_invitation::getInstance()->delete($poInvitation);
			return false;
		}
		return true;
	}


	private function isValidAwardInvitation($poInvitation)
	{
		$tAwards = $poInvitation->findAwards();
		foreach ($tAwards as $oAward) {
			$dt = new plugin_date($oAward->end_date, 'Y-m-d');
			if (plugin_vfa::afterDate(plugin_vfa::today(), $dt)) {
				// Supprime l'invit
				model_invitation::getInstance()->delete($poInvitation);
				return false;
			}
		}
		return true;
	}

	public function _invalid()
	{
		$oView = new _view('autoreg::invalid');
		$this->oLayout->add('work', $oView);
	}

	public function _toReject()
	{
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('invitation_id'));

		if ((_root::getRequest()->isPost()) && (true == $this->isInvitationParamsValid($oInvitation))) {
			$oInvitation->state = plugin_vfa::STATE_REJECTED;
			$oInvitation->modified_date = plugin_vfa::dateTimeSgbd();
			$oInvitation->update();

			$oView = new _view('autoreg::rejected');
			$this->oLayout->add('work', $oView);
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	public function _toConfirm()
	{
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('invitation_id'));
		$this->_oInvitation = $oInvitation;

		if (true == $this->isInvitationParamsValid($oInvitation)) {
			if (_root::getRequest()->isPost()) {
				$this->doPostConfirm($oInvitation);
			}
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	private function doPostConfirm($poInvitation)
	{
		$oConfirm = $this->doVerifyPostConfirm($poInvitation);
		if ($oConfirm->completed) {
			// Affiche la vue de fin
			if ($oConfirm->completedRegistration) {
				$oConfirm->textInvit =
					'L\'inscription est validée et vous êtes enregistré avec l\'identifiant <strong>' . $oConfirm->oUser->login . '</strong>';
			} else {
				$oConfirm->textInvit = 'L\'inscription est validée';
			}
			$oConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
			$oView = new _view('autoreg::ok');
			$oView->oConfirm = $oConfirm;
			$this->oLayout->add('work', $oView);
		} else {
			// Affiche la vue de confirmation
			$oView = new _view('autoreg::confirm');
			$oView->oConfirm = $oConfirm;
			$oView->tMessage = $oConfirm->getMessages();
			$oView->tSelectedYears = plugin_vfa::buildSelectedBirthYears($oConfirm->birthyear);

			$oPluginXsrf = new plugin_xsrf();
			$oView->token = $oPluginXsrf->getToken();

			$oView->oViewFormAccount = new _view('autoreg::formAccount');
			$oView->oViewFormAccount->oConfirm = $oConfirm;
			$oView->oViewFormAccount->tMessage = $oConfirm->getMessages();
			$oView->oViewFormAccount->tSelectedYears = plugin_vfa::buildSelectedBirthYears($oConfirm->birthyear);
			$oView->oViewFormAccount->token = $oView->token;

			$oView->oViewFormIdent = new _view('autoreg::formIdent');
			$oView->oViewFormIdent->oConfirm = $oConfirm;
			$oView->oViewFormIdent->tMessage = $oConfirm->getMessages();
			$oView->oViewFormIdent->token = $oView->token;

			// Force l'email déjà connu s'il existe
			if (null == $oConfirm->oConnection->myEmail) {
				$oConfirm->oConnection->myEmail = $oConfirm->email;
			}
			$oView->oViewForgottenPassword = new _view('connection::formForgottenPassword');
			$oView->oViewForgottenPassword->tHidden = array('invitation_id' => $poInvitation->invitation_id,
				'invitation_key' => $poInvitation->invitation_key);
			$oView->oViewForgottenPassword->oConnection = $oConfirm->oConnection;
			$oView->oViewForgottenPassword->tMessage = $oConfirm->oConnection->getMessages();
			$oView->oViewForgottenPassword->token = $oView->token;

			$oView->oViewModalMessage = new _view('connection::modalMessage');
			$oView->oViewModalMessage->oConnection = $oConfirm->oConnection;
			$oView->oViewModalMessage->tMessage = $oConfirm->oConnection->getMessages();

			$this->oLayout->add('work', $oView);

			// Gestion de l'affichage du bon panel
			$scriptView = new _view('autoreg::scriptConfirm');
			$scriptView->oConnection = $oConfirm->oConnection;
			$this->oLayout->add('script', $scriptView);
			// Gestion de l'affichage de la boite modale
			$scriptView = new _view('connection::scriptForgottenPassword');
			$scriptView->oConnection = $oConfirm->oConnection;
			$this->oLayout->add('script', $scriptView);
		}
	}

	private function doVerifyPostConfirm($poInvitation)
	{
		// Récupère les params
		$oConfirm = $this->makeConfirmWithParams($poInvitation);

		// Verifie le token
		if (_root::getParam('token')) {
			$oPluginXsrf = new plugin_xsrf();
			if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
				$oConfirm->setMessages(array('token' => $oPluginXsrf->getMessage()));
				if (!$oConfirm->oConnection) {
					$oConfirm->oConnection = new row_connection();
				}
				return $oConfirm;
			}
		}
		// Reconstruit le texte
		$this->makeTextConfirmation($poInvitation, $oConfirm);
		// Force les fermetures des panels
		$oConfirm->openAccount = false;
		$oConfirm->openLogin = false;
		$oConfirm->openPassword = false;
		// Dispatch
		switch (_root::getParam('action')) {
			case 'toConfirm':
				$oConfirm->email = $poInvitation->email;
				$oConfirm->login = $poInvitation->email;
				break;
			case 'toIdentify':
				$this->doVerifyToIdentify($poInvitation, $oConfirm);
				break;
			case 'toRegistry':
				$this->doVerifyToRegistry($poInvitation, $oConfirm);
				break;
			case 'submitForgottenPassword':
				$this->doVerifyForgottenPassword($oConfirm);
				break;
			default:
				_root::redirect('autoreg::invalid');
		}
		if (!$oConfirm->oConnection) {
			$oConfirm->oConnection = new row_connection();
		}
		return $oConfirm;
	}

	private function makeConfirmWithParams($poInvitation)
	{
		$oConfirm = new row_confirm_invitation();
		$oConfirm->action = _root::getParam('action');

		// Récupère les params cachés nécessaire au Token (entre autre)
		$oConfirm->invitation_id = _root::getParam('invitation_id');
		$oConfirm->invitation_key = _root::getParam('invitation_key');

		// Copie la saisie Identification
		$oConfirm->cf_login = _root::getParam('cf_login');
		$oConfirm->cf_password = _root::getParam('cf_password');

		// Copie la saisie Compte Utilisateur
		$oConfirm->login = _root::getParam('login');
		$oConfirm->email = _root::getParam('email', $poInvitation->email);
		$oConfirm->newPassword = _root::getParam('newPassword');
		$oConfirm->confirmPassword = _root::getParam('confirmPassword');
		$oConfirm->last_name = _root::getParam('last_name');
		$oConfirm->first_name = _root::getParam('first_name');
		$oConfirm->birthyear = _root::getParam('birthyear');
		$oConfirm->gender = _root::getParam('gender');
		return $oConfirm;
	}

	private function doVerifyForgottenPassword($poConfirm)
	{
		// Force l'ouverture du panel de Mot de passe
		$poConfirm->openPassword = true;

		$poConfirm->oConnection = module_connection::doForgottenPassword();

		if (true == $poConfirm->oConnection->mailSent) {
			$poConfirm->openPassword = false;
		}
	}

	private function doVerifyToIdentify($poInvitation, $poConfirm)
	{
		// Force l'ouverture du panel d'identification
		$poConfirm->openLogin = true;
		// Validation
		if ($poConfirm->isValid()) {
			// Recup params
			$sLogin = $poConfirm->cf_login;
			$sPass = plugin_vfa::cryptPassword($poConfirm->cf_password);
			// Recherche et vérifie "login/pass" dans la base
			$oUser = model_user::getInstance()->findByLoginAndCheckPass($sLogin, $sPass);
			// Connexion si utilisateur autorisé
			if (null != $oUser) {
				if ($this->updateUser($oUser, $poInvitation, $poConfirm)) {
					if ($this->acceptInvitation($poInvitation)) {
						$poConfirm->completed = true;
						$poConfirm->completedIdentify = true;
						$oUserSession = model_user_session::getInstance()->create($oUser);
						_root::getAuth()->connect($oUserSession);
					}
				}
			} else {
				$poConfirm->setMessages(array('cf_login' => array('badIdentity'), 'cf_password' => array('badIdentity')));
			}
		}
	}

	private function doVerifyToRegistry($poInvitation, $poConfirm)
	{
		// Force l'ouverture du panel d'enregistrement du compte
		$poConfirm->openAccount = true;

		// Validation
		if ($poConfirm->isValid()) {
			// Doublon ?
			$oUserDoublon = model_user::getInstance()->findByLogin($poConfirm->login);
			if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
				if (plugin_vfa::checkPassword($poConfirm, _root::getParam('newPassword'), _root::getParam('confirmPassword'))) {
					if ($this->createUser($poInvitation, $poConfirm)) {
						if ($this->acceptInvitation($poInvitation)) {
							$poConfirm->completed = true;
							$poConfirm->completedRegistration = true;
							$oUserSession = model_user_session::getInstance()->create($poConfirm->oUser);
							_root::getAuth()->connect($oUserSession);
						}
					}
				}
			} else {
				$poConfirm->setMessages(array('login' => array('doublon')));
			}
		} else {
			if (!$poConfirm->login) {
				$poConfirm->login = $poInvitation->email;
			}
		}
	}

	private function createUser($poInvitation, $poConfirm)
	{
		$oUser = new row_user();
		$oUser->created_date = plugin_vfa::dateTimeSgbd();
		$oUser->modified_date = plugin_vfa::dateTimeSgbd();

		$oUser->login = $poConfirm->login;
		$oUser->email = $poConfirm->email;
		$oUser->password = $poConfirm->password;
		$oUser->last_name = $poConfirm->last_name;
		$oUser->first_name = $poConfirm->first_name;
		$oUser->birthyear = $poConfirm->birthyear;
		$oUser->gender = $poConfirm->gender;

		$tIdGroups = array($poInvitation->group_id);
		$tIdAwards = explode(',', $poInvitation->awards_ids);

		$tIdRoles = array();
		$oRole = model_role::getInstance()->findByName($poInvitation->type);
		$tIdRoles[] = $oRole->getId();
		if ($poInvitation->type == plugin_vfa::ROLE_RESPONSIBLE) {
			$oRole = model_role::getInstance()->findByName(plugin_vfa::ROLE_READER);
			$tIdRoles[] = $oRole->getId();
		}

		$oUser->save();
		model_user::getInstance()->saveUserRoles($oUser->getId(), $tIdRoles);
		model_user::getInstance()->saveUserGroups($oUser->getId(), $tIdGroups);
		model_user::getInstance()->saveUserAwards($oUser->getId(), $tIdAwards);

		$poConfirm->oUser = $oUser;
		return true;
	}

	private function updateUser($poUser, $poInvitation, $poConfirm)
	{
		$poUser->modified_date = plugin_vfa::dateTimeSgbd();

		$tIdGroups = array($poInvitation->group_id);
		$tIdAwards = explode(',', $poInvitation->awards_ids);

		$tIdRoles = array();
		$oRole = model_role::getInstance()->findByName($poInvitation->type);
		$tIdRoles[] = $oRole->getId();
		if ($poInvitation->type == plugin_vfa::ROLE_RESPONSIBLE) {
			$oRole = model_role::getInstance()->findByName(plugin_vfa::ROLE_READER);
			$tIdRoles[] = $oRole->getId();
		}

		$poUser->save();
		model_user::getInstance()->mergeUserRoles($poUser, $tIdRoles);
		model_user::getInstance()->mergeUserGroups($poUser, $tIdGroups);
		model_user::getInstance()->mergeUserAwards($poUser, $tIdAwards);

		$poConfirm->oUser = $poUser;
		return true;
	}

	private function acceptInvitation($poInvitation)
	{
		$poInvitation->state = plugin_vfa::STATE_ACCEPTED;
		$poInvitation->modified_date = plugin_vfa::dateTimeSgbd();

		// Sauve en base
		$poInvitation->save();

		return true;
	}

	private function makeTextInvitation($poInvitation, $poConfirm)
	{
		$poConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
		$poConfirm->textInvit = plugin_vfa::buildTextInvitation($poInvitation, true);
	}

	private function makeTextConfirmation($poInvitation, $poConfirm)
	{
		$tAwards = $poInvitation->findAwards();
		$oGroup = $poInvitation->findGroup();

		$tPrix = array();
		foreach ($tAwards as $oAward) {
			$tPrix[] = $oAward->toString();
		}
		natsort($tPrix);

		$textPrix = '';
		$i = 0;
		foreach ($tPrix as $prix) {
			if ($i > 0) {
				$textPrix .= ', ';
			}
			$textPrix .= $prix;
			$i++;
		}

		$tInscription = array();
		$tInscription['Adresse'] = $poInvitation->email;

		$tInscription['Groupe'] = $oGroup->group_name;

		switch ($poInvitation->type) {
			case plugin_vfa::TYPE_BOARD:
				$tInscription['Rôle'] = 'Membre du comité de sélection';
				$poConfirm->titleInvit = 'Inscription pour voter avec le Comité de sélection';
				break;
			case plugin_vfa::TYPE_READER:
				$tInscription['Rôle'] = 'Lecteur';
				$poConfirm->titleInvit = 'Inscription pour voter au Prix de la BD INTER CE';
				break;
			case plugin_vfa::TYPE_RESPONSIBLE:
				$tInscription['Rôle'] = 'Correspondant, Lecteur';
				$poConfirm->titleInvit = 'Inscription pour devenir Correspondant de votre groupe et voter au Prix de la BD INTER CE';
				break;
		}

		$tInscription['Prix'] = $textPrix;
		$poConfirm->tInscription = $tInscription;
	}

	private function isInvitationParamsValid($poInvitation)
	{
		$id = _root::getParam('id', _root::getParam('invitation_id'));
		$key = _root::getParam('key', _root::getParam('invitation_key'));
		if ((null == $id) || (null == $key) || (null == $poInvitation)) {
			return false;
		}
		if ((null != $poInvitation) && ($poInvitation->isEmpty())) {
			return false;
		}
		if ($id != $poInvitation->invitation_id) {
			return false;
		}
		if ($key != $poInvitation->invitation_key) {
			return false;
		}
		return true;
	}
}
