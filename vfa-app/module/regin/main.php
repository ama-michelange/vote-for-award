<?php

class module_regin extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		$this->oLayout = new _layout('tpl_bs_bar_context');
		$this->oGroupBoard = null;
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$navBar = plugin_BsHtml::buildNavBar();

		$navBar->addChild(new Bar('left'));
		$navBar->addChild(new BarButtons('right'));

		switch (_root::getAction()) {
			case 'index':
				$navBar->setTitle('S\'inscrire', new NavLink('regin', 'index'));
				break;
			case 'open':
				$navBar->setTitle('Créer la permission', new NavLink('regin', 'open'));
				break;
			case 'opened':
			case 'update':
			case 'delete':
				$navBar->setTitle('Permission en cours', new NavLink('regin', 'opened'));
				break;
			case 'validate':
				$navBar->setTitle('Valider les inscriptions', new NavLink('regin', 'validate'));
				break;
			case 'openReader':
				$navBar->setTitle('Créer la permission', new NavLink('regin', 'openReader'));
				break;
			case 'openedReader':
			case 'updateReader':
			case 'deleteReader':
				$navBar->setTitle('Permission en cours', new NavLink('regin', 'openedReader'));
				break;
			case 'validateReader':
				$navBar->setTitle('Valider les inscriptions', new NavLink('regin', 'validateReader'));
				break;
			case 'openBoard':
				$navBar->setTitle('Créer la permission', new NavLink('regin', 'openBoard'));
				break;
			case 'openedBoard':
			case 'updateBoard':
			case 'deleteBoard':
				$navBar->setTitle('Permission en cours', new NavLink('regin', 'openedBoard'));
				break;
			case 'validateBoard':
				$navBar->setTitle('Valider les inscriptions', new NavLink('regin', 'validateBoard'));
				break;
			case 'openResponsible':
				$navBar->setTitle('Créer la permission', new NavLink('regin', 'openResponsible'));
				break;
			case 'openedResponsible':
			case 'updateResponsible':
			case 'deleteResponsible':
				$navBar->setTitle('Permission en cours', new NavLink('regin', 'openedResponsible'));
				break;
		}

		$this->buildMenuRight($navBar);
		return $navBar;
	}

	/**
	 * @param NavBar $pNavBar
	 */
	public function buildMenuRight($pNavBar)
	{
		switch (_root::getAction()) {
			case 'openedReader':
			case 'updateReader':
			case 'deleteReader':
				if ($this->oLayout->__isset('idRegin')) {
					$tParams = array('id' => $this->oLayout->idRegin);
				} else {
					$tParams = array('id' => _root::getParam('id'));
				}
				if ($tParams['id']) {
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Détail',
						new NavLink('regin', 'openedReader', $tParams), 'glyphicon-eye-open'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Modifier',
						new NavLink('regin', 'updateReader', $tParams), 'glyphicon-edit'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Supprimer',
						new NavLink('regin', 'deleteReader', $tParams), 'glyphicon-trash'));
				}
				break;
			case 'openedBoard':
			case 'updateBoard':
			case 'deleteBoard':
				if ($this->oLayout->__isset('idRegin')) {
					$tParams = array('id' => $this->oLayout->idRegin);
				} else {
					$tParams = array('id' => _root::getParam('id'));
				}
				if ($tParams['id']) {
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Détail', new NavLink('regin', 'openedBoard', $tParams),
						'glyphicon-eye-open'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Modifier',
						new NavLink('regin', 'updateBoard', $tParams), 'glyphicon-edit'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Supprimer',
						new NavLink('regin', 'deleteBoard', $tParams), 'glyphicon-trash'));
				}
				break;
			case 'openedResponsible':
			case 'updateResponsible':
			case 'deleteResponsible':
				if ($this->oLayout->__isset('idRegin')) {
					$tParams = array('id' => $this->oLayout->idRegin);
				} else {
					$tParams = array('id' => _root::getParam('id'));
				}
				if ($tParams['id']) {
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Liste',
						new NavLink('regin', 'openedResponsibleList'), 'glyphicon-list'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Détail',
						new NavLink('regin', 'openedResponsible', $tParams), 'glyphicon-eye-open'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Modifier',
						new NavLink('regin', 'updateResponsible', $tParams), 'glyphicon-edit'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Supprimer',
						new NavLink('regin', 'deleteResponsible', $tParams), 'glyphicon-trash'));
				}
				break;
		}
	}

	public function _index()
	{
		if (_root::getRequest()->isPost()) {
			switch (_root::getParam('action')) {
				case 'toGetCode':
					$this->postGetCode();
					break;
				default:
					_root::redirect('default::index');
					break;
			}
		} else {
			$this->showViewGetCode(new row_registry());
		}
	}


	public function _opened()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$this->_openedBoard();
		} else {
			$this->_openedReader();
		}
	}

	public function _open()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$this->_openBoard();
		} else {
			$this->_openReader();
		}
	}

	public function _update()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$this->_updateBoard();
		} else {
			$this->_updateReader();
		}
	}

	public function _validate()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$this->_validateBoard();
		} else {
			$this->_validateReader();
		}
	}

	public function _openedReader()
	{
		_root::getRequest()->setAction('openedReader');
		$this->doOpenedForReaders();
	}

	public function _openedBoard()
	{
		_root::getRequest()->setAction('openedBoard');
		$this->doOpenedForBoards();
	}

	public function _openedResponsibleList()
	{
		$this->_openedResponsible();
	}

	public function _openedResponsible()
	{
		_root::getRequest()->setAction('openedResponsible');
		$id = _root::getParam('id', null);
		if ($id) {
			$this->doOpenedForAResponsible($id);
		} else {
			$this->doOpenedForResponsibles();
		}
	}

	public function _openReader()
	{
		_root::getRequest()->setAction('openReader');
		// Vérifie si le responsable peut ouvrir les inscriptions
		if ($this->verifyOkToOpenForReaders()) {
			$this->doOpenForReaders();
		}
	}

	public function _openBoard()
	{
		_root::getRequest()->setAction('openBoard');
		// Vérifie si l'organisateur peut ouvrir les inscriptions
		if ($this->verifyOkToOpenForBoards()) {
			$this->doOpenForBoards();
		}
	}

	public function _openResponsible()
	{
		_root::getRequest()->setAction('openResponsible');
		// Vérifie si l'organisateur peut ouvrir les inscriptions
		if ($this->verifyOkToOpenForResponsibles()) {
			$this->doOpenForResponsibles();
		}
	}

	public function _updateReader()
	{
		_root::getRequest()->setAction('updateReader');
		$this->updateForReaders();
	}

	public function _updateBoard()
	{
		_root::getRequest()->setAction('updateBoard');
		$this->updateForBoards();
	}

	public function _updateResponsible()
	{
		_root::getRequest()->setAction('updateResponsible');
		$this->updateForResponsible();
	}

	public function _validateReader()
	{
		_root::getRequest()->setAction('validateReader');
		$this->validateForReaders();
	}

	public function _validateBoard()
	{
		_root::getRequest()->setAction('validateBoard');
		$this->validateForBoards();
	}

	/**
	 * Verifie si un responsable peut ouvrir les inscriptions.
	 * @return bool Si Ok pour l'ouverture
	 */
	private function verifyOkToOpenForReaders()
	{
		$ok = true;
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();

		$tInProgressAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER, true);
		$tGroupRegistryAwards = model_group::getInstance()->findAllRegistryInProgressAwards($oReaderGroup->getId());
		// Ouverture uniquement si un prix est en cours
		if (0 == count($tInProgressAwards)) {
			$ok = false;
		} else {
			// Ouverture uniquement si le groupe est inscrit auprès d'Alices
			if (0 == count($tGroupRegistryAwards)) {
				$ok = false;
			}
		}
		if (false == $ok) {
			$oView = new _view('regin::koToOpen');
			$oView->text = 'Aucun prix n\'est ouvert !';
			$oView->oGroup = $oReaderGroup;
			$oView->tGroupRegistryAwards = $tGroupRegistryAwards;
			$oView->tInProgressAwards = $tInProgressAwards;
			$this->oLayout->add('work', $oView);
		} else {
			// Ouverture uniquement si aucune n'existe déjà
			$tRegins = model_regin::getInstance()
				->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_READER, $oUserSession->getReaderGroup()->getId());
			if (count($tRegins) > 0) {
				$ok = false;
				_root::redirect('regin::opened');
			}
		}
		return $ok;
	}

	/**
	 * Verifie si un organisateur peut ouvrir les inscriptions des présélections.
	 * @return bool Si Ok pour l'ouverture
	 */
	private function verifyOkToOpenForBoards()
	{
		$ok = true;
		$oBoardGroup = $this->getGroupBoard();
		$tInProgressAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_BOARD);
		// Ouverture uniquement si un prix est en cours
		if (0 == count($tInProgressAwards)) {
			$ok = false;
			$tGroupRegistryAwards = null;
		} else {
			// Sauve la présélection automatiquement qd elle existe
			$tGroupRegistryAwards = model_group::getInstance()->findAllRegistryInProgressAwards($oBoardGroup->getId());
			if (0 == count($tGroupRegistryAwards)) {
				$tIds = array();
				foreach ($tInProgressAwards as $oAward) {
					$tIds[] = $oAward->getId();
				}
				model_group::getInstance()->saveGroupAwards($oBoardGroup->getId(), $tIds);
			}
		}
		if (false == $ok) {
			$oView = new _view('regin::koToOpen');
			$oView->text = 'Aucune présélection n\'est ouverte !';
			$oView->oGroup = $oBoardGroup;
			$oView->tGroupRegistryAwards = $tGroupRegistryAwards;
			$oView->tInProgressAwards = $tInProgressAwards;
			$this->oLayout->add('work', $oView);
		} else {
			// Ouverture uniquement si aucune n'existe déjà
			$tRegins = model_regin::getInstance()->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_BOARD, $oBoardGroup->getId());
			if (count($tRegins) > 0) {
				$ok = false;
				_root::redirect('regin::opened');
			}
		}
		return $ok;
	}

	private function getGroupBoard()
	{
		if (null == $this->oGroupBoard) {
			$toBoardGroups = model_group::getInstance()->findAllByRoleName(plugin_vfa::ROLE_BOARD);
			$this->oGroupBoard = $toBoardGroups[0];
		}
		return $this->oGroupBoard;
	}

	/**
	 * Verifie si un organisateur peut ouvrir les inscriptions d'un groupe à un prix.
	 * @return bool Si Ok pour l'ouverture
	 */
	private function verifyOkToOpenForResponsibles()
	{
		$ok = true;
		$tInProgressAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER);
		// Ouverture uniquement si un prix est en cours
		if (0 == count($tInProgressAwards)) {
			$ok = false;
		}
		if (false == $ok) {
			$oView = new _view('regin::koToOpen');
			$oView->text = 'Aucun prix n\'est ouvert !';
			$oView->tInProgressAwards = $tInProgressAwards;
			$this->oLayout->add('work', $oView);
		}
		return $ok;
	}

	private function doOpenedForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();

		$tRegins = model_regin::getInstance()->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_READER, $oReaderGroup->getId());

		$oView = new _view('regin::openedForReaders');
		$oView->oReaderGroup = $oReaderGroup;
		$oView->tAwards = $oReaderGroup->findAwards();

		$oView->tRegins = $tRegins;
		if (count($tRegins) > 0) {
			$oView->oViewShow = $this->makeViewShowForReaders($tRegins[0]);

			$oView->oRegin = $tRegins[0];
			$this->oLayout->idRegin = $tRegins[0]->getId();
		}
		$this->oLayout->add('work', $oView);
	}

	private function doOpenedForBoards()
	{
		$oBoardGroup = $this->getGroupBoard();
		$tRegins = model_regin::getInstance()->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_BOARD, $oBoardGroup->getId());

		$oView = new _view('regin::openedForBoards');
		$oView->oBoardGroup = $oBoardGroup;
		$oView->tAwards = $oBoardGroup->findAwards();

		$oView->tRegins = $tRegins;
		if (count($tRegins) > 0) {
			$oView->oViewShow = $this->makeViewShowForBoards($tRegins[0]);

			$oView->oRegin = $tRegins[0];
			$this->oLayout->idRegin = $tRegins[0]->getId();
		}
		$this->oLayout->add('work', $oView);
	}

	private function doOpenedForAResponsible($pIdRegin)
	{
		$oRegin = model_regin::getInstance()->findById($pIdRegin);

		$oView = new _view('regin::openedForAResponsible');
		$oView->oRegin = $oRegin;
		if (false == $oRegin->isEmpty()) {
			$oView->tAwards = $oRegin->findAwards();
			$oView->oViewShow = $this->makeViewShowForResponsible($oRegin);
			$this->oLayout->idRegin = $oRegin->getId();
		}
		$this->oLayout->add('work', $oView);
	}

	private function doOpenedForResponsibles()
	{
		$tRegins = model_regin::getInstance()->findAllByTypeByState(plugin_vfa::TYPE_RESPONSIBLE);
		$oView = new _view('regin::openedForResponsibles');
		$oView->tRegins = $tRegins;
		$this->oLayout->add('work', $oView);
	}

	private function makeViewShowForReaders($poRegin = null)
	{
		if ($poRegin) {
			$oRegin = $poRegin;
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
		}

		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();

		$oView = new _view('regin::show');
		$oView->oRegin = $oRegin;
		$oView->tAwards = $oReaderGroup->findAwards();
		$oView->oGroup = $oReaderGroup;

		$this->oLayout->idRegin = $oRegin->getId();

		return $oView;
	}

	private function makeViewShowForBoards($poRegin = null)
	{
		if ($poRegin) {
			$oRegin = $poRegin;
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
		}

		$oBoardGroup = $this->getGroupBoard();

		$oView = new _view('regin::show');
		$oView->oRegin = $oRegin;
		$oView->tAwards = $oBoardGroup->findAwards();
		$oView->oGroup = $oBoardGroup;

		$this->oLayout->idRegin = $oRegin->getId();

		return $oView;
	}

	private function makeViewShowForResponsible($poRegin = null)
	{
		if ($poRegin) {
			$oRegin = $poRegin;
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
		}

		$oView = new _view('regin::show');
		$oView->oRegin = $oRegin;
		$oView->tAwards = $oRegin->findAwards();
		$oView->oGroup = $oRegin->findGroup();

		$this->oLayout->idRegin = $oRegin->getId();

		return $oView;
	}

	private function doOpenForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::openForReaders');
		$oView->oGroup = $oReaderGroup;
		$oView->tAwards = $tAwards;

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForType($oReaderGroup, $tAwards[0], plugin_vfa::TYPE_READER);
		} else {
			$oRegin = new row_regin();
			$oRegin->created_user_id = $oUserSession->getUser()->getId();
			$oRegin->type = plugin_vfa::TYPE_READER;
			$oRegin->state = plugin_vfa::STATE_OPEN;
			$oRegin->group_id = $oReaderGroup->getId();
			$oRegin->awards_ids = $oReaderGroup->getAwardIds();

			$oRegin->process_end = $this->buildProcessEndDate($tAwards[0])->toString();
			$oRegin->process = plugin_vfa::PROCESS_INTIME_VALIDATE;
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();

		$oView->novalidate = false;
		if ($oRegin->process == plugin_vfa::PROCESS_INTIME) {
			$oView->novalidate = true;
		}

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	private function doOpenForBoards()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oBoardGroup = $this->getGroupBoard();
		$tAwards = $oBoardGroup->findAwards();

		$oView = new _view('regin::openForBoards');
		$oView->oGroup = $oBoardGroup;
		$oView->tAwards = $tAwards;

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForType($oBoardGroup, $tAwards[0], plugin_vfa::TYPE_BOARD);
		} else {
			$oRegin = new row_regin();
			$oRegin->created_user_id = $oUserSession->getUser()->getId();
			$oRegin->type = plugin_vfa::TYPE_BOARD;
			$oRegin->state = plugin_vfa::STATE_OPEN;
			$oRegin->group_id = $oBoardGroup->getId();
			$oRegin->awards_ids = $oBoardGroup->getAwardIds();

			$oRegin->process_end = $this->buildProcessEndDate($tAwards[0])->toString();
			$oRegin->process = plugin_vfa::PROCESS_INTIME_VALIDATE;
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();

		$oView->novalidate = false;
		if ($oRegin->process == plugin_vfa::PROCESS_INTIME) {
			$oView->novalidate = true;
		}

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	private function doOpenForResponsibles()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$tAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER, true);

		$oView = new _view('regin::openForResponsibles');
		$oView->tAwards = $tAwards;
		$oView->tSelectedGroups = plugin_vfa::buildOptionSelected(model_group::getInstance()->getSelect(plugin_vfa::ROLE_READER), null);
		$oView->oGroup = new row_group();

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForType(null, $tAwards[0], plugin_vfa::TYPE_RESPONSIBLE);
		} else {
			$oRegin = new row_regin();
			$oRegin->created_user_id = $oUserSession->getUser()->getId();
			$oRegin->type = plugin_vfa::TYPE_RESPONSIBLE;
			$oRegin->state = plugin_vfa::STATE_OPEN;
			$oRegin->group_id = '';
			$oRegin->awards_ids = plugin_vfa::getIds($tAwards);

			$oRegin->process_end = $this->buildProcessEndDate($tAwards[0])->toString();
			$oRegin->process = plugin_vfa::PROCESS_INTIME;
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();

		$oView->novalidate = false;
		if ($oRegin->process == plugin_vfa::PROCESS_INTIME) {
			$oView->novalidate = true;
		}

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	private function updateForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::update');
		$oView->oGroup = $oReaderGroup;
		$oView->tAwards = $tAwards;

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForType($oReaderGroup, $tAwards[0], plugin_vfa::TYPE_READER);
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
			if ($oRegin->isEmpty()) {
				_root::redirect('default::index');
			} else {
				$oRegin->group_id = $oReaderGroup->getId();
				$oRegin->awards_ids = $oReaderGroup->getAwardIds();
			}
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();

		$oView->novalidate = false;
		if ($oRegin->process == plugin_vfa::PROCESS_INTIME) {
			$oView->novalidate = true;
		}

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	private function updateForBoards()
	{
		$oBoardGroup = $this->getGroupBoard();
		$tAwards = $oBoardGroup->findAwards();

		$oView = new _view('regin::update');
		$oView->oGroup = $oBoardGroup;
		$oView->tAwards = $tAwards;

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForType($oBoardGroup, $tAwards[0], plugin_vfa::TYPE_BOARD);
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
			if ($oRegin->isEmpty()) {
				_root::redirect('default::index');
			} else {
				$oRegin->group_id = $oBoardGroup->getId();
				$oRegin->awards_ids = $oBoardGroup->getAwardIds();
			}
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();

		$oView->novalidate = false;
		if ($oRegin->process == plugin_vfa::PROCESS_INTIME) {
			$oView->novalidate = true;
		}

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	private function updateForResponsible()
	{
		/* @var $oUserSession row_user_session */
//		$oUserSession = _root::getAuth()->getUserSession();
//		$oReaderGroup = $oUserSession->getReaderGroup();
//		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::update');

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForType(null, null, plugin_vfa::TYPE_RESPONSIBLE);
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
			if ($oRegin->isEmpty()) {
				_root::redirect('default::index');
			}
		}
		$oView->oGroup = model_group::getInstance()->findById($oRegin->group_id);

		$tIdAwards = explode(',', $oRegin->awards_ids);
		$tAwards = array();
		foreach ($tIdAwards as $id) {
			$tAwards[] = model_award::getInstance()->findById($id);
		}
		$oView->tAwards = $tAwards;

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();

		$oView->novalidate = false;
		if ($oRegin->process == plugin_vfa::PROCESS_INTIME) {
			$oView->novalidate = true;
		}

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	private function validateForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::validateForReaders');
		$oView->oGroup = $oReaderGroup;
		$oView->tAwards = $tAwards;


		$oRegin = null;
		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doPostValidateForReaders();
		}

		if ($oRegin) {
			// Suite à un post
			$tReginUsers = $oRegin->tReginUsers;
		} else {
			// Suite à un get
			$tRegins = model_regin::getInstance()->findAllInTimeByTypeByGroup(plugin_vfa::TYPE_READER, $oReaderGroup->getId());
			if (count($tRegins) > 0) {
				$oRegin = $tRegins[0];
				$tReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			}
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();
		$oView->tReginUsers = $tReginUsers;

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);


		$oView->oViewModalConfirm = new _view('regin::modalConfirmValidate');
		$oView->oViewModalConfirm->oRegin = $oRegin;
		$oView->oViewModalConfirm->token = $oView->token;
		$oView->oViewModalConfirm->tReginUsers = $tReginUsers;

		// Ajout du javascript
		$scriptView = new _view('regin::scriptValidate');
		$scriptView->oRegin = $oRegin;
		$this->oLayout->add('script', $scriptView);
	}

	private function validateForBoards()
	{
		$oBoardGroup = $this->getGroupBoard();
		$tAwards = $oBoardGroup->findAwards();

		$oView = new _view('regin::validateForBoards');
		$oView->oGroup = $oBoardGroup;
		$oView->tAwards = $tAwards;


		$oRegin = null;
		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doPostValidateForBoards();
		}

		if ($oRegin) {
			// Suite à un post
			$tReginUsers = $oRegin->tReginUsers;
		} else {
			// Suite à un get
			$tRegins = model_regin::getInstance()->findAllInTimeByTypeByGroup(plugin_vfa::TYPE_BOARD, $oBoardGroup->getId());
			if (count($tRegins) > 0) {
				$oRegin = $tRegins[0];
				$tReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			} else {
				$oRegin = new row_regin();
				$tReginUsers = array();
			}
		}

		$oView->oRegin = $oRegin;
		$oView->tMessage = $oRegin->getMessages();
		$oView->tReginUsers = $tReginUsers;

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);


		$oView->oViewModalConfirm = new _view('regin::modalConfirmValidate');
		$oView->oViewModalConfirm->oRegin = $oRegin;
		$oView->oViewModalConfirm->token = $oView->token;
		$oView->oViewModalConfirm->tReginUsers = $tReginUsers;

		// Ajout du javascript
		$scriptView = new _view('regin::scriptValidate');
		$scriptView->oRegin = $oRegin;
		$this->oLayout->add('script', $scriptView);
	}

	/**
	 * @return row_regin
	 */
	private function doPostValidateForReaders()
	{
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oRegin = new row_regin();
			$oRegin->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oRegin;
		}

		$iId = _root::getParam('regin_id');
		if ($iId == null) {
			$oRegin = new row_regin();
			$oRegin->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oRegin;
		}
		$oRegin = model_regin::getInstance()->findById($iId);
		$tReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
		$oRegin->tReginUsers = $tReginUsers;

		$accepted = _root::getParam('accepted');
		$rejected = _root::getParam('rejected');
		$oRegin->nbAccepted = 0;
		$oRegin->nbRejected = 0;
		foreach ($tReginUsers as $oReginUser) {
			if ((null != $accepted) && (true == in_array($oReginUser->getId(), $accepted))) {
				$oReginUser->accepted = 1;
				$oRegin->nbAccepted++;
			} else if ((null != $rejected) && (true == in_array($oReginUser->getId(), $rejected))) {
				$oReginUser->accepted = -1;
				$oRegin->nbRejected++;
			} else {
				$oReginUser->accepted = 0;
			}
		}
		$oRegin->openModalConfirm = false;
		if ('toConfirm' == _root::getParam('action')) {
			$this->registryAllUsers($oRegin, plugin_vfa::ROLE_READER);
			// Force la relecture dans la base
			$oRegin = null;
		} else {
			if (($oRegin->nbAccepted > 0) || ($oRegin->nbRejected > 0)) {
				$oRegin->openModalConfirm = true;
			}
		}
		return $oRegin;
	}

	/**
	 * @return row_regin
	 */
	private function doPostValidateForBoards()
	{
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oRegin = new row_regin();
			$oRegin->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oRegin;
		}

		$iId = _root::getParam('regin_id');
		if ($iId == null) {
			$oRegin = new row_regin();
			$oRegin->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oRegin;
		}
		$oRegin = model_regin::getInstance()->findById($iId);
		$tReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
		$oRegin->tReginUsers = $tReginUsers;

		$accepted = _root::getParam('accepted');
		$rejected = _root::getParam('rejected');
		$oRegin->nbAccepted = 0;
		$oRegin->nbRejected = 0;
		foreach ($tReginUsers as $oReginUser) {
			if ((null != $accepted) && (true == in_array($oReginUser->getId(), $accepted))) {
				$oReginUser->accepted = 1;
				$oRegin->nbAccepted++;
			} else if ((null != $rejected) && (true == in_array($oReginUser->getId(), $rejected))) {
				$oReginUser->accepted = -1;
				$oRegin->nbRejected++;
			} else {
				$oReginUser->accepted = 0;
			}
		}
		$oRegin->openModalConfirm = false;
		if ('toConfirm' == _root::getParam('action')) {
			$this->registryAllUsers($oRegin, plugin_vfa::ROLE_BOARD);
			// Force la relecture dans la base
			$oRegin = null;
		} else {
			if (($oRegin->nbAccepted > 0) || ($oRegin->nbRejected > 0)) {
				$oRegin->openModalConfirm = true;
			}
		}
		return $oRegin;
	}


	/**
	 * @param row_regin $poRegin
	 */
	private function registryAllUsers($poRegin, $pRole)
	{
		$tAcceptedReginUsers = array();
		$tRejectedReginUsers = array();
		//var_dump($poRegin);
		foreach ($poRegin->tReginUsers as $oReginUser) {
			if (1 == $oReginUser->accepted) {
				$tAcceptedReginUsers[] = $oReginUser;
			} elseif (-1 == $oReginUser->accepted) {
				$tRejectedReginUsers[] = $oReginUser;
			}
		}
		foreach ($tRejectedReginUsers as $oReginUser) {
//			var_dump($oReginUser);
			$oReginUser->delete();
		}
		$tUsers = array();
		foreach ($tAcceptedReginUsers as $oReginUser) {
			$oUser = $oReginUser->findUser();
			$tUsers[] = $oUser;
//			var_dump($oUser);
			$this->saveGroupAwardsToUser($poRegin, $oUser, $pRole);
			$oReginUser->delete();
		}
		if (count($tUsers) > 0) {
			$this->sendMailRegistryAllUsers($poRegin, $tUsers, plugin_vfa::ROLE_READER == $pRole);
		}
	}

	/**
	 * @param row_regin $poRegin
	 * @param $ptUsers
	 * @return bool
	 */
	private function sendMailRegistryAllUsers($poRegin, $ptUsers, $pResponsibles)
	{
		$oMail = new plugin_email();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));

		if ($pResponsibles) {
			$responsibles = model_user::getInstance()->findAllByGroupIdByRoleName($poRegin->group_id, plugin_vfa::ROLE_RESPONSIBLE);
			foreach ($responsibles as $user) {
				$oMail->addTo($user->email);
			}
		} else {
			$createdUser = $poRegin->findCreatedUser();
			$oMail->addTo($createdUser->email);
		}
		$oMail->addBCC(_root::getConfigVar('vfa-app.mail.from'));
		foreach ($ptUsers as $user) {
			$oMail->addBCC($user->email);
		}

		$tAwards = $poRegin->findAwards();

		// Sujet
		$oMail->setSubject('[PrixBD' . $tAwards[0]->year . '] Votre inscription est validée');
		// Prepare le body TXT
		$oViewMail = new _view('regin::mailValidateTxt');
		$oViewMail->tAwards = $tAwards;
		$bodyTxt = $oViewMail->show();
