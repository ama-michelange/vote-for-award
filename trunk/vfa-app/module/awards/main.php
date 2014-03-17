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

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->setTitle('Prix', new NavLink('awards', 'index'));
		$navBar->addChild(new BarButtons('left'));
		plugin_BsContextBar::buildDefaultContextBar($navBar);
		if (true == $this->hasTitles()) {
			$bar = $navBar->getChild('left');
			$bar->addChild(plugin_BsHtml::buildSeparator());
			$tParamAward = array('idAward' => _root::getParam('id'));
			$bar->addChild(plugin_BsHtml::buildButtonItem('Sélection', new NavLink('nominees', 'list', $tParamAward), 'glyphicon-list'));
			$bar->addChild(plugin_BsHtml::buildButtonItem('Sélectionner', new NavLink('nominees', 'create', $tParamAward), 'glyphicon-heart'));
		}
		return $navBar;
	}

	private function hasTitles()
	{
		$flags = $this->flagsMenu;
		if ($flags && isset($flags['titles'])) {
			return true;
		}
		return false;
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
		$oAward = model_award::getInstance()->findById(_root::getParam('id'));
		$oSelection = model_selection::getInstance()->findById($oAward->selection_id);
		$toTitles = $oAward->findTitles();
		$oView = new _view('awards::show');
		$oView->oAward = $oAward;
		$oView->oSelection = $oSelection;
		$oView->toTitles = $toTitles;

		$flags = array();
		if ($toTitles) {
			$flags['titles'] = true;
		} else {
			$flags['titles'] = false;
		}
		$this->flagsMenu = $flags;

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
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oAward = new row_award();
			$oAward->setMessages(array('token' => $oPluginXsrf->getMessage()));
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
		// Saisie valide ?
		if ($oAward->isValid()) {
			// Doublon ?
			$doublon = $oAwardModel->findByYearNameType($oAward->year, $oAward->name, $oAward->type);
			if (($doublon->isEmpty()) || ($doublon->getId() == $oAward->getId())) {
				// Sauvegarde si pas doublon ou soi-même
				$oAward->save();
				_root::redirect('awards::read', array('id' => $oAward->award_id));
			} else {
				$oAward->setMessages(array('year' => array('doublon'), 'name' => array('doublon'), 'type' => array('doublon')));
			}
		}

		return $oAward;
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

		$oAwardModel = new model_award();
		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oAwardModel->deleteAwardCascades($iId);
		}
		_root::redirect('awards::list');
		return null;
	}
}
