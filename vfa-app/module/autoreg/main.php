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
		$oInvitationModel = new model_invitation();
		$oInvitation = $oInvitationModel->findById(_root::getParam('id'));
		
		if (true == $this->isValid($oInvitation)) {
			$oView = new _view('autoreg::index');
			$oView->tAwards = $oInvitation->findAwards();
			$oView->oGroup = $oInvitation->findGroup();
			$oView->oCreatedUser = $oInvitation->findCreatedUser();
			
			$xPrix = '';
			$sPrix = '';
			$awardCount = count($oView->tAwards);
			if ($awardCount > 1) {
				$xPrix = 'x';
				$sPrix = 's';
			}
			$oView->textInvit = sprintf(
				'%s, <small>le responsable du Prix BD de </small>%s<small>, vous invite à confirmer votre inscription au%s prix suivant%s pour voter : </small>', 
				$oView->oCreatedUser->username, $oView->oGroup->group_name, $xPrix, $sPrix);
			
			for ($i = 0; $i < $awardCount; $i ++) {
				if ($i > 0) {
					$oView->textInvit .= ', ';
				}
				$oView->textInvit .= $oView->tAwards[$i]->getTypeNameString();
			}
			$oView->tMessage = "";
			$this->oLayout->add('work', $oView);
		} else {
			$oView = new _view('autoreg::invalid');
			$this->oLayout->add('work', $oView);
		}
	}

	public function after()
	{
		$this->oLayout->show();
	}

	private function isValid($poInvitation)
	{
		$key = _root::getParam('key');
		if ((null == $key) || (null == $poInvitation)) {
			return false;
		}
		if ((null != $poInvitation) && ($poInvitation->isEmpty())) {
			return false;
		}
		if (_root::getParam('id') != $poInvitation->invitation_id) {
			return false;
		}
		if ($key != $poInvitation->invitation_key) {
			return false;
		}
		return true;
	}
}
