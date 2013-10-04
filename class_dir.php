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
 * _dir classe pour gerer un repertoire
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
class _dir{

	private $sAdresse=null;

	/**
	 * constructeur
	 * @access public
	 * @param string $sAdresse l'adresse du repertoire
	 */
	public function __construct($sAdresse=null){
		if($sAdresse!=null){
			$this->setAdresse($sAdresse);
		}
	}
	/**
	 * indique que c'est un repertoire (utilise apres un _file->getList
	 * @access public
	 * @return true
	 */
	public function isDir(){
		return true;
	}
	/**
	 * indique que ce n'est pas un fichier (utilise apres un _file->getList
	 * @access public
	 * @return false
	 */
	public function isFile(){
		return false;
	}
	/**
	 * defini l'adresse du repertoire
	 * @access public
	 * @param string $sAdresse l'adresse du repertoire
	 */
	public function setAdresse($sAdresse){
		if($sAdresse!=null){
			$this->sAdresse=$sAdresse;
			//$this->verif();
		}
	}

	public function getAdresse(){
		return  $this->sAdresse;
	}

	/**
	 * retourne le nom du repertoire
	 * @access public
	 * @return string
	 */
	public function getName(){
		$this->verif();
		return basename($this->sAdresse);
	}
	/**
	 * recupere la liste des fichiers / repertoire
	 * @access public
	 * @param array $tInclusion tableau des extensions a prendre
	 * @param array $tExclusion tableau des extensions a exclure
	 * @param string $sType dir|file pour filtrer si besoin que les fichiers/repertoires
	 * @param boolen $bWithHidden boolean avec fichier cache ou non
	 * @return array $tFile tableau de _file et _dir contenu dans le repertoire
	 */
	public function getList($tInclusion=null,$tExclusion=null,$sFilterType=null,$bWithHidden=false){
		$this->verif();

		$open=openDir($this->sAdresse);

		$tFile=array();

		while(false !== ($sFile=readDir($open)) ){

			if($bWithHidden==false and $sFile[0]=='.'){
				continue;
			}

			$bIsDir=is_dir($this->sAdresse.'/'.$sFile);
				
			if($bIsDir==true){
				$oElement=new _dir($this->sAdresse.'/'.$sFile);
			}else{
				$oElement=new _file($this->sAdresse.'/'.$sFile);
			}
				
			if($bIsDir==false and $sFilterType!='dir'){
				if($tInclusion==null and $tExclusion==null){
					$tFile[]=$oElement;
				}else if($tInclusion!=null and in_array($oElement->getExtension(),$tInclusion) ){
					$tFile[]=$oElement;
				}else if($tExclusion!=null and !in_array($oElement->getExtension(),$tExclusion)){
					$tFile[]=$oElement;
				}
			}else if($bIsDir==true and $sFilterType!='file'){
				$tFile[]=$oElement;
			}
		}

		return $tFile;
	}
	/**
	 * retourne un tableau des fichiers disponible
	 * @access public
	 * @param array $tInclusion
	 * @param array $tExclusion
	 * @return array d'objet _file
	 */
	public function getListFile($tInclusion=null,$tExclusion=null){
		return $this->getList($tInclusion,$tExclusion,'file');
	}
	/**
	 * retourne un tableau des repertoire disponible
	 * @access public
	 * @param array $tInclusion
	 * @param array $tExclusion
	 * @return array d'objet _file
	 */
	public function getListDir(){
		return $this->getList(null,null,'dir');
	}
	/**
	 * supprime le repertoire
	 * @access public
	 */
	public function delete(){
		$this->verif();

		if(!@rmdir($this->sAdresse)){
			throw new Exception('Erreur rmdir ('.$this->sAdresse.')');
		}
	}
	/**
	 * test l'existence du repertoire
	 * @access public
	 * @return bool true ou false
	 */
	public function exist(){
		return file_exists($this->sAdresse);
	}

	public function save(){
		mkdir($this->sAdresse);
	}

	public function chmod($valeur){
		chmod($this->sAdresse,0777);
	}

	private function verif(){

		if($this->sAdresse==null){
			throw new Exception('objet _dir: Adresse du repertoire non defini');
		}

		if(!$this->exist()){
			throw new Exception($this->sAdresse.' n\'existe pas');
		}
	}
}
