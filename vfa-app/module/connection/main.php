<?php

class module_connection extends abstract_module
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

	public function after()
	{
		$this->oLayout->show();
	}

	public function _logout()
	{
		_root::getAuth()->logout();
	}

	public function _forgotten()
	{
		$oConnection = $this->doForgottenPassword();

		if ($oConnection->openModalMessage) {
			$oConnection->redirectOnClose = true;
		}

		$oView = new _view('connection::forgottenPassword');
		$oView->oConnection = $oConnection;
		$oView->tMessage = $oConnection->getMessages();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$oView->oViewForgottenPassword = new _view('connection::formForgottenPassword');
		$oView->oViewForgottenPassword->oConnection = $oConnection;
		$oView->oViewForgottenPassword->tMessage = $oConnection->getMessages();
		$oView->oViewForgottenPassword->token = $oView->token;


		$oView->oViewModalMessage = new _view('connection::modalMessage');
		$oView->oViewModalMessage->oConnection = $oConnection;
		$oView->oViewModalMessage->tMessage = $oConnection->getMessages();

		$this->oLayout->add('work', $oView);

		// Gestion de l'affichage de la boite modale
		$scriptView = new _view('connection::scriptForgottenPassword');
		$scriptView->oConnection = $oConnection;
		$this->oLayout->add('script', $scriptView);

	}

	private function doLogin()
	{
		// Recup params
		$sLogin = _root::getParam('login');
		$sPass = plugin_vfa::cryptPassword(_root::getParam('password'));
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
			$oConnection->link_id = _root::getParam('regin_id');
			// Validation
			if ($oConnection->isValid()) {
				// Email existant ?
				$oUser = model_user::getInstance()->findByEmail($oConnection->myEmail);
				if (true == $oUser->isEmpty()) {
					$oConnection->setMessages(array('myEmail' => array('unknown')));
				} else {
					$oInvit = self::saveInvitationModifyPassword($oConnection);
					if (self::sendMail($oInvit)) {
						$oConnection->mailSent = true;
						$oConnection->linkWhenClose = 'default::index';
						$oConnection->textModalMessage = 'Vous allez bientôt recevoir un message à l\'adresse "' . $oConnection->myEmail .
							'" pour saisir un nouveau mot de passe.';
					} else {
						$oConnection->textModalMessage =
							'<p>Impossible d\'envoyer un message à l\'adresse <strong>' . $oConnection->myEmail . '</strong>.</p>' .
							'<p><br>Retentez dans un moment !<br/>';
						$oInvit->delete();
					}
					$oConnection->openModalMessage = true;
				}
			}
		}
		return $oConnection;
	}

	private static function saveInvitationModifyPassword($poConnection)
	{
		$oInvit = model_invitation::getInstance()->findByEmailModifyPassword($poConnection->myEmail);
		if ($oInvit->isEmpty()) {
			$oInvit = new row_invitation();

			// Remplissage de l'invit
			$oInvit->created_user_id = 0;
			$oInvit->state = plugin_vfa::STATE_OPEN;
			$oInvit->category = plugin_vfa::CATEGORY_MODIFY;
			$oInvit->type = plugin_vfa::TYPE_PASSWORD;
			$oInvit->email = $poConnection->myEmail;
			$oInvit->created_date = plugin_vfa::dateTimeSgbd();
			$oInvit->ip = $_SERVER['REMOTE_ADDR'];
			$oInvit->invitation_key = self::buildKeyModifyPassword($oInvit);
			$oInvit->link_id = $poConnection->link_id;
		} else {
			$oInvit->state = plugin_vfa::STATE_OPEN;
			$oInvit->ip = $_SERVER['REMOTE_ADDR'];
			$oInvit->modified_date = plugin_vfa::dateTimeSgbd();
		}
		// Sauve en base
		$oInvit->save();

		return $oInvit;
	}

	private static function buildKeyModifyPassword($poInvitation)
	{
		$s = $poInvitation->category . $poInvitation->type . $poInvitation->email;
		$sSha1 = sha1($s);
		$key = $sSha1 . time();
		return $key;
	}

	private static function sendMail($poInvitation)
	{
		$oMail = new plugin_email();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));
		$oMail->addTo($poInvitation->email);
//		$createdUser = $poInvitation->findCreatedUser();
//		$oMail->addCC($createdUser->email);
		$oMail->addBCC(_root::getConfigVar('vfa-app.mail.from'));
		// Sujet
		$oMail->setSubject(plugin_vfa::buildTitleInvitation($poInvitation));
		// Prepare le body TXT
		$oViewMail = new _view('connection::mailTxt');
		$oViewMail->oInvit = $poInvitation;
		$bodyTxt = $oViewMail->show();
		// _root::getLog()->log($bodyTxt);
		$oMail->setBody($bodyTxt);

		// Envoi le mail
		$sent = plugin_vfa::sendEmail($oMail);
		if ($sent) {
			$poInvitation->state = plugin_vfa::STATE_SENT;
			$poInvitation->modified_date = plugin_vfa::dateTimeSgbd();
			$poInvitation->update();
		}
		return $sent;
	}

}
