<?php
/*
 This file is part of Mkframework.

Mkframework is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License.

Mkframework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

*/
/**
 * classe abstract_sgbd
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
abstract class abstract_sgbd{

	protected $tConfig;
	protected $sConfig;
	protected $sClassRow;
	protected $pDb;
	protected $sReq;
	protected $tId;

	private static $tInstance=array();

	/**
	 * @access public
	 * @return une object
	*/
	protected static function _getInstance($class,$sConfig) {
		if ( !isset(self::$tInstance[$class][$sConfig]) ){
			$oSgbd = new $class();
			$oSgbd->chooseConfig($sConfig);
			self::$tInstance[$class][$sConfig]=$oSgbd;

		}
		return self::$tInstance[$class][$sConfig];
	}

	/**
	 * force la classe row
	 * @access public
	 * @param string $sClassRow
	 */
	public function setClassRow($sClassRow){
		$this->sClassRow=$sClassRow;
	}
	/**
	 * choisit le profile de connection
	 * @param string $sConfig
	 */
	public function chooseConfig($sConfig){
		$this->sConfig=$sConfig;
	}
	/**
	 * definir le tableau de connection
	 * @param array $tConfig
	 */
	public function setConfig($tConfig){
		$this->tConfig=$tConfig;
	}
	/**
	 * retourne la requete
	 */
	public function getRequete(){
		return $this->sReq;
	}
	/**
	 * @param array $tReq
	 *
	 */
	public function bind($tReq){
		$sReq='';
		$i=0;
		if(is_array($tReq)){
			$sReq=$tReq[0];
			foreach($tReq as $sVal){
				if($i == 0){
					$i+=1;continue;
				}
				$sVal=$this->quote($sVal);
				$sReq=preg_replace('/[?]/',$sVal,$sReq,1);
			}
		}else{
			return $tReq;
		}

		return $sReq;
	}

	public function getInsertFromTab($tProperty){

		$sCols='';
		$sVals='';

		if($tProperty){
			foreach($tProperty as $sVar => $sVal){
				//_root::getLog()->log('AMA >>> $sVar : '.$sVar.', $sVal = '.$sVal);
				if (null != $sVal) {
					$sCols.=$sVar.',';
					$sVals.=$this->quote($sVal).',';
				}
			}
		}
		return '('.substr($sCols,0,-1).') VALUES ('.substr($sVals,0,-1).') ';
	}
	public function getUpdateFromTab($tProperty){
		$sReq='';
		if($tProperty){
			foreach($tProperty as $sVar => $sVal){
				//_root::getLog()->log('AMA >>> $sVar : '.$sVar.', $sVal = '.$sVal);
				if (null == $sVal) {
					//_root::getLog()->log('AMA >>> $sVal = NULL');
					$sReq.=$sVar.'=NULL,';
				}
				else {
					$sReq.=$sVar.'='.$this->quote($sVal).',';
				}
			}
		}
		return substr($sReq,0,-1);
	}
	public function setId($tId){
		$this->tId=$tId;
	}
	public function getWhereFromTab($tId){
		$sWhere='';
		if(is_array($tId)){
			foreach($tId as $sVar => $sVal){
				if($sWhere!=''){
					$sWhere.=' AND ';
				}
				$sWhere.=$sVar.'='.$this->quote($sVal);
			}
		}

		return $sWhere;
	}
	public function erreur($sErreur){
		throw new Exception($sErreur);
	}

}
