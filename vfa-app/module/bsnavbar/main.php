<?php

class module_bsnavbar extends abstract_module
{

	public function _index()
	{
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
		$bar->addChild(new MenuItem('Accueil', new NavLink('default', 'index')));

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
		$bar->addChild(plugin_BsHtml::buildMenuItem('Voter', new NavLink('votes', 'index')));

		$this->buildMenuAwards($bar);
		$this->buildMenuReader($bar);
		$this->buildMenuRegistrations($bar);
		$this->buildMenuAdmin($bar);

		$bar = $pNavBar->getChild('right');
		$this->buildMenuAccount($bar, $oUserSession);
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
		$item->addChild(plugin_BsHtml::buildMenuItem('Prix en cours', new NavLink('results', 'awardInProgress')));
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
	private function buildMenuRegistrations($pItems)
	{
		$tMenuItems = array();
		$tMenuItems[] = plugin_BsHtml::buildMenuItem('S\'inscrire', new NavLink('regin', 'index'));
		$tMenuItems[] = plugin_BsHtml::buildSeparator();
		$tMenuItems[] = plugin_BsHtml::buildMenuItem('Ouverture d\'inscriptions', new NavLink('regin', 'opened'));
		$tMenuItems[] = plugin_BsHtml::buildMenuItem('Validation d\'inscriptions', new NavLink('regin', 'validates'));

		$pItems->addChild(plugin_BsHtml::buildDropdownMenuItem($tMenuItems, 'Inscriptions', 'S\'inscrire', true));
	}

	public function buildView()
	{
		// Vue par défaut
		return new _view('bsnavbar::index');
	}
}
