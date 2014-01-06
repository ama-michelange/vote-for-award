<?php

/**
 * @author ANTON-MA
 *
 */
class plugin_LabelItem
{

	private $_sLabel;

	private $_sIcon;

	private $_oLink;

	private $tChildren;

	private $_bSeparator;

	private $_bOnlySeparatorChildren;

	public static function buildSeparator()
	{
		return new plugin_LabelItem();
	}

	public static function buildDefaultLink($pLabel = null, $pLink = null)
	{
		return new plugin_LabelItem($pLabel, null, $pLink);
	}

	public static function buildLink($pLabel = null, $pLink = null)
	{
		$ret = new plugin_LabelItem($pLabel, null, $pLink);
		if (false == $ret->isPermit()) {
			$ret = null;
		}
		return $ret;
	}

	public static function build($pLabel = null, $pIcon = null, $pLink = null)
	{
		$ret = new plugin_LabelItem($pLabel, $pIcon, $pLink);
		if (false == $ret->isPermit()) {
			$ret = null;
		}
		return $ret;
	}

	public function __construct($pLabel = null, $pIcon = null, $pLink = null)
	{
		$this->_sLabel = $pLabel;
		$this->_sIcon = $pIcon;
		$this->_oLink = $pLink;
		$_bSeparator = false;
		if ((false == isset($this->_sLabel)) && (false == isset($this->_sIcon)) && (false == isset($this->_sModule))) {
			$this->_bSeparator = true;
		}
		$this->_bOnlySeparatorChildren = true;
	}

	public function isSeparator()
	{
		return $this->_bSeparator;
	}

	public function getLabel()
	{
		return $this->_sLabel;
	}

	public function getIcon()
	{
		return $this->_sIcon;
	}

	public function hasIcon()
	{
		return isset($this->_sIcon);
	}

	public function getHref()
	{
		$ret = '#';
		if (isset($this->_oLink)) {
			$ret = $this->_oLink->getLink();
		}
		return $ret;
	}

	public function getLink()
	{
		return $this->_oLink;
	}

	public function hasLink()
	{
		return isset($this->_oLink);
	}

	public function isPermit()
	{
		$ret = true;
		if (isset($this->_oLink)) {
			if (false == $this->_oLink->isPermit()) {
				$ret = false;
			}
		}
		return $ret;
	}

	public function addChild($pItem)
	{
		if (isset($pItem)) {
			if (false == isset($this->tChildren)) {
				$this->tChildren = array();
			}
			$this->tChildren[] = $pItem;
			$this->_bOnlySeparatorChildren &= $pItem->isSeparator();
		}
	}

	public function getChildren()
	{
		return $this->tChildren;
	}

	public function hasChildren()
	{
		$ret = false;
		if (isset($this->tChildren)) {
			$ret = (false == empty($this->tChildren));
		}
		return $ret;
	}

	public function hasOnlySeparatorChildren()
	{
		return $this->_bOnlySeparatorChildren;
	}

	public function isActivePage()
	{
		$ret = false;
		if ($this->hasLink()) {
			$ret = $this->getLink()->isCurrentAction();
		}
		if ((false == $ret) && (isset($this->tChildren))) {
			foreach ($this->getChildren() as $child) {
				if ($child->hasLink()) {
					if ($child->getLink()->isCurrentModule()) {
						$ret = true;
						break;
					}
				}
			}
		}
		return $ret;
	}

	public function hasCurrentModule()
	{
		$ret = false;
		if ($this->hasLink()) {
			$ret = $this->getLink()->isCurrentModule();
		}
		if (false == $ret) {
			foreach ($this->getChildren() as $child) {
				if ($child->hasLink()) {
					if ($child->getLink()->isCurrentModule()) {
						$ret = true;
						break;
					}
				}
			}
		}
		return $ret;
	}

	public function buildHtmlLink()
	{
		$ret = '<a href="';
		$ret .= $this->getHref();
		$ret .= '"';
		if ($this->hasLink()) {
			$ret .= $this->getLink()->buildHtmlProperties();
		}
		$ret .= '>';
		if ($this->hasIcon()) {
			$ret .= '<i class="glyphicon ';
			$ret .= $this->getIcon();
			$ret .= ' with-text"></i>';
		}
		$ret .= $this->getLabel();
		$ret .= '</a>';
		return $ret;
	}
}
