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
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$navBar = plugin_BsHtml::buildNavBar();
//		$navBar->setTitle('Comptes', new NavLink('users', 'index'));
//		$navBar->setTitle(_root::getParamNav());
//		$navBar->setTitle('Comptes');


		switch (_root::getAction()) {
			case 'listMyGroup':
				$title = $oUserSession->getReaderGroup()->toString();
				break;
			case 'create':
			case 'delete':
			case 'update':
			case 'read':
				$title = 'Compte';
				break;
			default:
				$title = plugin_i18n::getFirst('title', _root::getModule(), _root::getAction());
				break;
		}
		$navBar->setTitle($title);


		$navBar->addChild(new Bar('left'));
		$this->buildMenuUsersByGroup($navBar->getChild('left'), $oUserSession);

		$navBar->addChild(new BarButtons('right'));
		$bar = $navBar->getChild('right');
		$bar->addChild(plugin_BsHtml::buildButtonItem('Liste par groupe', new NavLink('users', 'listByGroup'), 'glyphicon-list'));
		plugin_BsContextBar::buildDefaultContextBar($bar);
		return $navBar;
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	public static function buildMenuUsersByGroup($pBar, $poUserSession)
	{
		$tItems = array();

		$oReaderGroup = $poUserSession->getReaderGroup();
		$item = plugin_BsHtml::buildMenuItem($oReaderGroup->toString(), new NavLink('users', 'listMyGroup'));
		if ($item) {
			$tItems[] = $item;
		}
		$item = plugin_BsHtml::buildMenuItem(plugin_i18n::getFirst('title', 'users', 'listResponsibleGroup'),
			new NavLink('users', 'listResponsibleGroup'));
		if ($item) {
			$tItems[] = $item;
		}
		$item = plugin_BsHtml::buildMenuItem(plugin_i18n::getFirst('title', 'users', 'listBoardGroup'),
			new NavLink('users', 'listBoardGroup'));
		if ($item) {
			$tItems[] = $item;
		}
		$tItems[] = plugin_BsHtml::buildSeparator();
		$item = plugin_BsHtml::buildMenuItem(plugin_i18n::getFirst('title', 'users', 'list'), new NavLink('users', 'list'));
		if ($item) {
			$tItems[] = $item;
		}
		$item = plugin_BsHtml::buildMenuItem(plugin_i18n::getFirst('title', 'users', 'listDetailed'), new NavLink('users', 'listDetailed'));
		if ($item) {
			$tItems[] = $item;
		}
//		$tItems[] = plugin_BsHtml::buildSeparator();
//		$item = plugin_BsHtml::buildMenuItem('Tous les groupes', new NavLink('groups', 'list'));
//		if ($item) {
//			$tItems[] = $item;
//		}


		switch (count($tItems)) {
			case 0:
				break;
			case 1:
				if (_root::getParamNav() != $tItems[0]->getLink()->getNav()) {
					$tItems[0]->setLabel('Compte');
					$tItems[0]->setName('Compte');
					$pBar->addChild($tItems[0]);
				}
				break;
			default:
				$drop = new DropdownMenuItem('Autres Comptes');
				foreach ($tItems as $item) {
					if (plugin_BsHtml::isSeparator($item)) {
						$drop->addChild($item);
					} elseif (_root::getParamNav() != $item->getLink()->getNav()) {
						$drop->addChild($item);
					}
				}
				if ($drop->hasRealChildren()) {
					$pBar->addChild($drop);
				}
				break;
		}
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
		$oView->title = 'Tous les comptes';

		$this->oLayout->add('work', $oView);
	}

	public function _listDetailed()
	{
		$oUserModel = new model_user();
		$tUsers = $oUserModel->findAll();

		$oView = new _view('users::listDetailed');
		$oView->tUsers = $tUsers;
		$oView->tColumn = $oUserModel->getListColumn();

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

	public function _listMyGroup()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();

		$tUsers = model_user::getInstance()->findAllByGroupId($oReaderGroup->group_id);

		$oView = new _view('users::list');
		$oView->tUsers = $tUsers;
		$oView->title = $oReaderGroup->toString();

		$this->oLayout->add('work', $oView);
	}

	public function _listBoardGroup()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oBoardGroup = $oUserSession->getBoardGroup();

		$tUsers = model_user::getInstance()->findAllByGroupId($oBoardGroup->group_id);

		$oView = new _view('users::list');
		$oView->tUsers = $tUsers;
		$oView->title = $oBoardGroup->toString();

		$this->oLayout->add('work', $oView);
	}

	public function _listResponsibleGroup()
	{
		$tUsers = model_user::getInstance()->findAllByRoleName(plugin_vfa::ROLE_RESPONSIBLE);

		$oView = new _view('users::list');
		$oView->tUsers = $tUsers;
		$oView->title = 'Correspondants';

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
		$oView->tSelectedRoles = plugin_vfa::buildOptionSelected(model_role::getInstance()->getSelectAll(), $tUserRoles);
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
		$tColumns = array('user_id', 'login', 'email', 'last_name', 'first_name', 'birthyear', 'gender');

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
		$oView->tSelectedRoles = plugin_vfa::buildOptionSelected(model_role::getInstance()->getSelectAll(), $tUserRoles);
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
		$oUser = model_user::getInstance()->findById(_root::getParam('id'));

		$oView = new _view('users::show');
		$oView->oUser = $oUser;
		$oView->oViewShowUser = $this->buildViewShowUser($oUser);
		$oView->oViewShowReaderGroup = $this->buildViewShowReaderGroup($oUser);
		$oView->oViewShowBoardGroup = $this->buildViewShowBoardGroup($oUser);

//		$oView->toRoles = $oUser->findRoles();
//		$oView->toGroups = $oUser->findGroups();
//		$oView->toAwards = $oUser->findAwards();

		return $oView;
	}

	/**
	 * @param row_user $poUser
	 * @return _view
	 */
	public function buildViewShowUser($poUser)
	{
		$oView = new _view('users::showUser');
		$oView->oUser = $poUser;

		return $oView;
	}

	/**
	 * @param row_user $poUser
	 * @return _view
	 */
	public function buildViewShowReaderGroup($poUser)
	{
		$oGroup = $poUser->findGroupByRoleName(plugin_vfa::ROLE_READER);
		if ($oGroup->isEmpty()) {
			return null;
		}
		$oView = new _view('users::showGroup');
		$oView->oUser = $poUser;
		$oView->oGroup = $oGroup;

		$roles = 'Lecteur';
		if ($poUser->isInRole(plugin_vfa::ROLE_RESPONSIBLE)) {
			$roles .= ' et Correspondant';
		}
		$oView->roles = $roles;
		$oView->toValidAwards = model_award::getInstance()->findAllValidByUserId($poUser->user_id, plugin_vfa::TYPE_AWARD_READER);

		return $oView;
	}

	/**
	 * @param row_user $poUser
	 * @return _view
	 */
	public function buildViewShowBoardGroup($poUser)
	{
		$oGroup = $poUser->findGroupByRoleName(plugin_vfa::ROLE_BOARD);
		if ($oGroup->isEmpty()) {
			return null;
		}
		$oView = new _view('users::showGroup');
		$oView->oUser = $poUser;
		$oView->oGroup = $oGroup;

		$roles = '';
		if ($poUser->isInRole(plugin_vfa::ROLE_BOARD)) {
			$roles .= 'Membre';
		}
		$oView->roles = $roles;
		$oView->toValidAwards = model_award::getInstance()->findAllValidByUserId($poUser->user_id, plugin_vfa::TYPE_AWARD_BOARD);

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
			if (false == $this->hasDuplicateLogin($oUser)) {
				if ($this->isValidGroupsRoles($oUser, $tUserGroups, $tUserRoles)) {
					if ($this->checkPassword($oUser)) {
						$oUser->save();
						$oUserModel->saveUserRoles($oUser->user_id, $tUserRoles);
						$oUserModel->saveUserGroups($oUser->user_id, $tUserGroups);
						_root::redirect('users::read', array('id' => $oUser->user_id));
					}
				}
			}
		}
		return $oUser;
	}

	private function checkPassword($poUser)
	{
		$canSave = true;
		$newPassword = _root::getParam('newPassword');
		$confirmPassword = _root::getParam('confirmPassword');
		if (null != $newPassword) {
			$lenPassword = strlen($newPassword);
			if (($lenPassword < 7) OR ($lenPassword > 30)) {
				$poUser->newPassword = $newPassword;
				$poUser->confirmPassword = $confirmPassword;
				$poUser->setMessages(array('newPassword' => array('badSize')));
				$canSave = false;
			} else {
				if ($newPassword === $confirmPassword) {
					$poUser->password = sha1($newPassword);
					$canSave = true;
				} else {
					$poUser->newPassword = $newPassword;
					$poUser->confirmPassword = $confirmPassword;
					$poUser->setMessages(array('newPassword' => array('isEqualKO'), 'confirmPassword' => array('isEqualKO')));
					$canSave = false;
				}
			}
		}
		return $canSave;
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
		if ($duplicate) {
			$poUser->setMessages(array('login' => array('doublon')));
		}
		return $duplicate;
	}

	private function isValidGroupsRoles($poUser, $ptUserGroups, $ptUserRoles)
	{
		$valid = true;
		$toGroups = model_group::getInstance()->findAllByIds($ptUserGroups);
		if ($this->isDuplicateTypeGroup($toGroups)) {
			$poUser->addMessage('user_groups', 'notUniqueTypeGroup');
			$valid = false;
		}
		$toRoles = model_role::getInstance()->findAllByIds($ptUserRoles);
		if (false == $this->validateGroupsWithRoles($toRoles, $toGroups)) {
			$poUser->addMessage('user_groups', 'invalidGroupsWithRoles');
			$valid = false;
		}
		if (false == $this->validateRolesWithGroups($toRoles, $toGroups)) {
			$poUser->addMessage('user_roles', 'invalidRolesWithGroups');
			$valid = false;
		}
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
					case plugin_vfa::TYPE_READER:
					case plugin_vfa::TYPE_BOARD:
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

