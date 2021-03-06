<?php

/*
 * This file is part of Mkframework. Mkframework is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License. Mkframework is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details. You should have received a copy of the GNU Lesser General Public License along with Mkframework. If not, see <http://www.gnu.org/licenses/>.
 */

class sgbd_mysql extends abstract_sgbd
{

	public static function getInstance($sConfig)
	{
		return self::_getInstance(__CLASS__, $sConfig);
	}

	public function findMany($tSql, $sClassRow)
	{
		$pRs = $this->query($this->bind($tSql));

		if (empty($pRs)) {
			return null;
		}

		$tObj = array();
		while ($tRow = mysql_fetch_assoc($pRs)) {
			$oRow = new $sClassRow($tRow);
			$tObj[] = $oRow;
		}
		return $tObj;
	}

	public function findManySimple($tSql, $sClassRow)
	{
		$pRs = $this->query($this->bind($tSql));

		if (empty($pRs)) {
			return null;
		}

		$tObj = array();
		while ($oRow = mysql_fetch_object($pRs)) {
			$tObj[] = $oRow;
		}
		return $tObj;
	}

	public function findOne($tSql, $sClassRow)
	{
		$pRs = $this->query($this->bind($tSql));

		if (empty($pRs)) {
			return null;
		}

		$tRow = mysql_fetch_assoc($pRs);
		$oRow = new $sClassRow($tRow);

		return $oRow;
	}

	public function findOneSimple($tSql, $sClassRow)
	{
		$pRs = $this->query($this->bind($tSql));

		if (empty($pRs)) {
			return null;
		}

		$oRow = mysql_fetch_object($pRs);

		return $oRow;
	}

	public function execute($tSql)
	{
		return $this->query($this->bind($tSql));
	}

	public function update($sTable, $tProperty, $twhere)
	{
		$this->query(
			'UPDATE ' . $sTable . ' SET ' . $this->getUpdateFromTab($tProperty) . ' WHERE ' . $this->getWhereFromTab($twhere));
	}

	// AMA : ajout de la recherche de l'ID qui vient d'être inséré
	public function insert($sTable, $tProperty)
	{
		$this->query('INSERT INTO ' . $sTable . ' ' . $this->getInsertFromTab($tProperty));
		return $this->getLastInsertId();
	}

	public function delete($sTable, $twhere)
	{
		$this->query('DELETE FROM ' . $sTable . ' WHERE ' . $this->getWhereFromTab($twhere));
	}

	public function getListColumn($sTable)
	{
		$pRs = $this->query(sgbd_syntax_mysql::getListColumn($sTable));
		$tCol = array();

		if (empty($pRs)) {
			return $tCol;
		}

		while ($tRow = mysql_fetch_row($pRs)) {
			$tCol[] = $tRow[0];
		}

		return $tCol;
	}

	public function getListTable()
	{
		$pRs = $this->query(sgbd_syntax_mysql::getListTable());
		$tCol = array();

		if (empty($pRs)) {
			return $tCol;
		}

		while ($tRow = mysql_fetch_row($pRs)) {
			$tCol[] = $tRow[0];
		}
		return $tCol;
	}

	private function connect()
	{
		if (empty($this->_pDb)) {
			if (($this->_pDb = mysql_connect($this->_tConfig[$this->_sConfig . '.hostname'],
					$this->_tConfig[$this->_sConfig . '.username'], $this->_tConfig[$this->_sConfig . '.password'])) == false
			) {
				throw new Exception('Probleme connexion sql : ' . mysql_error());
			}
			if (mysql_select_db($this->_tConfig[$this->_sConfig . '.database'], $this->_pDb) == false) {
				$sMsg = 'Probleme selection de la base : ' . $this->_tConfig[$this->_sConfig . '.database'] . ' ' . mysql_error();
				throw new Exception($sMsg);
			}

			// Ajout AMA : Définition du jeu de caractères à utiliser
			if (function_exists('mysql_set_charset')) {
				if (!mysql_set_charset('utf8', $this->_pDb)) {
					throw new Exception(
						'Impossible de définir le jeu de caractères : ' . $this->_tConfig[$this->_sConfig . '.database'] . ' 		' .
						mysql_error());
				}
			} else {
				mysql_query("set names 'utf8'");
			}
		}
	}

	public function _ORI_getLastInsertId()
	{
		$pRs = $this->query(sgbd_syntax_mysql::getLastInsertId());

		if (empty($pRs)) {
			return null;
		}
		$tRow = $pRs->fetch(PDO::FETCH_NUM);
		return (int)$tRow[0];
	}

	public function getLastInsertId()
	{
		return mysql_insert_id();
	}

	private function query($sReq)
	{
		$this->connect();
		$this->_sReq = $sReq;
		return mysql_query($sReq);
	}

	public function ORI_quote($sVal)
	{
		return str_replace("\\", '', str_replace("'", '\'', "'" . $sVal . "'"));
	}

	public function quote($sVal)
	{
		$this->connect();
		// return str_replace("'",'\'',"'".$sVal."'");
		$newVal = "'" . mysql_real_escape_string($sVal, $this->_pDb) . "'";
		return $newVal;
	}

	public function AMA_quote($sVal)
	{
		$this->connect();
		// return str_replace("'",'\'',"'".$sVal."'");
		$newVal = "'" . mysql_real_escape_string($sVal, $this->pDb) . "'";
		// _root::getLog()->log('AMA >>> quote:$newVal = '.$newVal);
		return $newVal;
	}

	public function getWhereAll()
	{
		return '1=1';
	}
}
