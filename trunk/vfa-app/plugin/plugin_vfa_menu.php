<?php
/*
 *
 */
/**
 *
 * @author AMA
 */
class plugin_vfa_menu
{

	public static function buildViewNavTop()
	{
		$oView = new _view('bsnavcontext::nav-top');
		$oView->tTitles = self::buildTopTitle();
		return $oView;
	}

	public static function buildViewNavTopCrud($pDataFlags = NULL)
	{
		$oView = new _view('bsnavcontext::nav-top');
		$oView->tLink = self::buildContextNavLinks($pDataFlags);
		$oView->tButtonGroup = self::buildContextNavButtonGroup();
		$oView->tTitles = self::buildTopTitle();
		return $oView;
	}

	public static function buildViewNavLeft()
	{
		$oView = new _view('bsnavcontext::nav-left');
		$oView->bActiveByModule = false;
		switch (_root::getModule()) {
			case 'awards':
			case 'docs':
			case 'groups':
			case 'nominees':
			case 'titles':
			case 'users':
				$oView->sTitle = 'Administration';
				$oView->tLink = self::buildItemsAdmin();
				$oView->bActiveByModule = true;
				break;
			case 'invitations':
				$oView->sTitle = 'Inscription';
				$oView->tLink = self::buildItemsInscription();
				break;
			default:
				$sTitle = _root::getModule();
				break;
		}
		return $oView;
	}

	public static function buildContextNavLinks($pDataFlags = NULL)
	{
		$acl = _root::getACL();
		$sModule = _root::getModule();
		$tLink = array();
		switch ($sModule) {
			case 'nominees':
				if ('listAwards' != _root::getAction()) {
					if (false === strpos(_root::getAction(), 'list')) {
						if ($acl->permit($sModule . '::list')) {
							$tLink['Liste'] = array(
								$sModule . '::list' . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-list'
							);
						}
					}
					if ($acl->permit($sModule . '::create')) {
						$tLink['Sélectionner'] = array(
							$sModule . '::create' . '&idAward=' . _root::getParam('idAward'),
							'glyphicon glyphicon-heart'
						);
					}
				}
				switch (_root::getAction()) {
					case 'read':
					case 'update':
					case 'delete':
						if ($acl->permit($sModule . '::read')) {
							$tLink['Détail'] = array(
								$sModule . '::read' . '&id=' . _root::getParam('id') . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-eye-open'
							);
						}
						if ($acl->permit($sModule . '::update')) {
							$tLink['Modifier'] = array(
								$sModule . '::update' . '&id=' . _root::getParam('id') . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-edit'
							);
						}
						if ($acl->permit($sModule . '::delete')) {
							$tLink['Supprimer'] = array(
								$sModule . '::delete' . '&id=' . _root::getParam('id') . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-trash'
							);
						}
						break;
				}
				$tLink[] = "_separator_";
				if ($acl->permit('awards::read')) {
					$tLink['Prix'] = array(
						'awards::read' . '&id=' . _root::getParam('idAward'),
						'glyphicon glyphicon-eye-open'
					);
				}
				break;
			case 'users':
				if ($acl->permit($sModule . '::listByGroup')) {
					$tLink['Liste par groupe'] = array(
						$sModule . '::listByGroup',
						'glyphicon glyphicon-list'
					);
				}
				$tLink = array_merge($tLink, self::buildDefaultContextNavLinks());
				break;
			case 'awards':
				$tLink = array_merge($tLink, self::buildDefaultContextNavLinks());
				if ($pDataFlags && isset($pDataFlags['titles'])) {
					$tLink[] = "_separator_";
					if (true == $pDataFlags['titles'] && $acl->permit('nominees::list')) {
						$tLink['Titres sélectionnés'] = array(
							'nominees::list' . '&idAward=' . _root::getParam('id'),
							'glyphicon glyphicon-list'
						);
					}
					if (false == $pDataFlags['titles'] && $acl->permit('nominees::create')) {
						$tLink['Sélectionner un titre'] = array(
							'nominees::create' . '&idAward=' . _root::getParam('id'),
							'glyphicon glyphicon-heart'
						);
					}
				}
				break;
			default:
				$tLink = array_merge($tLink, self::buildDefaultContextNavLinks());
		}
		return $tLink;
	}

