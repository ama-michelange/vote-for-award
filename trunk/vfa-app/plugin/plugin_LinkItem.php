<?php

/**
 * @author ANTON-MA
 *
 */
class plugin_LinkItem
{

	protected $_sLink;

	protected $_tProperties;

	public function __construct($pLink, $pProperties)
	{
		$this->_sLink = $pLink;
		$this->_tProperties = $pProperties;
	}

	public function getLink()
	{
		return $this->_sLink;
	}

	public function getProperties()
	{
		return $this->_tProperties;
	}

	public function buildHtmlProperties()
	{
		$ret = '';
		if (isset($this->_tProperties)) {
			foreach ($this->_tProperties as $label => $value) {
				$ret .= ' ';
				$ret .= $label;
				$ret .= '="';
				$ret .= $value;
				$ret .= '"';
			}
		}
		return $ret;
	}

	public function isPermit()
	{
		return true;
	}

	public function isCurrentModule()
	{
		return false;
	}

	public function isCurrentAction()
	{
		return false;
	}
	
	
}