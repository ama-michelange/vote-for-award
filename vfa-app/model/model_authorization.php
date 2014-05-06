<?php

class model_authorization extends abstract_model
{

	protected $sClassRow = 'row_authorization';

	protected $sTable = 'vfa_authorizations';

	protected $sConfig = 'mysql';

	protected $tId = array(
		'authorization_id'
	);

	/**
	 * @return model_authorization
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE authorization_id=?', $uId);
	}

	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY role_id, module, action');
	}

	public function findAllByRoleId($pRoleId)
	{
		$sql = 'SELECT * FROM vfa_authorizations WHERE role_id=? ORDER BY module, action';
		return $this->findMany($sql, $pRoleId);
	}

	public function saveRoleAuthorizations($pIdRole, $ptoAuthorizations)
	{
		$this->execute('DELETE FROM vfa_authorizations WHERE role_id=?', $pIdRole);
		foreach ($ptoAuthorizations as $oAuthorization) {
			$oAuthorization->save();
		}
	}
}

class row_authorization extends abstract_row
{

	protected $sClassModel = 'model_authorization';
	
	/*
	 * exemple jointure public function findAuteur(){ return model_auteur::getInstance()->findById($this->auteur_id); }
	 */
	/*exemple test validation*/
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		/*
		 * renseigner vos check ici $oPluginValid->isEqual('champ','valeurB'); $oPluginValid->isNotEqual('champ','valeurB'); $oPluginValid->isUpperThan('champ','valeurB'); $oPluginValid->isUpperOrEqualThan('champ','valeurB'); $oPluginValid->isLowerThan('champ','valeurB'); $oPluginValid->isLowerOrEqualThan('champ','valeurB'); $oPluginValid->isEmpty('champ'); $oPluginValid->isNotEmpty('champ'); $oPluginValid->isEmailValid('champ'); $oPluginValid->matchExpression('champ','/[0-9]/'); $oPluginValid->notMatchExpression('champ','/[a-zA-Z]/');
		 */
		
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
