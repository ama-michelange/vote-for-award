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
			if (array_key_exists('VFA_USER_SESSION', $_COOKIE)) {
				$oUser = $oUserSession->getUser();
				$oUserSession = model_user_session::getInstance()->create($oUser);
				_root::getAuth()->setUserSession($oUserSession);
				setcookie("VFA_USER_SESSION", "", time() - 3600);
			}
		}

		$toReaderRegins = array();
		$toBoardRegins = array();
		foreach ($toRegins as $oRegin) {
			if ($oRegin->type == plugin_vfa::TYPE_BOARD) {
				$toBoardRegins[] = $oRegin;
			} else {
				$toReaderRegins[] = $oRegin;
			}
		}

		$oView->toBoardRegins = $toBoardRegins;
		$oView->toReaderRegins = $toReaderRegins;

		$oView->reginToValidate = false;
		if ($oUserSession->isInRole(plugin_vfa::ROLE_RESPONSIBLE)) {
			$toRegins = model_regin::getInstance()
				->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_READER, $oUserSession->getReaderGroup()->getId());
			if (count($toRegins) > 0) {
				$toReginUsers = model_regin_users::getInstance()->findAllByReginId($toRegins[0]->getId());
				if (count($toReginUsers) > 0) {
					$oView->reginToValidate = true;
				}
			}
		}
		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$toBoardGroups = model_group::getInstance()->findAllByRoleName(plugin_vfa::ROLE_BOARD);
			$toRegins = model_regin::getInstance()
				->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_BOARD, $toBoardGroups[0]->getId());
			if (count($toRegins) > 0) {
				$toReginUsers = model_regin_users::getInstance()->findAllByReginId($toRegins[0]->getId());
				if (count($toReginUsers) > 0) {
					$oView->reginToValidate = true;
				}
			}
		}

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
