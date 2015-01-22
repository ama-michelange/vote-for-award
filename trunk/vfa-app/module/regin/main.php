<?php

class module_regin extends abstract_module
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
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$navBar = plugin_BsHtml::buildNavBar();

		$navBar->addChild(new Bar('left'));
		$navBar->addChild(new BarButtons('right'));

		switch (_root::getAction()) {
			case 'open':
				$navBar->setTitle('Ouverture des inscriptions', new NavLink('regin', 'open'));
				break;
			case 'opened':
			case 'update':
			case 'delete':
				$navBar->setTitle('Inscriptions ouvertes', new NavLink('regin', 'opened'));
				break;
		}


		if ($oUserSession->isInRole(plugin_vfa::ROLE_RESPONSIBLE)) {
			$this->buildMenuResponsible($navBar, $oUserSession);
		}
		return $navBar;
	}

	/**
	 * @param NavBar $pNavBar
	 * @param row_user_session $poUserSession
	 */
	public function buildMenuResponsible($pNavBar, $poUserSession)
	{
		switch (_root::getAction()) {
			case 'opened':
			case 'update':
			case 'delete':
				if ($this->oLayout->__isset('idRegin')) {
					$tParams = array('id' => $this->oLayout->idRegin);
				} else {
					$tParams = array('id' => _root::getParam('id'));
				}
				$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Détail', new NavLink('regin', 'opened', $tParams),
					'glyphicon-eye-open'));
				$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Modifier', new NavLink('regin', 'update', $tParams),
					'glyphicon-edit'));
				$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Fermeture', new NavLink('regin', 'delete', $tParams),
					'glyphicon-trash'));

				break;
		}
	}


	public function _index()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
		} else {
//			$this->openForReaders();
		}
	}

	public function _opened()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
		} else {
			$this->openedForReaders();
		}
	}

	public function _open()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
		} else {
			// Vérifie si le responsable peut ouvrir les inscriptions
			if ($this->verifyOkToOpenForReaders()) {
				$this->openForReaders();
			}
		}
	}

	public function _update()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
		} else {
			$this->updateForReaders();
		}
	}

	public function _validate()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		if ($oUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $oUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
		} else {
			$this->validateForReaders();
		}
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
			$oView = new _view('regin::koToOpenForReaders');
			$oView->oReaderGroup = $oReaderGroup;
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

	private function openedForReaders()
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

	private function openForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::openForReaders');
		$oView->oGroup = $oReaderGroup;
		$oView->tAwards = $tAwards;

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForReaders($oReaderGroup, $tAwards[0]);
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

	private function updateForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::updateForReaders');
		$oView->oGroup = $oReaderGroup;
		$oView->tAwards = $tAwards;

		if (_root::getRequest()->isPost()) {
			$oRegin = $this->doSaveOpenForReaders($oReaderGroup, $tAwards[0]);
		} else {
			$oRegin = model_regin::getInstance()->findById(_root::getParam('id'));
			if ($oRegin->isEmpty()) {
				_root::redirect('default::index');
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

	private function validateForReaders()
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$tAwards = $oReaderGroup->findAwards();

		$oView = new _view('regin::validateForReaders');
		$oView->oGroup = $oReaderGroup;
		$oView->tAwards = $tAwards;


		// FIXME A terminer
		// FIXME Comment cela va fonctionner si 2 responsables différents peuvent valider : exemple pour instant envoi des mails uniquement du createur du RegIn !!!
		if (_root::getRequest()->isPost()) {
			//$oRegin = $this->doSaveOpenForReaders($oReaderGroup, $tAwards[0]);
		} else {
			$oRegin = model_regin::getInstance()->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_READER, $oReaderGroup->getId());
			if (false == $oRegin->isEmpty()) {
				$oReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			}
		}

		$oView->oRegin = $oRegin;
		$oView->oReginUsers = $oReginUsers;
		$oView->tMessage = $oReginUsers->getMessages();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$this->oLayout->add('work', $oView);
	}

	/**
	 * @param row_group $poGroup
	 * @param row_award $poAward
	 * @return row_regin
	 */
	private function doSaveOpenForReaders($poGroup, $poAward)
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

		// Récupère les données saisies
		foreach (model_regin::getInstance()->getListColumn() as $sColumn) {
			if (in_array($sColumn, model_regin::getInstance()->getIdTab())) {
				continue;
			}
			if ((_root::getParam($sColumn, null) == null) && (null != $oRegin->$sColumn)) {
				$oRegin->$sColumn = null;
			} else {
				$oRegin->$sColumn = _root::getParam($sColumn, null);
			}
		}
		// Horodatage
		if ($iId == null) {
			$oRegin->created_date = plugin_vfa::dateTimeSgbd();
		}
		$oRegin->modified_date = plugin_vfa::dateTimeSgbd();

		// Date de fin des inscriptions
		$oRegin->process_end = plugin_vfa::toStringDateSgbd(_root::getParam('process_end', null));
		// Validation
		if (_root::getParam('novalidate', null)) {
			$oRegin->process = plugin_vfa::PROCESS_INTIME;
		} else {
			$oRegin->process = plugin_vfa::PROCESS_INTIME_VALIDATE;
		}
		// Valide la date de fin des inscriptions
		$endDateValide = false;
		$today = plugin_vfa::today();
		$today->addDay(-1);
		$maxEnd = $this->buildProcessEndDate($poAward);
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
		// Sauvegarde
		if ($endDateValide && $oRegin->isValid()) {
			if (null == _root::getParam('code', null)) {
				// Génération du code d'inscription
				$code = plugin_vfa::generateRegistrationCode($poAward->year, $poGroup->toString());
				$oRegin->code = $code;
				$oRegin->save();
				// Vérifie les doublons de code (rarissime)
				$founds = model_regin::getInstance()->findAllByCode($code);
				while (count($founds) > 1) {
					$code = plugin_vfa::generateRegistrationCode($poAward->year, $poGroup->toString());
					$oRegin->code = $code;
					$oRegin->save();
					$founds = model_regin::getInstance()->findAllByCode($code);
				}
			} else {
				$oRegin->save();
			}
			_root::redirect('regin::opened');
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
		$processEnd->addDay(-1);
		return $processEnd;
	}

	public function _delete()
	{
		$tMessage = $this->delete();

		$oView = new _view('regin::delete');
		$oView->oViewShow = $this->makeViewShowForReaders();

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('work', $oView);
	}

	private function delete()
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
			$oRegin->delete();
		}
		_root::redirect('regin::opened');
	}
}
