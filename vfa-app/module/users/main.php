<?php

class module_users extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		
		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-left', plugin_vfa_menu::buildViewNavLeft());
		$this->oLayout->add('bsnav-top', plugin_vfa_menu::buildViewNavTopCrud());
	}

	public function _index()
	{
		// on considere que la page par defaut est la page de listage
		$this->_list();
	}

	public function _list()
	{
		// Force l'action pour n'avoir qu'un seul test dans le menu contextuel
		_root::getRequest()->setAction('list');
		
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
		$tGroups = _root::getAuth()->getAccount()->getGroups();
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
		$tColumns = array(
			'username',
			'last_name',
			'first_name',
			'email',
			'birthyear',
			'gender',
			'vote'
		);
		
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
		$tColumns = array(
			'user_id',
			'username',
			'last_name',
			'first_name',
			'email',
			'birthyear',
			'gender',
			'vote'
		);
		
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
		// $oView->oUser=$oUser;
		// $oView->tColumn=$oUserModel->getListColumn();
		// $oView->tId=$oUserModel->getIdTab();
		
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
		if (! _root::getRequest()->isPost()) {
			// si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf = new plugin_xsrf();
		if (! $oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			$oDoc = new row_title();
			$oDoc->setMessages(array(
				'token' => $oPluginXsrf->getMessage()
			));
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
			} elseif ('vote' == $sColumn) {
				if ('on' == _root::getParam($sColumn, null)) {
					$oUser->$sColumn = 1;
				} else {
					$oUser->$sColumn = 0;
				}
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
			$bSave = false;
			$oUserDoublon = $oUserModel->findByLogin(_root::getParam('username', null));
			if ((null == $oUserDoublon) || (true == $oUserDoublon->isEmpty())) {
				$bSave = true;
			} else 
				if ((null != $oUser->getId()) && ($oUserDoublon->getId() == $oUser->getId())) {
					$bSave = true;
				}
			if (true == $bSave) {
				$oUser->save();
				$oUserModel->saveUserRoles($oUser->user_id, $tUserRoles);
				$oUserModel->saveUserGroups($oUser->user_id, $tUserGroups);
				_root::redirect('users::read', array(
					'id' => $oUser->user_id
				));
			} else {
				$oUser->setMessages(array(
					'username' => array(
						'doublon'
					)
				));
			}
		}
		// return $oUser->getListError();
		return $oUser;
	}

	public function delete()
	{
		if (! _root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf = new plugin_xsrf();
		if (! $oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			return array(
				'token' => $oPluginXsrf->getMessage()
			);
		}
		
		$oUserModel = new model_user();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oUserModel->deleteUserCascades($iId);
		}
		_root::redirect('users::list');
	}

	public function after()
	{
		$this->oLayout->show();
	}
}