	private static function buildDefaultContextNavLinks()
	{
		$acl = _root::getACL();
		$sModule = _root::getModule();
		$tLink = array();
		// Vue par défaut
		if ($acl->permit($sModule . '::list')) {
			$tLink['Liste'] = array(
				$sModule . '::list',
				'glyphicon glyphicon-list'
			);
		}
		if ($acl->permit($sModule . '::create')) {
			$tLink['Créer'] = array(
				$sModule . '::create',
				'glyphicon glyphicon-plus-sign'
			);
		}
		switch (_root::getAction()) {
			case 'read':
			case 'update':
			case 'delete':
				if ($acl->permit($sModule . '::read')) {
					$tLink['Détail'] = array(
						$sModule . '::read' . '&id=' . _root::getParam('id'),
						'glyphicon glyphicon-eye-open'
					);
				}
				if ($acl->permit($sModule . '::update')) {
					$tLink['Modifier'] = array(
						$sModule . '::update' . '&id=' . _root::getParam('id'),
						'glyphicon glyphicon-edit'
					);
				}
				if ($acl->permit($sModule . '::delete')) {
					$tLink['Supprimer'] = array(
						$sModule . '::delete' . '&id=' . _root::getParam('id'),
						'glyphicon glyphicon-trash'
					);
				}
				break;
		}
		return $tLink;
	}

	public static function buildContextNavButtonGroup()
	{
		$acl = _root::getACL();
		$sModule = _root::getModule();
		$tButtonGroup = array();
		switch ($sModule) {
			case 'nominees':
				switch (_root::getAction()) {
					case 'list':
					case 'listThumbnail':
					case 'listThumbnailLarge':
						if ($acl->permit($sModule . '::list')) {
							$tButtonGroup['Liste'] = array(
								$sModule . '::list' . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-list'
							);
						}
						if ($acl->permit($sModule . '::listThumbnail')) {
							$tButtonGroup['Vignettes'] = array(
								$sModule . '::listThumbnail' . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-th'
							);
						}
						if ($acl->permit($sModule . '::listThumbnailLarge')) {
							$tButtonGroup['Vignettes Larges'] = array(
								$sModule . '::listThumbnailLarge' . '&idAward=' . _root::getParam('idAward'),
								'glyphicon glyphicon-th-large'
							);
						}
						break;
				}
				break;
			case 'docs':
				switch (_root::getAction()) {
					case 'list':
					case 'listThumbnail':
					case 'listThumbnailLarge':
						if ($acl->permit($sModule . '::list')) {
							$tButtonGroup['Liste'] = array(
								$sModule . '::list',
								'glyphicon glyphicon-list'
							);
						}
						if ($acl->permit($sModule . '::listThumbnail')) {
							$tButtonGroup['Vignettes'] = array(
								$sModule . '::listThumbnail',
								'glyphicon glyphicon-th'
							);
						}
						if ($acl->permit($sModule . '::listThumbnailLarge')) {
							$tButtonGroup['Vignettes Larges'] = array(
								$sModule . '::listThumbnailLarge',
								'glyphicon glyphicon-th-large'
							);
						}
						break;
				}
				break;
			default:
				break;
		}
		return $tButtonGroup;
	}

	public static function buildNavBarMenu()
	{
		$tLink = array(
			'Accueil' => 'default::index'
		);
		
		if (_root::getAuth()->isConnected()) {
			$tLink['Accueil'] = 'home_enable::index';
			$tLink['Vote'] = 'home_enable::index';
			
			$tLink['Prix'] = 'results::index';
			if (_root::getACL()->isInRole('admin_group')) {
				$tLink['Prix'] = array();
				$tLink['Prix']['Résultats'] = 'results::index';
				$tLink['Prix']['sep1'] = 'separator';
				$tLink['Prix']['Prix'] = 'awards::list';
				$tLink['Prix']['Titres'] = 'titles::list';
				$tLink['Prix']['Albums'] = 'docs::list';
				$tLink['Prix']['sep2'] = 'separator';
				$tLink['Prix']['Toto - Titi'] = 'docs::list';
			}
			
			if (_root::getACL()->permit(array(
				'invitations'
			))) {
				$tLink['Inscription'] = self::buildItemsInscription();
			}
			if (_root::getACL()->permit(array(
				'awards',
				'nominees',
				'docs',
				'groups',
				'users'
			))) {
				$tLink['Administration'] = self::buildItemsAdmin();
			}
		}
		return $tLink;
	}

