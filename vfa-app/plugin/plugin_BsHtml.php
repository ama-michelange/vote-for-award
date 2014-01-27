<?php

/**
 * @author ANTON-MA
 */
class plugin_BsHtml
{

	public static function buildNavBar()
	{
		return new NavBar();
	}

	public static function buildSeparator()
	{
		return new SeparatorItem();
	}

	public static function isSeparator($pItem)
	{
		$ret = false;
		if ('SeparatorItem' == get_class($pItem)) {
			$ret = true;
		}
		return $ret;
	}

	public static function buildMenuItem($pLabel, $pLink, $pIcon = null)
	{
		$ret = new MenuItem($pLabel, $pLink, $pIcon);
		if (false == $ret->isPermit()) {
			$ret = null;
		}
		return $ret;
	}

	public static function buildButtonItem($pLabel, $pLink, $pIcon = null, $pShowLabel = true)
	{
		$ret = new ButtonItem($pLabel, $pLink, $pIcon, $pShowLabel);
		if ((false == $ret->isPermit()) || (true == $ret->isCurrentPage())) {
			$ret = null;
		}
		return $ret;
	}

	public static function buildGroupedButtonItem($pLabel, $pLink, $pIcon = null, $pShowLabel = false)
	{
		$ret = new ButtonItem($pLabel, $pLink, $pIcon, $pShowLabel);
		$ret->setActive($ret->isCurrentPage());
		if (false == $ret->isPermit()) {
			$ret = null;
		}
		return $ret;
	}

	public static function buildBrandItem($pLabel, $pLink, $pIcon = null)
	{
		$ret = new LabelItem($pLabel, $pLink, $pIcon);
		$pLink->setProperties(array('class' => 'navbar-brand'));
		return $ret;
	}
}

class NavBar extends DefaultItem
{

	private $_oTitle;

	public function __construct($pName = null)
	{
		$name = $pName;
		if (null == $name) {
			$name = 'defaut';
		}
		parent::__construct($name);
	}

	public function setTitle($pLabel, $pLink, $pIcon = null)
	{
		$this->_oTitle = plugin_BsHtml::buildBrandItem($pLabel, $pLink, $pIcon);
	}

	public function toHtmlTitle()
	{
		return $this->_oTitle->toHtml();
	}
}

class Bar extends DefaultItem
{

	public function toHtml()
	{
		$ret = '<ul class="nav navbar-nav';
		if ('right' == $this->getName()) {
			$ret .= ' navbar-right';
		}
		$ret .= '">';
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $item) {
				$ret .= $item->toHtml();
			}
		}
		$ret .= '</ul>';
		return $ret;
	}
}

class MenuItem extends LabelItem
{

	public function toHtml()
	{
		$ret = '<li';
		if ($this->isActivePage()) {
			$ret .= ' class="active"';
		}
		$ret .= '>';
		$ret .= parent::toHtml();
		$ret .= '</li>';
		return $ret;
	}
}

class DropdownMenuItem extends LabelItem
{

	public function addChildSeparator()
	{
		if ($this->hasRealChildren()) {
			$this->addChild(plugin_BsHtml::buildSeparator());
		}
	}

	public function toHtml()
	{
		$ret = '<li class="dropdown';
		if ($this->isActivePage()) {
			$ret .= ' active';
		}
		$ret .= '">';
		$ret .= $this->toHtmlDropdownMenuToggle();
		$ret .= $this->toHtmlDropdownMenu();
		$ret .= '</li>';
		
		return $ret;
	}

	protected function toHtmlDropdownMenuToggle()
	{
		$ret = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
		$ret .= $this->toHtmlIconText();
		$ret .= ' <b class="caret"></b>';
		$ret .= '</a>';
		return $ret;
	}

	protected function toHtmlDropdownMenu()
	{
		$ret = '<ul class="dropdown-menu">';
		foreach ($this->getChildren() as $item) {
			if (plugin_BsHtml::isSeparator($item)) {
				$ret .= '<li class="divider"></li>';
			} else {
				$ret .= '<li>' . $item->toHtml() . '</li>';
			}
		}
		$ret .= '</ul>';
		return $ret;
	}
}

class BarButtons extends Bar
{

	public function toHtml()
	{
		$ret = '<div class="nav navbar-nav';
		if ('right' == $this->getName()) {
			$ret .= ' navbar-right';
		}
		$ret .= '">';
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $item) {
				$ret .= $item->toHtml();
				$ret .= '&nbsp;&nbsp;';
			}
		}
		$ret .= '</div>';
		return $ret;
	}
}

class ButtonItem extends LabelItem
{

	public function toHtml()
	{
		$ret = '<a class="btn btn-default btn-sm navbar-btn';
		if ($this->isActive()) {
			$ret .= ' active';
		}
		$ret .= '"';
		$ret .= ' href="';
		$ret .= $this->getHref();
		$ret .= '"';
		$ret .= $this->toHtmlProperties();
		$ret .= '>';
		$ret .= $this->toHtmlIconText();
		$ret .= '</a>';
		return $ret;
	}
}

class GroupButtonItem extends DefaultItem
{

	public function toHtml()
	{
		$ret = '<div class="btn-group">';
		$ret .= parent::toHtml();
		$ret .= '</div>';
		return $ret;
	}
}

/**
 *
 * @author ANTON-MA
 */
class DefaultItem
{

	private $_sName;

	private $_tChildren;

	private $_bActive = false;

	public function __construct($pName)
	{
		$this->_sName = $pName;
	}

	public function getName()
	{
		return $this->_sName;
	}

