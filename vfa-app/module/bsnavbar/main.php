<?php

class module_bsnavbar extends abstract_module
{
	public function before()
	{

	}

	public function _index()
	{
		if (_root::getAuth()->isConnected()) {
			// Si le cookie existe, c'est suite à une inscription alors recréation des données utilisateurs en session
			if (array_key_exists('VFA_USER_SESSION', $_COOKIE)) {
				/* @var $oUserSession row_user_session */
				$oUserSession = _root::getAuth()->getUserSession();
				$oUser = $oUserSession->getUser();
				$oUserSession = model_user_session::getInstance()->create($oUser);
				_root::getAuth()->setUserSession($oUserSession);
				setcookie("VFA_USER_SESSION", "", time() - 3600);
			}
		}


		$oView = $this->buildView();
		$oView->oNavBar = $this->buildNavBar();
		return $oView;
	}

	public function _logout()
	{
		_root::getAuth()->logout();
	}

	private function buildNavBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->addChild(new Bar('left'));
		$navBar->addChild(new Bar('right'));
		if (_root::getAuth()->isConnected()) {
			$this->buildConnectedBar($navBar);
		} else {
			$this->buildDefaultBar($navBar);
		}
		return $navBar;
	}

	private function buildDefaultBar($pNavBar)
	{
		$pNavBar->setTitle(_root::getConfigVar('vfa-app.title'), new NavLink('default', 'index'));
		$bar = $pNavBar->getChild('left');
		$bar->addChild(new MenuItem('S\'inscrire', new NavLink('default', 'code')));

		$bar = $pNavBar->getChild('right');
		$item = new SplitButtonDropdownItem('Connexion', new Link('#modalLogin', array('data-toggle' => 'modal')), 'glyphicon-user');
		$item->addChild(new MenuItem('S\'identifier', new Link('#modalLogin', array('data-toggle' => 'modal')), 'glyphicon-user'));
		$item->addChild(new MenuItem('Mot de passe oublié ?', new NavLink('connection', 'forgotten', null, true), 'glyphicon-fire'));
		$bar->addChild($item);
	}

	private function buildConnectedBar($pNavBar)
	{
		/* @var $oUserSession row_user_session */
		$oUserSession = _root::getAuth()->getUserSession();

		$pNavBar->setTitle(_root::getConfigVar('vfa-app.title'), new NavLink('default', 'index'));
		$bar = $pNavBar->getChild('left');

//		$toValidAwards = $oUserSession->getValidAwards();
//		if (count($toValidAwards) > 0) {
//			$bar->addChild(plugin_BsHtml::buildMenuItem('Voter', new NavLink('votes', 'index')));
//		}

		$this->buildMenuVotes($bar, $oUserSession);
		$this->buildMenuAwards($bar);
		$this->buildMenuReader($bar);
		$this->buildMenuRegistrations($bar, $oUserSession);
		$this->buildMenuAdmin($bar);

		$bar = $pNavBar->getChild('right');
		$this->buildMenuAccount($bar, $oUserSession);
	}

	/**
	 * @param Bar $pBar
	 * @param row_user_session $poUserSession
	 */
	private function buildMenuVotes($pBar, $poUserSession)
	{
		$tItems = array();
		$toValidAwards = $poUserSession->getValidAwards();
		if (count($toValidAwards) > 0) {
			$tItems[] = plugin_BsHtml::buildMenuItem('Voter', new NavLink('votes', 'index'));
		}
		$tItems[] = plugin_BsHtml::buildMenuItem('Votes en cours', new NavLink('votes_progress', 'index'));

		$tNotNullItems = array();
		foreach ($tItems as $item) {
			if (null != $item) {
				$tNotNullItems[] = $item;
			}
		}

		if (count($tNotNullItems) > 1) {
			$dropItem = new DropdownMenuItem('Votes');
			foreach ($tNotNullItems as $item) {
				$dropItem->addChild($item);
			}
			if ($dropItem->hasRealChildren()) {
				$pBar->addChild($dropItem);
			}
		} elseif (count($tNotNullItems) == 1) {
			$pBar->addChild($tItems[0]);
		}
	}

	/**
	 * @param Bar $pItems
	 * @param row_user_session $poUserSession
	 */
	private function buildMenuAccount($pItems, $poUserSession)
	{
		$item = new DropdownMenuItem($poUserSession->getUser()->login, null, 'glyphicon-user');
		$item->addChild(plugin_BsHtml::buildMenuItem('Mon compte', new NavLink('accounts', 'index', null, true)), 'glyphicon-user');
		$item->addChild(plugin_BsHtml::buildMenuItem('Déconnexion', new NavLink('bsnavbar', 'logout', null, true)), 'glyphicon-remove-sign');

		if ($item->hasRealChildren()) {
			$pItems->addChild($item);
		}
	}

	private function buildMenuAwards($pItems)
	{
		$item = new DropdownMenuItem('Prix');
		$toInProgressAwards = model_award::getInstance()->findAllInProgress(plugin_vfa::TYPE_AWARD_READER, true);
		if (count($toInProgressAwards) > 0) {
			$item->addChild(plugin_BsHtml::buildMenuItem('Sélection ' . $toInProgressAwards[0]->year,
				new NavLink('results', 'awardInProgress')));
		}
		$item->addChild(plugin_BsHtml::buildMenuItem('Classement intermédiaire', new NavLink('results', 'live')));
		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Résultat du dernier prix', new NavLink('results', 'last')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Archives', new NavLink('results', 'archives')));

		if ($item->hasRealChildren()) {
			$pItems->addChild($item);
		}
	}

	private function buildMenuAdmin($pItems)
	{
		$item = new DropdownMenuItem('Administrer');
		$item->addChild(plugin_BsHtml::buildMenuItem('Albums', new NavLink('docs', 'index')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Sélections', new NavLink('selections', 'index')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Prix', new NavLink('awards', 'index')));

		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Groupes', new NavLink('groups', 'index')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Utilisateurs', new NavLink('users', 'index')));

		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Rôles', new NavLink('roles', 'index')));

		if ($item->hasRealChildren()) {
			$pItems->addChild($item);
		}
	}

	/**
	 * @param Bar $pItems
	 */
	private function buildMenuReader($pItems)
	{
		$item = new DropdownMenuItem('Lecteurs');

		$item->addChild(plugin_BsHtml::buildMenuItem('Inscrits', new NavLink('registred', 'index')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Invités', new NavLink('invitations', 'index')));

		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Mon groupe', new NavLink('users', 'listMyGroup')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Correspondants', new NavLink('users', 'listResponsibleGroup')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Comité de sélection', new NavLink('users', 'listBoardGroup')));

		if ($item->hasRealChildren()) {
			$pItems->addChild($item);
		}
	}

	/**
	 * @param Bar $pItems
	 */
	private function buildMenuRegistrations($pItems, $poUserSession)
	{
		$tMenuItems = array();
		$tMenuItems[] = plugin_BsHtml::buildMenuItem('S\'inscrire', new NavLink('regin', 'index'));
		$tMenuItems[] = plugin_BsHtml::buildSeparator();
		if ($poUserSession->isInRole(plugin_vfa::ROLE_ORGANIZER) || $poUserSession->isInRole(plugin_vfa::ROLE_OWNER)) {
			$tMenuItems[] = plugin_BsHtml::buildMenuItem('Inscriptions ouvertes', new NavLink('regin', 'opened'));
			$tMenuItems[] = plugin_BsHtml::buildMenuItem('Validation d\'inscriptions', new NavLink('regin', 'validate'));
		} elseif ($poUserSession->isInRole(plugin_vfa::ROLE_RESPONSIBLE)) {
			$tRegins = model_regin::getInstance()
				->findAllByTypeByGroupIdByState(plugin_vfa::TYPE_READER, $poUserSession->getReaderGroup()->getId());
			if (0 == count($tRegins)) {
				$tMenuItems[] = plugin_BsHtml::buildMenuItem('Ouverture des inscriptions', new NavLink('regin', 'open'));
			} else {
				$tMenuItems[] = plugin_BsHtml::buildMenuItem('Inscription ouverte', new NavLink('regin', 'opened'));
				$tMenuItems[] = plugin_BsHtml::buildMenuItem('Validation des participations', new NavLink('regin', 'validate'));
			}
		}
		$pItems->addChild(plugin_BsHtml::buildDropdownMenuItem($tMenuItems, 'Inscriptions', 'S\'inscrire', true));
	}

	public function buildView()
	{
		// Vue par défaut
		return new _view('bsnavbar::index');
	}
}