	public static function buildItemsAdmin()
	{
		$acl = _root::getACL();
		$tLink = array();
		if ($acl->permit('docs')) {
			$tLink['Albums'] = 'docs::index';
		}
		if ($acl->permit('titles')) {
			$tLink['Titres'] = 'titles::index';
		}
		if ($acl->permit('awards')) {
			$tLink['Prix'] = 'awards::index';
		}
		if ($acl->permit('nominees')) {
			$tLink['Titres sélectionnés'] = 'nominees::index';
		}
		if (count($tLink) > 0) {
			$tLink['sep1'] = 'separator';
		}
		if ($acl->permit('groups')) {
			$tLink['Groupes'] = 'groups::index';
		}
		if ($acl->permit('users')) {
			$tLink['Utilisateurs'] = 'users::index';
		}
		if ($acl->permit('roles')) {
			$tLink['Roles'] = 'roles::index';
		}
		return $tLink;
	}

	public static function buildItemsInscription()
	{
		$acl = _root::getACL();
		$tLink = array();
		if ($acl->permit('invitations::list')) {
			$tLink['Invitations'] = 'invitations::list';
		}
		if ($acl->permit('invitations::reader')) {
			$tLink['Invitation aux lecteurs'] = 'invitations::reader';
		}
		if ($acl->permit('invitations::free')) {
			$tLink['Inscriptions libres'] = 'invitations::free';
		}
		if (count($tLink) > 0) {
			$tLink['sep1'] = 'separator';
		}
		if ($acl->permit('invitations::responsible')) {
			$tLink['Responsable de groupe'] = 'invitations::responsible';
		}
		if ($acl->permit('invitations::board')) {
			$tLink['Membre du comité'] = 'invitations::board';
		}
		return $tLink;
	}

	public static function buildTopTitle()
	{
		$tTitle = array();
		switch (_root::getModule()) {
			case 'awards':
				$tTitle[] = 'Prix';
				$tTitle[] = 'awards::index';
				break;
			case 'docs':
				$tTitle[] = 'Albums';
				$tTitle[] = 'docs::index';
				break;
			case 'groups':
				$tTitle[] = 'Groupes';
				$tTitle[] = 'groups::index';
				break;
			case 'nominees':
				$tTitle[] = 'Titres sélectionnés';
				$tTitle[] = 'nominees::index' . '&idAward=' . _root::getParam('idAward');
				break;
			case 'users':
				$tTitle[] = 'Utilisateurs';
				$tTitle[] = 'users::index';
				break;
			case 'roles':
				$tTitle[] = 'Rôles';
				$tTitle[] = 'roles::index';
				break;
			case 'invitations':
				switch (_root::getAction()) {
					case 'list':
						$tTitle[] = 'Invitations';
						break;
					case 'reader':
						$tTitle[] = 'Invitation pour inscrire un Lecteur';
						break;
					case 'free':
						$tTitle[] = 'Electeur libre';
						break;
					case 'responsible':
						$tTitle[] = 'Invitation pour inscrire un Responsable de groupe';
						break;
					case 'board':
						$tTitle[] = 'Invitation pour inscrire un Membre du comité de sélection';
						break;
					default:
						$tTitle[] = _root::getModule() . '::' . _root::getAction();
						break;
				}
				$tTitle[] = _root::getModule() . '::' . _root::getAction();
				break;
			default:
				$tTitle[] = _root::getModule();
				$tTitle[] = _root::getModule() . '::' . _root::getAction();
				break;
		}
		return $tTitle;
	}
}
