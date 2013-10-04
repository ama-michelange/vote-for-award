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
class sgbd_csv extends abstract_sgbd{


	public static function getInstance($sConfig){
		return self::_getInstance(__CLASS__,$sConfig);
	}

	public function findMany($tSql,$sClassRow){
		$tRows=$this->query($this->bind($tSql),$sClassRow);

		if(!$tRows){
			return null;
		}

		return $tRows;
	}
	public function findOne($tSql,$sClassRow){
		$tRs=$this->query($this->bind($tSql),$sClassRow);

		if(empty($tRs)){
			return null;
		}

		return $tRs[0];
	}
	public function execute($tSql){
		return $this->query($this->bind($tSql));
	}

	public function update($sTable,$tProperty,$tWhere){
		$iId=$this->getIdFromTab($tWhere);

		$tProperty['id']=$iId;

		$tFile=$this->getTabFromTable($sTable);

		$tHeader=$this->getListColumn($sTable);

		foreach($tFile as $i => $sLigne){
			if($i < 2) continue;
			$tLigne=preg_split('/;/',$sLigne);
			if($tLigne[0] == $iId){
				foreach($tHeader as $sHeader){
					$tUpdate[]=$this->encode($tProperty[ $sHeader]);
				}
					
				$tFile[$i]=implode(';',$tUpdate).';';
			}
		}


		$this->save($tFile,$sTable);
	}
	public function delete($sTable,$tWhere){
		$iId=$this->getIdFromTab($tWhere);

		$tFile=$this->getTabFromTable($sTable);

		foreach($tFile as $i => $sLigne){
			if($i < 2) continue;
			$tLigne=preg_split('/;/',$sLigne);
			if($tLigne[0] == $iId){
					
				unset( $tFile[$i] );
			}
		}

		$this->save($tFile,$sTable);

	}
	public function insert($sTable,$tProperty){
		$iId=$this->getMaxId($sTable);

		$iMax=($iId+1);

		$tProperty['id']=$iId;

		$tHeader=$this->getListColumn($sTable);
		foreach($tHeader as $sHeader){
			$tInsert[]=$this->encode($tProperty[ $sHeader]);
		}

		$tFile=$this->getTabFromTable($sTable);
		$tFile[]=implode(';',$tInsert).';';
		$tFile[0]=$iMax;

		$this->save($tFile,$sTable);

		return $iId;
	}

	public function getListColumn($sTable){

		$tFile=$this->getTabFromTable($sTable);

		$tHeader=preg_split('/;/',$tFile[1]);

		if( trim($tHeader[ count($tHeader)-1 ])==''){
			unset($tHeader[ count($tHeader)-1 ]);
		}

		return $tHeader;

	}
	public function getListTable(){
		$oDir=new _dir( $this->tConfig[$this->sConfig.'.database']);
		$tDir=$oDir->getList();
		$tSDir=array();
		foreach($tDir as $oDir){
			$tSDir[]= preg_replace('/.csv/','',$oDir->getName());
		}
		return $tSDir;
	}

	private function connect(){
	}

