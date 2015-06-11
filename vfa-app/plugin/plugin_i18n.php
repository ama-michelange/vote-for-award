<?php

/*
 * This file is part of Mkframework. Mkframework is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License. Mkframework is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details. You should have received a copy of the GNU Lesser General Public License along with Mkframework. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * plugin_i18n classe pour gerer l'internationnalisation
 *
 * @author Mika
 * @link http://mkf.mkdevs.com/
 *
 */
class plugin_i18n
{

	private static $tLangue;

	/**
	 * charge le fichier de langue situe dans la section [path], valeur de i18n
	 *
	 * @access public static
	 * @param string $sLang
	 *         (doit etre present dans le fichier de config [language] allow separer par des virgules
	 */
	public static function load($sLang)
	{
		$tAllowed = preg_split('/,/', _root::getConfigVar('language.allow'));
		if (!in_array($sLang, $tAllowed) and $sLang != _root::getConfigVar('language.default')) {
			throw new Exception('Lang not allowed, list allow:' . _root::getConfigVar('language.allow'));
		}
		include_once _root::getConfigVar('path.i18n') . $sLang . '.php';

		self::$tLangue = _root::getConfigVar('tLangue');
	}

	/**
	 * retourne la traduction du tag $sTag
	 *
	 * @access public static
	 * @param string $sTag
	 *         tag du mot a traduire
	 */
	public static function get($sTag)
	{
		if (!isset(self::$tLangue[$sTag])) {
			return $sTag . '(need translation)';
		}
		return self::$tLangue[$sTag];
	}

	/**
	 * @param $pTag1
	 * @param null $pTag2
	 * @param null $pTag3
	 * @return bool|string
	 */
	public static function findFirst($pTag1, $pTag2 = null, $pTag3 = null)
	{
		$tag = $pTag1 . '.' . $pTag2 . '.' . $pTag3;
		if (isset(self::$tLangue[$tag])) {
			return self::$tLangue[$tag];
		}
		$tag = $pTag1 . '.' . $pTag2;
		if (isset(self::$tLangue[$tag])) {
			return self::$tLangue[$tag];
		}
		return false;
	}

	/**
	 * @param string $pTag1
	 * @param string|null $pTag2
	 * @param string|null $pTag3
	 * @return string
	 */
	public static function getFirst($pTag1, $pTag2 = null, $pTag3 = null)
	{
		$ret = self::findFirst($pTag1, $pTag2, $pTag3);
		if (!$ret) {
			$ret = '???' . $pTag1 . '.' . $pTag2 . '.' . $pTag3 . '???';
		}
		return $ret;
	}

}
