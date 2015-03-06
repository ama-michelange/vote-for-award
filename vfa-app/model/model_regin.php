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
	 * @param string $pState
	 * @return row_regin[]
	 */
	public function findAllByTypeByGroupIdByState($pType, $pGroupId, $pState = plugin_vfa::STATE_OPEN)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE type=? and group_id=? and state=? ORDER BY created_date DESC';
		return $this->findMany($sql, $pType, $pGroupId, $pState);
	}
	/**
	 * @param string $pType
	 * @param string | int $pGroupId
	 * @param string $pState
	 * @return row_regin[]
	 */
	public function findAllInTimeByTypeByGroup($pType, $pGroupId, $pState = plugin_vfa::STATE_OPEN)
	{
		$sql = 'SELECT * FROM' . ' ' . $this->sTable . ' WHERE (process_end >= ?) AND type=? AND group_id=? AND state=? ORDER BY created_date DESC';
		return $this->findMany($sql, plugin_vfa::dateSgbd(), $pType, $pGroupId, $pState);
	}

	/**
	 * @param $pCode
	 * @return row_regin
	 */
	public function findByCode($pCode)
	{
		return $this->findOne('SELECT * FROM' . ' ' . $this->sTable . ' WHERE code=?', $pCode);
	}

	/**
	 * @param $pCode
	 * @return row_regin[]
	 */
	public function findAllByCode($pCode)
	{
		return $this->findMany('SELECT * FROM' . ' ' . $this->sTable . ' WHERE code=?', $pCode);
	}

	/**
	 * @param int $pIdRegin
	 * @param int $pIdUser
	 */
	public function saveReginUser($pIdRegin, $pIdUser)
	{
		$oReginUsers = model_regin_users::getInstance()->findByReginIdUserId($pIdRegin, $pIdUser);
		if ($oReginUsers->isEmpty()) {
			$oReginUsers->regin_id = $pIdRegin;
			$oReginUsers->user_id = $pIdUser;
			$oReginUsers->created_date = plugin_vfa::dateTimeSgbd();
			$oReginUsers->save();
		}
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

	/**
	 * @return bool Vrai si le processus de cet enregistrement est toujours valide
	 */
	public function verifyProcessValidity()
	{
		$valid = false;
		switch ($this->type) {
			case plugin_vfa::TYPE_READER:
				if ($this->state == plugin_vfa::STATE_OPEN) {
					if ((plugin_vfa::PROCESS_INTIME == $this->process) || (plugin_vfa::PROCESS_INTIME_VALIDATE == $this->process)) {
						$processEnd = plugin_vfa::toDateFromSgbd($this->process_end);
						$processEnd->addDay(1);
						if (plugin_vfa::beforeDate(plugin_vfa::today(), $processEnd)) {
							$valid = true;
						}
					}
				}
				break;
		}
		return $valid;
	}

	/**
	 * @return string
	 */
	public function toStringAllAwards()
	{
		$ret = '';
		$tAwards = $this->findAwards();
		if (null != $tAwards) {
			$i = 0;
			foreach ($tAwards as $award) {
				if ($i > 0) {
					$ret .= ', ';
				}
				$ret .= $award->toString();
				$i++;
			}
		}
		return $ret;
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

