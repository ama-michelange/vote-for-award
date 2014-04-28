<?php

class module_users extends abstract_module
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
		$navBar->setTitle('Utilisateurs', new NavLink('users', 'index'));
		$navBar->addChild(new BarButtons('left'));

		$bar = $navBar->getChild('left');
		$bar->addChild(plugin_BsHtml::buildButtonItem('Liste par groupe', new NavLink('users', 'listByGroup'), 'glyphicon-list'));
		plugin_BsContextBar::buildDefaultContextBar($navBar);
		return $navBar;
	}

	public function _index()
	{
		// Force l'action pour n'avoir qu'un seul test dans le menu contextuel
		_root::getRequest()->setAction('list');
		$this->_list();
	}

	public function _list()
	{
		$oUserModel = new model_user();
		$tUsers = $oUserModel->findAll();

		$oView = new _view('users::list');
		$oView->tUsers = $tUsers;
		$oView->tColumn = $oUserModel->getListColumn(); // array('id','titre');//

		$this->oLayout->add('work', $oView);
	}

	public function _listByGroup()
	{
		$oUserModel = new model_user();
		$tGroups = _root::getAuth()->getUserSession()->getGroups();
		$SelectedIdGroup = null;
		$tUsers = null;

		if (_root::getRequest()->isPost()) {
			$SelectedIdGroup = _root::getParam('selectedGroup', null);
		}
		if (count($tGroups) >= 1) {
			if (null == $SelectedIdGroup) {
				$SelectedIdGroup = $tGroups[0]->getId();
			}
			$tUsers = $oUserModel->findAllByGroupId($SelectedIdGroup);
		}

		$oView = new _view('users::listByGroup');
		$oView->tUsers = $tUsers;
		$oView->tGroups = $tGroups;
		$oView->SelectedIdGroup = $SelectedIdGroup;

		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$oUserModel = new model_user();
		$tColumns = array('login', 'last_name', 'first_name', 'email', 'birthyear', 'gender', 'vote');

		$oUser = $this->save($tColumns);
		if (null == $oUser) {
			$oUser = new row_user();
			$tUserRoles = $oUser->getSelectedRoles();
			$tUserGroups = $oUser->getSelectedGroups();
		} else {
			$tMessage = $oUser->getMessages();
			$tUserRoles = plugin_vfa::copyValuesToKeys(_root::getParam('user_roles', null));
			$tUserGroups = plugin_vfa::copyValuesToKeys(_root::getParam('user_groups', null));
		}

		$oView = new _view('users::edit');
		$oView->oUser = $oUser;
		$oView->tSelectedRoles = plugin_vfa::buildOptionSelected(model_role::getInstance()->getSelect(), $tUserRoles);
		$oView->tSelectedGroups = plugin_vfa::buildOptionSelected(model_group::getInstance()->getSelect(), $tUserGroups);
		$oView->tColumn = $tColumns;
		$oView->tId = $oUserModel->getIdTab();
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer un utilisateur';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$oUserModel = new model_user();
		$tColumns = array('user_id', 'login', 'email', 'alias', 'last_name', 'first_name', 'birthyear', 'gender');

		$oUser = $this->save($tColumns);
		if (null == $oUser) {
			$oUser = $oUserModel->findById(_root::getParam('id'));
			$tUserRoles = $oUser->getSelectedRoles();
			$tUserGroups = $oUser->getSelectedGroups();
		} else {
			$tMessage = $oUser->getMessages();
			$tUserRoles = plugin_vfa::copyValuesToKeys(_root::getParam('user_roles', null));
			$tUserGroups = plugin_vfa::copyValuesToKeys(_root::getParam('user_groups', null));
		}

		$oView = new _view('users::edit');
		$oView->oUser = $oUser;
		$oView->tSelectedRoles = plugin_vfa::buildOptionSelected(model_role::getInstance()->getSelect(), $tUserRoles);
		$oView->tSelectedGroups = plugin_vfa::buildOptionSelected(model_group::getInstance()->getSelect(), $tUserGroups);
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier un utilisateur';
		$oView->tColumn = $tColumns;
		$oView->tId = $oUserModel->getIdTab();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('users::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oUserModel = new model_user();
		$oUser = $oUserModel->findById(_root::getParam('id'));

		$oView = new _view('users::show');
		$oView->oUser = $oUser;
		$oView->toRoles = $oUser->findRoles();
		$oView->toGroups = $oUser->findGroups();
		$oView->toAwards = $oUser->findAwards();

		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('users::delete');
		$oView->oViewShow = $this->buildViewShow();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	public function save($tColumns)
	{
		if (!_root::getRequest()->isPost()) {
			// si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			$oDoc = new row_title();
			$oDoc->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oDoc;
		}

		$oUserModel = new model_user();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oUser = new row_user();
			$oUser->created_date = plugin_vfa::dateTimeSgbd();
		} else {
			$oUser = $oUserModel->findById(_root::getParam('id', null));
		}
		$oUser->modified_date = plugin_vfa::dateTimeSgbd();
		// Copie la saisie dans un enregistrement
		foreach ($tColumns as $sColumn) {
			if (in_array($sColumn, $oUserModel->getIdTab())) {
				continue;
			}
			if ('password' == $sColumn) {
				$oUser->$sColumn = sha1(_root::getParam($sColumn, null));
			} else {
				if ((_root::getParam($sColumn, null) == null) && (null != $oUser->$sColumn)) {
					$oUser->$sColumn = null;
				} else {
					$oUser->$sColumn = _root::getParam($sColumn, null);
				}
			}
		}
		// Récupère les rôles associés
		$tUserRoles = _root::getParam('user_roles', null);
		// Récupère les groupes associés
		$tUserGroups = _root::getParam('user_groups', null);

		if ($oUser->isValid()) {
			if ($this->hasDuplicateLogin($oUser)) {
				$oUser->setMessages(array('login' => array('doublon')));
			} elseif (false == $this->isValidGroupsRoles($oUser, $tUserGroups, $tUserRoles)) {
			} else {
				$oUser->save();
				$oUserModel->saveUserRoles($oUser->user_id, $tUserRoles);
				$oUserModel->saveUserGroups($oUser->user_id, $tUserGroups);
				_root::redirect('users::read', array('id' => $oUser->user_id));
			}
		}
		return $oUser;
	}

	private function hasDuplicateLogin($poUser)
	{
		$duplicate = true;
		$oUserDoublon = model_user::getInstance()->findByLogin($poUser->login);
		if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
			$duplicate = false;
		} else if ((null != $poUser->getId()) && ($oUserDoublon->getId() == $poUser->getId())) {
			$duplicate = false;
		}
		return $duplicate;
	}

	private function isValidGroupsRoles($poUser, $ptUserGroups, $ptUserRoles)
	{
		$valid = true;
		$tMess = array();
		$toGroups = model_group::getInstance()->findAllByIds($ptUserGroups);
		if ($this->isDuplicateTypeGroup($toGroups)) {
			//$poUser->setMessages(array('user_groups' => array('notUniqueTypeGroup')));
//			$tMess['user_groups'] = array('notUniqueTypeGroup');
			$poUser->addMessage('user_groups', 'notUniqueTypeGroup');
			$valid = false;
		}
		$toRoles = model_role::getInstance()->findAllByIds($ptUserRoles);
		if (false == $this->validateGroupsWithRoles($toRoles, $toGroups)) {
			//$poUser->setMessages(array('user_groups' => array('invalidGroupsWithRoles')));
//			$tMess['user_groups'] = array('invalidGroupsWithRoles');
			$poUser->addMessage('user_groups', 'invalidGroupsWithRoles');
			$valid = false;
		}
		if (false == $this->validateRolesWithGroups($toRoles, $toGroups)) {
			//$poUser->setMessages(array('user_roles' => array('invalidRolesWithGroups')));
//			$tMess['user_roles'] = array('invalidRolesWithGroups');
			$poUser->addMessage('user_roles', 'invalidRolesWithGroups');
			$valid = false;
		}
//		if(false==$valid){
//			$poUser->setMessages($tMess);
//		}
		return $valid;
	}

	private function isDuplicateTypeGroup($ptoGroups)
	{
		$ret = false;
		if (count($ptoGroups) > 0) {
			$types = array();
			foreach ($ptoGroups as $oGroup) {
				$types[$oGroup->role_id_default] = $oGroup->getId();
			}
			if (count($ptoGroups) != count($types)) {
				$ret = true;
			}
		}
		return $ret;
	}

	private function validateGroupsWithRoles($ptoRoles, $ptoGroups)
	{
		$roles = array();
		if ($ptoRoles) {
			foreach ($ptoRoles as $oRole) {
				$roles[$oRole->getId()] = $oRole->role_name;
			}
		}
		if ($ptoGroups) {
			foreach ($ptoGroups as $oGroup) {
				if (false == array_key_exists($oGroup->role_id_default, $roles)) {
					return false;
				}
			}
		}
		return true;
	}

	private function validateRolesWithGroups($ptoRoles, $ptoGroups)
	{
		$group = array();
		if ($ptoGroups) {
			foreach ($ptoGroups as $Group) {
				$group[$Group->role_id_default] = $Group->getId();
			}
		}
		if ($ptoRoles) {
			foreach ($ptoRoles as $oRole) {
				switch ($oRole->role_name) {
					case 'reader':
					case 'board':
						if (false == array_key_exists($oRole->role_id, $group)) {
							return false;
						}
						break;
				}
			}
		}
		return true;
	}

	private function delete()
	{
		if (!_root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$oUserModel = new model_user();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oUserModel->deleteUserCascades($iId);
		}
		_root::redirect('users::list');
	}
}

