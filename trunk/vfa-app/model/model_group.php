<?php

class model_group extends abstract_model
{

	protected $sClassRow = 'row_group';

	protected $sTable = 'vfa_groups';

	protected $sConfig = 'mysql';

	protected $tId = array(
		'group_id'
	);

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE group_id=?', $uId);
	}

	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY group_name');
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
}

class row_group extends abstract_row
{

	protected $sClassModel = 'model_group';

	protected $tMessages = null;

	public function findUsers()
	{
		return model_user::getInstance()->findAllByGroupId($this->group_id);
	}

	public function getTypeString()
	{
		switch ($this->type) {
			case 'BOARD':
				$s = "Comité de sélection";
				break;
			default:
				$s = "Groupe de lecteurs";
				break;
		}
		return $s;
	}

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('group_name');
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
