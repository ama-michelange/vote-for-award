<?php

class module_autoreg extends abstract_module
{

	public function before()
	{
		_root::startSession();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_base');
		// $this->oLayout->addModule('bsnavbar','bsnavbar::index');
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
		$oConfirm = new row_confirm_invitation();
		$oConfirm->invitation_id = _root::getParam('id');
		$oConfirm->invitation_key = _root::getParam('key');
		$this->makeTextInvitation($poInvitation, $oConfirm);

		$oView = new _view('autoreg::index');
		$oView->oConfirm = $oConfirm;

		$this->oLayout->add('work', $oView);
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
			} /*
			   * else { $this->doGet($oInvitation); }
			   */
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	public function after()
	{
		$this->oLayout->show();
	}

	private function doGet($poInvitation)
	{
		$oConfirm = new row_confirm_invitation();
		$oConfirm->invitation_id = _root::getParam('id');
		$oConfirm->invitation_key = _root::getParam('key');
		$this->makeTextInvitation($poInvitation, $oConfirm);

		$oView = new _view('autoreg::index');
		$oView->oConfirm = $oConfirm;
		$oView->tMessage = "";
		$oView->tSelectedYears = plugin_vfa::buildSelectedBirthYears($oConfirm->birthyear);

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	private function doPost($poInvitation)
	{
		$oConfirm = $this->doVerifyPost($poInvitation);

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
	}

	private function doVerifyPost($poInvitation)
	{
		$oConfirm = new row_confirm_invitation();
		// Verifie le token
		if (_root::getParam('token')) {
			$oPluginXsrf = new plugin_xsrf();
			if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
				$oConfirm->setMessages(array('token' => $oPluginXsrf->getMessage()));
				return $oConfirm;
			}
		}

		// Récupère les params cachés et reconstruit le texte
		$oConfirm->invitation_id = _root::getParam('invitation_id');
		$oConfirm->invitation_key = _root::getParam('invitation_key');
		$this->makeTextConfirmation($poInvitation, $oConfirm);

		switch (_root::getParam('action')) {
			case 'toConfirm':
				// $this->doVerifyToRegistry($poInvitation, $oConfirm);
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

	private function doVerifyToIdentify($poInvitation, $poConfirm)
	{
		// Copie la saisie dans un enregistrement
		$poConfirm->action = _root::getParam('action', null);
		$poConfirm->cf_login = _root::getParam('cf_login', null);
		$poConfirm->cf_password = _root::getParam('cf_password', null);

		if ($poConfirm->isValid()) {
			$poConfirm->validation = true;
		}
		// var_dump($poConfirm);
	}

	private function doVerifyToRegistry($poInvitation, $poConfirm)
	{
		// Copie la saisie dans un enregistrement
		$poConfirm->action = _root::getParam('action', null);
		$poConfirm->login = _root::getParam('login', null);
		$poConfirm->email = _root::getParam('email', null);
		$poConfirm->email_bis = _root::getParam('email_bis', null);
		$poConfirm->password = _root::getParam('password', null);
		$poConfirm->password_bis = _root::getParam('password_bis', null);
		$poConfirm->last_name = _root::getParam('last_name', null);
		$poConfirm->first_name = _root::getParam('first_name', null);
		$poConfirm->birthyear = _root::getParam('birthyear', null);
		$poConfirm->gender = _root::getParam('gender', null);

		if ($poConfirm->isValid()) {
			// Doublon ?
			$oUserDoublon = model_user::getInstance()->findByLogin($poConfirm->login);
			if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
				$poConfirm->validation = true;
			} else {
				$poConfirm->setMessages(array('login' => array('doublon')));
			}
		}
		// var_dump($poConfirm);
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
		$tInscription['Groupe'] = $oGroup->group_name;
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
