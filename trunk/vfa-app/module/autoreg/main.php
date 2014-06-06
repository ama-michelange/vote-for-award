<?php

class module_autoreg extends abstract_module
{

	public function before()
	{
		_root::startSession();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_base');
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
			case plugin_vfa::CATEGORY_CHANGE:
				switch ($poInvitation->type) {
					case plugin_vfa::TYPE_EMAIL:
						$this->doChangeEmail($poInvitation);
						break;
				}
				break;
		}
	}

	private function doChangeEmail($poInvitation)
	{
		// Compare le datetime courant avec celle de création
		// Si dépassement de 48h, message d'erreur
		$dt = new plugin_datetime($poInvitation->created_date, 'Y-m-d H:i:s');
		$dt->addDay(2);
		if (plugin_vfa::beforeDateTime(plugin_vfa::todayDateTime(), $dt)) {
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
			$oConfirm->textInvit = 'Cet accès n\'est plus valide ! Recommencez votre changement d\'adresse et validez le plus rapidement.';

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

			$oView = new _view('autoreg::index');
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
		}


		// TODO Vérifier la validité des prix et du groupe


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

		if (true == $this->isInvitationParamsValid($oInvitation)) {
			if (_root::getRequest()->isPost()) {
				$this->doPost($oInvitation);
			}
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	public function after()
	{
		$this->oLayout->show();
	}

//	private function doGet($poInvitation)
//	{
//		$oConfirm = new row_confirm_invitation();
//		$oConfirm->invitation_id = _root::getParam('id');
//		$oConfirm->invitation_key = _root::getParam('key');
//		$this->makeTextInvitation($poInvitation, $oConfirm);
//
//		$oView = new _view('autoreg::index');
//		$oView->oConfirm = $oConfirm;
//		$oView->tMessage = "";
//		$oView->tSelectedYears = plugin_vfa::buildSelectedBirthYears($oConfirm->birthyear);
//
//		$oPluginXsrf = new plugin_xsrf();
//		$oView->token = $oPluginXsrf->getToken();
//
//		$this->oLayout->add('work', $oView);
//	}

	private function doPost($poInvitation)
	{
		$oConfirm = $this->doVerifyPost($poInvitation);
		if ($oConfirm->validate) {
			$oConfirm->titleInvit = plugin_vfa::buildTitleInvitation($poInvitation);
			$oConfirm->textInvit =
				'L\'inscription est terminée et vous êtes enregistré avec l\'identifiant <strong>' . $oConfirm->oUser->login . '</strong>.';

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

			$this->oLayout->add('work', $oView);

			// Prepare l'affichage du bon panel
			$scriptView = new _view('autoreg::scriptConfirm');
			$this->oLayout->add('script', $scriptView);
		}
	}

	private function doVerifyPost($poInvitation)
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
		// Reconstruit le texte
		$this->makeTextConfirmation($poInvitation, $oConfirm);
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
			default:
				_root::redirect('autoreg::invalid');
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

	private function doVerifyToIdentify($poInvitation, $poConfirm)
	{
		// Force l'ouverture du panel d'identification
		$poConfirm->openLogin = true;

		// Validation
		if ($poConfirm->isValid()) {
			$poConfirm->validate = true;
		}
		// var_dump($poConfirm);
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
				if ($this->checkPassword($poConfirm)) {
					if ($this->createUser($poInvitation, $poConfirm)) {
						if ($this->acceptInvitation($poInvitation)) {
							$poConfirm->validate = true;
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

	private function checkPassword($poConfirm)
	{
		$canSave = false;
		$newPassword = _root::getParam('newPassword');
		$confirmPassword = _root::getParam('confirmPassword');
		if (null != $newPassword) {
			$lenPassword = strlen($newPassword);
			if (($lenPassword < 7) OR ($lenPassword > 30)) {
				$poConfirm->setMessages(array('newPassword' => array('badSize')));
			} else {
				if ($newPassword === $confirmPassword) {
					$poConfirm->password = sha1($newPassword);
					$canSave = true;
				} else {
					$poConfirm->setMessages(array('newPassword' => array('isEqualKO'), 'confirmPassword' => array('isEqualKO')));
				}
			}
		}
		return $canSave;
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
		switch ($poInvitation->type) {
			case plugin_vfa::ROLE_RESPONSIBLE:
				$oRole = model_role::getInstance()->findByName(plugin_vfa::ROLE_READER);
				$tIdRoles[] = $oRole->getId();
			default:
				$oRole = model_role::getInstance()->findByName($poInvitation->type);
				$tIdRoles[] = $oRole->getId();
		}

		$oUser->save();
		model_user::getInstance()->saveUserRoles($oUser->user_id, $tIdRoles);
		model_user::getInstance()->saveUserGroups($oUser->user_id, $tIdGroups);
		model_user::getInstance()->saveUserAwards($oUser->user_id, $tIdAwards);

		$poConfirm->oUser = $oUser;
//		var_dump($oUser);
//		var_dump($poInvitation);
//		var_dump($tIdGroups);
//		var_dump($tIdAwards);
//		var_dump($tIdRoles);
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
				$poConfirm->titleInvit = 'Inscription pour voter au Prix de la Bande Dessinée';
				break;
			case plugin_vfa::TYPE_RESPONSIBLE:
				$tInscription['Rôle'] = 'Correspondant, Lecteur';
				$poConfirm->titleInvit = 'Inscription pour devenir Correspondant de votre groupe et voter au  Prix de la Bande Dessinée';
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