	public function addChild($pItem)
	{
		if (isset($pItem)) {
			if (false == isset($this->_tChildren)) {
				$this->_tChildren = array();
			}
			$this->_tChildren[$pItem->getName()] = $pItem;
		}
	}

	public function getChild($pName)
	{
		$ret = null;
		if (isset($this->_tChildren)) {
			$ret = $this->_tChildren[$pName];
		}
		return $ret;
	}

	public function getChildren()
	{
		return $this->_tChildren;
	}

	public function hasChildren()
	{
		$ret = false;
		if (isset($this->_tChildren)) {
			$ret = (false == empty($this->_tChildren));
		}
		return $ret;
	}

	public function hasRealChildren()
	{
		$ret = false;
		if (isset($this->_tChildren)) {
			foreach ($this->getChildren() as $item) {
				if (false == plugin_BsHtml::isSeparator($item)) {
					$ret = true;
					break;
				}
			}
		}
		return $ret;
	}

	public function isActive()
	{
		return $this->_bActive;
	}

	public function setActive($pActive)
	{
		$this->_bActive = $pActive;
	}

	public function hasLink()
	{
		return false;
	}

	public function toHtml()
	{
		$ret = '';
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $item) {
				$ret .= $item->toHtml();
			}
		}
		return $ret;
	}
}

/**
 *
 * @author ANTON-MA
 */
class SeparatorItem extends DefaultItem
{

	private static $_cpt = 0;

	public function __construct()
	{
		self::$_cpt ++;
		parent::__construct('__separator__' . self::$_cpt);
	}
}

/**
 *
 * @author ANTON-MA
 */
class ActionItem extends DefaultItem
{

	private $_oLink;

	public function __construct($pName, $pLink = null)
	{
		if (isset($pName)) {
			parent::__construct($pName);
		} else {
			parent::__construct($pLink->getName());
		}
		$this->_oLink = $pLink;
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

	public function isActivePage()
	{
		$ret = false;
		// _root::getLog()->log('AMA >>> isActivePage() for ' . $this->getName());
		if ($this->hasLink()) {
			$ret = $this->getLink()->isCurrentModule();
		}
		if ((false == $ret) && (true == $this->hasChildren())) {
			foreach ($this->getChildren() as $child) {
				// _root::getLog()->log('AMA >>> isActivePage() : $child = ' . $child->getName());
				// _root::getLog()->log('AMA >>> isActivePage() : $$child->hasLink() = ' . $child->hasLink());
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

	public function isCurrentPage()
	{
		$ret = false;
		if ($this->hasLink()) {
			$ret = $this->getLink()->isCurrentModule() && $this->getLink()->isCurrentAction();
		}
		return $ret;
	}
}

/**
 *
 * @author ANTON-MA
 */
class LabelItem extends ActionItem
{

	private $_sLabel;

	private $_bShowLabel;

	private $_sIcon;

	public function __construct($pLabel, $pLink = null, $pIcon = null, $pShowLabel = true)
	{
		parent::__construct($pLabel, $pLink);
		$this->_sLabel = $pLabel;
		$this->_sIcon = $pIcon;
		$this->_bShowLabel = $pShowLabel;
	}

	public function getLabel()
	{
		return $this->_sLabel;
	}

	public function isShowLabel()
	{
		return $this->_bShowLabel;
	}

	public function getIcon()
	{
		return $this->_sIcon;
	}

	public function hasIcon()
	{
		return isset($this->_sIcon);
	}

	public function toHtml()
	{
		$ret = '<a href="';
		$ret .= $this->getHref();
		$ret .= '"';
		$ret .= $this->toHtmlProperties();
		$ret .= '>';
		$ret .= $this->toHtmlIconText2();
		$ret .= '</a>';
		return $ret;
	}

	protected function toHtmlIconText()
	{
		$ret = '';
		if ($this->hasIcon()) {
			$ret .= '<i class="glyphicon ';
			$ret .= $this->getIcon();
			$ret .= ' with-text"></i>';
		}
		$ret .= $this->getLabel();
		return $ret;
	}

	protected function toHtmlIconText2()
	{
		$ret = '';
		if ($this->hasIcon()) {
			$ret .= '<i class="glyphicon ';
			$ret .= $this->getIcon();
			if ($this->isShowLabel()) {
				$ret .= ' with-text"';
			}
			$ret .= '></i>';
		}
		if (true==$this->isShowLabel()) {
			$ret .= $this->getLabel();
		}
		return $ret;
	}

	protected function toHtmlProperties()
	{
		$ret = '';
		if ($this->hasLink()) {
			$props = $this->getLink()->getProperties();
			if (isset($props)) {
				foreach ($props as $key => $value) {
					$ret .= ' ';
					$ret .= $key;
					$ret .= '="';
					$ret .= $value;
					$ret .= '"';
				}
			}
		}
		return $ret;
	}
}

/**
 *
 * @author ANTON-MA
 *        
 *        
 */
class NavLink extends Link
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

	public function getName()
	{
		return $this->getNav();
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

/**
 *
 * @author ANTON-MA
 *        
 *        
 */
class Link
{

	protected $_sLink;

	protected $_tProperties;

	public function __construct($pLink, $pProperties)
	{
		$this->_sLink = $pLink;
		$this->_tProperties = $pProperties;
	}

	public function getName()
	{
		return $this->_sLink;
	}

	public function getLink()
	{
		return $this->_sLink;
	}

	public function getProperties()
	{
		return $this->_tProperties;
	}

	public function setProperties($pProperties)
	{
		$this->_tProperties = $pProperties;
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

