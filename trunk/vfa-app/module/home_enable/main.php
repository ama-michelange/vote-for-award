<?php

class module_home_enable extends abstract_module
{

	public function before()
	{
		_root::getAuth()->enable();
		$this->oLayout = new _layout('tpl_bs_bar');

	}

	public function _index()
	{
		// Récupère les infos de l'utilisateur connecté
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$oView = new _view('home_enable::index');


		$toRegins = array();
		$toReginUsers = model_regin_users::getInstance()->findAllByUserId($oUserSession->getUser()->getId());
		foreach ($toReginUsers as $oReginUser) {
			$toRegins[] = $oReginUser->findRegin();
		}
		if (count($toRegins) > 0) {
			setcookie('VFA_USER_SESSION', 'GENERATE', 0);
		} else {
			if (array_key_exists( 'VFA_USER_SESSION',$_COOKIE)) {
				$oUser = $oUserSession->getUser();
				$oUserSession = model_user_session::getInstance()->create($oUser);
				_root::getAuth()->setUserSession($oUserSession);
				setcookie ("VFA_USER_SESSION", "", time() - 3600);
			}
		}
		$oView->toRegins = $toRegins;

		$oView->toUserRegistredAwards = $oUserSession->getValidAwards();
		$oView->toInProgressAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER, true);


		$this->oLayout->add('work', $oView);
	}


	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->show();
	}
}
