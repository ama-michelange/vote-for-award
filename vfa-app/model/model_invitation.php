<?php

class model_invitation extends abstract_model
{

	protected $sClassRow = 'row_invitation';

	protected $sTable = 'vfa_invitations';

	protected $sConfig = 'mysql';

	protected $tId = array(
		'invitation_id'
	);

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE invitation_id=?', $uId);
	}

	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable . ' ORDER BY email, type, created_date DESC');
	}

	public function findByRegistry($poRegistry, $pState = 'OPEN')
	{
		$sql = 'SELECT * FROM ' . $this->sTable . ' WHERE type=?' . '	AND state=?' . '	AND email=?' . '	AND group_id=?' .
			 '	AND awards_ids=?';
		return $this->findOne($sql, $poRegistry->type, $pState, $poRegistry->email, $poRegistry->group_id, 
			implode(',', $poRegistry->awards_ids));
	}
}

class row_invitation extends abstract_row
{

	protected $sClassModel = 'model_invitation';

	protected $tMessages = null;

	public function findGroup()
	{
		$Group = null;
		if (null != $this->group_id) {
			$Group = model_group::getInstance()->findById($this->group_id);
		}
		return $Group;
	}

	public function findAwards()
	{
		$tAwards = array();
		if (null != $this->awards_ids) {
			$tIds = explode(',', $this->awards_ids);
			foreach ($tIds as $id) {
				$tAwards[] = model_award::getInstance()->findById($id);
			}
		}
		return $tAwards;
	}

	public function showType()
	{
		switch ($this->type) {
			case 'READER':
				return 'Lecteur';
			case 'BOARD':
				return 'Membre';
			case 'RESPONSIBLE':
				return 'Responsable';
		}
		return $this->type;
	}
	public function showState()
	{
		switch ($this->state) {
			case 'OPEN':
				return 'En attente';
			case 'CLOSE':
				return 'Inscrit';
		}
		return $this->state;
	}
	
	/* exemple test validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());
		
		$oPluginValid->isNotEmpty('key');
		$oPluginValid->isNotEmpty('type');
		$oPluginValid->isNotEmpty('state');
		
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
