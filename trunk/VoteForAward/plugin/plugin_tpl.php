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
* plugin_tpl classe d'aide de template
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_tpl{

	private $sHtml;
	private $oFileTpl;
	private $oTplApp;
	
	static $tAlternate;
	
	/** la classe est cree par _tpl, donc cette partie n'est pas a connaitre en principe
	* constructeur
	* @access public
	* @param string $sRessource adresse de la vue cible (fichier produit par le plugin)
	*/
	public function __construct($sRessource){
		$this->oFileTpl=new _file($sRessource);
		if($this->oFileTpl->exist() and _root::getConfigVar('site.mode')!='dev'){ return; }
		
		if(_root::getConfigVar('template.class_app',null)!=''){
			$sClass=_root::getConfigVar('template.class_app');
			$this->oTplApp=new $sClass;
		}
		$this->parse($sRessource._root::getConfigVar('template.extension'));
	}
	
	/** retourne en alternance une valeur du tableau $tab, un deuxieme argument (optionnel) permet d'avoir plusieurs lots d'alternance
	* @access public
	* @param array $tab tableau contenant les valeurs a alterner
	* @param string $uRef
	*/
	public static function alternate($tab,$uRef=0){
		if(!isset(self::$tAlternate[$uRef])){
			self::$tAlternate[$uRef]=0;
		}else{
			self::$tAlternate[$uRef]+=1;
		}
		if(self::$tAlternate[$uRef] >= count($tab)) self::$tAlternate[$uRef]=0;
		
		return $tab[self::$tAlternate[$uRef] ];
	}
	
	private function getAttributes($oChild){
				
		if($oChild->nodeType == XML_TEXT_NODE){ return $this->parseContent($oChild->nodeValue); }

		$sHtml='';
		foreach($oChild->attributes as $attr){
			//$sHtml.=$attr->name.'="'.$attr->value.'" ';
		}
		
		
		foreach($oChild->attributes as $attr){
			$cle=$attr->name;
			$prefix=$attr->prefix;

			if($prefix=='' ){ //bloc 
				$valeur=$attr->value;
				
				$sHtml.=$cle.'="'.$valeur.'" ';
				
			}elseif($prefix=='mkf' ){ //bloc framework
				$valeur=$attr->value;
				$tReturn=$this->{'_'.$cle}($valeur);
				if(isset($tReturn['content'])){
					$sHtml.=$tReturn['content'];
				}
				
			}elseif($prefix=='app' ){ //bloc metier 
				$valeur=$attr->value;
				$tReturn=$this->oTplApp->{'_'.$cle}($valeur);
				if(isset($tReturn['content'])){
					$sHtml.=$tReturn['content'];
				}
				
			}
		}

		
		
		return $sHtml;
	}
	
	private function getPhp($oChild,$sHtml){

		$sHtmlBefore='';
		$sHtmlAfter='';
		
		if($oChild->nodeType == XML_TEXT_NODE){ return $this->parseContent($oChild->nodeValue); }

		
		foreach($oChild->attributes as $attr){
			$cle=$attr->name;
			//bloc framework 
			if($oChild->getAttributeNodeNS('http://mkdevs.com/mkf',$cle) ){ 
				$valeur=$oChild->getAttributeNodeNS('http://mkdevs.com/mkf',$cle);
				$tReturn=$this->{'_'.$cle}($valeur->nodeValue);
				if(isset($tReturn['before'])){
					$sHtmlBefore.=$tReturn['before'];
				}
				if(isset($tReturn['after'])){
					$sHtmlAfter=$tReturn['after'].$sHtmlAfter;
				}
			}
			//bloc metier
			if($oChild->getAttributeNodeNS('http://mkdevs.com/app',$cle) ){ 
				$valeur=$oChild->getAttributeNodeNS('http://mkdevs.com/app',$cle);
				$tReturn=$this->oTplApp->{'_'.$cle}($valeur->nodeValue);
				if(isset($tReturn['before'])){
					$sHtmlBefore.=$tReturn['before'];
				}
				if(isset($tReturn['after'])){
					$sHtmlAfter=$tReturn['after'].$sHtmlAfter;
				}
			}
		}
		
		
		return $sHtmlBefore.$sHtml.$sHtmlAfter;

	}
	private function getPhpContent($oChild,$sHtml){
		$sHtmlBefore='';
		$sHtmlAfter='';
		
		if($oChild->nodeType == XML_TEXT_NODE){ return $this->parseContent($oChild->nodeValue); }

		
		foreach($oChild->attributes as $attr){
			$cle=$attr->name;
			//bloc framework 
			if($oChild->getAttributeNodeNS('http://mkdevs.com/mkf',$cle) ){ 
				$valeur=$oChild->getAttributeNodeNS('http://mkdevs.com/mkf',$cle);
				$tReturn=$this->{'_'.$cle}($valeur->nodeValue);
				if(isset($tReturn['before_content'])){
					$sHtmlBefore.=$tReturn['before_content'];
				}
				if(isset($tReturn['after_content'])){
					$sHtmlAfter=$tReturn['after_content'].$sHtmlAfter;
				}
			}
			//bloc metier
			if($oChild->getAttributeNodeNS('http://mkdevs.com/app',$cle) ){ 
				$valeur=$oChild->getAttributeNodeNS('http://mkdevs.com/app',$cle);
				$tReturn=$this->oTplApp->{'_'.$cle}($valeur->nodeValue);
				if(isset($tReturn['before_content'])){
					$sHtmlBefore.=$tReturn['before_content'];
				}
				if(isset($tReturn['after_content'])){
					$sHtmlAfter=$tReturn['after_content'].$sHtmlAfter;
				}
			}
		}

		
		return $sHtmlBefore.$sHtml.$sHtmlAfter;
	}
	private function getXml($oChild){
	
		$sContent=null;
		
		if($oChild->childNodes){
			if($oChild->nodeName=='code'){
				$sHtml='';
				if(in_array($oChild->nodeName,array('table','tr'))){ $sHtml.="\n"; }
			
				$sHtml.=$sContent;
				foreach($oChild->childNodes as $oChild2){
					$sHtml.=$this->getXml($oChild2);
				}
			}else{
		
				$sHtml='';
				$sHtml.='<'.$oChild->nodeName.' '.$this->getAttributes($oChild).'>';
				if(in_array($oChild->nodeName,array('table','tr'))){ $sHtml.="\n"; }
			
				$sHtml.=$sContent;
				foreach($oChild->childNodes as $oChild2){
					$sHtml.=$this->getXml($oChild2);
				}
				$sHtml.='</'.$oChild->nodeName.'>'."\n";
			}
		}else{
			if($oChild->nodeName=='code'){
				$sHtml=$this->getAttributes($oChild).$sContent;
			}elseif(in_array($oChild->nodeName, array('img','br','hr','input')) ){
				$sHtml='<'.$oChild->nodeName.' '.$this->getAttributes($oChild).$sContent.' />';
				
			}else{
				$sHtml='<'.$oChild->nodeName.' '.$this->getAttributes($oChild).'>'.$sContent.'</'.$oChild->nodeName.'>'."\n";
			}
		}
		
		
		$sHtml=$this->getPhp($oChild,$sHtml);
		
		return $sHtml;
		
	}
	private function parseContent($sContent){
		$sContent=preg_replace('/\{\{(.*)\}\}/','<?php echo $1?>',$sContent);
		return $sContent;
	}
	private function parse($sFileXml){
		
		$sXml=
		'<?xml version="1.0" ?>
		<main xmlns:mkf="http://mkdevs.com/mkf" xmlns:app="http://mkdevs.com/app">';
		$sXml.=implode("\n",file($sFileXml));
		$sXml.='</main>';
	
		$oXml=new DOMDocument();
		try{
		$oXml->loadXML($sXml,LIBXML_NOCDATA );
		}catch(Exception $e){

			throw new Exception('
					
					Probleme de parsing xml ('.$e->getMessage().')
					Le xml '.$sFileXml.' n\'est pas conforme
					 verifiez les points suivants:
					- remplacer dans les attributs les & par &amp;amp; 
					- ajouter dans les balises &lt;code&gt; &lt;![CDATA[ et ]]&gt;
					
					');
		}
		$oXmlnodes=$oXml->childNodes;
		$oXmlHtml=$oXmlnodes->item(0);
		 
		foreach($oXmlHtml->childNodes as $oXmlChild){
			$this->sHtml.=$this->getXml($oXmlChild);
		}
  
		
		$this->oFileTpl->setContent($this->sHtml);
		$this->oFileTpl->save();
	
	}

	//methodes automatiques appelees lors du parsage
	private function _foreach($sValue){
		return array(
			'before' => '<?php foreach('.$sValue.'):?>',
			'after' => '<?php endforeach;?>',
		);
	}
	private function _for($sValue){
		return array(
			'before' => '<?php for('.$sValue.'):?>',
			'after' => '<?php endfor;?>',
		);
	}
	private function _if($sValue){
		return array(
			'before' => '<?php if('.$sValue.'):?>',
			'after' => '<?php endif;?>',
		);
	}
	private function _elseif($sValue){
		return array(
			'before' => '<?php elseif('.$sValue.'):?>',
			'after' => '<?php endif;?>',
		);
	}
	private function _else($sValue=null){
		return array(
			'before' => '<?php else:?>',
			'after' => '<?php endif;?>',
		);
	}
	private function _if_($sValue=null){
		return array(
			'before' => '<?php if('.$sValue.'):?>',
		);
	}
	private function _elseif_($sValue=null){
		return array(
			'before' => '<?php elseif('.$sValue.'):?>',
		);
	}
	private function _php($sValue){
		return array(
			'content' => '<?php '.$sValue.'?>',
		);
	}
	private function _echo($sValue){
		return array(
			'content' => '<?php echo '.$sValue.'?>',
		);
	}
	private function _alternate($sValue){
		
		return array(
			'content' => 'class="<?php echo plugin_tpl::alternate('.$sValue.')?>"',
		);
	}
	private function _href($sValue){
		return array(
			'content' => 'href="<?php echo $this->getLink('.$sValue.');?>"',
		);
	}
	private function _src($sValue){
		return array(
			'content' => 'src="<?php echo '.$sValue.'?>"',
		);
	}
	private function _attributes($sValue){
		
		list($sAttribute,$sValue)=preg_split('/:/',$sValue,2);
		
		
		return array(
			'content' => $sAttribute.'="<?php echo '.$sValue.'?>"',
		);
	}
	private function _language($sValue){
		if($sValue=='php'){
			return array(
				'before' => '<?php ',
				'after' => '?>',
			);
		}elseif($sValue=='javascript'){
			return array(
				'before' => '<script type="text/javascript">',
				'after' => '</script>',
			);
		}else{
			throw new Exception('plugin_tpl : language '.$sValue.' non reconnu ');
		}
	}
	

}
