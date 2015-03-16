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
				if ($tParams['id']) {
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Détail', new NavLink('regin', 'opened', $tParams),
						'glyphicon-eye-open'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Modifier', new NavLink('regin', 'update', $tParams),
						'glyphicon-edit'));
					$pNavBar->getChild('right')->addChild(plugin_BsHtml::buildButtonItem('Fermeture', new NavLink('regin', 'delete', $tParams),
						'glyphicon-trash'));
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


		$oView->oViewModalConfirm = new _view('regin::modalConfirmValidateForReaders');
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
			$this->registryAllUsers($oRegin);
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
	private function registryAllUsers($poRegin)
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
			$this->saveGroupAwardsToUser($poRegin, $oUser);
			$oReginUser->delete();
		}
		if (count($tUsers) > 0) {
			$this->sendMailRegistryAllUsers($poRegin, $tUsers);
		}
	}

	/**
	 * @param row_regin $poRegin
	 * @param $ptUsers
	 * @return bool
	 */
	private function sendMailRegistryAllUsers($poRegin, $ptUsers)
	{
		$oMail = new plugin_email();
		$oMail->setFrom(_root::getConfigVar('vfa-app.mail.from.label'), _root::getConfigVar('vfa-app.mail.from'));

		$responsibles = model_user::getInstance()->findAllByGroupIdByRoleName($poRegin->group_id, plugin_vfa::ROLE_RESPONSIBLE);
		foreach ($responsibles as $user) {
			$oMail->addTo($user->email);
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

		// Prepare le body HTML
		$oViewMail = new _view('regin::mailValidateHtml');
		$oViewMail->tAwards = $tAwards;
		$bodyHtml = $oViewMail->show();
		$oMail->setBodyHtml($bodyHtml);

		// Envoi le mail
		$sent = plugin_vfa::sendEmail($oMail);
		return $sent;
	}

	/**
	 * @param row_regin $poRegin
	 * @param $poUser
	 */
	private function saveGroupAwardsToUser($poRegin, $poUser)
	{
		// Sauve le groupe de même rôle à l'utilisateur
		$tIdGroups = array($poRegin->group_id);
		model_user::getInstance()->mergeUserGroups($poUser, $tIdGroups);
		// Ajoute les prix à l'utilisateur
		$tIdAwards = explode(',', $poRegin->awards_ids);
		model_user::getInstance()->mergeUserAwards($poUser, $tIdAwards);
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
			$toReginUsers = model_regin_users::getInstance()->findAllByReginId($oRegin->getId());
			foreach ($toReginUsers as $oReginUser) {
			 	$oReginUser->delete();
			}
			$oRegin->delete();
		}
		_root::redirect('regin::opened');
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
		}
	}

	/**
	 * @param row_registry $poRegistry
	 */
	private function doRegistryReader($poRegistry)
	{
		if (plugin_vfa::PROCESS_INTIME == $poRegistry->oRegin->process) {
			// Associe le groupe de même rôle et les prix à l'utilisateur
			$this->saveGroupAwardsToUser($poRegistry->oRegin, $poRegistry->oUser);
			// Met à jour la session
			$oUserSession = _root::getAuth()->getUserSession();
			$oUserSession->setUser($poRegistry->oUser);
			_root::getAuth()->setUserSession($oUserSession);
		} else {
			// Sauvegarde pour validation
			model_regin::getInstance()->saveReginUser($poRegistry->oRegin->getId(), $poRegistry->oUser->getId());
			module_default::sendMailReginToValid($poRegistry);
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
