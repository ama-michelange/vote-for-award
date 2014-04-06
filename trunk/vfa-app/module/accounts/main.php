<?php

class module_accounts extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_bar');
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
//		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));

		$this->oLayout->show();
	}

//	private function buildContextBar()
//	{
//		$navBar = plugin_BsHtml::buildNavBar();
//		$navBar->setTitle('Mon compte');
//		$navBar->addChild(new BarButtons('left'));
//
//		$bar = $navBar->getChild('left');
//		$bar->addChild(plugin_BsHtml::buildButtonItem('Liste par groupe', new NavLink('accounts', 'listByGroup'), 'glyphicon-list'));
//		plugin_BsContextBar::buildDefaultContextBar($navBar);
//		return $navBar;
//	}

	public function _index()
	{
		// Force l'action pour n'avoir qu'un seul test dans le menu contextuel
		_root::getRequest()->setAction('update');
		$this->_update();
	}


	public function _update()
	{
		$tMessage = null;
//		$oUserModel = new model_user();

		$oUser = $this->save();
		if (null == $oUser) {
			$oUser = _root::getAuth()->getUserSession()->getUser();
		} else {
			$tMessage = $oUser->getMessages();
		}

		$oView = new _view('accounts::edit');
		$oView->oUser = $oUser;
		$oView->tMessage = $tMessage;

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}


	public function save()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oUser = new row_user();
			$oUser->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oUser;
		}

		$oUserModel = new model_user();
		$iId = _root::getParam('user_id', null);
		if ($iId == null) {
			return null;
		} else {
			$oUser = $oUserModel->findById($iId);
		}
		$oUser->modified_date = plugin_vfa::dateTimeSgbd();
		// Copie la saisie dans un enregistrement
		$tColumns = array('user_id', 'username', 'last_name', 'first_name', 'email', 'birthyear', 'gender');
		foreach ($tColumns as $sColumn) {
			if (in_array($sColumn, $oUserModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oUser->$sColumn)) {
				$oUser->$sColumn = null;
			} else {
				$oUser->$sColumn = _root::getParam($sColumn, null);
			}
		}
		// Gère la saisie du mot de passe
		$canValidate = false;
		$newPassword = _root::getParam('newPassword');
		$confirmPassword = _root::getParam('confirmPassword');
		if (null != $newPassword) {
			$lenPassword = strlen($newPassword);
			if (($lenPassword < 7) OR ($lenPassword > 30)) {
				$oUser->openPassword = true;
				$oUser->newPassword = $newPassword;
				$oUser->confirmPassword = $confirmPassword;
				$oUser->setMessages(array('newPassword' => array('badSize')));
			} else {
				if ($newPassword === $confirmPassword) {
					$oUser->password = sha1($newPassword);
					$canValidate = true;
				} else {
					$oUser->openPassword = true;
					$oUser->newPassword = $newPassword;
					$oUser->confirmPassword = $confirmPassword;
					$oUser->setMessages(array('newPassword' => array('isEqualKO'), 'confirmPassword' => array('isEqualKO')));
				}
			}
		} else {
			$canValidate = true;
		}

		if (true == $canValidate && $oUser->isValid()) {
			// Sauvegarde
			$oUser->save();
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($oUser);
			_root::getAuth()->setUserSession($oUserSession);
			//_root::redirect('accounts::saved');
			$this->oLayout->add('script', new _view('accounts::scriptSaved'));
		}
		return $oUser;
	}


}

