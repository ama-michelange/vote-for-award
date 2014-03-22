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
		$bar->addChild(new MenuItem('Connexion', new Link('#myModal', array('data-toggle' => 'modal')), 'glyphicon-user'));
	}

	private function buildConnectedBar($pNavBar)
	{
		$pNavBar->setTitle(_root::getConfigVar('vfa-app.title'), new NavLink('default', 'index'));
		$bar = $pNavBar->getChild('left');
		$bar->addChild(plugin_BsHtml::buildMenuItem('Accueil', new NavLink('home_enable', 'index')));
		$bar->addChild(plugin_BsHtml::buildMenuItem('Vote', new NavLink('home_enable', 'index')));

		$this->buildMenuPrix($bar);
		$this->buildMenuInscription($bar);
		$this->buildMenuAdmin($bar);

		$bar = $pNavBar->getChild('right');
		$this->buildMenuConnectedUser($bar);
	}

	private function buildMenuConnectedUser($pItems)
	{
		$account = _root::getAuth()->getAccount();
		$item = new DropdownMenuItem($account->getUser()->username, null, 'glyphicon-user');
		$item->addChild(plugin_BsHtml::buildMenuItem('Mon compte', new NavLink('connected_user', 'account', null, true)), 'glyphicon-user');
		$item->addChild(plugin_BsHtml::buildMenuItem('Déconnexion', new NavLink('bsnavbar', 'logout', null, true)), 'glyphicon-remove-sign');

		if ($item->hasRealChildren()) {
			$pItems->addChild($item);
		}
	}

	private function buildMenuPrix($pItems)
	{
		$item = new DropdownMenuItem('Prix');
		$item->addChild(plugin_BsHtml::buildMenuItem('Résultats', new NavLink('results', 'index')));

		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Prix', new NavLink('awards', 'list')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Sélections', new NavLink('nominees', 'list')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Albums', new NavLink('docs', 'list')));

		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Toto - Titi', new NavLink('docs', 'list')));

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

	private function buildMenuInscription($pItems)
	{
		$item = new DropdownMenuItem('Inscription');
		$item->addChild(plugin_BsHtml::buildMenuItem('Invitations', new NavLink('invitations', 'list')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Invitation aux lecteurs', new NavLink('invitations', 'reader')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Inscriptions libres', new NavLink('invitations', 'free')));

		$item->addChildSeparator();
		$item->addChild(plugin_BsHtml::buildMenuItem('Responsable de groupe', new NavLink('invitations', 'responsible')));
		$item->addChild(plugin_BsHtml::buildMenuItem('Membre du comité', new NavLink('invitations', 'board')));

		if ($item->hasRealChildren()) {
			$pItems->addChild($item);
		}
	}

	public function buildView()
	{
		// _root::getLog()->log('AMA >>> module_authent._login()');
		// Vue par défaut
		$oView = new _view('bsnavbar::index');
		// Post ?
		if (_root::getRequest()->isPost()) {
			// Recup params
			$sLogin = _root::getParam('login');
			$sPass = sha1(_root::getParam('password'));
			// _root::getLog()->log('AMA >>> login = '.$sLogin.', pass = '.$sPass);
			// Recherche et vérifie "login/pass" dans la base
			$oUser = model_user::getInstance()->findByLoginAndCheckPass($sLogin, $sPass);
			// Connexion si utilisateur autorisé
			if (null != $oUser) {
				$oAccount = model_account::getInstance()->create($oUser);
				// _root::getLog()->log('AMA >>> $oAccount = '.$oAccount->getUser()->username);
				_root::getAuth()->connect($oAccount);
				_root::redirect('home_enable::index');
			} else {
				// TODO Passer une info du genre : $this->sUnknownLogin=$sLogin; pour indiquer au module parent une erreur
			}
		}
		return $oView;
	}
}