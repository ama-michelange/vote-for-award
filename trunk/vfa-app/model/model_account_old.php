<?php

class model_account extends abstract_model
{

	protected $sClassRow = 'row_account';

	protected $sTable = 'account';

	protected $sConfig = 'csv';

	protected $tId = array(
		'id'
	);

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE id=?', $uId);
	}

	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

	public function getListAccount()
	{
		$tAccount = $this->findAll();
		
		$tLoginPassAccount = array();
		
		foreach ($tAccount as $oAccount) {
			$tLoginPassAccount[$oAccount->login][$oAccount->pass] = $oAccount;
		}
		
		return $tLoginPassAccount;
	}
}

class row_account extends abstract_row
{

	protected $sClassModel = 'model_account';
	
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
