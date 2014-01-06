<?php

class module_bsnavbar extends abstract_module
{

	public function _index()
	{
		$oView = $this->buildView();
		$oView->oBar = $this->buildBar();
		return $oView;
	}

	public function _logout()
	{
		_root::getAuth()->logout();
	}

	private function buildBar()
	{
		$navBar = new plugin_NavBar();
		$navBar->put('left', new plugin_NavItems());
		$navBar->put('right', new plugin_NavItems());
		if (_root::getAuth()->isConnected()) {
			$this->buildConnectedBar($navBar);
		} else {
			$this->buildDefaultBar($navBar);
		}
		return $navBar;
	}

	private function buildDefaultBar($pBar)
	{
		$items = $pBar->get('left');
		$items->put(plugin_LabelItem::buildDefaultLink('Accueil', new plugin_ActionLinkItem('default', 'index')));
		$items = $pBar->get('right');
		$items->put(
			new plugin_LabelItem('Connexion', 'glyphicon-user', new plugin_LinkItem('#myModal', array(
				'data-toggle' => 'modal'
			))));
		
// 		<a href="#myModal" data-toggle="modal"><i
// 		class="glyphicon glyphicon-user  with-text"></
// 		i>Connexion<
	}

	private function buildConnectedBar($pBar)
	{
		$items = $pBar->get('left');
		$items->put(plugin_LabelItem::buildLink('Accueil', new plugin_ActionLinkItem('home_enable', 'index')));
		$items->put(plugin_LabelItem::buildLink('Vote', new plugin_ActionLinkItem('home_enable', 'index')));
		
		$this->buildItemsPrix($items);
		$this->buildItemsInscription($items);
		$this->buildItemsAdmin($items);
		
		$items = $pBar->get('right');
		$this->buildItemsUser($items);
	}

	private function buildItemsUser($pItems)
	{
		$account = _root::getAuth()->getAccount();
		$item = new plugin_LabelItem($account->getUser()->username, 'glyphicon-user');
		$item->addChild(plugin_LabelItem::build('Mon compte', 'glyphicon-user', new plugin_ActionLinkItem('user', 'account')));
		$item->addChild(
			plugin_LabelItem::build('Déconnexion', 'glyphicon-remove-sign', new plugin_ActionLinkItem('bsnavbar', 'logout')));
		
		if (false == $item->hasOnlySeparatorChildren()) {
			$pItems->put($item);
		}
	}

	private function buildItemsPrix($pItems)
	{
		$item = new plugin_LabelItem('Prix');
		$item->addChild(plugin_LabelItem::buildLink('Résultats', new plugin_ActionLinkItem('results', 'index')));
		if (false == $item->hasOnlySeparatorChildren()) {
			$item->addChild(plugin_LabelItem::buildSeparator());
		}
		$item->addChild(plugin_LabelItem::buildLink('Prix', new plugin_ActionLinkItem('awards', 'list')));
		$item->addChild(plugin_LabelItem::buildLink('Titres sélectionnés', new plugin_ActionLinkItem('nominees', 'list')));
		$item->addChild(plugin_LabelItem::buildLink('Albums', new plugin_ActionLinkItem('docs', 'list')));
		if (false == $item->hasOnlySeparatorChildren()) {
			$item->addChild(plugin_LabelItem::buildSeparator());
		}
		$item->addChild(plugin_LabelItem::buildLink('Toto - Titi', new plugin_ActionLinkItem('docs', 'list')));
		
		if (false == $item->hasOnlySeparatorChildren()) {
			$pItems->put($item);
		}
	}

	private function buildItemsAdmin($pItems)
	{
		$item = new plugin_LabelItem('Administrer');
		$item->addChild(plugin_LabelItem::buildLink('Albums', new plugin_ActionLinkItem('docs', 'index')));
		$item->addChild(plugin_LabelItem::buildLink('Prix', new plugin_ActionLinkItem('awards', 'index')));
		$item->addChild(plugin_LabelItem::buildLink('Titres sélectionnés', new plugin_ActionLinkItem('nominees', 'index')));
		
		if (false == $item->hasOnlySeparatorChildren()) {
			$item->addChild(plugin_LabelItem::buildSeparator());
		}
		$item->addChild(plugin_LabelItem::buildLink('Groupes', new plugin_ActionLinkItem('groups', 'index')));
		$item->addChild(plugin_LabelItem::buildLink('Utilisateurs', new plugin_ActionLinkItem('users', 'index')));
		$item->addChild(plugin_LabelItem::buildLink('Rôles', new plugin_ActionLinkItem('roles', 'index')));
		
		if (false == $item->hasOnlySeparatorChildren()) {
			$pItems->put($item);
		}
	}

	private function buildItemsInscription($pItems)
	{
		$item = new plugin_LabelItem('Inscription');
		$item->addChild(plugin_LabelItem::buildLink('Invitations', new plugin_ActionLinkItem('invitations', 'list')));
		$item->addChild(
			plugin_LabelItem::buildLink('Invitation aux lecteurs', new plugin_ActionLinkItem('invitations', 'reader')));
		$item->addChild(plugin_LabelItem::buildLink('Inscriptions libres', new plugin_ActionLinkItem('invitations', 'free')));
		
		if (false == $item->hasOnlySeparatorChildren()) {
			$item->addChild(plugin_LabelItem::buildSeparator());
		}
		$item->addChild(
			plugin_LabelItem::buildLink('Responsable de groupe', new plugin_ActionLinkItem('invitations', 'responsible')));
		$item->addChild(plugin_LabelItem::buildLink('Membre du comité', new plugin_ActionLinkItem('invitations', 'board')));
		
		if (false == $item->hasOnlySeparatorChildren()) {
			$pItems->put($item);
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
