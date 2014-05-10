<?php

/**
 * Class plugin_BsHtml
 */
class plugin_BsHtml
{
	/**
	 * @return NavBar
	 */
	public static function buildNavBar()
	{
		return new NavBar();
	}

	/**
	 * @return SeparatorItem
	 */
	public static function buildSeparator()
	{
		return new SeparatorItem();
	}

	/**
	 * @param $pItem
	 * @return bool
	 */
	public static function isSeparator($pItem)
	{
		$ret = false;
		if ('SeparatorItem' == get_class($pItem)) {
			$ret = true;
		}
		return $ret;
	}

	/**
	 * @param $pLabel
	 * @param null $pLink
	 * @param null $pIcon
	 * @param bool $pShowLabel
	 * @return DropdownMenuItem
	 */
	public static function buildDropdownActivable($pLabel, $pLink = null, $pIcon = null, $pShowLabel = true)
	{
		$ret = new DropdownMenuItem($pLabel, $pLink, $pIcon, $pShowLabel);
		$ret->setEnableActive(true);
		return $ret;
	}

	/**
	 * @param $pLabel
	 * @param $pLink
	 * @param null $pIcon
	 * @return MenuItem|null
	 */
	public static function buildMenuItemActivable($pLabel, $pLink, $pIcon = null)
	{
		$ret = self::buildMenuItem($pLabel, $pLink, $pIcon);
		if (null != $ret) {
			$ret->setEnableActive(true);
		}
		return $ret;
	}

	/**
	 * @param $pLabel
	 * @param null $pIcon
	 * @return MenuItem|null
	 */
	public static function buildMenuItemDisabled($pLabel, $pIcon = null)
	{
		$ret = new MenuItem($pLabel, null, $pIcon);
		$ret->setDisabled(true);
		return $ret;
	}

	/**
	 * @param $pLabel
	 * @param $pLink
	 * @param null $pIcon
	 * @return MenuItem|null
	 */
	public static function buildMenuItem($pLabel, $pLink, $pIcon = null)
	{
		$ret = new MenuItem($pLabel, $pLink, $pIcon);
		if (false == $ret->isPermit()) {
			$ret = null;
		}
		return $ret;
	}

	/**
	 * @param $pLabel
	 * @param $pLink
	 * @param null $pIcon
	 * @param bool $pShowLabel
	 * @return ButtonItem|null
	 */
	public static function buildButtonItem($pLabel, $pLink, $pIcon = null, $pShowLabel = true)
	{
		$ret = new ButtonItem($pLabel, $pLink, $pIcon, $pShowLabel);
		if ((false == $ret->isPermit()) || (true == $ret->isCurrentPage())) {
			$ret = null;
		}
		return $ret;
	}

	/**
	 * @param $pLabel
	 * @param $pLink
	 * @param null $pIcon
	 * @param bool $pShowLabel
	 * @return ButtonItem|null
	 */
	public static function buildGroupedButtonItem($pLabel, $pLink, $pIcon = null, $pShowLabel = false)
	{
		$ret = new ButtonItem($pLabel, $pLink, $pIcon, $pShowLabel);
		$ret->setActive($ret->isCurrentPage());
		if (false == $ret->isPermit()) {
			$ret = null;
		}
		return $ret;
	}

	/**
	 * @param string $pLabel
	 * @param Link $pLink
	 * @param string $pIcon
	 * @return LabelItem
	 */
	public static function buildBrandItem($pLabel, $pLink, $pIcon = null)
	{
		$ret = new LabelItem($pLabel, $pLink, $pIcon);
		$pLink->setProperties(array('class' => 'navbar-brand'));
		return $ret;
	}

	public static function showNavLabel($pLabel, $pLink, $pIcon = null)
	{
		$item = new LabelItem($pLabel, $pLink, $pIcon);
		if (false == $item->isPermit()) {
			$ret = $pLabel;
		} else {
			$ret = $item->toHtml();
		}
		return $ret;
	}

