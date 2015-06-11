<?php

/**
 * Class model_regin_users
 */
class model_regin_users extends abstract_model
{

	protected $sClassRow = 'row_regin_users';

	protected $sTable = 'vfa_regin_users';

	protected $sConfig = 'mysql';

	protected $tId = array('regin_users_id');

	/**
	 * @return model_regin_users
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId
	 * @return row_regin_users
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM' . ' ' . $this->sTable . ' WHERE regin_users_id=' . $uId);
		// La ligne ci-dessous est commentaire pour se souvenir qu'il peut y avoir une erreur avec le module SGBD
		// return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE regin_users_id=?', $uId);
	}

	/**
	 * @return row_regin_users[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM' . ' ' . $this->sTable . ' ORDER BY regin_id, user_id');
	}

	/**
	 * @param string $pReginId
	 * @param string $pUserId
	 * @return row_regin_users
	 */
	public function findByReginIdUserId($pReginId, $pUserId)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE regin_id=? AND user_id=?';
		return $this->findOne($sql, $pReginId, $pUserId);
	}

	/**
	 * @param string $pUserId
	 * @return row_regin_users[]
	 */
	public function findAllByUserId($pUserId)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE user_id=?';
		return $this->findMany($sql, $pUserId);
	}

	/**
	 * @param string $pReginId
	 * @return row_regin_users[]
	 */
	public function findAllByReginId($pReginId)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE regin_id=? ORDER BY created_date';
		return $this->findMany($sql, $pReginId);
	}
}

class row_regin_users extends abstract_row
{

	protected $sClassModel = 'model_regin_users';

	protected $tMessages = null;

	public function findRegin()
	{
		$oGroup = null;
		if (null != $this->regin_id) {
			$oGroup = model_regin::getInstance()->findById($this->regin_id);
		}
		return $oGroup;
	}

	public function findUser()
	{
		$user = null;
		if (null != $this->user_id) {
			$user = model_user::getInstance()->findById($this->user_id);
		}
		return $user;
	}

	private function getCheck()
	{
		$oPluginValid = new plugin_valid($this->getTab());

		$oPluginValid->isNotEmpty('regin_id');
		$oPluginValid->isNotEmpty('user_id');

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

