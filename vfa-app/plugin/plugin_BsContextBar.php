<?php

/**
 *
 * @author AMA
 */
class plugin_BsContextBar
{

	public static function buildDefaultContextBar($pNavBar)
	{
		$sModule = _root::getModule();
		$bar = $pNavBar->getChild('left');
		$bar->addChild(plugin_BsHtml::buildButtonItem('Liste', new NavLink($sModule, 'list'), 'glyphicon-list'));
		$bar->addChild(plugin_BsHtml::buildButtonItem('Créer', new NavLink($sModule, 'create'), 'glyphicon-plus-sign'));
		switch (_root::getAction()) {
			case 'read':
			case 'update':
			case 'delete':
				$bar->addChild(
					plugin_BsHtml::buildButtonItem('Détail', new NavLink($sModule, 'read', array('id' => _root::getParam('id'))), 
						'glyphicon-eye-open'));
				$bar->addChild(
					plugin_BsHtml::buildButtonItem('Modifier', 
						new NavLink($sModule, 'update', array('id' => _root::getParam('id'))), 'glyphicon-edit'));
				$bar->addChild(
					plugin_BsHtml::buildButtonItem('Supprimer', 
						new NavLink($sModule, 'delete', array('id' => _root::getParam('id'))), 'glyphicon-trash'));
				break;
		}
	}
}
