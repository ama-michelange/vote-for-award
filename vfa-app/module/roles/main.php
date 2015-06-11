<?php

/**
 * Class module_roles
 */
class module_roles extends abstract_module
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
		$navBar->setTitle('Rôles', new NavLink('roles', 'index'));
		$navBar->addChild(new BarButtons('left'));
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
		$oRoleModel = new model_role();
		$tRoles = $oRoleModel->findAll();

		$oView = new _view('roles::list');
		$oView->tRoles = $tRoles;

		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$tCompleteAclModules = $this->buildCompleteAclModules();

		$oRole = $this->save($tCompleteAclModules);
		if (null == $oRole) {
			$oRole = new row_role();
		} else {
			$tMessage = $oRole->getMessages();
		}

		$oView = new _view('roles::edit');
		$oView->oRole = $oRole;
		$oView->tCompleteAclModules = $tCompleteAclModules;
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer un rôle';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$oRoleModel = new model_role();
		$tCompleteAclModules = $this->buildCompleteAclModules();

		$oRole = $this->save($tCompleteAclModules);
		if (null == $oRole) {
			$oRole = $oRoleModel->findById(_root::getParam('id'));
		} else {
			$tMessage = $oRole->getMessages();
		}

		$oView = new _view('roles::edit');
		$oView->oRole = $oRole;
		$oView->tCompleteAclModules = $this->addCheckedAcl($tCompleteAclModules, $oRole->findAuthorizations());
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier un rôle';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('roles::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oRoleModel = new model_role();
		$oRole = $oRoleModel->findById(_root::getParam('id'));
		$tCompleteAclModules = $this->buildCompleteAclModules();

		$oView = new _view('roles::show');
		$oView->oRole = $oRole;
		$oView->tCompleteAclModules = $this->addCheckedAcl($tCompleteAclModules, $oRole->findAuthorizations());

		return $oView;
	}

	private function addCheckedAcl($ptCompleteAclModules, $poAuthorizations)
	{
		foreach ($poAuthorizations as $oAuth) {
			$module = $oAuth->module;
			$key = $oAuth->module . '::' . $oAuth->action;
			if (isset($ptCompleteAclModules[$module][$key]['checked'])) {
				$ptCompleteAclModules[$module][$key]['checked'] = true;
			}
		}
		return $ptCompleteAclModules;
	}

	private function buildCompleteAclModules()
	{
		$aclModules = _root::getConfigVar('acl.modules');
		$tAclModules = explode(',', $aclModules);

		$pathModule = _root::getConfigVar('path.module');
		$oDir = new _dir($pathModule);

		$tModuleDir = $oDir->getListDir();
		$tModules = array();
		foreach ($tModuleDir as $oModuleDir) {
			$sModuleDirname = $oModuleDir->getName();
			if (in_array($sModuleDirname, $tAclModules)) {
				$tDetailModules = array();
				$tDetailModules['module'] = $sModuleDirname;
				$tMethods = get_class_methods('module_' . $sModuleDirname);
				$filterMethods = array_filter($tMethods, 'filterMethod');
				natcasesort($filterMethods);
				$tDetailModules['methods'] = array_map("transformMethod", $filterMethods);
				$tModules[$sModuleDirname] = $tDetailModules;
			}
		}
		//var_dump($tModules);

		$tComplete = array();
		foreach ($tModules as $tDetail) {
			$module = $tDetail['module'];
			$tActions = array();
			foreach ($tDetail['methods'] as $method) {
				$checkbox = array();
				$checkbox['action'] = $method;
				$checkbox['checked'] = false;
				$tActions[$module . '::' . $method] = $checkbox;
			}
			$tComplete[$module] = $tActions;
		}
		ksort($tComplete);
		// var_dump($tComplete);
		return $tComplete;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('roles::delete');
		$oView->oViewShow = $this->buildViewShow();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	public function save($ptCompleteAclModules)
	{
		if (!_root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			$oRole = new row_role();
			$oRole->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oRole;
		}

		$oRoleModel = new model_role();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oRole = new row_role();
		} else {
			$oRole = $oRoleModel->findById($iId);
		}
		// Copie la saisie dans un enregistrement
		foreach ($oRoleModel->getListColumn() as $sColumn) {
			if (in_array($sColumn, $oRoleModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oRole->$sColumn)) {
				$oRole->$sColumn = null;
			} else {
				$oRole->$sColumn = _root::getParam($sColumn, null);
			}
		}

		if ($oRole->isValid()) {
			$oRole->save();
			$this->saveRoleAcl($oRole->getId(), $ptCompleteAclModules);
			_root::redirect('roles::read', array('id' => $oRole->getId()));
		}
		return $oRole;
	}

	public function saveRoleAcl($pIdRole, $ptCompleteAclModules)
	{
		if (!_root::getRequest()->isPost()) {
			return;
		}
		// Recup la saisie
		$toAuthorizations = array();
		foreach ($ptCompleteAclModules as $module => $tActions) {
			foreach ($tActions as $key => $checkbox) {
				$value = _root::getParam($key, null);
				if ($value) {
					$oAuth = new row_authorization();
					$oAuth->role_id = $pIdRole;
					$oAuth->module = $module;
					$oAuth->action = $checkbox['action'];
					$toAuthorizations[] = $oAuth;
				}
			}
		}
		// var_dump($toAuthorizations);
		model_authorization::getInstance()->saveRoleAuthorizations($pIdRole, $toAuthorizations);
	}

	public function delete()
	{
		if (!_root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$oRoleModel = new model_role();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oRole = $oRoleModel->deleteRoleCascades($iId);
		}
		_root::redirect('roles::list');
	}
}

function filterMethod($pMethod)
{
	$ret = false;
	$pos0 = substr($pMethod, 0, 1);
	$pos1 = substr($pMethod, 1, 1);
	if (($pos0 == '_') && ($pos1 != '_')) {
		$ret = true;
	}
	return $ret;
}

function transformMethod($pMethod)
{
	return substr($pMethod, 1);
}
