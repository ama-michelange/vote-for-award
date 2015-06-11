<?php
/*
 * This file is part of Mkframework. Mkframework is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License. Mkframework is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with Mkframework. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * plugin_authent Gestion de l'authentification
 *
 * @author michelange
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
class plugin_authent extends abstract_auth
{

	private $oUserSession = null;

	public function setUserSession($oUserSession)
	{
		$_SESSION['oUserSession'] = serialize($oUserSession);
		$this->oUserSession = $oUserSession;
	}

	public function getUserSession()
	{
		if (null == $this->oUserSession) {
			$this->oUserSession = unserialize($_SESSION['oUserSession']);
		}
		return $this->oUserSession;
	}

	/**
	 * methode appele a la connexion
	 *
	 * @access public
	 * @return bool retourne true/false selon que la personne est ou non authentifiee
	 */
	public function isConnected()
	{
		if (!$this->_isConnected()) {
			return false;
		}
		$this->setUserSession(unserialize($_SESSION['oUserSession']));
		// ajouter critere supp pour verification de l'authentification
		return true;
	}

	/**
	 * Connexion du compte d'un utilisateur
	 *
	 * @access public
	 * @param object $oUserSession
	 *          L'instance du compte
	 */
	public function connect($oUserSession)
	{
		$this->_connect();
		$this->setUserSession($oUserSession);
	}

	/**
	 * methode appele a la deconnexion
	 *
	 * @access public
	 */
	public function logout()
	{
		_root::startSession();
		$this->_disconnect();
		_root::redirect(_root::getConfigVar('auth.module'));
	}
}
