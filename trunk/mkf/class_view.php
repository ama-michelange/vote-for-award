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
 * classe _tpl
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
class _view{

	protected $sModule;
	protected $sTpl;
	protected $tVar;
	protected $sPath;

	/**
	 * constructeur
	 * @access public
	 * @param string $sRessource nom du fichier de template a utiliser (module::fichier)
	 */
	public function __construct($sRessource=null){

		$this->tVar=array();

		/*LOG*/_root::getLog()->info('--vue: initialisation ['.$sRessource.']');
		if($sRessource!=null){

			if(preg_match('/::/',$sRessource)){
				list($this->sModule,$this->sTpl)=preg_split('/::/',$sRessource);
				$sRessource='module/'.$this->sModule.'/'._root::getConfigVar('path.view','tpl/').$this->sTpl.'.php';
			}

			$this->choose($sRessource);
		}
	}

	public function __set($sVar, $sVal){
		/*LOG*/_root::getLog()->info('---vue: assignation ['.$sVar.']');
		$this->tVar[$sVar]=$sVal;
	}
	public function __get($sVar){
		if(!array_key_exists($sVar,$this->tVar)){
			/*LOG*/_root::getLog()->error('Variable '.$sVar.' inexistante dans le template '.$this->sModule.'::'.$this->sTpl);
			throw new Exception('Variable '.$sVar.' inexistante dans le template '.$this->sModule.'::'.$this->sTpl);
		}else{
			return $this->tVar[$sVar];
		}
	}
	public function exists($sVar){
		if(!array_key_exists($sVar,$this->tVar)){
			return false;
		}else{
			if (empty($this->tVar[$sVar])) {
				return false;
			}
		}
		return true;
	}
	/**
	 * retourne la sortie
	 * @access public
	 * @return string
	 */
	public function show(){
		//utilisation du plugin plugin_tpl
		if(_root::getConfigVar('template.enabled',0)==1 and _root::getConfigVar('template.class',null)!=''){
			$sClass=_root::getConfigVar('template.class');
			if(file_exists($this->sPath._root::getConfigVar('template.extension')) ){
				try{
					$oPluginTpl=new $sClass($this->sPath);
				}catch(Exception $e ){
					throw new Exception('Probleme lors de la generation du template '.$this->sPath._root::getConfigVar('template.extension')."\nNote: verifier les droits de votre repertoire ".dirname($this->sPath)."\n".$e);
				}
			}
		}

		/*LOG*/_root::getLog()->info('--vue: affichage ['.$this->sPath.']');
		ob_start();
		include($this->sPath);
		$sSortie=ob_get_contents();
		ob_end_clean();

		return $sSortie;
	}
	/**
	 * retourne le path de la vue
	 * @access public
	 * @return string
	 */
	public function getPath(){
		return $this->sPath;
	}
	/**
	 * retourne un lien framework
	 * @access public
	 * @return string
	 */
	public function getLink($sLink,$tParam=null,$bAmp=true){
		return _root::getLink($sLink,$tParam,$bAmp);
	}

	protected function choose($sPath){
		if(!file_exists($sPath) and !file_exists($sPath._root::getConfigVar('template.extension'))){
			/*LOG*/_root::getLog()->error('vue '.$sPath.' et  inexistant');
			throw new Exception('vue '.$sPath.' et '.$sPath._root::getConfigVar('template.extension').' inexistant');
		}
		$this->sPath=$sPath;
	}



}
