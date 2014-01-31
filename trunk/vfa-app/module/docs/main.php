<?php

class module_docs extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		$this->oLayout = new _layout('tpl_bs_bar_context');
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

		$oDocModel = new model_doc();
		$tDocs = $oDocModel->findAll();

		$oView = new _view('docs::list');
		$oView->tDocs = $tDocs;

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnailLarge()
	{
		$oDocModel = new model_doc();
		$tDocs = $oDocModel->findAll();

		$oView = new _view('docs::listThumbnailLarge');
		$oView->tDocs = $tDocs;

		$this->oLayout->add('work', $oView);
	}

	public function _listThumbnail()
	{
		$oDocModel = new model_doc();
		$tDocs = $oDocModel->findAll();

		$oView = new _view('docs::listThumbnail');
		$oView->tDocs = $tDocs;

		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$oDoc = $this->save();
		if (null == $oDoc) {
			$oDoc = new row_doc();
		} else {
			$tMessage = $oDoc->getMessages();
		}

		$oView = new _view('docs::edit');
		$oView->oDoc = $oDoc;
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer un album';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$oDocModel = new model_doc();

		$oDoc = $this->save();
		if (null == $oDoc) {
			$oDoc = $oDocModel->findById(_root::getParam('id'));
		} else {
			$tMessage = $oDoc->getMessages();
		}

		$oView = new _view('docs::edit');
		$oView->oDoc = $oDoc;
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier un album';

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('docs::read');
		$oView->oViewShow = $this->buildViewShow();

		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oDocModel = new model_doc();
		$oDoc = $oDocModel->findById(_root::getParam('id'));
		$toAwards = model_award::getInstance()->findAllByDocId(_root::getParam('id'));

		$oView = new _view('docs::show');
		$oView->oDoc = $oDoc;
		$oView->toAwards = $toAwards;
		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('docs::delete');
		$oView->oViewShow = $this->buildViewShow();

		$oView->ok = true;
		if ($oView->oViewShow->toAwards) {
			$oView->ok = false;
		}

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
			$oDoc = new row_doc();
			$oDoc->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oDoc;
		}

		$oDocModel = new model_doc();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oDoc = new row_doc();
		} else {
			$oDoc = $oDocModel->findById(_root::getParam('id', null));
		}
		// Copie la saisie dans un enregistrement
		foreach ($oDocModel->getListColumn() as $sColumn) {
			if (in_array($sColumn, $oDocModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oDoc->$sColumn)) {
				$oDoc->$sColumn = null;
			} else {
				$oDoc->$sColumn = _root::getParam($sColumn, null);
			}
		}
		// Champ de tri : déplace l'article à la fin du titre s'il existe
		$oDoc->order_title = plugin_vfa::pushArticle($oDoc->title);
		if (null == $oDoc->date_legal) {
			$date = new plugin_date(date('Y-m-d'), 'Y-m-d');
			$oDoc->date_legal = $date->toString();
		}
		if ($oDoc->isValid()) {
			$oDoc->save();
			_root::redirect('docs::read', array('id' => $oDoc->getId()));
		}
		return $oDoc;
	}

	public function delete()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			return array('token' => $oPluginXsrf->getMessage());
		}

		$oDocModel = new model_doc();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oDoc = $oDocModel->findById(_root::getParam('id', null));
			$oDoc->delete();
		}
		_root::redirect('docs::list');
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
		$navBar->setTitle('Albums', new NavLink('docs', 'index'));
		$navBar->addChild(new BarButtons('left'));
		$navBar->addChild(new BarButtons('right'));
		plugin_BsContextBar::buildDefaultContextBar($navBar);
		$this->buildContextButtonBar($navBar);
		return $navBar;
	}

	private function buildContextButtonBar($pNavBar)
	{
		$bar = $pNavBar->getChild('right');
		$group = new GroupButtonItem('list');
		switch (_root::getAction()) {
			case 'list':
			case 'listThumbnail':
			case 'listThumbnailLarge':
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Liste', new NavLink('docs', 'list'), 'glyphicon-list'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes', new NavLink('docs', 'listThumbnail'), 'glyphicon-th'));
				$group->addChild(plugin_BsHtml::buildGroupedButtonItem('Vignettes Larges', new NavLink('docs', 'listThumbnailLarge'),
					'glyphicon-th-large'));
				break;
		}
		if ($group->hasRealChildren()) {
			$bar->addChild($group);
		}
	}
}
