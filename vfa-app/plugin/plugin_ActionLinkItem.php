<?php

/**
 * @author ANTON-MA
 *
 */
class plugin_ActionLinkItem extends plugin_LinkItem
{

	protected $_sAction;

	protected $_sModule;

	protected $_tParams;

	public function __construct($pModule, $pAction, $ptParams = null)
	{
		$this->_sModule = $pModule;
		$this->_sAction = $pAction;
		$this->_tParams = $ptParams;
		$this->_sLink = _root::getLink($this->getNav(), $this->_tParams, false);
	}

	public function getModule()
	{
		return $this->_sModule;
	}

	public function getAction()
	{
		return $this->_sAction;
	}

	public function getNav()
	{
		return $this->_sModule . '::' . $this->_sAction;
	}

	public function isPermit()
	{
		return _root::getACL()->permit($this->getNav());
	}
	
	public function isCurrentModule()
	{
		$ret = false;
		if ($this->_sModule == _root::getModule()) {
			$ret = true;
		}
		return $ret;
	}

	public function isCurrentAction()
	{
		$ret = false;
		if ($this->_sModule == _root::getModule()) {
			if ($this->_sAction == _root::getAction()) {
				$ret = true;
			}
		}
		return $ret;
	}
}