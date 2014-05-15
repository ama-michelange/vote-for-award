<?php

class model_role extends abstract_model
{

	protected $sClassRow = 'row_role';

	protected $sTable = 'vfa_roles';

	protected $sConfig = 'mysql';

	protected $tId = array('role_id');

	private $tStringRoles;

	/**
	 * @return model_role
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $pId
	 * @return row_role
	 */
	public function findById($pId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE role_id=?', $pId);
	}

	/**
	 * @param $pName
	 * @return row_role
	 */
	public function findByName($pName)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE role_name=?', $pName);
	}

	/**
	 * @param $pName
	 * @param $pUserId
	 * @return boolean
	 */
	public function isInRole($pName, $pUserId)
	{
		$sql = 'SELECT vfa_roles.role_id, vfa_roles.role_name FROM vfa_roles, vfa_user_roles ' . 'WHERE vfa_roles.role_name=? ' .
			'AND (vfa_user_roles.role_id = vfa_roles.role_id) ' . 'AND (vfa_user_roles.user_id = ?)';
		$role = $this->findOne($sql, $pName, $pUserId);
		if ($role->isEmpty()) {
			return false;
		}
		return true;
	}

	/**
	 * @return row_role[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY role_name');
	}

	/**
	 * @param $ptId array string
	 * @return row_role[]
	 */
	public function findAllByIds($ptId)
	{
		$ret = null;
		if ($ptId) {
			$sql = 'SELECT * FROM ' . $this->sTable;
			$sql .= ' WHERE';
			$i = 0;
			foreach ($ptId as $id) {
				if ($i > 0) {
					$sql .= ' OR';
				}
				$sql .= ' role_id=' . $id;
				$i++;
			}
			$ret = $this->findMany($sql);
		}
		return $ret;
	}

	/**
	 * @param $pUserId
	 * @return row_role[]
	 */
	public function findAllByUserId($pUserId)
	{
		$sql = 'SELECT vfa_roles.role_id, vfa_roles.role_name FROM vfa_roles, vfa_user_roles ' .
			'WHERE (vfa_user_roles.role_id = vfa_roles.role_id) ' . 'AND (vfa_user_roles.user_id = ?) ' . 'ORDER BY vfa_roles.role_name';
		return $this->findMany($sql, $pUserId);
	}

	public function getSelectAll()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->role_id] = $oRow->role_name;
		}
		return $tSelect;
	}

	public function getSelectById($pRoleId)
	{
		$oRow = $this->findById($pRoleId);
		$tSelect = array();
		$tSelect[$oRow->role_id] = $oRow->toString();
		return $tSelect;
	}

	/**
	 * Supprime
	 *
	 * @param int $pIdRole
	 */
	public function deleteRoleCascades($pIdRole)
	{
		$this->execute('DELETE FROM vfa_roles WHERE role_id=?', $pIdRole);
		$this->execute('DELETE FROM vfa_authorizations WHERE role_id=?', $pIdRole);
		$this->execute('DELETE FROM vfa_user_roles WHERE role_id=?', $pIdRole);
	}

	/**
	 * @param $pIdRole
	 * @return string
	 */
	public function getI18nStringRole($pIdRole)
	{
		if (!isset($this->tStringRoles[$pIdRole])) {
			if (!isset($this->tStringRoles)) {
				$this->tStringRoles = array();
			}
			$oRole = model_role::getInstance()->findById($pIdRole);
			$this->tStringRoles[$pIdRole] = plugin_i18n::get('role.' . $oRole ->role_name);
		}
		return $this->tStringRoles[$pIdRole];
	}
}

class row_role extends abstract_row
{

	protected $sClassModel = 'model_role';

	/**
	 * @return row_authorization
	 */
	public function findAuthorizations()
	{
		return model_authorization::getInstance()->findAllByRoleId($this->role_id);
	}

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('role_name');
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

	public function toString()
	{
		return plugin_i18n::get('role.' . $this->role_name);
	}
}
