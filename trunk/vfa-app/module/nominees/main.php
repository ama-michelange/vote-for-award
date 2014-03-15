<?php

class module_nominees extends abstract_module
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
		$navBar->setTitle('Albums sélectionnés', new NavLink('nominees', 'index', array('idSelection' => _root::getParam('idSelection'))));
		$navBar->addChild(new BarButtons('left'));
		$this->buildContextLeftBar($navBar);
		$navBar->addChild(new BarButtons('right'));
		$this->buildContextRightBar($navBar);
		return $navBar;
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildContextLeftBar($pNavBar)
	{
		$tParamSelection = array('idSelection' => _root::getParam('idSelection'));
		$bar = $pNavBar->getChild('left');
		if (false === strpos(_root::getAction(), 'list')) {
			$bar->addChild(plugin_BsHtml::buildButtonItem('Liste', new NavLink('nominees', 'list', $tParamSelection), 'glyphicon-list'));
		}
		$bar->addChild(plugin_BsHtml::buildButtonItem('Créer', new NavLink('nominees', 'create', $tParamSelection),
			'glyphicon-plus-sign'));
		plugin_BsContextBar::buildRUDContextBar($pNavBar, $tParamSelection);
		if ('listSelections' != _root::getAction()) {
			$bar->addChild(plugin_BsHtml::buildSeparator());
			$bar->addChild(plugin_BsHtml::buildButtonItem('Sélection',
				new NavLink('selections', 'read', array('id' => _root::getParam('idSelection'))), 'glyphicon-eye-open'));
		}
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildContextRightBar($pNavBar)
	{
		$bar = $pNavBar->getChild('right');
		$group = new GroupButtonItem('list');
		switch (_root::getAction()) {
			case 'list':
			case 'listThumbnail':
			case 'listThumbnailLarge':
				$idSelection = _root::getParam('idSelection');
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Liste',
					new NavLink('nominees', 'list', array('idSelection' => $idSelection)), 'glyphicon-list'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes',
					new NavLink('nominees', 'listThumbnail', array('idSelection' => $idSelection)), 'glyphicon-th'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes Larges',
					new NavLink('nominees', 'listThumbnailLarge', array('idSelection' => $idSelection)), 'glyphicon-th-large'));
				break;
		}
		if ($group->hasRealChildren()) {
			$bar->addChild($group);
		}
	}

	public function _index()
	{
		if (null == _root::getParam('idSelection', null)) {
			_root::getRequest()->setAction('listSelections');
			$this->_listSelections();
		} else {
			_root::getRequest()->setAction('list');
			$this->_list();
		}
	}

	public function _listSelections()
	{
		$oSelectionModel = new model_selection();
		$tSelections = $oSelectionModel->findAll();

		$oView = new _view('nominees::listSelections');
		$oView->tSelections = $tSelections;

		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$tTitleDocs = null;

		$oTitle = $this->save();
		if (null == $oTitle) {
			$oTitle = new row_title();
		} else {
			$tMessage = $oTitle->getMessages();
			$tTitleDocs = plugin_vfa::copyValuesToKeys(_root::getParam('title_docs', null));
		}
		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('idSelection'));

		$oView = new _view('nominees::edit');
		$oView->oTitle = $oTitle;
		$oView->oSelection = $oSelection;
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);

		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Ajouter un album sélectionné';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$tTitleDocs = null;
		$oTitleModel = new model_title();

		$oTitle = $this->save();
		if (null == $oTitle) {
			$oTitle = $oTitleModel->findById(_root::getParam('id'));
			$tTitleDocs = $oTitle->getSelectDocs();
		} else {
			$tMessage = $oTitle->getMessages();
			$tTitleDocs = plugin_vfa::copyValuesToKeys(_root::getParam('title_docs', null));
		}

		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('idSelection'));

		$oView = new _view('nominees::edit');
		$oView->oTitle = $oTitle;
		$oView->oSelection = $oSelection;
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier l\'album sélectionné : ' . $oTitle->toString();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _list()
	{
		$oView = new _view('nominees::list');
		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('idSelection'));
		$toTitles = $oSelection->findTitles();

		$oView->oSelection = $oSelection;
		$oView->toTitles = $toTitles;

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnailLarge()
	{
		$oView = new _view('nominees::listThumbnailLarge');
		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('idSelection'));
		$toTitles = $oSelection->findTitles();

		$oView->oSelection = $oSelection;
		$oView->toTitles = $toTitles;

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnail()
	{
		$oView = new _view('nominees::listThumbnail');
		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('idSelection'));
		$toTitles = $oSelection->findTitles();

		$oView->oSelection = $oSelection;
		$oView->toTitles = $toTitles;

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('nominees::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function _readWithDoc()
	{
		$oTitle = model_title::getInstance()->findByDocIdSelectionId(_root::getParam('idDoc'), _root::getParam('idSelection'));
		_root::redirect('nominees::read', array('id' => $oTitle->getId(), 'idSelection' => _root::getParam('idSelection')));
	}

	public function buildViewShow()
	{
		$oTitleModel = new model_title();
		if (_root::getParam('idDoc')) {
			$oTitle = $oTitleModel->findByDocIdSelectionId(_root::getParam('idDoc'), _root::getParam('idSelection'));
		} else {
			$oTitle = $oTitleModel->findById(_root::getParam('id'));
		}

		$toDocs = $oTitle->findDocs();
		$oSelectionModel = new model_selection();
		$oSelection = $oSelectionModel->findById(_root::getParam('idSelection'));

		$oView = new _view('nominees::show');
		$oView->oTitle = $oTitle;
		$oView->toDocs = $toDocs;
		$oView->oSelection = $oSelection;

		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('nominees::delete');
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
			$oTitle = new row_title();
			$oTitle->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oTitle;
		}

		$oTitleModel = new model_title();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oTitle = new row_title();
		} else {
			$oTitle = $oTitleModel->findById(_root::getParam('id', null));
		}
		$idSelection = _root::getParam('idSelection');

		// Copie la saisie dans un enregistrement
		foreach ($oTitleModel->getListColumn() as $sColumn) {
			if (in_array($sColumn, $oTitleModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oTitle->$sColumn)) {
				$oTitle->$sColumn = null;
			} else {
				$oTitle->$sColumn = _root::getParam($sColumn, null);
			}
		}
		// Récupère les albums associés
		$tTitleDocs = _root::getParam('title_docs', null);
		if ($tTitleDocs) {
			// Récupère les enregistrements des albums
			$tRowDoc = array();
			$oDocModel = new model_doc();
			foreach ($tTitleDocs as $idDoc) {
				$tRowDoc[] = $oDocModel->findById($idDoc);
			}
			// Vérifie que tous les albums ont le même titre
			$sDocTitle = $tRowDoc[0]->title;
			$bOk = true;
			foreach ($tRowDoc as $oDoc) {
				$bOk = $bOk && ($sDocTitle == $oDoc->title);
			}
			if ($bOk) {
				// Force le titre avec le 1er enregistrement des albums
				$oTitle->title = plugin_vfa::formatTitle($tRowDoc);
				$oTitle->numbers = plugin_vfa::formatTitleNumbers($tRowDoc);
				// Champ de tri : déplace l'article à la fin du titre s'il existe
				$oTitle->order_title = plugin_vfa::pushArticle($oTitle->title) . $oTitle->numbers;
				// Vérifie que le titre n'existe pas déjà
//				$oTitleDoublon = $this->findDoublon($oTitleModel, $oTitle);
//				if (null == $oTitleDoublon) {
				// Sauvegarde si valide
				if ($oTitle->isValid()) {
					$oTitle->save();
					$oTitleModel->saveTitleDocs($oTitle->title_id, $tTitleDocs);
					$oTitleModel->saveSelectionTitle($idSelection, $oTitle->title_id);
					_root::redirect('nominees::list', array('idSelection' => $idSelection));
				}
//				} else {
//					$oTitleModel->saveSelectionTitle($idSelection, $oTitleDoublon->title_id);
//					_root::redirect('nominees::list', array('idSelection' => $idSelection));
//				}
			} else {
				$tMessage['title_docs'][] = 'all-equals';
				$oTitle->setMessages($tMessage);
			}
		} else {
			$tMessage['title_docs'][] = 'required-selection';
			$oTitle->setMessages($tMessage);
		}
		return $oTitle;
	}

	/**
	 * Recherche si le titre existe déjà
	 * @param model_title $poTitleModel
	 * @param row_title $poTitle
	 * @return row_title Le doublon trouvé ou null sinon
	 */
	public function findDoublon($poTitleModel, $poTitle)
	{
		$oTitleDoublon = $poTitleModel->findByTitleAndNumbers($poTitle->title, $poTitle->numbers);
		if ((null == $oTitleDoublon) || (true == $oTitleDoublon->isEmpty())) {
			$oTitleDoublon = null;
		} else if ((null != $poTitle->getId()) && ($oTitleDoublon->getId() == $poTitle->getId())) {
			$oTitleDoublon = null;
		}
		return $oTitleDoublon;
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

		$iId = _root::getParam('id', null);
		$idSelection = _root::getParam('idSelection');
		if (null != $iId) {
			// Supprime la relation avec le Prix
			$oTitleModel = new model_title();
			$oTitleModel->deleteSelectionTitle($idSelection, $iId);
			// Supprime le titre si aucun autre Prix ne l'utilise
			$oSelectionModel = new model_selection();
			$tSelections = $oSelectionModel->findAllByTitleId($iId);
			$count = count($tSelections);
			if (1 == $count) {
				$oTitle = $oTitleModel->findById($iId);
				$oTitle->delete();
				// Supprime la relation avec les documents
				$oTitleModel->deleteTitleDocs($iId);
			}
		}
		_root::redirect('nominees::list', array('idSelection' => $idSelection));
	}
}
