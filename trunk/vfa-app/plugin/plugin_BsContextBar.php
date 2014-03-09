<?php

/**
 * Class plugin_BsContextBar.
 */
class plugin_BsContextBar
{
	/**
	 * Construit la vue du menu contextuel.
	 * @param NavBar $pNavBar Les barres de boutons du menu contextuel à affecter dans la vue
	 * @return _view
	 */
	public static function buildViewContextBar($pNavBar)
	{
		$oView = new _view('bsnavcontext::index');
		$oView->oNavBar = $pNavBar;
		return $oView;
	}

	/**
	 * Construit la barre de boutons de gauche du menu contextuel
	 * @param NavBar $pNavBar
	 */
	public static function buildDefaultContextBar($pNavBar, $pOtherParams = null)
	{
		$sModule = _root::getModule();
		$bar = $pNavBar->getChild('left');
		$bar->addChild(plugin_BsHtml::buildButtonItem('Liste', new NavLink($sModule, 'list'), 'glyphicon-list'));
		$bar->addChild(plugin_BsHtml::buildButtonItem('Créer', new NavLink($sModule, 'create'), 'glyphicon-plus-sign'));
		self::buildRUDContextBar($pNavBar, $pOtherParams);
	}

	/**
	 * Construit la barre de boutons RUD (Read, Update, Delete) de gauche du menu contextuel
	 * @param NavBar $pNavBar
	 */
	public static function buildRUDContextBar($pNavBar, $pOtherParams = null)
	{
		$sModule = _root::getModule();
		$bar = $pNavBar->getChild('left');

		$tParams = array('id' => _root::getParam('id'));
		if (null != $pOtherParams) {
			$tParams = array_merge($tParams, $pOtherParams);
		}
		switch (_root::getAction()) {
			case 'read':
			case 'update':
			case 'delete':
				$bar->addChild(plugin_BsHtml::buildButtonItem('Détail', new NavLink($sModule, 'read', $tParams), 'glyphicon-eye-open'));
				$bar->addChild(plugin_BsHtml::buildButtonItem('Modifier', new NavLink($sModule, 'update', $tParams), 'glyphicon-edit'));
				$bar->addChild(plugin_BsHtml::buildButtonItem('Supprimer', new NavLink($sModule, 'delete', $tParams), 'glyphicon-trash'));
				break;
		}
	}
}
