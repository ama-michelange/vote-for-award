<?php
/*
 *
 */
/**
 *
 * @author AMA
 */
class plugin_validation
{

	/**
	 * Renvoie les erreurs d'un champ à partir d'un tableau d'erreurs d'un objet "row"
	 *
	 * @access public
	 * @param array $tErrors
	 *        	Le tableau d'erreurs
	 * @param string $sField
	 *        	Le nom du champ
	 * @return string La valeur de l'erreur ou une chaine vide si pas d'erreur
	 */
	public static function exist($tErrors, $sField)
	{
		$bExist = false;
		if (isset($tErrors) and isset($tErrors[$sField])) {
			$bExist = true;
		}
		return $bExist;
	}

	/**
	 * Renvoie les erreurs d'un champ à partir d'un tableau d'erreurs d'un objet "row"
	 *
	 * @access public
	 * @param array $tErrors
	 *        	Le tableau d'erreurs
	 * @param string $sField
	 *        	Le nom du champ
	 * @return string La valeur de l'erreur ou une chaine vide si pas d'erreur
	 */
	public static function showDirect($tErrors, $sField)
	{
		$value = '';
		if (isset($tErrors) and isset($tErrors[$sField])) {
			$value = implode(', ', $tErrors[$sField]);
		}
		return $value;
	}

	/**
	 * Renvoie les erreurs d'un champ à partir d'un tableau d'erreurs d'un objet "row"
	 *
	 * @access public
	 * @param array $tErrors
	 *        	Le tableau d'erreurs
	 * @param string $sField
	 *        	Le nom du champ
	 * @return string La valeur de l'erreur ou une chaine vide si pas d'erreur
	 */
	public static function show($tErrors, $sField)
	{
		$translated = '';
		if (isset($tErrors) and isset($tErrors[$sField])) {
			if (is_array($tErrors[$sField])) {
				$tTrans = array();
				foreach ($tErrors[$sField] as $value) {
					$sI18n = plugin_i18n::get($value . '.' . $sField);
					$pos = strpos($sI18n, $value . '.' . $sField);
					if ($pos === false) {
						$tTrans[] = $sI18n;
					} else {
						$tTrans[] = plugin_i18n::get($value);
					}
				}
				$translated = implode(', ', $tTrans);
			} else {
				$translated = plugin_i18n::get($sField);
			}
		}
		return $translated;
	}

	/**
	 * Ajoute la classe "error" à la classe originale si un erreur existe pour le champ.
	 *
	 * @access public
	 * @param string $sOriginalClass
	 *        	La classe originale
	 * @param array $tErrors
	 *        	Le tableau d'erreurs
	 * @param
	 *        	array or string $sFields
	 *        	Le (les) nom du champ
	 * @return string La classe du champ
	 */
	public static function addClassError($sOriginalClass, $tErrors, $sFields)
	{
		$sClass = $sOriginalClass;
		if (is_array($sFields)) {
			foreach ($sFields as $field) {
				if (isset($tErrors) and isset($tErrors[$field])) {
					$sClass = $sClass . ' has-error';
					break;
				}
			}
		} else {
			if (isset($tErrors) and isset($tErrors[$sFields])) {
				$sClass = $sClass . ' has-error';
			}
		}
		return $sClass;
	}
}
