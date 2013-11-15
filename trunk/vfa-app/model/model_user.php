<?php

class model_user extends abstract_model
{

	protected $sClassRow = 'row_user';

	protected $sTable = 'vfa_users';

	protected $sConfig = 'mysql';

	protected $tId = array(
		'user_id'
	);

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE user_id=?', $uId);
	}

	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY vfa_users.username');
	}

	public function findAllByGroupId($pGroupId)
	{
		$sql = 'SELECT * FROM vfa_users, vfa_user_groups ' . 'WHERE (vfa_user_groups.user_id = vfa_users.user_id) ' .
			 'AND (vfa_user_groups.group_id = ?) ' . 'ORDER BY vfa_users.username';
		// .'AND (vfa_user_groups.group_id = vfa_groups.group_id) '
		return $this->findMany($sql, $pGroupId);
	}

	public function findByLogin($login)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE username=?', $login);
	}

	/**
	 * Recherche un utilisateur par son "login".
	 * Si un utilisateur est trouvé, vérifie si son mot de passe est identique à celui donné.
	 * 
	 * @param String $pLogin
	 *        	Le login (ou username) unique dans la table
	 * @param String $pPass
	 *        	Le mot de passe crypté SHA1
	 * @return L'utilisateur correspondant ou null si non trouvé ou si le mot de passe ne correspond pas
	 */
	public function findByLoginAndCheckPass($pLogin, $pPass)
	{
		$oUser = $this->findByLogin($pLogin);
		if (null != $oUser) {
			if ($pPass != $oUser->password) {
				$oUser = null;
			}
		}
		return $oUser;
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->user_id] = $oRow->username;
		}
		return $tSelect;
	}

	public function getListLoginPassUser()
	{
		$tUser = $this->findAll();
		$tLoginPassUser = array();
		foreach ($tUser as $oUser) {
			$tLoginPassUser[$oUser->username][$oUser->password] = $oUser;
			// _root::getLog()->log('AMA >>> login = '.$oUser->username.', pass = '.$oUser->password.', sha1 = '.sha1($oUser->password));
		}
		return $tLoginPassUser;
	}

	/**
	 * Supprime
	 * 
	 * @param int $pIdUser        	
	 */
	public function deleteUserCascades($pIdUser)
	{
		$this->execute('DELETE FROM vfa_users WHERE user_id=?', $pIdUser);
		$this->execute('DELETE FROM vfa_user_roles WHERE user_id=?', $pIdUser);
		$this->execute('DELETE FROM vfa_user_groups WHERE user_id=?', $pIdUser);
	}

	public function saveUserGroups($pIdUser, $pIdGroups)
	{
		$this->execute('DELETE FROM vfa_user_groups WHERE user_id=?', $pIdUser);
		if ($pIdGroups) {
			foreach ($pIdGroups as $idGroup) {
				$this->execute('INSERT INTO vfa_user_groups (user_id, group_id) VALUES (?,?)', $pIdUser, $idGroup);
			}
		}
	}

	public function saveUserRoles($pIdUser, $pIdRoles)
	{
		$this->execute('DELETE FROM vfa_user_roles WHERE user_id=?', $pIdUser);
		if ($pIdRoles) {
			foreach ($pIdRoles as $idRole) {
				$this->execute('INSERT INTO vfa_user_roles (user_id, role_id) VALUES (?,?)', $pIdUser, $idRole);
			}
		}
	}
}

class row_user extends abstract_row
{

	protected $sClassModel = 'model_user';

	protected $tMessages = null;

	public function findGroups()
	{
		$tGroups = null;
		if (null != $this->user_id) {
			$tGroups = model_group::getInstance()->findAllByUserId($this->user_id);
		}
		return $tGroups;
	}

	public function getSelectedGroups()
	{
		$tSelect = array();
		$tab = $this->findGroups();
		if (null != $tab) {
			foreach ($tab as $oRow) {
				$tSelect[$oRow->group_id] = $oRow->group_name;
			}
		}
		return $tSelect;
	}

	public function findRoles()
	{
		$tRoles = null;
		if (null != $this->user_id) {
			$tRoles = model_role::getInstance()->findAllByUserId($this->user_id);
		}
		return $tRoles;
	}

	public function getSelectedRoles()
	{
		$tSelect = array();
		$tab = $this->findRoles();
		if (null != $tab) {
			foreach ($tab as $oRow) {
				$tSelect[$oRow->role_id] = $oRow->role_name;
			}
		}
		return $tSelect;
	}

	public function findAwards()
	{
		$tAwards = null;
		if (null != $this->user_id) {
			$tAwards = model_award::getInstance()->findAllByUserId($this->user_id);
		}
		return $tAwards;
	}
	
	/* exemple test validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('username');
		$oPluginValid->isNotEmpty('email');
		if (null != $this->__get('email')) {
			$oPluginValid->isEmailValid('email');
		}
		if (null != $this->__get('birthyear')) {
			$oPluginValid->matchExpression('birthyear', '/^[0-9]+$/');
			$date = new plugin_date(date('Y-m-d'));
			$date->removeYear(100);
			$oPluginValid->isUpperOrEqualThan('birthyear', $date->getYear());
			$date->addYear(91);
			$oPluginValid->isLowerThan('birthyear', $date->getYear());
		}
		// $oPluginValid->isNotEmpty('last_name');
		// $oPluginValid->isNotEmpty('first_name');
		return $oPluginValid;
	}

	public function isValid()
	{
		return $this->getCheck()->isValid();
	}

	public function getListError()
	{
		return $this->getCheck()->getListError();
	}

	public function setMessages($ptMessages)
	{
		$this->tMessages = $ptMessages;
	}

	public function getMessages()
	{
		if (null != $this->tMessages) {
			return $this->tMessages;
		}
		return $this->getListError();
	}
}
