<?php

class model_regin extends abstract_model
{

	protected $sClassRow = 'row_regin';

	protected $sTable = 'vfa_regin';

	protected $sConfig = 'mysql';

	protected $tId = array('regin_id');

	/**
	 * @return model_regin
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_regin
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM' . ' ' . $this->sTable . ' WHERE regin_id=' . $uId);
		// La ligne ci-dessous est commentaire pour se souvenir qu'il peut y avoir une erreur avec le module SGBD
		// return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE regin_id=?', $uId);
	}

	/**
	 * @return row_regin[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM' . ' ' . $this->sTable . ' ORDER BY type, created_date DESC');
	}

	/**
	 * @param $pUserId
	 * @return row_regin[]
	 */
	public function findAllByCreatedUserId($pUserId)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE created_user_id=? ORDER BY type, created_date DESC';
		return $this->findMany($sql, $pUserId);
	}

	/**
	 * @param string $pType
	 * @return row_regin[]
	 */
	public function findAllByType($pType)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE type=? ORDER BY created_date DESC';
		return $this->findMany($sql, $pType);
	}

	/**
	 * @param string $pType
	 * @param string | int $pGroupId
	 * @return row_regin[]
	 */
	public function findAllByTypeByGroupId($pType, $pGroupId)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE type=? and group_id=? ORDER BY created_date DESC';
		return $this->findMany($sql, $pType, $pGroupId);
	}
}

class row_regin extends abstract_row
{

	protected $sClassModel = 'model_regin';

	protected $tMessages = null;

	public function findGroup()
	{
		$oGroup = null;
		if (null != $this->group_id) {
			$oGroup = model_group::getInstance()->findById($this->group_id);
		}
		return $oGroup;
	}

	public function findCreatedUser()
	{
		$user = null;
		if (null != $this->created_user_id) {
			$user = model_user::getInstance()->findById($this->created_user_id);
		}
		return $user;
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
			case plugin_vfa::TYPE_READER:
				return 'Lecteur';
			case plugin_vfa::TYPE_BOARD:
				return 'Membre';
			case plugin_vfa::TYPE_RESPONSIBLE:
				return 'Correspondant';
		}
		return $this->type;
	}

	public function showState()
	{
		switch ($this->state) {
			case plugin_vfa::STATE_OPEN:
				return 'Ouverte';
				break;
			case plugin_vfa::STATE_CLOSE:
				return 'Fermée';
				break;
		}
		return $this->state;
	}

	/* exemple test validation */
	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());

		$oPluginValid->isNotEmpty('type');
		$oPluginValid->isNotEmpty('code');
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

