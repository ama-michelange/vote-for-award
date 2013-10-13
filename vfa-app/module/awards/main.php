<?php

class module_awards extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		
		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->flagsMenu = array();
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
		
		$oAwardModel = new model_award();
		$tAwards = $oAwardModel->findAll();
		
		$oView = new _view('awards::list');
		$oView->tAwards = $tAwards;
		
		$this->oLayout->add('work', $oView);
	}

	public function _create()
	{
		$tMessage = null;
		$oAwardModel = new model_award();
		$tAwardTitles = null;
		
		$oAward = $this->save();
		if (null == $oAward) {
			$oAward = new row_award();
			$oAward->type = 'PBD';
		} else {
			$tMessage = $oAward->getMessages();
		}
		
		$oView = new _view('awards::edit');
		$oView->oAward = $oAward;
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Créer un prix';
		
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		
		$this->oLayout->add('work', $oView);
	}

	public function _update()
	{
		$tMessage = null;
		$tAwardTitles = null;
		$oAwardModel = new model_award();
		
		$oAward = $this->save();
		if (null == $oAward) {
			$oAward = $oAwardModel->findById(_root::getParam('id'));
		} else {
			$tMessage = $oAward->getMessages();
		}
		
		$oView = new _view('awards::edit');
		$oView->oAward = $oAward;
		
		$oView->tMessage = $tMessage;
		$oView->textTitle = 'Modifier un prix';
		
		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		
		$this->oLayout->add('work', $oView);
	}

	public function _read()
	{
		$oView = new _view('awards::read');
		$oView->oViewShow = $this->buildViewShow();
		
		$this->oLayout->add('work', $oView);
	}

	public function buildViewShow()
	{
		$oAwardModel = new model_award();
		$oAward = $oAwardModel->findById(_root::getParam('id'));
		$toTitles = $oAward->findTitles();
		
		$oView = new _view('awards::show');
		$oView->oAward = $oAward;
		$oView->toTitles = $toTitles;
		
		$flags = array();
		if ($toTitles) {
			$flags['titles'] = true;
		} else {
			$flags['titles'] = false;
		}
		$this->flagsMenu =$flags;
		
		return $oView;
	}

	public function _delete()
	{
		$tMessage = $this->delete();
		
		$oView = new _view('awards::delete');
		$oView->oViewShow = $this->buildViewShow();
		
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
			$oAward = new row_award();
			$oAward->setMessages(array(
				'token' => $oPluginXsrf->getMessage()
			));
			return $oAward;
		}
		
		$oAwardModel = new model_award();
		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oAward = new row_award();
		} else {
			$oAward = $oAwardModel->findById(_root::getParam('id', null));
		}
		// Copie la saisie dans un enregistrement
		foreach ($oAwardModel->getListColumn() as $sColumn) {
			if (in_array($sColumn, $oAwardModel->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oAward->$sColumn)) {
				$oAward->$sColumn = null;
			} elseif (('start_date' == $sColumn) || ('end_date' == $sColumn)) {
				$oAward->$sColumn = plugin_vfa::toStringDateSgbd(_root::getParam($sColumn, null));
			} elseif ('public' == $sColumn) {
				if (_root::getParam($sColumn, null)) {
					$oAward->$sColumn = 1;
				} else {
					$oAward->$sColumn = 0;
				}
			} else {
				$oAward->$sColumn = _root::getParam($sColumn, null);
			}
		}
		
		if ($oAward->isValid()) {
			$oAward->save();
			_root::redirect('awards::read', array(
				'id' => $oAward->award_id
			));
		}
		return $oAward;
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
		
		$oAwardModel = new model_award();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oAward = $oAwardModel->deleteAwardCascades($iId);
		}
		_root::redirect('awards::list');
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_vfa_menu::buildViewNavTopCrud($this->flagsMenu));
		
		$this->oLayout->show();
	}
}
