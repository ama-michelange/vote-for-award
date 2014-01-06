<?php

/**
 * @author ANTON-MA
 *
 */
class plugin_NavItems
{

	private $_tItems;

	public function __construct()
	{
		$this->_tItems = array();
	}

	public function add($pLabelItem)
	{
		if (isset($pLabelItem)) {
			$this->_tItems[] = $pLabelItem;
		}
	}

	public function put($pLabelItem)
	{
		if (isset($pLabelItem)) {
			$label = $pLabelItem->getLabel();
			$this->_tItems[$label] = $pLabelItem;
		}
	}

	public function get($pLabel)
	{
		return $this->_tItems[$pLabel];
	}

	public function getItems()
	{
		return $this->_tItems;
	}
}