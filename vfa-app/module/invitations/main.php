<?php

class module_invitations extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		$this->oLayout = new _layout('tpl_bs_bar_context');
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->setTitle('Invitations', new NavLink('nominees', 'index', array('idAward' => _root::getParam('idAward'))));
		$navBar->addChild(new BarButtons('left'));
		plugin_BsContextBar::buildDefaultContextBar($navBar);
		return $navBar;
	}

	public function _index()
	{
		// redirection vers la page par défaut
		_root::redirect('invitations::list');
	}

	public function _list()
	{
		$oInvitationModel = new model_invitation();
		$tInvitations = $oInvitationModel->findAll();

		$oView = new _view('invitations::list');
		$oView->tInvitations = $tInvitations;

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('invitations::read');
		$oView->oViewShow = $this->makeViewShow();
		$this->oLayout->add('work', $oView);
	}

	public function before_reader()
	{
		if (false == _root::getACL()->permit('invitations::reader')) {
			_root::redirect('default::index');
		}
	}

	public function _reader()
	{
		$oRegistry = $this->verify();
		if (null == $oRegistry) {
			$oRegistry = new row_registry_invitation();
			$oRegistry->type = plugin_vfa::INVITATION_TYPE_READER;
			$oRegistry->phase = 'prepare';
			$oRegistry->new = true;
		}
		$this->dispatch($oRegistry);
	}

	public function before_responsible()
	{
		if (false == _root::getACL()->permit('invitations::responsible')) {
			_root::redirect('default::index');
		}
	}

	public function _responsible()
	{
		$oRegistry = $this->verify();
		if (null == $oRegistry) {
			$oRegistry = new row_registry_invitation();
			$oRegistry->type = plugin_vfa::INVITATION_TYPE_RESPONSIBLE;
			$oRegistry->phase = 'prepare';
			$oRegistry->new = true;
		}
		$this->dispatch($oRegistry);
	}

	public function before_free()
	{
		if (false == _root::getACL()->permit('invitations::free')) {
			_root::redirect('default::index');
		}
	}

	public function _free()
	{
		$tMessage = null;
		$oView = new _view('invitations::prepare');
		$oView->textTitle = 'Libre';
		$oView->tMessage = $tMessage;
		// $oView->oViewShow=$this->makeViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function before_board()
	{
		if (false == _root::getACL()->permit('invitations::board')) {
			_root::redirect('default::index');
		}
	}

	public function _board()
	{
		$oRegistry = $this->verify();
		if (null == $oRegistry) {
			$oRegistry = new row_registry_invitation();
			$oRegistry->type = plugin_vfa::INVITATION_TYPE_BOARD;
			$oRegistry->phase = 'prepare';
			$oRegistry->new = true;
		}
		$this->dispatch($oRegistry);
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('invitations::delete');
		$oView->oViewShow = $this->makeViewShow();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	private function dispatch($poRegistry)
	{
		switch ($poRegistry->phase) {
			case 'prepare':
				$this->makeViewInvitPrepare($poRegistry);
				break;
			case 'confirm':
				$this->makeViewInvitConfirm($poRegistry);
				break;
			case 'notsent':
				$this->makeViewInvitMailSent($poRegistry, false);
				break;
			case 'sent':
				$this->makeViewInvitMailSent($poRegistry);
				break;
		}
	}

	private function makeViewInvitPrepare($poRegistry)
	{
		$oRegistry = $poRegistry;
		// var_dump($oRegistry);
		$tMessage = null;
		if (null == $oRegistry->new) {
			$tMessage = $oRegistry->getMessages();
			// var_dump($tMessage);
		}
		$oView = new _view('invitations::invitPrepare');
		$oView->oRegistry = $oRegistry;
		$oView->tMessage = $tMessage;
		$this->fillAwards($oView);
		$this->fillGroups($oView);

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	private function fillAwards($pView)
	{
		switch (_root::getAction()) {
			case 'board':
				$tAwards = model_award::getInstance()->findAllByType('PSBD');
				break;
			default:
				$tAwards = model_award::getInstance()->findAllByType('PBD');
				break;
		}
		$pView->countAwards = count($tAwards);
		if ($pView->countAwards == 0) {
			$tMessage = $pView->tMessage;
			switch (_root::getAction()) {
				case 'board':
					$tMessage['awards'][] = 'nonePSBD';
					break;
				default:
					$tMessage['awards'][] = 'nonePBD';
					break;
			}
			$pView->tMessage = $tMessage;
		} elseif ($pView->countAwards == 1) {
			$pView->oAward = $tAwards[0];
		} else {
			$tSelect = plugin_vfa::toSelect($tAwards, 'award_id', null, 'getTypeNameString');
			if ($pView->oRegistry->awards_ids) {
				$pView->oRegistry->awards_ids = array_flip($pView->oRegistry->awards_ids);
			}
			$pView->tSelectedAwards = plugin_vfa::buildOptionSelected($tSelect, $pView->oRegistry->awards_ids);
		}
	}

	private function fillGroups($pView)
	{
		switch (_root::getAction()) {
			case 'board':
				$tGroups = model_group::getInstance()->findAllByType(plugin_vfa::GROUP_TYPE_BOARD);
				break;
			case 'responsible':
				$tGroups = model_group::getInstance()->findAllByType(plugin_vfa::GROUP_TYPE_READER);
				break;
			default:
				$tGroups = _root::getAuth()->getUserSession()->getReaderGroups();
				break;
		}
		$pView->countGroups = count($tGroups);
		if ($pView->countGroups == 0) {
			$tMessage = $pView->tMessage;
			switch (_root::getAction()) {
				case 'board':
					$pView->tMessage['groups'][] = 'noneCS';
					break;
				default:
					$tMessage['groups'][] = 'none';
					break;
			}
			$pView->tMessage = $tMessage;
		} elseif ($pView->countGroups == 1) {
			$pView->oGroup = $tGroups[0];
		} else {
			$tSelect = plugin_vfa::toSelect($tGroups, 'group_id', 'group_name', null, true);
			$pView->tSelectedGroups = plugin_vfa::buildOptionSelected($tSelect, $pView->oRegistry->group_id);
		}
	}

	private function makeViewInvitConfirm($poRegistry)
	{
		$oRegistry = $poRegistry;
		// var_dump($oRegistry);
		$tMessage = $oRegistry->getMessages();
		// var_dump($tMessage);

		$oView = new _view('invitations::invitConfirm');
		$oView->oRegistry = $oRegistry;
		$oView->tMessage = $tMessage;

		$tAwards = array();
		if ($oRegistry->award_id) {
			$tAwards[] = model_award::getInstance()->findById($oRegistry->award_id);
		} else {
			foreach ($oRegistry->awards_ids as $id) {
				$tAwards[] = model_award::getInstance()->findById($id);
			}
		}
		$oView->tAwards = $tAwards;
		$oView->oGroup = model_group::getInstance()->findById($oRegistry->group_id);

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	private function makeViewInvitMailSent($poRegistry, $pSent = true)
	{
		$oRegistry = $poRegistry;
		$tMessage = $oRegistry->getMessages();
		if ($pSent) {
			$oView = new _view('invitations::invitMailSent');
		} else {
			$oView = new _view('invitations::invitMailNotSent');
		}
		$oView->oRegistry = $oRegistry;
		$oView->tMessage = $tMessage;

		$tAwards = array();
		if ($oRegistry->award_id) {
			$tAwards[] = model_award::getInstance()->findById($oRegistry->award_id);
		} else {
			foreach ($oRegistry->awards_ids as $id) {
				$tAwards[] = model_award::getInstance()->findById($id);
			}
		}
		$oView->tAwards = $tAwards;
		$oView->oGroup = model_group::getInstance()->findById($oRegistry->group_id);

		// $oPluginXsrf = new plugin_xsrf();
		// $oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	private function makeViewShow()
	{
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('id'));

		$oView = new _view('invitations::show');
		$oView->oInvitation = $oInvitation;
		$oView->tAwards = $oInvitation->findAwards();
		$oView->oGroup = $oInvitation->findGroup();
		$oView->oCreatedUser = $oInvitation->findCreatedUser();
		return $oView;
	}

	private function verify()
	{
		if (!_root::getRequest()->isPost()) {
			return null;
		}
		$oRegistry = new row_registry_invitation();

		switch (_root::getParam('phase', null)) {
			case 'sent':
			case 'notsent':
				break;
			default:
				$oPluginXsrf = new plugin_xsrf();
				if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
					$oRegistry->setMessages(array('token' => $oPluginXsrf->getMessage()));
					$oRegistry->phase = 'prepare';
					return $oRegistry;
				}
				break;
		}

		// Copie la saisie dans un enregistrement
		$oRegistry->type = _root::getParam('type', null);
		$oRegistry->phase = _root::getParam('phase', null);
		$oRegistry->email = _root::getParam('email', null);
		$oRegistry->award_id = _root::getParam('award_id', null);
		$oRegistry->awards_ids = _root::getParam('awards_ids', null);
		$oRegistry->group_id = _root::getParam('group_id', null);
		// var_dump($oRegistry);

		switch (_root::getParam('cancel', null)) {
			case 'prepare':
				// Annulation de la phase 'prepare'
				// Retour à la phase 'prepare' vierge
				$oRegistry = null;
				break;
			case 'confirm':
				// Annulation de la phase 'confirm'
				// Retour à la phase 'prepare' avec la même saisie
				$oRegistry->phase = 'prepare';
				break;
			default:
				// Pas d'annulation -> Validation
				switch ($oRegistry->phase) {
					case 'prepare':
					case 'confirm':
						if ($oRegistry->isValid()) {
							$oInvitDoublon = model_invitation::getInstance()->findByRegistry($oRegistry);
							if ((null != $oInvitDoublon) && (false == $oInvitDoublon->isEmpty())) {
								$oRegistry->setMessages(array('doublon' => 'isNotUnique'));
								$oRegistry->phase = 'prepare';
							} else {
								// Pas de doublon
								switch ($oRegistry->phase) {
									case 'prepare':
										$oRegistry->phase = 'confirm';
										break;
									case 'confirm':
										$oInvitation = $this->saveInvitation($oRegistry);
										if ($this->sendMail($oInvitation)) {
											$oRegistry->phase = 'sent';
										} else {
											$oRegistry->phase = 'notsent';
										}
										$oRegistry->invit = $oInvitation;
										break;
								}
							}
						}
						break;
					default:
						// Retour à la phase 'prepare' avec la même saisie mais sans l'email
						$oRegistry->phase = 'prepare';
						$oRegistry->new = true;
						$oRegistry->email = null;
						break;
				}
				break;
		}
		return $oRegistry;
	}

	private function buildInvitationKey($poRegistry)
	{
		$s = $poRegistry->type . $poRegistry->email . $poRegistry->group_id;
		if ($poRegistry->award_id) {
			$s .= $poRegistry->award_id;
		} else {
			foreach ($poRegistry->awards_ids as $id) {
				$s .= $id;
			}
		}
		$sSha1 = sha1($s);
		$key = $sSha1 . time();
		return $key;
	}

	private function saveInvitation($poRegistry)
	{
		$oInvit = new row_invitation();

		// Remplissage de l'invit
		$oInvit->created_user_id = _root::getAuth()->getUserSession()->getUser()->user_id;
		$oInvit->invitation_key = $this->buildInvitationKey($poRegistry);
		$oInvit->state = plugin_vfa::INVITATION_STATE_OPEN;
		$oInvit->type = $poRegistry->type;
		$oInvit->email = $poRegistry->email;
		$oInvit->group_id = $poRegistry->group_id;
		if ($poRegistry->award_id) {
			$oInvit->awards_ids = array($poRegistry->award_id);
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
			$sent = $oMail->send();
		} catch (Exception $e) {
			$sent = false;
		}
		if ($sent) {
			$poInvitation->state = plugin_vfa::INVITATION_STATE_SENT;
			$poInvitation->update();
		}
		return $sent;
	}

	private function delete()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		// on verifie que le token est valide
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			return array('token' => $oPluginXsrf->getMessage());
		}

		$oInvitationModel = new model_invitation();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oInvitation = $oInvitationModel->findById($iId);
			$oInvitation->delete();
		}
		_root::redirect('invitations::list');
	}
}
