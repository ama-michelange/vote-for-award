<?php

class module_selections extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();

		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->memos = array();
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
		$navBar->setTitle('Sélections', new NavLink('selections', 'index'));
		$navBar->addChild(new BarButtons('left'));
		plugin_BsContextBar::buildDefaultContextBar($navBar);
		if ($this->getMemo('oSelection')) {
			$oSelection = $this->getMemo('oSelection');
			$tParamSelection = array('idSelection' => $oSelection->selection_id);

			$bar = $navBar->getChild('left');
			$bar->addChild(plugin_BsHtml::buildSeparator());

			$item = new DropdownButtonItem('Nominés');
			$item->addChild(plugin_BsHtml::buildMenuItem('Nominés de ' . $oSelection->toString(),
				new NavLink('nominees', 'list', $tParamSelection), 'glyphicon-list'));
			$item->addChild(plugin_BsHtml::buildMenuItem('Ajouter un nominé à ' . $oSelection->toString(),
				new NavLink('nominees', 'create', $tParamSelection), 'glyphicon-plus-sign'));
			$bar->addChild($item);

			$toAwards = model_award::getInstance()->findAllBySelectionId($oSelection->selection_id);
			$item = new DropdownButtonItem('Prix');
			foreach ($toAwards as $oAward) {
				$item->addChild(plugin_BsHtml::buildMenuItem($oAward->toString(),
					new NavLink('awards', 'read', array('id' => $oAward->getId())), 'glyphicon-eye-open'));

			}
			$bar->addChild($item);
		}
		return $navBar;
	}

	private function getMemo($pKey)
	{
		$memos = $this->memos;
		if ($memos && isset($memos[$pKey])) {
			return $memos[$pKey];
		}
		return null;
	}

	private function setMemo($pKey, $pValue)
	{
		$memos = $this->memos;
		$memos[$pKey] = $pValue;
		$this->memos = $memos;
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

		$oSelectionModel = new model_selection();
		$tSelections = $oSelectionModel->findAll();

		$oView = new _view('selections::list');
		$oView->tSelections = $tSelections;

		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$tSelectionTitles = null;

		$oSelection = $this->save();
		if (null == $oSelection) {
			$oSelection = new row_selection();
		} else {
			$tMessage = $oSelection->getMessages();
		}

		$oView = new _view('selections::edit');
		$oView->oSelection = $oSelection;
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer une nouvelle sélection';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$tSelectionTitles = null;
		$oSelectionModel = new model_selection();

		$oSelection = $this->save();
		if (null == $oSelection) {
			$oSelection = $oSelectionModel->findById(_root::getParam('id'));
		} else {
			$tMessage = $oSelection->getMessages();
		}

		$oView = new _view('selections::edit');
		$oView->oSelection = $oSelection;
		$this->setMemo('oSelection', $oSelection);

		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier la sélection : ' . $oSelection->toString();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('selections::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('id'));
		$toTitles = $oSelection->findTitles();

		$oView = new _view('selections::show');
		$oView->oSelection = $oSelection;
		$oView->toTitles = $toTitles;

		$this->setMemo('oSelection', $oSelection);

		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('selections::delete');
		$oView->oViewShow = $this->buildViewShow();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	public function save()
	{
		if (!_root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			$oSelection = new row_selection();
			$oSelection->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oSelection;
		}

		$oSelectionModel = new model_selection();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oSelection = new row_selection();
		} else {
			$oSelection = $oSelectionModel->findById(_root::getParam('id', null));
		}
		// Copie la saisie dans un enregistrement
		foreach ($oSelectionModel->getListColumn() as $sColumn) {
			if (in_array($sColumn, $oSelectionModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oSelection->$sColumn)) {
				$oSelection->$sColumn = null;
			} else {
				$oSelection->$sColumn = _root::getParam($sColumn, null);
			}
		}

		if ($oSelection->isValid()) {
			$oSelection->save();
			_root::redirect('selections::read', array('id' => $oSelection->selection_id));
		}
		return $oSelection;
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

		$oSelectionModel = new model_selection();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oSelectionModel->deleteSelectionCascades($iId);
		}
		_root::redirect('selections::list');
		return null;
	}
}
