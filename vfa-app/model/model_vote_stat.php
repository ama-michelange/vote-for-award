<?php

/**
 * Class model_vote_stat
 */
class model_vote_stat extends abstract_model
{

	protected $sClassRow = 'row_vote_stat';

	protected $sTable = 'vfa_vote_stats';

	protected $sConfig = 'mysql';

	protected $tId = array('vote_stat_id');


	/**
	 * @return model_vote_stat
	 */
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	/**
	 * @param $uId string
	 * @return row_vote_stat
	 */
	public function findById($uId)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE vote_stat_id=?', $uId);
	}

	/**
	 * @return row_vote_stat[]
	 */
	public function findAll()
	{
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

	/**
	 * @param $pIdAward string
	 * @return row_vote_stat[]
	 */
	public function findAllByIdAward($pIdAward)
	{
		$results = $this->findMany('SELECT * FROM ' . $this->sTable . ' WHERE award_id=? ORDER BY code', $pIdAward);
		$ret = array();
		foreach ($results as $stat) {
			$ret[$stat->code] = $stat;
		}
		return $ret;
	}

	/**
	 * @param $pIdAward string
	 * @param $pCode string
	 * @return row_vote_stat
	 */
	public function findByIdAwardCode($pIdAward, $pCode)
	{
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE award_id=? AND code=?', $pIdAward, $pCode);
	}

	/**
	 * @param $poNewStat row_vote_stat
	 */
	public function saveStat($poNewStat)
	{
		$oOldStat = $this->findByIdAwardCode($poNewStat->award_id, $poNewStat->code);
		if ($oOldStat->isEmpty()) {
			$poNewStat->save();
		} else {
			$oOldStat->num_int = $poNewStat->num_int ;
			$oOldStat->update();
		}
	}

	/**
	 * @param $ptStats row_vote_stat[]
	 * @param $pCode string
	 * @return row_vote_stat
	 */
	public function extract($ptStats, $pCode)
	{
		$ret = "";
		if (isset($ptStats[$pCode])) {
			$oStat = $ptStats[$pCode];
			$ret = $oStat->__get('num_int');
		}
		return $ret;
	}
}

class row_vote_stat extends abstract_row
{
	// Mémo
	// vote_stat_id, INT(11)
	// award_id, INT(11)
	// code, VARCHAR(20)
	// text, TEXT
	// num_int, INT(11)
	// num_dec, DECIMAL(10,5)

	protected $sClassModel = 'model_vote_stat';

	protected $tMessages = null;

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
