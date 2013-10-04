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
class sgbd_pdo_mysql extends abstract_sgbd_pdo{

	public static function getInstance($sConfig){
		return self::_getInstance(__CLASS__,$sConfig);
	}

	public function getListColumn($sTable){
		$pRs=$this->query(sgbd_syntax_mysql::getListColumn($sTable));
		$tObj=array();

		if(empty($pRs)){
			return null;
		}

		while($tRow=$pRs->fetch(PDO::FETCH_NUM)){
			$tObj[]=$tRow[0];
		}
		return $tObj;
	}
	public function getListTable(){
		$pRs=$this->query(sgbd_syntax_mysql::getListTable());

		if(empty($pRs)){
			return null;
		}

		$tObj=array();
		while($tRow=$pRs->fetch(PDO::FETCH_NUM)){
			$tObj[]=$tRow[0];
		}
		return $tObj;
	}

	protected function connect(){
		if(empty($this->pDb)){
			$this->pDb=new PDO(
					$this->tConfig[$this->sConfig.'.dsn'],
					$this->tConfig[$this->sConfig.'.username'],
					$this->tConfig[$this->sConfig.'.password']
			);
		}
	}

	public function getWhereAll(){
		return '1=1';
	}

}
