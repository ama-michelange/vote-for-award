<?php

class model_group extends abstract_model
{

	protected $sClassRow = 'row_group';

	protected $sTable = 'vfa_groups';

	protected $sConfig = 'mysql';

	protected $tId = array('group_id');

	private $tTypeGroups;

	/**
	 * @return model_group
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId string
	 * @return row_group
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE group_id=?', $uId);
	}

	/**
	 * @return array row_group
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY group_name');
	}

	/**
	 * @return array row_group
	 */
	public function findAllReader()
	{
		$role_id=model_role::getInstance()->findByName(plugin_vfa::TYPE_READER)->role_id;
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' WHERE role_id_default=? ORDER BY group_name', $role_id);
	}

	/**
	 * @param $ptId array string
	 * @return array row_group
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
				$sql .= ' group_id=' . $id;
				$i++;
			}
			$ret = $this->findMany($sql);
		}
		return $ret;
	}


	public function findAllByUserId($pUserId)
	{
		$sql = 'SELECT * FROM vfa_groups, vfa_user_groups ';
		$sql .= 'WHERE (vfa_user_groups.group_id = vfa_groups.group_id) AND (vfa_user_groups.user_id = ?) ';
		$sql .= 'ORDER BY vfa_groups.group_name';
		return $this->findMany($sql, $pUserId);
	}

	public function findAllByType($pType)
	{
		$sql = 'SELECT * FROM vfa_groups ';
		$sql .= 'WHERE (vfa_groups.type = ?) ';
		$sql .= 'ORDER BY vfa_groups.group_name';
		return $this->findMany($sql, $pType);
	}

	public function findAllByUserIdByType($pUserId, $pType)
	{
		$sql = 'SELECT * FROM vfa_groups, vfa_user_groups ';
		$sql .= 'WHERE (vfa_user_groups.group_id = vfa_groups.group_id) AND (vfa_user_groups.user_id = ?) AND (vfa_groups.type = ?) ';
		$sql .= 'ORDER BY vfa_groups.group_name';
		return $this->findMany($sql, $pUserId, $pType);
	}

	public function findByName($pName)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE group_name=?', $pName);
	}

	public function getSelect()
	{
		$tab = $this->findAll();
		$tSelect = array();
		foreach ($tab as $oRow) {
			$tSelect[$oRow->group_id] = $oRow->group_name;
		}
		return $tSelect;
	}

	/**
	 * @param $pIdRole
	 * @return string
	 */
	public function getStringTypeGroup($pIdRole)
	{
		if (!isset($this->tTypeGroups[$pIdRole])) {
			if (!isset($this->tTypeGroups)) {
				$this->tTypeGroups = array();
			}
			$oRole = model_role::getInstance()->findById($pIdRole);
			$this->tTypeGroups[$pIdRole] = plugin_i18n::get('group.' . $oRole ->role_name);
		}
		return $this->tTypeGroups[$pIdRole];
	}

}

class row_group extends abstract_row
{

	protected $sClassModel = 'model_group';

	protected $tMessages = null;

	public function findUsers()
	{
		return model_user::getInstance()->findAllByGroupId($this->group_id);
	}

	/**
	 * @return string
	 */
	public function getTypeString()
	{
		return model_group::getInstance()->getStringTypeGroup($this->role_id_default);
	}

	/**
	 * @return string
	 */
	public function getRoleString()
	{
		return model_role::getInstance()->getStringRole($this->role_id_default);
	}

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('group_name');
		$oPluginValid->isNotEmpty('role_id_default');
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
