<?php

class module_autoreg extends abstract_module
{

	public function before()
	{
		_root::startSession();
		plugin_vfa::loadI18n();
		
		$this->oLayout = new _layout('tpl_bs_bar');
		// $this->oLayout->addModule('bsnavbar','bsnavbar::index');
	}

	public function _index()
	{
		// $params = _root::getRequest()->getParams();
		// var_dump($params);
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('id'));
		
		if (true == $this->isInvitationParamsValid($oInvitation)) {
			if (_root::getRequest()->isPost()) {
				$this->doPost($oInvitation);
			} else {
				$this->doGet($oInvitation);
			}
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	public function _invalid()
	{
		$oView = new _view('autoreg::invalid');
		$this->oLayout->add('work', $oView);
	}

	public function _toReject()
	{
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('id'));
		
		if (true == $this->isInvitationParamsValid($oInvitation)) {
			$oInvitation->state = plugin_vfa::INVITATION_STATE_REJECTED;
			$oInvitation->modified_date = plugin_vfa::dateTimeSgbd();
			$oInvitation->update();
			_root::redirect('autoreg::rejected');
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	public function _rejected()
	{
		$oView = new _view('autoreg::rejected');
		$this->oLayout->add('work', $oView);
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
		$oView->tSelectedYears = $this->buildSelectedYears($oConfirm);
		
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		
		$this->oLayout->add('work', $oView);
	}

	private function doPost($poInvitation)
	{
		$oConfirm = $this->doVerifyPost($poInvitation);
		
		$oView = new _view('autoreg::index');
		$oView->oConfirm = $oConfirm;
		$oView->tMessage = $oConfirm->getMessages();
		$oView->tSelectedYears = $this->buildSelectedYears($oConfirm);
		
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		
		$this->oLayout->add('work', $oView);
	}

	private function buildSelectedYears($poConfirm)
	{
		$tYear = array();
		$date = new plugin_date(date('Y-m-d'));
		$date->removeYear(10);
		for ($i = 0; $i < 91; $i ++) {
			if (($poConfirm->birtyear) && ($poConfirm->birtyear == $date->getYear())) {
				$tYear[$date->getYear()] = true;
			} else {
				$tYear[$date->getYear()] = false;
			}
			$date->removeYear(1);
		}
		return $tYear;
	}

	private function doVerifyPost($poInvitation)
	{
		$oConfirm = new row_confirm_invitation();
		// Verifie le token
		$oPluginXsrf = new plugin_xsrf();
		if (! $oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oConfirm->setMessages(array(
				'token' => $oPluginXsrf->getMessage()
			));
			return $oConfirm;
		}
		// Récupère les params cachés et reconstruit le texte
		$oConfirm->invitation_id = _root::getParam('invitation_id');
		$oConfirm->invitation_key = _root::getParam('invitation_key');
		$this->makeTextInvitation($poInvitation, $oConfirm);
		
		switch (_root::getParam('action')) {
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
		$poConfirm->username = _root::getParam('username', null);
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
			$oUserDoublon = model_user::getInstance()->findByLogin($poConfirm->username);
			if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
				$poConfirm->validation = true;
			} else {
				$poConfirm->setMessages(array(
					'username' => array(
						'doublon'
					)
				));
			}
		}
		// var_dump($poConfirm);
	}

	private function makeTextInvitation($poInvitation, $poConfirm)
	{
		$tAwards = $poInvitation->findAwards();
		$oGroup = $poInvitation->findGroup();
		$oCreatedUser = $poInvitation->findCreatedUser();
		
		$xPrix = '';
		$sPrix = '';
		$awardCount = count($tAwards);
		if ($awardCount > 1) {
			$xPrix = 'x';
			$sPrix = 's';
		}
		
		$textInvit = sprintf(
			'Le responsable du groupe <strong>%s</strong>, vous invite à vous inscrire pour participer au%s prix BD suivant%s : ', 
			$oGroup->group_name, $xPrix, $sPrix);
		
		$textInvit .= '<br/><strong>';
		for ($i = 0; $i < $awardCount; $i ++) {
			if ($i > 0) {
				$textInvit .= ', ';
			}
			$textInvit .= $tAwards[$i]->getTypeNameString();
		}
		$textInvit .= '</strong>';
		$poConfirm->textInvit = $textInvit;
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
