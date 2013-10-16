<?php

class module_docs extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		
		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
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
		
		$oDocModel = new model_doc();
		$tDocs = $oDocModel->findAll();
		
		$oView = new _view('docs::list');
		$oView->tDocs = $tDocs;
		
		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$oDocModel = new model_doc();
		
		$oDoc = $this->save();
		if (null == $oDoc) {
			$oDoc = new row_doc();
		} else {
			$tMessage = $oDoc->getMessages();
		}
		
		$oView = new _view('docs::edit');
		$oView->oDoc = $oDoc;
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Ajouter un album';
		$oView->iconTitle = 'glyphicon glyphicon-plus';
		
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
		$oView->iconTitle = 'glyphicon glyphicon-pencil';
		
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
		if (! _root::getRequest()->isPost()) { // si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf = new plugin_xsrf();
		if (! $oPluginXsrf->checkToken(_root::getParam('token'))) { // on verifie que le token est valide
			$oDoc = new row_doc();
			$oDoc->setMessages(array(
				'token' => $oPluginXsrf->getMessage()
			));
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
			_root::redirect('docs::read', array(
				'id' => $oDoc->getId()
			));
		}
		return $oDoc;
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
		$this->oLayout->show();
	}
}
