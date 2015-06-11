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
	 * Construit la barre de boutons du menu contextuel
	 * @param Bar $pBar
	 * @param null $pOtherParams
	 */
	public static function buildDefaultContextBar($pBar, $pOtherParams = null)
	{
		$sModule = _root::getModule();
//		$pBar->addChild(plugin_BsHtml::buildButtonItem('Liste', new NavLink($sModule, 'list'), 'glyphicon-list'));
		$pBar->addChild(plugin_BsHtml::buildButtonItem('Créer', new NavLink($sModule, 'create'), 'glyphicon-plus-sign'));
		self::buildRUDContextBar($pBar, $pOtherParams);
	}

	/**
	 * Construit la barre de boutons RUD (Read, Update, Delete) du menu contextuel
	 * @param $pBar
	 * @param array|null $pOtherParams
	 */
	public static function buildRUDContextBar($pBar, $pOtherParams = null)
	{
		$sModule = _root::getModule();

		$tParams = array('id' => _root::getParam('id'));
		if (null != $pOtherParams) {
			$tParams = array_merge($tParams, $pOtherParams);
		}
		switch (_root::getAction()) {
			case 'read':
			case 'update':
			case 'delete':
				$pBar->addChild(plugin_BsHtml::buildButtonItem('Détail', new NavLink($sModule, 'read', $tParams), 'glyphicon-eye-open'));
				$pBar->addChild(plugin_BsHtml::buildButtonItem('Modifier', new NavLink($sModule, 'update', $tParams), 'glyphicon-edit'));
				$pBar->addChild(plugin_BsHtml::buildButtonItem('Supprimer', new NavLink($sModule, 'delete', $tParams), 'glyphicon-trash'));
				break;
		}
	}

	/**
	 * Construit la barre de boutons RD (Read, Delete) du menu contextuel
	 * @param $pBar
	 * @param array|null $pOtherParams
	 */
	public static function buildRDContextBar($pBar, $pOtherParams = null)
	{
		$sModule = _root::getModule();

		$tParams = array('id' => _root::getParam('id'));
		if (null != $pOtherParams) {
			$tParams = array_merge($tParams, $pOtherParams);
		}
		switch (_root::getAction()) {
			case 'read':
			case 'delete':
				$pBar->addChild(plugin_BsHtml::buildButtonItem('Détail', new NavLink($sModule, 'read', $tParams), 'glyphicon-eye-open'));
				$pBar->addChild(plugin_BsHtml::buildButtonItem('Supprimer', new NavLink($sModule, 'delete', $tParams), 'glyphicon-trash'));
				break;
		}
	}
}
