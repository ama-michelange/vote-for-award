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
 *classe abstract_auth
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
abstract class abstract_auth{

	private $bConnected=false;

	public function enable(){
		_root::startSession();

		$sModuleToLoad=_root::getRequest()->getModule();

		if(preg_match('/::/',_root::getConfigVar('auth.module'))){
			list($sAuthModule,$sAuthAction)=preg_split('/::/',_root::getConfigVar('auth.module'));
		}else{
			$sAuthModule=_root::getConfigVar('auth.module');
		}

		if( !_root::getAuth()->isConnected() and $sModuleToLoad != $sAuthModule ){
			_root::redirect(_root::getConfigVar('auth.module'));
		}

	}

	/**
	 * @access public
	 * indique si l'utilisateur est connecte
	 * @return bool
	 */
	public function _isConnected(){

		if( !isset($_SESSION['ip']) or $_SESSION['ip']!=sha1($_SERVER['REMOTE_ADDR']) ){
			return false;
		}else if((int)_root::getConfigVar('auth.session.timeout.enabled')==1 and (!isset($_SESSION['timeout']) or ((int)$_SESSION['timeout']-time() ) < 0)){
			//on regenere un nouvel id de session
			session_regenerate_id(true);
			return false;
		}else if(_root::getConfigVar('security.xsrf.checkReferer.enabled') ==1 and isset($_SERVER['HTTP_REFERER'])){

			if(isset($_SERVER['HTTPS']) ){
				$sPattern='https://'.$_SERVER['SERVER_NAME'];

			}else{
				$sPattern='http://'.$_SERVER['SERVER_NAME'];
			}
			$urllen=strlen($sPattern);

			if( substr($_SERVER['HTTP_REFERER'],0,$urllen)!=$sPattern ){
				return false;
			}

		}

		if((int)_root::getConfigVar('auth.session.timeout.enabled')==1){
			$_SESSION['timeout']=(time()+(int)_root::getConfigVar('auth.session.timeout.lifetime') );
		}

		return true;
	}
	/**
	 * @access public
	 */
	public function _connect(){
		//on regenere un nouvel id de session
		session_regenerate_id(true);

		$this->bConnected=true;

		$_SESSION['ip']=sha1($_SERVER['REMOTE_ADDR']);
		if((int)_root::getConfigVar('auth.session.timeout.enabled')==1){
			$_SESSION['timeout']=(time()+(int)_root::getConfigVar('auth.session.timeout.lifetime') );
		}
	}
	/**
	 * @access public
	 */
	public function _disconnect(){
		$_SESSION=array();

		//on regenere un nouvel id de session
		session_regenerate_id(true);

		$this->bConnected=false;
	}
}