	/**
	 * @param $pImage
	 * @param $pTextAlt
	 * @param $pSize
	 * @param $pLink NavLink
	 * @param bool $pShowText
	 * @internal param $
	 * @return string
	 */
	public static function showNavImage($pImage, $pTextAlt, $pSize, $pLink, $pShowText = false)
	{
		$ret = '';
		$permit = $pLink->isPermit();
		if (isset($pImage)) {
			if ($permit) {
				$ret .= '<a href="';
				$ret .= $pLink->getLink();
				$ret .= '"';
				$ret .= '>';
			}
			$ret .= '<img class="';
			$ret .= $pSize;
			$ret .= '" src="';
			$ret .= $pImage;
			$ret .= '" alt="';
			$ret .= $pTextAlt;
			$ret .= '">';
			if ($permit) {
				$ret .= '</a>';
			}
		} else {
			$ret .= '<p class="' . $pSize . '">';
			if ($pShowText) {
				if ($permit) {
					$ret .= '<a href="';
					$ret .= $pLink->getLink();
					$ret .= '"';
					$ret .= '>';
				}
				$ret .= '<strong>';
				$ret .= $pTextAlt;
				$ret .= '</strong>';
				if ($permit) {
					$ret .= '</a>';
				}
				$ret .= '<br/>';
			}
			if ($permit) {
				$ret .= '<a href="';
				$ret .= $pLink->getLink();
				$ret .= '"';
				$ret .= '>';
			}
			$ret .= '<i class="glyphicon glyphicon-book"></i>';
			if ($permit) {
				$ret .= '</a>';
			}
			$ret .= '</p>';
		}
		return $ret;
	}
}

/**
 * Class NavBar
 */
class NavBar extends DefaultItem
{
	/**
	 * @var LabelItem Le titre de
	 */
	private $_oTitle;
	private $_sTitle;

	public function __construct($pName = null)
	{
		$name = $pName;
		if (null == $name) {
			$name = 'default';
		}
		parent::__construct($name);
	}

	public function setTitle($pLabel, $pLink = null, $pIcon = null)
	{
		if (null == $pLink) {
			$this->_sTitle = $pLabel;
		} else {
			$this->_oTitle = plugin_BsHtml::buildBrandItem($pLabel, $pLink, $pIcon);
		}
	}

	public function addTitle($pLabel, $pLink, $pIcon = null)
	{
		$this->_oTitle->addChild(plugin_BsHtml::buildBrandItem($pLabel, $pLink, $pIcon));
	}

	public function toHtmlTitle()
	{
		if ($this->_sTitle) {
			$ret = '<span class="navbar-brand">' . $this->_sTitle . '</span>';
		} else {
			$ret = $this->_oTitle->toHtml();
			if ($this->_oTitle->hasChildren()) {
				foreach ($this->_oTitle->getChildren() as $item) {
					$ret .= '<span class="navbar-brand">/</span>' . $item->toHtml();
				}
			}
		}
		return $ret;
	}
}

/**
 * Class Bar
 */
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

/**
 * Class Bar
 */
class BreadcrumbBar extends DefaultItem
{

	public function toHtml()
	{
		$ret = '<ul class="nav navbar-nav';
		if ('right' == $this->getName()) {
			$ret .= ' navbar-right';
		}
		$ret .= '"><li><ul class="breadcrumb">';
		if ($this->hasChildren()) {
			foreach ($this->getChildren() as $item) {
				$ret .= $item->toHtml();
			}
		}
		$ret .= '</ul></li></ul>';
		return $ret;
	}
}

/**
 * Class MenuItem
 */
class MenuItem extends LabelItem
{

	public function toHtml()
	{
		$ret = '<li';
		if ($this->isEnableActive() && $this->isActivePage()) {
			$ret .= ' class="active"';
		} elseif ($this->isDisabled()) {
			$ret .= ' class="disabled"';
		}
		$ret .= '>';
		$ret .= parent::toHtml();
		$ret .= '</li>';
		return $ret;
	}
}

