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
* plugin_check classe pour verifier un lot de valeurs (verification de formulaire par exemple)
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_check{
	
	/**
	* verifie si $uValueA est egal a $uValueB
	* @access public
	* @param undefined $uValueA valeur A
	* @param undefined $uValueB valeur B
	* @return bool retourne true/false selon
	*/
	public function isEqual($uValueA,$uValueB){
		if($uValueA==$uValueB){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA  n'est pas egal a $uValueB
	* @access public
	* @param undefined $uValueA valeur A
	* @param undefined $uValueB valeur B
	* @return bool retourne true/false selon
	*/
	public function isNotEqual($uValueA,$uValueB){
		if($uValueA!=$uValueB){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA est superieur a $uValueB
	* @access public
	* @param undefined $uValueA valeur A
	* @param undefined $uValueB valeur B
	* @return bool retourne true/false selon
	*/
	public function isUpperThan($uValueA,$uValueB){
		if($uValueA > $uValueB){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA est superieur ou egal a $uValueB
	* @access public
	* @param undefined $uValueA valeur A
	* @param undefined $uValueB valeur B
	* @return bool retourne true/false selon
	*/
	public function isUpperOrEqualThan($uValueA,$uValueB){
		if($uValueA >= $uValueB){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA est inferieur a $uValueB
	* @access public
	* @param undefined $uValueA valeur A
	* @param undefined $uValueB valeur B
	* @return bool retourne true/false selon
	*/
	public function isLowerThan($uValueA,$uValueB){
		if($uValueA < $uValueB){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA est inferieur ou egal a $uValueB
	* @access public
	* @param undefined $uValueA valeur A
	* @param undefined $uValueB valeur B
	* @return bool retourne true/false selon
	*/
	public function isLowerOrEqualThan($uValueA,$uValueB){
		if($uValueA <= $uValueB){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA est vide
	* @access public
	* @param undefined $uValueA valeur A
	* @return bool retourne true/false selon
	*/
	public function isEmpty($uValueA){
		if(trim($uValueA)==''){
			return true;
		}
		return false;
	}
	/**
	* verifie si $uValueA n'est pas vide
	* @access public
	* @param undefined $uValueA valeur A
	* @return bool retourne true/false selon
	*/
	public function isNotEmpty($uValueA){
		if(trim($uValueA)!=''){
			return true;
		}
		return false;
	}
	/**
	* verifie si le $uValueA est un email valide
	* @access public
	* @param undefined $uValueA valeur A
	* @return bool retourne true/false selon
	*/
	public function isEmailValid($uValueA){
		if(preg_match('/^[\w.-_]+@[\w.-_]+\.[a-zA-Z]{2,6}$/',$uValueA)){
			return true;
		}
		return false;
	}
	/**
	* verifie si le champ $sName verifie l'expression
	* @access public
	* @param string $sName nom du champ a verifier
	* @return bool retourne true/false selon
	*/
	public function matchExpression($uValueA,$sExpression){
		if(preg_match($sExpression,$uValueA)){
			return true;
		}
		return false;
	}
	/**
	* verifie si le champ $sName ne verifie pas l'expression
	* @access public
	* @param string $sName nom du champ a verifier
	* @return bool retourne true/false selon
	*/
	public function notMatchExpression($uValueA,$sExpression){
		if(!preg_match($sExpression,$uValueA)){
			return true;
		}
		return false;
	}

}
