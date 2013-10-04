<?php
class model_title extends abstract_model{

	protected $sClassRow='row_title';

	protected $sTable='vfa_titles';
	protected $sConfig='mysql';

	protected $tId=array('title_id');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE title_id=?',$uId );
	}
	
	public function findByTitleAndNumbers($uTitle, $uNumbers){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE title=? AND numbers=?',$uTitle, $uNumbers );
	}
	
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY order_title');
	}

	public function findAllByAwardId($pAwardId) {
		$sql = 'SELECT * FROM vfa_titles, vfa_award_titles '
				.'WHERE (vfa_award_titles.title_id = vfa_titles.title_id) '
						.'AND (vfa_award_titles.award_id = ?) '
								.'ORDER BY vfa_titles.order_title';
		return $this->findMany($sql, $pAwardId );
	}

	public function findAllByDocId($pDocId) {
		$sql = 'SELECT * FROM vfa_titles, vfa_title_docs '
				.'WHERE (vfa_title_docs.title_id = vfa_titles.title_id) '
						.'AND (vfa_title_docs.doc_id= ?) '
								.'ORDER BY vfa_titles.order_title';
		return $this->findMany($sql, $pDocId );
	}

	public function getSelect(){
		$tab=$this->findAll();
		$tSelect=array();
		foreach($tab as $oRow){
			if (null == $oRow->numbers) {
				$tSelect[ $oRow->title_id ]=$oRow->title;
			}
			else {
				$tSelect[ $oRow->title_id ]=$oRow->title.' ('.$oRow->numbers.')';
			}
		}
		return $tSelect;
	}

	public function getSelectByAwardId($pAwardId) {
		$tab=$this->findAllByAwardId($pAwardId);
		$tSelect=array();
		foreach($tab as $oRow){
			if (null == $oRow->numbers) {
				$tSelect[ $oRow->title_id ]=$oRow->title;
			}
			else {
				$tSelect[ $oRow->title_id ]=$oRow->title.' ('.$oRow->numbers.')';
			}
		}
		return $tSelect;
	}

	public function deleteTitleDocs($pIdTitle){
		$this->execute('DELETE FROM vfa_title_docs WHERE title_id=?', $pIdTitle);
	}

	public function saveTitleDocs($pIdTitle, $pIdDocs){
		$this->deleteTitleDocs($pIdTitle);
		foreach($pIdDocs as $idDoc){
			$this->execute('INSERT INTO vfa_title_docs (title_id, doc_id) VALUES (?,?)', $pIdTitle, $idDoc);
		}
	}
	public function deleteAwardTitle($pIdAward, $pIdTitle){
		$this->execute('DELETE FROM vfa_award_titles WHERE award_id=? AND title_id=?',$pIdAward, $pIdTitle );
	}

	public function saveAwardTitle($pIdAward, $pIdTitle){
		$find = $this->findOne('SELECT * FROM vfa_award_titles WHERE award_id=? AND title_id=?',$pIdAward, $pIdTitle );
		if (true == $find->isEmpty()) {
			$this->execute('INSERT INTO vfa_award_titles (award_id, title_id) VALUES (?, ?)', $pIdAward, $pIdTitle);
		}
	}
}

class row_title extends abstract_row{

	protected $sClassModel='model_title';
	protected $tMessages = null;

	public function findDocs(){
		$tArray = null;
		if (null != $this->title_id) {
			$tArray = model_doc::getInstance()->findAllByTitleId($this->title_id);
		}
		return $tArray;
	}
	public function findAwards(){
		$tArray = null;
		if (null != $this->title_id) {
			$tArray = model_award::getInstance()->findAllByTitleId($this->title_id);
		}
		return $tArray;
	}

	public function getSelectDocs() {
		$tArray = null;
		if (null != $this->title_id) {
			$tArray = model_doc::getInstance()->getSelectByTitleId($this->title_id);
		}
		return $tArray;
	}
	
	public function toString() {
		$name = $this->title;
		if (null != $this->numbers) {
			$name .= ' ('.$this->numbers.')'; 
		}
		return $name;
	}
	
	/*exemple test validation*/
	private function getCheck(){
		$oPluginValid=new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('title');
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

}
