<?php

class module_groups extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));

		$this->allTypes = false;
		if (_root::getACL()->isInRole('owner')) {
			$this->allTypes = true;
		}
	}

	public function after()
	{
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->setTitle('Groupes', new NavLink('groups', 'index'));
		$navBar->addChild(new BarButtons('right'));
		plugin_BsContextBar::buildDefaultContextBar($navBar->getChild('right'));
		return $navBar;
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

		if ($this->allTypes) {
			$tGroups = model_group::getInstance()->findAll();
		} else {
			$tGroups = model_group::getInstance()->findAllByRoleName(plugin_vfa::ROLE_READER);
		}

		$oView = new _view('groups::list');
		$oView->tGroups = $tGroups;

		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$tRoles = null;

		$oGroup = $this->save();
		if (null == $oGroup) {
			$oGroup = new row_group();
			$oGroup->role_id_default = model_role::getInstance()->findByName(plugin_vfa::ROLE_READER)->role_id;
		} else {
			$tMessage = $oGroup->getMessages();
		}

		$oView = new _view('groups::edit');
		$oView->oGroup = $oGroup;
		if ($this->allTypes) {
			$oView->tSelectedRoles = plugin_vfa::buildOptionSelected(model_role::getInstance()->getSelectAll(), $tRoles);
		}
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer un groupe';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$oGroupModel = new model_group();

		$oGroup = $this->save();
		if (null == $oGroup) {
			$oGroup = $oGroupModel->findById(_root::getParam('id'));
			if ($this->allTypes) {
				$tRoles = model_role::getInstance()->getSelectById($oGroup->role_id_default);
			}
		} else {
			$tMessage = $oGroup->getMessages();
			$tRoles = array(_root::getParam('role_id_default') => _root::getParam('role_id_default'));
		}

		$oView = new _view('groups::edit');
		$oView->oGroup = $oGroup;
		if ($this->allTypes) {
			$oView->tSelectedRoles = plugin_vfa::buildOptionSelected(model_role::getInstance()->getSelectAll(), $tRoles);
		}

		$tInProgressAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER);
		$tInProgressSelect = plugin_vfa::toSelect($tInProgressAwards, 'award_id', null, 'toString');
		$tAwards = $oGroup->findAwards();
		$tSelect = plugin_vfa::toSelect($tAwards, 'award_id', null, 'toString');
		$oView->tSelectedAwards = plugin_vfa::buildOptionSelected($tInProgressSelect, $tSelect);

		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier un groupe';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('groups::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oGroup = model_group::getInstance()->findById(_root::getParam('id'));
		$oRole = model_role::getInstance()->findById($oGroup->role_id_default);
		$toUsers = null;
		if (_root::getAction() == 'read') {
			$toUsers = $oGroup->findUsers();
		}
		$countUsers = $oGroup->countUsers();

		$oView = new _view('groups::show');
		$oView->oGroup = $oGroup;
		$oView->oRole = $oRole;
		$oView->toUsers = $toUsers;
		$oView->countUsers = $countUsers;

		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('groups::delete');
		$oView->oViewShow = $this->buildViewShow();

		$oView->ok = true;
		if ($oView->oViewShow->countUsers) {
			$oView->ok = false;
		}
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	public function save()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			$oGroup = new row_group();
			$oGroup->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oGroup;
		}

		$oGroupModel = new model_group();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oGroup = new row_group();
		} else {
			$oGroup = $oGroupModel->findById(_root::getParam('id', null));
		}

		foreach ($oGroupModel->getListColumn() as $sColumn) {
			if (in_array($sColumn, $oGroupModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oGroup->$sColumn)) {
				$oGroup->$sColumn = null;
			} else {
				$oGroup->$sColumn = _root::getParam($sColumn, null);
			}
		}

		$awardIds = _root::getParam('award_ids', null);

		if ($oGroup->isValid()) {
			$oGroup->save();
			model_group::getInstance()->saveGroupAwards($oGroup->getId(), $awardIds);
			_root::redirect('groups::read', array('id' => $oGroup->getId()));
		}
		return $oGroup;
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

		$oGroupModel = new model_group();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oGroup = $oGroupModel->findById(_root::getParam('id', null));
		}

		$oGroup->delete();
		_root::redirect('groups::list');
	}
}