/**
 * Class DropdownMenuItem
 */
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
		if ($this->isEnableActive() && $this->isActivePage()) {
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
		$ret .= '<b class="caret with-text"></b>';
		$ret .= '</a>';
		return $ret;
	}

	protected function toHtmlDropdownMenu()
	{
		$ret = '';
		if ($this->hasChildren()) {
			$ret .= '<ul class="dropdown-menu">';
			foreach ($this->getChildren() as $item) {
				if (plugin_BsHtml::isSeparator($item)) {
					$ret .= '<li class="divider"></li>';
				} else {
					$ret .= $item->toHtml();
				}
			}
			$ret .= '</ul>';
		}
		return $ret;
	}
}

/**
 * Class DropdownButtonItem
 */
class DropdownButtonItem extends DropdownMenuItem
{
	public function toHtml()
	{
		$ret = '<div class="btn-group">';
		$ret .= $this->toHtmlDropdownMenuToggle();
		$ret .= $this->toHtmlDropdownMenu();
		$ret .= '</div>';

		return $ret;
	}

	protected function toHtmlDropdownMenuToggle()
	{
		$ret = '<a href="#" class="btn btn-default btn-link btn-sm dropdown-toggle" data-toggle="dropdown">';
		$ret .= $this->toHtmlIconText();
		$ret .= '<b class="caret with-text"></b>';
		$ret .= '</a>';
		return $ret;
	}
}

/**
 * Class BarButtons
 */
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
				if (plugin_BsHtml::isSeparator($item)) {
					$ret .= '<span class="btn-separator"></span>';
				} else {
					$ret .= '<span class="btn-space"></span>';
					$ret .= $item->toHtml();
				}
			}
		}
		$ret .= '</div>';
		return $ret;
	}
}

/**
 * Class ButtonItem
 */
class ButtonItem extends LabelItem
{
	public function __construct($pLabel, $pLink = null, $pIcon = null, $pShowLabel = true)
	{
		parent::__construct($pLabel, $pLink, $pIcon, $pShowLabel);
	}

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

/**
 * Class GroupButtonItem
 */
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
 * Class DefaultItem
 */
abstract class DefaultItem
{
	/** @var  string */
	private $_sName;
	/** @var DefaultItem[] */
	private $_tChildren;
	/** @var bool */
	private $_bActive = false;

	public function __construct($pName)
	{
		$this->_sName = $pName;
	}

	/**
	 * @param DefaultItem $pItem
	 */
	public function addChild($pItem)
	{
		if (isset($pItem)) {
			if (false == isset($this->_tChildren)) {
				$this->_tChildren = array();
			}
			$this->_tChildren[$pItem->getName()] = $pItem;
		}
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_sName;
	}

	/**
	 * @param string $pName
	 */
	public function setName($pName)
	{
		$this->_sName = $pName;
	}

	public function getChild($pName)
	{
		$ret = null;
		if (isset($this->_tChildren)) {
			$ret = $this->_tChildren[$pName];
		}
		return $ret;
	}

	/**
	 * @return bool
	 */
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
			if ($ret) {
				$this->deleteDoublonSeparatorChildren();
			}
		}
		return $ret;
	}

	public function deleteDoublonSeparatorChildren()
	{
		if (isset($this->_tChildren)) {
			// Supprime les séparateurs avant le 1er terme
			$end = false;
			while (false == $end && count($this->_tChildren) > 0) {
				$item = reset($this->_tChildren);
				if (true == plugin_BsHtml::isSeparator($item)) {
					array_shift($this->_tChildren);
				} else {
					$end = true;
				}
			}
			// Supprime les séparateurs après le dernier terme
			$end = false;
			while (false == $end && count($this->_tChildren) > 0) {
				$item = end($this->_tChildren);
				if (true == plugin_BsHtml::isSeparator($item)) {
					array_pop($this->_tChildren);
				} else {
					$end = true;
				}
			}
			// Cherche et note les séparateurs cote à cote
			$t = array();
			foreach ($this->_tChildren as $key => $item) {
				if ((true == plugin_BsHtml::isSeparator($item))) {
					$t[] = $key;
				} else {
					$t[] = false;
				}
			}
			// Supprime les séparateurs cote à cote de trop
			$size = count($t);
			for ($i = 0; $i < $size; $i++) {
				if ($i > 0) {
					if ((false != $t[$i - 1]) && (false != $t[$i])) {
						unset($this->_tChildren[$t[$i]]);
					}
				}
			}
		}
		return;
	}

	/**
	 * @return DefaultItem[]
	 */
	public function getChildren()
	{
		return $this->_tChildren;
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

	/**
	 * @return bool
	 */
	public function hasChildren()
	{
		$ret = false;
		if (isset($this->_tChildren)) {
			$ret = (false == empty($this->_tChildren));
		}
		return $ret;
	}
}

