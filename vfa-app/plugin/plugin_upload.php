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
* plugin_upload classe gerant l'upload de fichier
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_upload{

	private $tFile;
	private $sOriginFileName;
	private $sTmpFileName;
	private $sNewPath;
	private $sExtension;

	/** 
	* constructeur
	* @access public
	* @param array $tFile tableau $_FILES[ nomDuChamp ]
	*/
	public function __construct($tFile){
		$this->tFile=$tFile;
		$this->originFileName=basename($tFile['name']);
		$this->sTmpFileName=$tFile['tmp_name'];
		$this->loadExtension();
	}

	/** 
	* indique l'adresse ou sauvegarder le fichier
	* @access public
	* @param string $sNewFileName adresse complete de destination (data/upload/fichier.jpg)
	* @return bool true/false selon que l'upload a bien fonctionne
	*/
	public function saveAs($sNewFileName){
		$this->sNewPath=$sNewFileName.'.'.$this->sExtension;

		if(move_uploaded_file($this->sTmpFileName, $this->sNewPath)){
			return true;
		}
		else{
			return false;
		}
	}
	/** 
	* retourn l'adresse complete du fichier uploade
	* @access public
	* @return string l'adresse complete du fichier uploade
	*/
	public function getPath(){
		return $this->sNewPath;
	}

	private function loadExtension(){
		$tFileName=preg_split('/\./',$this->originFileName);
		$this->sExtension= end($tFileName);
	}

}
