<?php
class model_role extends abstract_model{

	protected $sClassRow='row_role';

	protected $sTable='vfa_roles';
	protected $sConfig='mysql';

	protected $tId=array('role_id');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE role_id=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY role_name');
	}
	public function findAllByUserId($pUserId) {
		$sql = 'SELECT * FROM vfa_roles, vfa_user_roles '
				.'WHERE (vfa_user_roles.role_id = vfa_roles.role_id) '
						.'AND (vfa_user_roles.user_id = ?) '
								.'ORDER BY vfa_roles.role_name';
		return $this->findMany($sql, $pUserId );
	}


	public function getSelect(){
		$tab=$this->findAll();
		$tSelect=array();
		foreach($tab as $oRow){
			$tSelect[ $oRow->role_id ]=$oRow->role_name;
		}
		return $tSelect;
	}

	/**
	 * Supprime
	 * @param int $pIdRole
	 */
	public function deleteRoleCascades($pIdRole){
		$this->execute('DELETE FROM vfa_roles WHERE role_id=?', $pIdRole);
		$this->execute('DELETE FROM vfa_authorizations WHERE role_id=?', $pIdRole);
		$this->execute('DELETE FROM vfa_user_roles WHERE role_id=?', $pIdRole);
	}
}

class row_role extends abstract_row{

	protected $sClassModel='model_role';


	public function findAuthorizations(){
		return model_authorization::getInstance()->findAllByRoleId($this->role_id);
	}


	private function getCheck(){
		$oPluginValid=new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('role_name');
		return $oPluginValid;
	}

	public function isValid(){
		return $this->getCheck()->isValid();
	}
	public function getListError(){
		return $this->getCheck()->getListError();
	}

}
