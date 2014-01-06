<?php

/**
 * @author ANTON-MA
 *
 */
class plugin_NavBar
{

	private $_tNavItems;

	public function __construct()
	{
		$this->_tNavItems = array();
	}

	public function put($pName, $pNavItems)
	{
		$this->_tNavItems[$pName] = $pNavItems;
	}

	public function get($pName)
	{
		return $this->_tNavItems[$pName];
	}
}