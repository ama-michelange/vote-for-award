<?php
/*

*/

/**
 * plugin_authorization : classe de gestion des autorisations d'accès aux différentes ressources.
 * @author michelange
 */
class plugin_authorization {

	public function isInRole($pRole) {
		$bInRole = false;
		$oAccount = _root::getAuth()->getAccount();
		if (null != $oAccount) {
			$tRoles = $oAccount->getNameRoles();
			$bInRole = isset($tRoles['owner']);
			if (false == $bInRole) {
				if (is_array($pRole)){
					$size = count($pRole);
					for($i = 0; $i < $size; ++$i) {
						$bInRole = $bInRole || isset($tRoles[$pRole[$i]]);
					}
				}
				else {
					$bInRole = isset($tRoles[$pRole]);
				}
			}
		}
		return $bInRole;
	}

	public function enable() {
		_root::getAuth()->enable();

		$sModule=_root::getModule();
		$sAction=_root::getAction();
		$bPermit = $this->permit($sModule.'::'.$sAction);
		if (false == $bPermit){
			_root::redirect(_root::getConfigVar('auth.module'));
		}
	}

	public function permit($pModActions) {
		$oAccount = _root::getAuth()->getAccount();
		$tRoles = $oAccount->getNameRoles();
		$bPermit = isset($tRoles['owner']);
		if (false == $bPermit) {
			$tAuthos = $oAccount->getAuthorizations();
			if (is_array($pModActions)){
				foreach ($pModActions as $modAction){
					$bPermit = isset($tAuthos[$modAction]);
					if (true == $bPermit) {
						break;
					}
				}
			}
			else {
				$bPermit = isset($tAuthos[$pModActions]);
			}
		}
		return $bPermit;
	}

}
