<?php

/**
 * Classe pour verifier un lot de valeurs (verification de formulaire par exemple)
 * @author Ama
 *
 */
class plugin_vfa_check extends plugin_check {


	/**
	 * verifie si $uValueA est une date précédente a $uValueB
	 * @access public
	 * @param string $uValueA valeur A
	 * @param string $uValueB valeur B
	 * @return bool retourne true/false selon
	 */
	public function isDateBefore($pValueA,$pValueB){
		$sDateA = plugin_vfa::toDateFromSgbd($pValueA);
		$sDateB = plugin_vfa::toDateFromSgbd($pValueB);
		if ((null==$sDateA) || (null==$sDateB)) {
			return true;
		}
		if ($sDateA->getMkTime() < $sDateB->getMkTime()) {
			return true;
		}
		return false;
	}

	/**
	 * verifie si $uValueA est une date superieure a $uValueB
	 * @access public
	 * @param string $uValueA valeur A
	 * @param string $uValueB valeur B
	 * @return bool retourne true/false selon
	 */
	public function isDateAfter($uValueA,$uValueB){
		$sDateA = plugin_vfa::toDateFromSgbd($uValueA);
		$sDateB = plugin_vfa::toDateFromSgbd($uValueB);
		if ((null==$sDateA) || (null==$sDateB)) {
			return true;
		}
		if ($sDateA->getMkTime() > $sDateB->getMkTime()) {
			return true;
		}
		return false;
	}

	/**
	 * verifie si $uValueA OU $uValueB ne sont pas vide.
	 * @access public
	 * @param undefined $uValueA valeur A
	 * @param undefined $uValueB valeur B
	 * @return bool retourne true/false selon
	 */
	public function isNotEmptyOr($uValueA, $uValueB){
		$retA = $this->isNotEmpty($uValueA);
		$retB = $this->isNotEmpty($uValueB);
		return (true==$retA) || (true==$retB);
	}

	/**
	 * verifie si $uValue n'est pas vide.
	 * Si $uValue est un array, vérifie qu'il contient au moins un élément.
	 * @param undefined $uValue valeur
	 * @return bool retourne true/false selon
	 * @see plugin_check::isNotEmpty()
	 */
	public function isNotEmpty($uValue) {
		$ret = false;
		if (null != $uValue){
			if (true == is_array($uValue)){
				$ret = count($uValue) > 0;
			}
			else{
				$ret = parent::isNotEmpty($uValue);
			}
		}
		return $ret;
	}


}
