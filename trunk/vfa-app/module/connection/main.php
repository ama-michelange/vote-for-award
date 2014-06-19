<?php

class module_connection extends abstract_module
{


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

	/**
	 * @return row_connection
	 */
	public static function doForgottenPassword()
	{
		$oConnection = new row_connection;
		if (_root::getRequest()->isPost()) {
			// Valide la saisie de l'email
			$oConnection->action = _root::getParam('action');
			$oConnection->myEmail = _root::getParam('myEmail');
			// Validation
			if ($oConnection->isValid()) {
				// Email existant ?
				$oUser = model_user::getInstance()->findByEmail($oConnection->myEmail);
				if (true == $oUser->isEmpty()) {
					$oConnection->openModalMessage = true;
					$oConnection->textModalMessage = 'Adresse Email inconnue !';
				} else {
					// TODO
				}
			} else {
				$oConnection->openModalForgottenPassword = true;
			}
		}
		return $oConnection;
	}

	private function saveInvitation($poRegistry)
	{
		$oInvit = new row_invitation();

		// Remplissage de l'invit
		$oInvit->created_user_id = _root::getAuth()->getUserSession()->getUser()->user_id;
		$oInvit->invitation_key = $this->buildInvitationKey($poRegistry);
		$oInvit->state = plugin_vfa::STATE_OPEN;
		$oInvit->category = $poRegistry->category;
		$oInvit->type = $poRegistry->type;
		$oInvit->email = $poRegistry->email;
		$oInvit->group_id = $poRegistry->group_id;
		if ($poRegistry->award_id) {
			$oInvit->awards_ids = strval($poRegistry->award_id);
		} else {
			$oInvit->awards_ids = implode(',', $poRegistry->awards_ids);
		}
		$oInvit->created_date = plugin_vfa::dateTimeSgbd();

		// Sauve en base
		$oInvit->save();

		return $oInvit;
	}

	private function sendMail($poInvitation)
	{
		$oMail = new plugin_mail();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));
		$oMail->addTo($poInvitation->email);
		$createdUser = $poInvitation->findCreatedUser();
		$oMail->addCC($createdUser->email);
		$oMail->setBcc(_root::getConfigVar('vfa-app.mail.from'));
		// Sujet
		$oMail->setSubject(plugin_vfa::buildTitleInvitation($poInvitation));
		// Prepare le body TXT
		$oViewTxt = new _view('invitations::mailTxt');
		$oViewTxt->oInvit = $poInvitation;
		$bodyTxt = $oViewTxt->show();
		// _root::getLog()->log($bodyTxt);
		$oMail->setBody($bodyTxt);
		// Prepare le body HTML
		$oViewTxt = new _view('invitations::mailHtml');
		$oViewTxt->oInvit = $poInvitation;
		$bodyHtml = $oViewTxt->show();
		// _root::getLog()->log($bodyHtml);
		$oMail->setBodyHtml($bodyHtml);

		// Envoi le mail
		try {
			if (_root::getConfigVar('vfa-app.mail.enabled')) {
				$sent = $oMail->send();
			} else {
				$sent = true;
			}
		} catch (Exception $e) {
			$sent = false;
		}
		if ($sent) {
			$poInvitation->state = plugin_vfa::STATE_SENT;
			$poInvitation->modified_date = plugin_vfa::dateTimeSgbd();
			$poInvitation->update();
		}
		return $sent;
	}

}
