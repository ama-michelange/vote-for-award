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
class sgbd_xml extends abstract_sgbd{

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

		$oXml=simplexml_load_file($this->tConfig[$this->sConfig.'.database'].$sTable.'/'.$iId.'.xml');
		$tXml=(array) $oXml;

		//remove index
		$this->removeRowFromAllIndex($sTable,$tXml);


		foreach($tProperty as $sVar => $sVal){
			$tXml[$sVar]=(string)$sVal;
		}

		//add in index
		$this->addRowInAllIndex($sTable,$tXml);

		$this->save($tXml,$this->tConfig[$this->sConfig.'.database'].$sTable.'/'.$iId.'.xml');
	}
	public function insert($sTable,$tProperty){
		$iId=$this->getMaxId($sTable);

		$tMax=array('max'=>($iId+1));

		$tProperty['id']=$iId;

		$this->save($tProperty,$this->tConfig[$this->sConfig.'.database'].$sTable.'/'.$iId.'.xml');
		$this->save($tMax,$this->tConfig[$this->sConfig.'.database'].$sTable.'/max.xml');

		$this->addRowInAllIndex($sTable,$tProperty);

		return $iId;
	}
	public function delete($sTable,$tWhere){
		$iId=$this->getIdFromTab($tWhere);

		$oXml=simplexml_load_file($this->tConfig[$this->sConfig.'.database'].$sTable.'/'.$iId.'.xml');
		$tXml=(array) $oXml;

		//remove index
		$this->removeRowFromAllIndex($sTable,$tXml);

		unlink($this->tConfig[$this->sConfig.'.database'].$sTable.'/'.$iId.'.xml');
	}

	public function getListColumn($sTable){

		$oXml=simplexml_load_file($this->tConfig[$this->sConfig.'.database'].$sTable.'/structure.xml');

		$tXml=(array)$oXml;
		return $tXml['colonne'];

	}
	public function getListTable(){
		$oDir=new _dir( $this->tConfig[$this->sConfig.'.database']);
		$tDir=$oDir->getList();
		$tSDir=array();
		foreach($tDir as $oDir){
			$tSDir[]= $oDir->getName();
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
			if(isset($tReq['select'])){
				if( preg_match('/COUNT\(/i',$tReq['select'])){
					$bCount=true;
				}
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
				
			//UTILISATION D UN INDEX
			$tSqlFieldEqual=array_keys($tCritere);
			$tSqlFieldDifferent=array_keys($tCritereDifferent);

			$sIndexToUse='';
			$find=1;

			$oDirIndex=new _dir($this->tConfig[$this->sConfig.'.database'].$sTable.'/index');
			if($oDirIndex->exist()){
				$tFileIndex=$oDirIndex->getListDir();
				foreach($tFileIndex as $oFileIndex){
					$tFieldIndex=$this->getFieldsFromIndex($oFileIndex->getName());

					foreach($tSqlFieldEqual as $sSqlFieldEqual){
						if(!in_array($sSqlFieldEqual,$tFieldIndex) ){
							$find=0;break 2;
						}else{
							$find=2;
						}
					}
					if($find >0)
						foreach($tSqlFieldDifferent as $sSqlFieldEqual){
						if(!in_array($sSqlFieldEqual,$tFieldIndex) ){
							$find=0;break 2;
						}else{
							$find=2;
						}
					}
					if($find==2){
						$sIndexToUse=$oFileIndex->getName();
						break;
					}
				}
			}
			$tObj=array();
			//UTILISATION D UN INDEX
			if($sIndexToUse!=''){
				$sDirIndex=$this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndexToUse;

				$tFieldIndex=preg_split('/\./',$sIndexToUse);

				$oDirIndex=new _dir($sDirIndex);
				$tFileIndex=$oDirIndex->getListFile();
					
				//$tLine=file($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndexToUse);

				foreach($tFileIndex as $oFileIndex){
					$sFileIndex=trim($oFileIndex->getName());
						
					$tValue=$this->getValueFromIndex($sFileIndex);
					$tRow=array();
					foreach($tFieldIndex as $i => $var){
						$tRow[$var]=$tValue[$i];
					}


					$bFiltre=true;
					if($tCritere){
						foreach($tCritere as $sCritereField => $sCritereVal){
								
							if(!isset($tRow[$sCritereField]) or (string)$tRow[$sCritereField]!=(string)$sCritereVal){
								$bFiltre=false;

							}
						}
					}
					if($tCritereDifferent){
						foreach($tCritereDifferent as $sCritereField => $sCritereVal){
								
							if(!isset($tRow[$sCritereField]) or (string)$tRow[$sCritereField]==(string)$sCritereVal){
								$bFiltre=false;

							}
						}
					}

					if($bFiltre){
						//count
						if($bCount){
							$iCount++;
						}else{
							$tMatchedFile=file( $sDirIndex.'/'.$sFileIndex );
							foreach($tMatchedFile as $sMatchedFile){
								$sMatchedFile=trim($sMatchedFile);
								$tRow=(array)simplexml_load_file($this->tConfig[$this->sConfig.'.database'].$sTable.'/'.$sMatchedFile,null,LIBXML_NOCDATA);

								$oRow=new $sClassRow($tRow);
								$tObj[]=$oRow;
							}
							//$tFile[]=$oFile;
						}
					}
						
				}
			}elseif($tSqlFieldDifferent==array() and $tSqlFieldEqual==array('id')){
				$tRow=(array)simplexml_load_file($this->tConfig[$this->sConfig.'.database'].$sTable.'/'.(string)$tCritere['id'].'.xml',null,LIBXML_NOCDATA);

				if($bCount){
					$iCount++;
				}else{
					$oRow=new $sClassRow($tRow);
					$tObj[]=$oRow;
				}
			}else{
				$oDir=new _dir($this->tConfig[$this->sConfig.'.database'].$sTable);
				$tFile1=$oDir->getListFile();
					
				$tObj=array();
				//$tFile=array();
				if($tFile1){
					foreach($tFile1 as $oFile){
						if($oFile->getName() == 'structure.xml') continue;
						if($oFile->getName() == 'max.xml') continue;

						$tRow=(array)simplexml_load_file($oFile->getAdresse(),null,LIBXML_NOCDATA);
							
						$bFiltre=true;
						if($tCritere){
							foreach($tCritere as $sCritereField => $sCritereVal){

								if(!isset($tRow[$sCritereField]) or (string)$tRow[$sCritereField]!=(string)$sCritereVal){
									$bFiltre=false;
										
								}
							}
						}
						if($tCritereDifferent){
							foreach($tCritereDifferent as $sCritereField => $sCritereVal){

								if(!isset($tRow[$sCritereField]) or (string)$tRow[$sCritereField]==(string)$sCritereVal){
									$bFiltre=false;
										
								}
							}
						}

						if($bFiltre){
							//count
							if($bCount){
								$iCount++;
							}else{
								$oRow=new $sClassRow($tRow);
								$tObj[]=$oRow;
								//$tFile[]=$oFile;
							}
						}
							
					}
				}
			}
			//count
			if($bCount){
				return array($iCount);
			}
				
			if(isset($tReq['order']) and $tObj!=null){

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

			}
			return $tObj;
		}



	}
	public function quote($sVal){
		return $sVal;
	}
	public function getWhereAll(){
		return '1=1';
	}

	private function getFieldsFromIndex($sIndex){
		$tFields=preg_split('/\./',substr($sIndex,0,-6));//field.field.index
		return $tFields;
	}
	private function getValueFromIndex($sFileIndex){
		return preg_split('/####/',substr($sFileIndex,0,-4) );//value####value.csv
	}
	private function getFileIndexFromTab($sIndex,$tRow){
		$tFields=$this->getFieldsFromIndex($sIndex);
		$sFileIndex='';
		foreach($tFields as $sField){
			$sFileIndex.=$tRow[$sField];
			$sFileIndex.='####';
		}
		return $sFileIndex.'.csv';
	}
	public function generateIndexForTable($sTable,$sIndex){
		$tFields=$this->getFieldsFromIndex($sIndex);

		$oDir=new _dir($this->tConfig[$this->sConfig.'.database'].$sTable);
		$tFile=$oDir->getListFile();

		$tIndexContent=array();

		foreach($tFile as $oFile){
			if($oFile->getName() == 'structure.xml') continue;
			if($oFile->getName() == 'max.xml') continue;

			$tRow=(array)simplexml_load_file($oFile->getAdresse(),null,LIBXML_NOCDATA);

				
				
			$sKey='';
			foreach($tFields as $sField){
				$sKey.=$tRow[$sField];
				$sKey.='####';
			}
			$tIndexContent[$sKey][]=$tRow['id'].'.xml';
				
		}

		$oDir=new _dir($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndex);
		foreach($oDir->getListFile() as $oFile){
			$oFile->delete();
		}

		foreach($tIndexContent as $sKey => $tFile){
			$oFile=new _file($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sKey.'.csv');
			$oFile->setContent(implode($tFile,"\n"));
			$oFile->save();
		}
	}
	private function addRowInAllIndex($sTable,$tProperty){

		$oDir=new _dir($this->tConfig[$this->sConfig.'.database'].$sTable.'/index');
		if($oDir->exist()){
			$tDirIndex=$oDir->getListDir();
			foreach($tDirIndex as $oDirIndex){
				$this->addRowInIndex($sTable,$tProperty,$oDirIndex->getName());
			}
		}
	}
	private function addRowInIndex($sTable,$tProperty,$sIndex){

		$sFileIndex=$this->getFileIndexFromTab($sIndex,$tProperty);

		$oFile=new _file($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex);
		$oFile->addContent($tProperty['id'].'.xml');
		$oFile->save('a');
	}
	private function removeRowFromAllIndex($sTable,$tProperty){

		$oDir=new _dir($this->tConfig[$this->sConfig.'.database'].$sTable.'/index');
		if($oDir->exist()){
			$tDirIndex=$oDir->getListDir();
			foreach($tDirIndex as $oDirIndex){
				$this->removeRowFromIndex($sTable,$tProperty,$oDirIndex->getName());
			}
		}
	}
	private function removeRowFromIndex($sTable,$tProperty,$sIndex){

		$sFileIndex=$this->getFileIndexFromTab($sIndex,$tProperty);

		if(!file_exists($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex)) return;

		$tLine=file($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex);
		$tContent=array();
		foreach($tLine as $sLine){
			$sLine=trim($sLine);
			if($sLine==$tProperty['id'].'.xml') continue;
			$tContent[]=$sLine;
		}

		$oFile=new _file($this->tConfig[$this->sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex);
		$oFile->setContent(implode("\n",$tContent));
		$oFile->save();
	}

	private function getIdFromTab($tId){
		if(is_array($tId)){
			return current($tId);
		}else{
			return $tId;
		}
	}
	private function save($tProperty,$sFichier){
		$oFile=new _file($sFichier);
		$sRet="\n";
		$sXml='<?xml version="1.0" encoding="ISO-8859-1"?>'.$sRet;
		$sXml.='<main>'.$sRet;
		foreach($tProperty as $sVar => $sVal){
			$sXml.='<'.$sVar.'><![CDATA['.$sVal.']]></'.$sVar.'>'.$sRet;
		}
		$sXml.='</main>'.$sRet;
		$oFile->write($sXml);
	}
	private function getMaxId($sTable){
		$oXml=simplexml_load_file($this->tConfig[$this->sConfig.'.database'].$sTable.'/max.xml');
		return (int)$oXml->max;

	}



}