	private function query($sReq,$sClassRow){
		//traitement de la requete $sReq
		$sReq=trim($sReq);

		$msg="\n\n";
		$msg.="Le driver xml gere les requetes de type : \n";
		$msg.="- SELECT liste_des_champs FROM ma_table WHERE champ=valeur ORDER BY champ DESC/ASC \n";
		$msg.="- SELECT liste_des_champs FROM ma_table ORDER BY champ DESC/ASC \n";
		$msg.="- SELECT liste_des_champs FROM ma_table WHERE champ=valeur \n";
		$msg.="- SELECT liste_des_champs FROM ma_table  \n";
		$msg.=" la clause where accepte uniquement champ=valeur, champ!=valeur et AND \n";


		if(substr($sReq,0,6)== 'SELECT'){
			//SELECT (1)* FROM (2)article WHERE (3)id=2 and type=3
			if(preg_match_all('/^SELECT(?P<select>.*)FROM(?P<from>.*)WHERE(?P<where>.*)ORDER BY(?P<order>.*)/i',$sReq,$tResult,PREG_SET_ORDER)){
			}elseif(preg_match_all('/^SELECT(?P<select>.*)FROM(?P<from>.*)ORDER BY(?P<order>.*)/i',$sReq,$tResult,PREG_SET_ORDER)){
			}elseif(preg_match_all('/^SELECT(?P<select>.*)FROM(?P<from>.*)WHERE(?P<where>.*)/i',$sReq,$tResult,PREG_SET_ORDER)){
			}elseif(preg_match_all('/^SELECT(?P<select>.*)FROM(?P<from>.*)/i',$sReq,$tResult,PREG_SET_ORDER)){
			}else{
				$this->erreur('Requete non supportee : '.$sReq.$msg);
			}
				
			$tReq=$tResult[0];

			//count
			$bCount=false;
			$iCount=0;
			if(isset($tReq['select']) and preg_match('/COUNT\(/i',$tReq['select'])){
				$bCount=true;
			}
				
			$tCritere=array();
			$tCritereDifferent=array();
			if(isset($tReq['where'])){
				if(preg_match('/ or /i',$tReq['where'])){
					$this->erreur('Requete non supportee : '.$sReq.$msg);
				}elseif(preg_match('/ and /i',$tReq['where'])){
					$tWhere=preg_split('/ AND /i',$tReq['where']);
					foreach($tWhere as $sWhereVal){
						if(preg_match('/!=/',$sWhereVal)){
							list($sVar,$sVal)=preg_split('/!=/',$sWhereVal);
							$tCritereDifferent[trim($sVar)]=trim($sVal);
						}elseif(preg_match('/=/',$sWhereVal)){
							list($sVar,$sVal)=preg_split('/=/',$sWhereVal);
							$tCritere[trim($sVar)]=trim($sVal);
						}
					}
				}else{
					if(preg_match('/!=/',$tReq['where'])){
						list($sVar,$sVal)=preg_split('/!=/',$tReq['where']);
						$tCritereDifferent[trim($sVar)]=trim($sVal);
					}elseif(preg_match('/=/',$tReq['where'])){
						list($sVar,$sVal)=preg_split('/=/',$tReq['where']);
						$tCritere[trim($sVar)]=trim($sVal);
					}
				}
			}
				
			$sTable=trim($tReq['from']);
				
				
			$tFile1=$this->getTabFromFile($this->getTabFromTable($sTable));

				
				
			$tObj=array();
			//$tFile=array();
			if($tFile1){
				foreach($tFile1 as $tRow){
						
					$bFiltre=true;
					if($tCritere){
						foreach($tCritere as $sCritereField => $sCritereVal){

							if(!isset($tRow[$sCritereField]) or (string)$tRow[$sCritereField]!=(string)$sCritereVal){
								$bFiltre=false;break;
							}
						}
					}
					if($tCritereDifferent){
						foreach($tCritereDifferent as $sCritereField => $sCritereVal){
								
							if(!isset($tRow[$sCritereField]) or (string)$tRow[$sCritereField]==(string)$sCritereVal){
								$bFiltre=false;break;

							}
						}
					}

					if($bFiltre and $bCount){//count
						$iCount++;
					}else if($bFiltre){
						$oRow=new $sClassRow($tRow);
						$tObj[]=$oRow;
					}

				}
			}
			//count
			if($bCount){
				return array($iCount);
			}else if(isset($tReq['order']) and $tObj!=null){

				if(!preg_match('/ /',trim($tReq['order']) )){
					throw new Exception('Il faut definir un sens de tri: ASC ou DESC dans '.$sReq);
				}
				list($sChamp,$sSens)=preg_split('/ /',trim($tReq['order']));

				$tTri=array();
				$tIdObj=array();
				foreach($tObj as $i => $oObj){
					$tIdObj[ $i ]=$oObj;
					$tTri[ $i ]=(string)$oObj->$sChamp;
				}

				if($sSens=='DESC'){
					arsort($tTri);
				}else{
					asort($tTri);
				}

				$tOrderedObj=array();
				$tId= array_keys($tTri);
				foreach($tId as $id){
					$tOrderedObj[]=$tIdObj[$id];
				}

				return $tOrderedObj;

			}else{
				return $tObj;
			}
		}



	}
	public function quote($sVal){
		return $sVal;
	}
	public function getWhereAll(){
		return '1=1';
	}


	private function getIdFromTab($tId){
		if(is_array($tId)){
			return current($tId);
		}else{
			return $tId;
		}
	}
	private function save($tFile,$sTable){
		$oFile=new _file($this->tConfig[$this->sConfig.'.database'].$sTable.'.csv');
		$sRet="\n";

		$sFile='';
		if($tFile)
			foreach($tFile as $sLigne){
			$sFile.=trim($sLigne).$sRet;
		}

		$oFile->write($sFile);
	}
	private function getMaxId($sTable){
		$tFile=$this->getTabFromTable($sTable);

		$iMax=trim($tFile[0]);

		return (int)$iMax;
	}


	public function getTabFromFile($tContent){

		$tHeader=preg_split('/;/',$tContent[1]);

		$tab=array();
		foreach($tContent as $i => $sLigne){
			if($i < 2) continue;
			$sLigne=html_entity_decode($sLigne,ENT_QUOTES);
			$tLigne=preg_split('/;/',$sLigne);
				
			$tab2=array();
			foreach($tHeader as $i => $sHeader){
				$tab2[ $sHeader ]=$this->decode($tLigne[$i]);
			}
			$tab[]=$tab2;
				
		}
		return $tab;

	}
	public function getTabFromTable($sTable){
		$oFile=new _file($this->tConfig[$this->sConfig.'.database'].$sTable.'.csv');
		$tFile=$oFile->getTab();
		return $tFile;
	}

	public function encode($text){
		return preg_replace('/\r/','',preg_replace('/\n/','##retour_chariot_fmk##',$text));
	}
	public function decode($text){
		return preg_replace('/##retour_chariot_fmk##/',"\n",$text);
	}


}
