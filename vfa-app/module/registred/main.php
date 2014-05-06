<?php

class module_registred extends abstract_module
{

	public function before()
	{
		_root::getACL()->enable();
		plugin_vfa::loadI18n();
		$this->oLayout = new _layout('tpl_bs_bar_context');
	}

	public function after()
	{
		$this->oLayout->addModule('bsnavbar', 'bsnavbar::index');
		$this->oLayout->add('bsnav-top', plugin_BsContextBar::buildViewContextBar($this->buildContextBar()));
		$this->oLayout->show();
	}

	private function buildContextBar()
	{
		$navBar = plugin_BsHtml::buildNavBar();
		$navBar->setTitle('Inscrits', new NavLink('registred', 'index'));
		$navBar->addChild(new BarButtons('left'));
//		plugin_BsContextBar::buildDefaultContextBar($navBar);
		return $navBar;
	}

	public function _index()
	{
		// redirection vers la page par défaut
		_root::redirect('registred::list');
	}

	public function _list()
	{
		$oUserSession = _root::getAuth()->getUserSession();
		$oReaderGroup = $oUserSession->getReaderGroup();
		$oReaderAwards = $oUserSession->getValidReaderAwards();


		$tUsers = model_user::getInstance()->findAllByGroupIdByAwardId($oReaderGroup->group_id, $oReaderAwards[0]->award_id);

		$oView = new _view('registred::list');
		$oView->tUsers = $tUsers;

		$this->oLayout->add('work', $oView);
	}


	private function fillAwards($pView)
	{
		switch (_root::getAction()) {
			case 'board':
				$tAwards = model_award::getInstance()->findAllByType('PSBD');
				break;
			default:
				$tAwards = model_award::getInstance()->findAllByType('PBD');
				break;
		}
		$pView->countAwards = count($tAwards);
		if ($pView->countAwards == 0) {
			$tMessage = $pView->tMessage;
			switch (_root::getAction()) {
				case 'board':
					$tMessage['awards'][] = 'nonePSBD';
					break;
				default:
					$tMessage['awards'][] = 'nonePBD';
					break;
			}
			$pView->tMessage = $tMessage;
		} elseif ($pView->countAwards == 1) {
			$pView->oAward = $tAwards[0];
		} else {
			$tSelect = plugin_vfa::toSelect($tAwards, 'award_id', null, 'getTypeNameString');
			if ($pView->oRegistry->awards_ids) {
				$pView->oRegistry->awards_ids = array_flip($pView->oRegistry->awards_ids);
			}
			$pView->tSelectedAwards = plugin_vfa::buildOptionSelected($tSelect, $pView->oRegistry->awards_ids);
		}
	}

	private function fillGroups($pView)
	{
		switch (_root::getAction()) {
			case 'board':
				$tGroups = model_group::getInstance()->findAllByRoleName(plugin_vfa::TYPE_BOARD);
				break;
			case 'responsible':
				$tGroups = model_group::getInstance()->findAllByRoleName(plugin_vfa::TYPE_READER);
				break;
			default:
				$tGroups = _root::getAuth()->getUserSession()->getReaderGroups();
				break;
		}
		$pView->countGroups = count($tGroups);
		if ($pView->countGroups == 0) {
			$tMessage = $pView->tMessage;
			switch (_root::getAction()) {
				case 'board':
					$pView->tMessage['groups'][] = 'noneCS';
					break;
				default:
					$tMessage['groups'][] = 'none';
					break;
			}
			$pView->tMessage = $tMessage;
		} elseif ($pView->countGroups == 1) {
			$pView->oGroup = $tGroups[0];
		} else {
			$tSelect = plugin_vfa::toSelect($tGroups, 'group_id', 'group_name', null, true);
			$pView->tSelectedGroups = plugin_vfa::buildOptionSelected($tSelect, $pView->oRegistry->group_id);
		}
	}
}
