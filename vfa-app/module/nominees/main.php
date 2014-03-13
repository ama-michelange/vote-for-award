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
		$navBar->setTitle('Sélectionnés', new NavLink('nominees', 'index', array('idAward' => _root::getParam('idAward'))));
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
		$tParamAward = array('idAward' => _root::getParam('idAward'));
		$bar = $pNavBar->getChild('left');
		if ('listAwards' != _root::getAction()) {
			if (false === strpos(_root::getAction(), 'list')) {
				$bar->addChild(plugin_BsHtml::buildButtonItem('Liste', new NavLink('nominees', 'list', $tParamAward), 'glyphicon-list'));
			}
			$bar->addChild(plugin_BsHtml::buildButtonItem('Sélectionner', new NavLink('nominees', 'create', $tParamAward), 'glyphicon-heart'));

		}
		plugin_BsContextBar::buildRUDContextBar($pNavBar, $tParamAward);
		$bar->addChild(plugin_BsHtml::buildSeparator());
		$bar->addChild(plugin_BsHtml::buildButtonItem('Prix', new NavLink('awards', 'read', array('id' => _root::getParam('idAward'))),
			'glyphicon-eye-open'));
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
				$idAward = _root::getParam('idAward');
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Liste', new NavLink('nominees', 'list', array('idAward' => $idAward)),
					'glyphicon-list'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes',
					new NavLink('nominees', 'listThumbnail', array('idAward' => $idAward)), 'glyphicon-th'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes Larges',
					new NavLink('nominees', 'listThumbnailLarge', array('idAward' => $idAward)), 'glyphicon-th-large'));
				break;
		}
		if ($group->hasRealChildren()) {
			$bar->addChild($group);
		}
	}

	public function _index()
	{
		if (null == _root::getParam('idAward', null)) {
			_root::getRequest()->setAction('listAwards');
			$this->_listAwards();
		} else {
			_root::getRequest()->setAction('list');
			$this->_list();
		}
	}

	public function _listAwards()
	{
		$oAwardModel = new model_award();
		$tAwards = $oAwardModel->findAll();

		$oView = new _view('nominees::listAwards');
		$oView->tAwards = $tAwards;

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
		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('idAward'));

		$oView = new _view('nominees::edit');
		$oView->oTitle = $oTitle;
		$oView->oAward = $oAward;
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);

		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Sélectionner un titre';

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

		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('idAward'));

		$oView = new _view('nominees::edit');
		$oView->oTitle = $oTitle;
		$oView->oAward = $oAward;
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier un titre sélectionné';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _list()
	{
		$oView = new _view('nominees::list');
		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('idAward'));
		$toTitles = $oAward->findTitles();

		$oView->oAward = $oAward;
		$oView->toTitles = $toTitles;

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnailLarge()
	{
		$oView = new _view('nominees::listThumbnailLarge');
		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('idAward'));
		$toTitles = $oAward->findTitles();

		$oView->oAward = $oAward;
		$oView->toTitles = $toTitles;

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnail()
	{
		$oView = new _view('nominees::listThumbnail');
		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('idAward'));
		$toTitles = $oAward->findTitles();

		$oView->oAward = $oAward;
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
		$oTitle = model_title::getInstance()->findByDocIdAwardId(_root::getParam('idDoc'), _root::getParam('idAward'));
		_root::redirect('nominees::read', array('id' => $oTitle->getId(), 'idAward' => _root::getParam('idAward')));
	}

	public function buildViewShow()
	{
		$oTitleModel = new model_title();
		if (_root::getParam('idDoc')) {
			$oTitle = $oTitleModel->findByDocIdAwardId(_root::getParam('idDoc'), _root::getParam('idAward'));
		} else {
			$oTitle = $oTitleModel->findById(_root::getParam('id'));
		}

		$toDocs = $oTitle->findDocs();
		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('idAward'));

		$oView = new _view('nominees::show');
		$oView->oTitle = $oTitle;
		$oView->toDocs = $toDocs;
		$oView->oAward = $oAward;

		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('nominees::delete');
		$oView->oViewShow = $this->buildViewShow();

		// $oView->ok = true;
		// if ($oView->oViewShow->toAwards) {
		// $oView->ok = false;
		// }

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
				$oTitleDoublon = $this->findDoublon($oTitleModel, $oTitle);
				if (null == $oTitleDoublon) {
					// Sauvegarde si valide
					if ($oTitle->isValid()) {
						$oTitle->save();
						$oTitleModel->saveTitleDocs($oTitle->title_id, $tTitleDocs);
						$oTitleModel->saveAwardTitle(_root::getParam('idAward'), $oTitle->title_id);
						_root::redirect('nominees::list', array('idAward' => _root::getParam('idAward')));
					}
				} else {
					$oTitleModel->saveAwardTitle(_root::getParam('idAward'), $oTitleDoublon->title_id);
					_root::redirect('nominees::list', array('idAward' => _root::getParam('idAward')));
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
		if (!_root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$iId = _root::getParam('id', null);
		$idAward = _root::getParam('idAward');
		if (null != $iId) {
			// Supprime la relation avec le Prix
			$oTitleModel = new model_title();
			$oTitleModel->deleteAwardTitle($idAward, $iId);
			// Supprime le titre si aucun autre Prix ne l'utilise
			$oAwardModel = new model_award();
			$tAwards = $oAwardModel->findAllByTitleId($iId);
			$count = count($tAwards);
			if (1 == $count) {
				$oTitle = $oTitleModel->findById($iId);
				$oTitle->delete();
				// Supprime la relation avec les documents
				$oTitleModel->deleteTitleDocs($iId);
			}
		}
		_root::redirect('nominees::list', array('idAward' => $idAward));
	}
}