/**
 * Class SeparatorItem
 */
class SeparatorItem extends DefaultItem
{

	private static $_cpt = 0;

	public function __construct()
	{
		self::$_cpt++;
		parent::__construct('__separator__' . self::$_cpt);
	}
}

/**
 * Class ActionItem
 */
class ActionItem extends DefaultItem
{
	/** @var Link */
	private $_oLink;

	private $_enableActive = false;
	private $_disabled = false;

	/**
	 * @param string $pName
	 * @param Link $pLink
	 */
	public function __construct($pName, $pLink = null)
	{
		if (isset($pName)) {
			parent::__construct($pName);
		} else {
			parent::__construct($pLink->getName());
		}
		$this->_oLink = $pLink;
	}

	/**
	 * @return string
	 */
	public function getHref()
	{
		$ret = '#';
		if (isset($this->_oLink)) {
			$ret = $this->_oLink->getLink();
		}
		return $ret;
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

	public function setEnableActive($pEnableActive)
	{
		$this->_enableActive = $pEnableActive;
	}

	public function isEnableActive()
	{
		return $this->_enableActive;
	}

	public function setDisabled($pDisabled)
	{
		$this->_disabled = $pDisabled;
	}

	public function isDisabled()
	{
		return $this->_disabled;
	}

	private function isCrudAction($pAction)
	{
		switch ($pAction) {
			case 'create':
			case 'update':
			case 'read':
			case 'delete':
				$ret = true;
				break;
			default:
				$ret = false;
		}
		return $ret;
	}

	/**
	 * @return bool
	 */
	public function hasLink()
	{
		return isset($this->_oLink);
	}

	/**
	 * @return Link|null
	 */
	public function getLink()
	{
		return $this->_oLink;
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
 * Class LabelItem
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

	public function toHtml()
	{
		$ret = '<a href="';
		$ret .= $this->getHref();
		$ret .= '"';
		$ret .= $this->toHtmlProperties();
		$ret .= '>';
		$ret .= $this->toHtmlIconText();
		$ret .= '</a>';
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

	protected function toHtmlIconText()
	{
		$ret = '';
		if ($this->hasIcon()) {
			$ret .= '<i class="glyphicon ';
			$ret .= $this->getIcon();
			if ($this->isShowLabel()) {
				$ret .= ' with-text';
			}
			$ret .= '"></i>';
		}
		if (true == $this->isShowLabel()) {
			$ret .= $this->getLabel();
		}
		return $ret;
	}

	public function hasIcon()
	{
		return isset($this->_sIcon);
	}

	public function getIcon()
	{
		return $this->_sIcon;
	}

	public function isShowLabel()
	{
		return $this->_bShowLabel;
	}

	public function getLabel()
	{
		return $this->_sLabel;
	}

	public function setLabel($pLabel)
	{
		$this->_sLabel = $pLabel;
	}
}

/**
 * @author ANTON-MA
 */
class NavLink extends Link
{
	protected $_sAction;
	protected $_sModule;
	protected $_tParams;
	protected $_forcePermit = false;

	public function __construct($pModule, $pAction, $ptParams = null, $pForcePermit = false)
	{
		$this->_sModule = $pModule;
		$this->_sAction = $pAction;
		$this->_tParams = $ptParams;
		$this->_sLink = _root::getLink($this->getNav(), $this->_tParams, false);
		$this->_forcePermit = $pForcePermit;
	}

	public function getNav()
	{
		return $this->_sModule . '::' . $this->_sAction;
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

	public function isPermit()
	{
		$permit = $this->_forcePermit;
		if (!$permit) {
			$permit = _root::getACL()->permit($this->getNav());
		}
		return $permit;
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
 * @author ANTON-MA
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

