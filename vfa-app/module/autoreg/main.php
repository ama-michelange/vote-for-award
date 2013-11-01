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
		
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		
		$this->oLayout->add('work', $oView);
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
		return $oConfirm;
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
			'%s, <small>le responsable du Prix BD de </small>%s<small>, vous invite à confirmer votre inscription au%s prix suivant%s pour voter : </small>', 
			$oCreatedUser->username, $oGroup->group_name, $xPrix, $sPrix);
		
		for ($i = 0; $i < $awardCount; $i ++) {
			if ($i > 0) {
				$textInvit .= ', ';
			}
			$textInvit .= $tAwards[$i]->getTypeNameString();
		}
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
