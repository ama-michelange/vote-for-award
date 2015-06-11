<?php

class model_authorization extends abstract_model
{

	protected $sClassRow = 'row_authorization';

	protected $sTable = 'vfa_authorizations';

	protected $sConfig = 'mysql';

	protected $tId = array('authorization_id');

	/**
	 * @return model_authorization
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_authorization
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE authorization_id=?', $uId);
	}

	/**
	 * @return row_authorization[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY role_id, module, action');
	}

	/**
	 * @param $pRoleId
	 * @return row_authorization[]
	 */
	public function findAllByRoleId($pRoleId)
	{
		$sql = 'SELECT * FROM vfa_authorizations WHERE role_id=? ORDER BY module, action';
		return $this->findMany($sql, $pRoleId);
	}

	/**
	 * @param $pIdRole
	 * @param $ptoAuthorizations
	 */
	public function saveRoleAuthorizations($pIdRole, $ptoAuthorizations)
	{
		$this->execute('DELETE FROM vfa_authorizations WHERE role_id=?', $pIdRole);
		foreach ($ptoAuthorizations as $oAuthorization) {
			$oAuthorization->save();
		}
	}
}

/**
 * Class row_authorization
 */
class row_authorization extends abstract_row
{

	protected $sClassModel = 'model_authorization';

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
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
}
