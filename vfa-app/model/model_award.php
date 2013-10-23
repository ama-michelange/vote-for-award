<?php
class model_award extends abstract_model{

	protected $sClassRow='row_award';

	protected $sTable='vfa_awards';
	protected $sConfig='mysql';

	protected $tId=array('award_id');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE award_id=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY start_date DESC, name');
	}

	public function getSelect(){
		$tab=$this->findAll();
		$tSelect=array();
		foreach($tab as $oRow){
			$tSelect[ $oRow->award_id ]=$oRow->name;
		}
		return $tSelect;
	}
	
	public function findAllByType($pType) {
		$sql = 'SELECT * FROM vfa_awards '
				.'WHERE (vfa_awards.type = ?) '
				.'ORDER BY start_date DESC, name';
		return $this->findMany($sql, $pType );
	}
	
	/**
	 * Supprime
	 * @param string $pIdAward
	 */
	public function deleteAwardCascades($pIdAward){
		$this->execute('DELETE FROM vfa_awards WHERE award_id=?', $pIdAward);
		$this->execute('DELETE FROM vfa_award_titles WHERE award_id=?', $pIdAward);
		$sql = 'DELETE FROM vfa_titles '
				.'WHERE title_id NOT IN (SELECT title_id FROM vfa_award_titles)';
		$this->execute($sql);
		$sql = 'DELETE FROM vfa_title_docs '
				.'WHERE title_id NOT IN (SELECT title_id FROM vfa_titles)';
		$this->execute($sql);
	}
	
	public function findAllByTitleId($pTitleId) {
		$sql = 'SELECT * FROM vfa_awards, vfa_award_titles '
				.'WHERE (vfa_award_titles.award_id = vfa_awards.award_id) '
						.'AND (vfa_award_titles.title_id = ?) '
								.'ORDER BY vfa_awards.name';
		return $this->findMany($sql, $pTitleId );
	}
	
	public function findAllByDocId($pDocId) {
		$sql = 'SELECT * FROM vfa_awards, vfa_award_titles, vfa_title_docs '
				.'WHERE (vfa_award_titles.award_id = vfa_awards.award_id) '
				.'AND (vfa_award_titles.title_id = vfa_title_docs.title_id) '
				.'AND (vfa_title_docs.doc_id= ?) '
				.'ORDER BY vfa_awards.name';
		return $this->findMany($sql, $pDocId );
	}
	
	public function findAllByUserId($pUserId) {
		$sql = 'SELECT * FROM vfa_awards, vfa_user_awards '
				.'WHERE (vfa_user_awards.award_id = vfa_awards.award_id) '
						.'AND (vfa_user_awards.user_id = ?)';
		return $this->findMany($sql, $pUserId );
	}

}

class row_award extends abstract_row{

	protected $sClassModel='model_award';
	protected $tMessages = null;

	public function findTitles(){
		$tArray = null;
		if (null != $this->award_id) {
			$tArray = model_title::getInstance()->findAllByAwardId($this->award_id);
		}
		return $tArray;
	}

	public function getSelectTitles() {
		$tArray = null;
		if (null != $this->award_id) {
			$tArray = model_title::getInstance()->getSelectByAwardId($this->award_id);
		}
		return $tArray;
	}

	/*exemple test validation*/
	private function getCheck(){
		$oPluginValid=new plugin_valid($this->getTab());

		$oPluginValid->isNotEmpty('name');
		$oPluginValid->isNotEmpty('start_date');
		$oPluginValid->isNotEmpty('end_date');
		$oPluginValid->isDateBefore('start_date', 'end_date');
		$oPluginValid->isDateAfter('end_date', 'start_date');

		return $oPluginValid;
	}

	public function isValid(){
		return $this->getCheck()->isValid();
	}
	public function getListError(){
		return $this->getCheck()->getListError();
	}
	public function setMessages($ptMessages){
		$this->tMessages = $ptMessages;
	}
	public function getMessages() {
		if (null != $this->tMessages) {
			return $this->tMessages;
		}
		return $this->getListError();
	}

	public function getTypeString() {
		switch ($this->type) {
			case 'PSBD':
				$s = 'Présélection';
				break;
			default:
				$s = 'Prix';
				break;
		}
		return $s;
	}

	public function getTypeNameString() {
		$s = $this->getTypeString().' '.$this->name;
		return $s;
	}

	public function getShowString() {
		if ($this->public) {
			$s = 'Public';
		}
		else {
			$s = 'Privé';
		}
		return $s;
	}

	public function getTypeShowString() {
		$s = $this->getTypeString();
		switch ($this->type) {
			case 'PSBD':
				if ($this->public) {
					$s = $s.' publique';
				}
				else {
					$s = $s.' privée';
				}
				break;
			default:
				if ($this->public) {
					$s = $s.' public';
				}
				else {
					$s = $s.' privé';
				}
				break;
		}
		return $s;
	}

}