//		 _root::getLog()->log($bodyTxt);
		$oMail->setBody($bodyTxt);

		// Envoi le mail
		$sent = plugin_vfa::sendEmail($oMail);
		return $sent;
	}

	/**
	 * @param row_regin $poRegin
	 * @param $poUser
	 */
	private function saveGroupAwardsToUser($poRegin, $poUser, $pRole)
	{
		// Sauve le groupe de même rôle à l'utilisateur
		$tIdGroups = array($poRegin->group_id);
		model_user::getInstance()->mergeUserGroups($poUser, $tIdGroups);
		// Ajoute les prix à l'utilisateur
		$tIdAwards = explode(',', $poRegin->awards_ids);
		model_user::getInstance()->mergeUserAwards($poUser, $tIdAwards);
		// Ajoute le rôle à l'utilisateur
		$oRole = model_role::getInstance()->findByName($pRole);
		$tIdRoles = array($oRole->getId());
		model_user::getInstance()->mergeUserRoles($poUser, $tIdRoles);
	}

	/**
	 * @param row_group $poGroup
	 * @param row_award $poAward
	 * @param $pType
	 * @return row_regin
	 */
	private function doSaveOpenForType($poGroup, $poAward, $pType)
	{
		// on verifie que le token est valide
		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			$oRegin = new row_regin();
			$oRegin->setMessages(array('token' => $oPluginXsrf->getMessage()));
			return $oRegin;
		}

		$iId = _root::getParam('id', null);
		if ($iId == null) {
			$oRegin = new row_regin();
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id', null));
		}

		// Récupère les données cachées
		$oRegin->type = _root::getParam('type');
		$oRegin->code = _root::getParam('code');
		$oRegin->state = _root::getParam('state');
		$oRegin->created_user_id = _root::getParam('created_user_id');
		$oRegin->awards_ids = _root::getParam('awards_ids');
		$oRegin->group_id = _root::getParam('group_id');

		// Horodatage
		if ($iId == null) {
			$oRegin->created_date = plugin_vfa::dateTimeSgbd();
		}
		$oRegin->modified_date = plugin_vfa::dateTimeSgbd();

		// Récupère les données saisies
		// Date de fin des inscriptions
		$oRegin->process_end = plugin_vfa::toStringDateSgbd(_root::getParam('process_end', null));
		// Validation
		if (_root::getParam('novalidate', null)) {
			$oRegin->process = plugin_vfa::PROCESS_INTIME;
		} else {
			$oRegin->process = plugin_vfa::PROCESS_INTIME_VALIDATE;
		}

		/// Prix encore inconnu
		$oAward = $poAward;
		if (null == $oAward) {
			$tIdAwards = explode(',', $oRegin->awards_ids);
			$oAward = model_award::getInstance()->findById($tIdAwards[0]);
		}

		// Valide la date de fin des inscriptions
		$endDateValide = false;
		$today = plugin_vfa::today();
		$today->addDay(-1);
		$maxEnd = $this->buildProcessEndDate($oAward);
		$maxEnd->addDay(1);
		$processEnd = new plugin_date($oRegin->process_end);
		if (plugin_vfa::afterDate($processEnd, $today) && plugin_vfa::beforeDate($processEnd, $maxEnd)) {
			$endDateValide = true;
		} else {
			if (plugin_vfa::afterDate($processEnd, $today)) {
				$oRegin->setMessages(array('process_end' => array('isDateBeforeKO')));
			} else {
				$oRegin->setMessages(array('process_end' => array('isDateAfterKO')));
			}
		}
		// Gestion du groupe et des cas particuliers d'une permission d'inscription de responsable
		$oGroup = $poGroup;
		$prefixCode = null;
		if ((null == $oGroup) && ($pType == plugin_vfa::TYPE_RESPONSIBLE)) {
			$idGroup = _root::getParam('_group', null);
			if (null == $idGroup) {
				if (null == $oRegin->group_id) {
					$oRegin->setMessages(array('_group' => array('required-group')));
					return $oRegin;
				} else {
					$idGroup = $oRegin->group_id;
				}
			}
			$oGroup = model_group::getInstance()->findById($idGroup);
			$prefixCode = "COR";
			$oRegin->group_id = $idGroup;
			$oRegin->process = plugin_vfa::PROCESS_INTIME;
		}
		// Sauvegarde
		if ($endDateValide && $oRegin->isValid()) {
			if (null == _root::getParam('code', null)) {
				// Génération du code d'inscription
				var_dump($oAward->year);
				var_dump($oGroup->toString());
				var_dump($prefixCode);
				$code = plugin_vfa::generateRegistrationCode($oAward->year, $oGroup->toString(), $prefixCode);
				$oRegin->code = $code;
				$oRegin->save();
				// Vérifie les doublons de code (rarissime)
				$founds = model_regin::getInstance()->findAllByCode($code);
				while (count($founds) > 1) {
					$code = plugin_vfa::generateRegistrationCode($oAward->year, $oGroup->toString(), $prefixCode);
					$oRegin->code = $code;
					$oRegin->save();
					$founds = model_regin::getInstance()->findAllByCode($code);
				}
			} else {
				$oRegin->save();
			}
			// redirection
			switch ($pType) {
				case plugin_vfa::TYPE_BOARD:
					_root::redirect('regin::openedBoard');
					break;
				case plugin_vfa::TYPE_READER:
					_root::redirect('regin::openedReader');
					break;
				case plugin_vfa::TYPE_RESPONSIBLE:
					_root::redirect('regin::openedResponsible', array('id' => $oRegin->getId()));
					break;
				default:
					_root::redirect('regin::opened');
			}
		}
		return $oRegin;
	}

	/**
	 * @param row_award $poAward
	 * @return plugin_date
	 */
	private function buildProcessEndDate($poAward)
	{
		$processEnd = plugin_vfa::toDateFromSgbd($poAward->end_date);
		//$processEnd->addDay(-1);
		return $processEnd;
	}

	public function _delete()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$this->_deleteBoard();
		} else {
			$this->_deleteReader();
		}
	}

	public function _deleteReader()
	{
		_root::getRequest()->setAction('deleteReader');
		$tMessage = $this->deleteReader();

		$oView = new _view('regin::delete');
		$oView->oViewShow = $this->makeViewShowForReaders();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	private function deleteReader()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		// on verifie que le token est valide
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			return array('token' => $oPluginXsrf->getMessage());
		}

		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oRegin = model_regin::getInstance()->findById($iId);
			$toReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			foreach ($toReginUsers as $oReginUser) {
				$oReginUser->delete();
			}
			$oRegin->delete();
		}
		_root::redirect('regin::openedReader');
	}

	public function _deleteBoard()
	{
		_root::getRequest()->setAction('deleteBoard');
		$tMessage = $this->deleteBoard();

		$oView = new _view('regin::delete');
		$oView->oViewShow = $this->makeViewShowForBoards();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	private function deleteBoard()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		// on verifie que le token est valide
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			return array('token' => $oPluginXsrf->getMessage());
		}

		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oRegin = model_regin::getInstance()->findById($iId);
			$toReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			foreach ($toReginUsers as $oReginUser) {
				$oReginUser->delete();
			}
			$oRegin->delete();
		}
		_root::redirect('regin::openedBoard');
	}

	public function _deleteResponsible()
	{
		_root::getRequest()->setAction('deleteResponsible');
		$tMessage = $this->deleteResponsible();

		$oView = new _view('regin::delete');
		$oView->oViewShow = $this->makeViewShowForResponsible();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	private function deleteResponsible()
	{
		// si ce n'est pas une requete POST on ne soumet pas
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		// on verifie que le token est valide
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
			return array('token' => $oPluginXsrf->getMessage());
		}

		$iId = _root::getParam('id', null);
		if ($iId != null) {
			$oRegin = model_regin::getInstance()->findById($iId);
			$toReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			foreach ($toReginUsers as $oReginUser) {
				$oReginUser->delete();
			}
			$oRegin->delete();
		}
		_root::redirect('regin::openedResponsible');
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function showViewGetCode($poRegistry)
	{
		$oView = new _view('regin::code');

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();

		$oView->oRegistry = $poRegistry;
		$oView->tMessage = $poRegistry->getMessages();

		$this->oLayout->add('work', $oView);
	}

	private function postGetCode()
	{
		$codeValid = false;
		$oRegistry = new row_registry();
		// on verifie que le token est valide
		if ($this->isValidToken($oRegistry)) {
			$oRegistry->action = _root::getParam('action', null);
			$oRegistry->code = _root::getParam('code', null);
			if ($oRegistry->isValid()) {
				$oRegin = model_regin::getInstance()->findByCode($oRegistry->code);
				if ($oRegin->isEmpty()) {
					$oRegistry->setMessages(array('code' => array('registry.code.unknown')));
				} else {
					if (false == $oRegin->verifyProcessValidity()) {
						$oRegistry->setMessages(array('code' => array('registry.code.invalid')));
					} else {
						$codeValid = true;
						$oRegistry->oRegin = $oRegin;
						$oRegistry->regin_id = $oRegin->regin_id;
					}
				}
			}
		}
		if ($codeValid) {
			$oRegistry->oUser = _root::getAuth()->getUserSession()->getUser();
			$this->doRegistry($oRegistry);
			$this->showViewEndRegistry($oRegistry);
		} else {
			$this->showViewGetCode($oRegistry);
		}
	}

	/**
	 * Verifie le token
	 * @param row_registry $poRegistry
	 * @return bool
	 */
	private function isValidToken($poRegistry)
	{
		if (_root::getParam('token')) {
			$oPluginXsrf = new plugin_xsrf();
			if (!$oPluginXsrf->checkToken(_root::getParam('token'))) {
				$poRegistry->setMessages(array('token' => $oPluginXsrf->getMessage()));
				return false;
			}
		}
		return true;
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function doRegistry($poRegistry)
	{
		switch ($poRegistry->oRegin->type) {
			case plugin_vfa::TYPE_READER:
				$this->doRegistryReader($poRegistry);
				break;
			case plugin_vfa::TYPE_BOARD:
				$this->doRegistryBoard($poRegistry);
				break;
		}
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function doRegistryReader($poRegistry)
	{
		if (plugin_vfa::PROCESS_INTIME == $poRegistry->oRegin->process) {
			// Associe le groupe de même rôle et les prix à l'utilisateur
			$this->saveGroupAwardsToUser($poRegistry->oRegin, $poRegistry->oUser, plugin_vfa::ROLE_READER);
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($poRegistry->oUser);
			_root::getAuth()->setUserSession($oUserSession);
		} else {
			// Sauvegarde pour validation
			model_regin::getInstance()->saveReginUser($poRegistry->oRegin->getId(), $poRegistry->oUser->getId());
			module_default::sendMailReginToValid($poRegistry, true);
		}
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function doRegistryBoard($poRegistry)
	{
		if (plugin_vfa::PROCESS_INTIME == $poRegistry->oRegin->process) {
			// Associe le groupe de même rôle et les prix à l'utilisateur
			$this->saveGroupAwardsToUser($poRegistry->oRegin, $poRegistry->oUser, plugin_vfa::ROLE_BOARD);
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($poRegistry->oUser);
			_root::getAuth()->setUserSession($oUserSession);
		} else {
			// Sauvegarde pour validation
			model_regin::getInstance()->saveReginUser($poRegistry->oRegin->getId(), $poRegistry->oUser->getId());
			module_default::sendMailReginToValid($poRegistry, false);
		}
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function showViewEndRegistry($poRegistry)
	{
		$oView = new _view('regin::endRegistryReader');

//		$oPluginXsrf = new plugin_xsrf();
//		$oView->token = $oPluginXsrf->getToken();

		$oView->oRegistry = $poRegistry;
//		$oView->tMessage = $poRegistry->getMessages();

		$this->oLayout->add('work', $oView);
	}

}
