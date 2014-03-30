<?php

class module_nominees extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->memos = array();

		if (null == _root::getParam('idSelection', null)) {
			_root::redirect('awards:index');
		}
		$oSelection = model_selection::getInstance()->findById(_root::getParam('idSelection'));
		$this->setMemo('oSelection', $oSelection);
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
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

	private function buildContextBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->setTitle('Nominés', new NavLink('nominees', 'index', array('idSelection' => _root::getParam('idSelection'))));
		$navBar->addChild(new Bar('left'));
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
		$bar = $pNavBar->getChild('left');
		if (_root::getParam('idSelection', null)) {
			$toAwards = model_award::getInstance()->findAllBySelectionId(_root::getParam('idSelection'));
			if (count($toAwards) > 0) {
				$item = plugin_BsHtml::buildDropdownActivable('Prix');
				foreach ($toAwards as $oAward) {
					$item->addChild(plugin_BsHtml::buildMenuItem($oAward->toString(),
						new NavLink('awards', 'read', array('id' => $oAward->getId()))));
				}
				$item->addChild(plugin_BsHtml::buildSeparator());
				$item->addChild(plugin_BsHtml::buildMenuItem('Tous les prix', new NavLink('awards', 'index')));
				if ($item->hasRealChildren()) {
					$bar->addChild($item);
				}
			}
		}
		$item = plugin_BsHtml::buildDropdownActivable('Sélections');
		$item->addChild(plugin_BsHtml::buildMenuItem($this->getMemo('oSelection')->toString(),
			new NavLink('selections', 'read', array('id' => _root::getParam('idSelection')))));
		$item->addChild(plugin_BsHtml::buildSeparator());
		$item->addChild(plugin_BsHtml::buildMenuItem('Toutes les sélections', new NavLink('selections', 'index')));
		if ($item->hasRealChildren()) {
			$bar->addChild($item);
		}

		$item = plugin_BsHtml::buildDropdownActivable('Nominés');
		$item->addChild(plugin_BsHtml::buildMenuItem($this->getMemo('oSelection')->toString(),
			new NavLink('nominees', 'index', array('idSelection' => _root::getParam('idSelection')))));
		if ($item->hasRealChildren()) {
			$bar->addChild($item);
		}
		if (_root::getParam('id', null)) {
			$toDocs = model_doc::getInstance()->findAllByTitleId(_root::getParam('id'));
			if (count($toDocs) > 0) {
				$item = plugin_BsHtml::buildDropdownActivable('Albums');
				foreach ($toDocs as $oDoc) {
					$item->addChild(plugin_BsHtml::buildMenuItem($oDoc->toString(), new NavLink('docs', 'read', array('id' => $oDoc->getId()))));
				}
				$item->addChild(plugin_BsHtml::buildSeparator());
				$item->addChild(plugin_BsHtml::buildMenuItem('Tous les albums', new NavLink('docs', 'index')));
				if ($item->hasRealChildren()) {
					$bar->addChild($item);
				}
			}
		}
	}

	/**
	 * @param NavBar $pNavBar
	 */
	private function buildContextRightBar($pNavBar)
	{
		$bar = $pNavBar->getChild('right');

		$tParamSelection = array('idSelection' => _root::getParam('idSelection'));
		$bar->addChild(plugin_BsHtml::buildButtonItem('Créer', new NavLink('nominees', 'create', $tParamSelection), 'glyphicon-plus-sign'));
		plugin_BsContextBar::buildRUDContextBar($bar, $tParamSelection);
		$bar->addChild(plugin_BsHtml::buildSeparator());

		$group = new GroupButtonItem('list');
		switch (_root::getAction()) {
			case 'list':
			case 'listThumbnail':
			case 'listThumbnailLarge':
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Liste', new NavLink('nominees', 'list', $tParamSelection),
					'glyphicon-list'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes', new NavLink('nominees', 'listThumbnail', $tParamSelection),
					'glyphicon-th'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes Larges',
					new NavLink('nominees', 'listThumbnailLarge', $tParamSelection), 'glyphicon-th-large'));
				break;
		}
		if ($group->hasRealChildren()) {
			$bar->addChild($group);
		}
	}

	public function _index()
	{
		_root::getRequest()->setAction('list');
		$this->_list();
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

		$oView = new _view('nominees::edit');
		$oView->oTitle = $oTitle;
		$oView->oSelection = $this->getMemo('oSelection');
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);

		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer un nouveau nominé';

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

		$oView = new _view('nominees::edit');
		$oView->oTitle = $oTitle;
		$oView->oSelection = $this->getMemo('oSelection');
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier ' . $oTitle->toString();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _list()
	{
		$oView = new _view('nominees::list');
		$oView->oSelection = $this->getMemo('oSelection');
		$oView->toTitles = $this->getMemo('oSelection')->findTitles();

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnailLarge()
	{
		$oView = new _view('nominees::listThumbnailLarge');
		$oView->oSelection = $this->getMemo('oSelection');
		$oView->toTitles = $this->getMemo('oSelection')->findTitles();

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnail()
	{
		$oView = new _view('nominees::listThumbnail');
		$oView->oSelection = $this->getMemo('oSelection');
		$oView->toTitles = $this->getMemo('oSelection')->findTitles();

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
		if (_root::getParam('idDoc')) {
			$oTitle = model_title::getInstance()->findByDocIdSelectionId(_root::getParam('idDoc'), _root::getParam('idSelection'));
		} else {
			$oTitle = model_title::getInstance()->findById(_root::getParam('id'));
		}

		$toDocs = $oTitle->findDocs();

		$oView = new _view('nominees::show');
		$oView->oTitle = $oTitle;
		$oView->toDocs = $toDocs;
		$oView->oSelection = $this->getMemo('oSelection');


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
		// Récupère les titres associés
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
				// Sauvegarde si valide
				if ($oTitle->isValid()) {
					$oTitle->save();
					$oTitleModel->saveTitleDocs($oTitle->title_id, $tTitleDocs);
					$oTitleModel->saveSelectionTitle($idSelection, $oTitle->title_id);
					_root::redirect('nominees::list', array('idSelection' => $idSelection));
				}
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
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$iId = _root::getParam('id', null);
		$idSelection = _root::getParam('idSelection');
		if (null != $iId) {
			// Supprime la relation avec la Sélection
			$oTitleModel = new model_title();
			$oTitleModel->deleteSelectionTitle($idSelection, $iId);
			// Supprime le titre si aucune autre Sélection ne l'utilise
			$tSelections = model_selection::getInstance()->findAllByTitleId($iId);
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
