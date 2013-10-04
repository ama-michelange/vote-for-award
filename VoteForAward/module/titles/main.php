<?php 
class module_titles extends abstract_module{

	public function before(){
		_root::getACL()->enable();
		plugin_vfa::loadI18n();

		$this->oLayout=new _layout('tpl_bs_bar_context');
		$this->oLayout->addModule('bsnavbar','bsnavbar::index');
		$this->oLayout->add('bsnav-left',plugin_vfa_menu::buildViewNavLeft());
		$this->oLayout->add('bsnav-top',plugin_vfa_menu::buildViewNavTopCrud());
	}

	public function _index(){
		//on considere que la page par defaut est la page de listage
		$this->_list();
	}

	public function _list(){
		// Force l'action pour n'avoir qu'un seul test dans le menu contextuel
		_root::getRequest()->setAction('list');

		$oTitleModel=new model_title;
		$tTitles=$oTitleModel->findAll();

		$oView=new _view('titles::list');
		$oView->tTitles=$tTitles;

		$this->oLayout->add('work',$oView);
	}

	public function _create(){
		$tMessage = null;
		$oTitleModel = new model_title;
		$tTitleDocs = null;

		$oTitle = $this->save();
		if (null == $oTitle) {
			$oTitle = new row_title;
		}
		else {
			$tMessage = $oTitle->getMessages();
			$tTitleDocs = plugin_vfa::copyValuesToKeys(_root::getParam('title_docs',null));
		}

		$oView = new _view('titles::edit');
		$oView->oTitle = $oTitle;
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);
		
		$oView->tMessage=$tMessage;
		$oView->textTitle = 'Créer un titre';

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();

		$this->oLayout->add('work',$oView);
	}

	public function _update(){
		$tMessage = null;
		$tTitleDocs = null;
		$oTitleModel = new model_title;

		$oTitle = $this->save();
		if (null == $oTitle) {
			$oTitle = $oTitleModel->findById( _root::getParam('id') );
			$tTitleDocs = $oTitle->getSelectDocs();
		}
		else {
			$tMessage = $oTitle->getMessages();
			$tTitleDocs = plugin_vfa::copyValuesToKeys(_root::getParam('title_docs',null));
		}

		$oView = new _view('titles::edit');
		$oView->oTitle = $oTitle;
		$oView->tSelectedDocs = plugin_vfa::buildOptionSelected(model_doc::getInstance()->getSelectRecent(), $tTitleDocs);
		$oView->tMessage=$tMessage;
		$oView->textTitle = 'Modifier un titre';

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();

		$this->oLayout->add('work',$oView);
	}

	public function _read(){
		$oView = new _view('titles::read');
		$oView->oViewShow=$this->buildViewShow();

		$this->oLayout->add('work',$oView);
	}

	public function buildViewShow(){
		$oTitleModel=new model_title;
		$oTitle=$oTitleModel->findById( _root::getParam('id') );
		$toDocs = $oTitle->findDocs();
		$toAwards = $oTitle->findAwards();

		$oView=new _view('titles::show');
		$oView->oTitle=$oTitle;
		$oView->toDocs = $toDocs;
		$oView->toAwards = $toAwards;

		return $oView;
	}

	public function _delete(){
		$tMessage=$this->delete();

		$oView = new _view('titles::delete');
		$oView->oViewShow = $this->buildViewShow();
		
		$oView->ok = true;
		if ($oView->oViewShow->toAwards) {
			$oView->ok = false;
		}

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;

		$this->oLayout->add('work',$oView);
	}

	public function save(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			$oTitle=new row_title;
			$oTitle->setMessages(array('token'=>$oPluginXsrf->getMessage()));
			return $oTitle;
		}

		$oTitleModel=new model_title;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oTitle=new row_title;
		}
		else {
			$oTitle=$oTitleModel->findById( _root::getParam('id',null) );
		}
		// Copie la saisie dans un enregistrement
		foreach($oTitleModel->getListColumn() as $sColumn){
			if( in_array($sColumn,$oTitleModel->getIdTab())){
				continue;
			}
			if (( _root::getParam($sColumn,null) == null ) && (null != $oTitle->$sColumn)) {
				$oTitle->$sColumn = null;
			}
			else {
				$oTitle->$sColumn = _root::getParam($sColumn, null);
			}
		}
		// Récupère les albums associés
		$tTitleDocs = _root::getParam('title_docs',null);
		if ($tTitleDocs){
			// Récupère les enregistrements des albums
			$tRowDoc = array();
			$oDocModel=new model_doc;
			foreach ($tTitleDocs as $idDoc){
				$tRowDoc[] = $oDocModel->findById($idDoc);
			}
			// Vérifie que tous les albums ont le même titre
			$sDocTitle = $tRowDoc[0]->title;
			$bOk = true;
			foreach ($tRowDoc as $oDoc){
				$bOk = $bOk && ($sDocTitle == $oDoc->title);
			}
			if ($bOk){
				// Force le titre avec le 1er enregistrement des albums
				$oTitle->title = plugin_vfa::formatTitle($tRowDoc);
				$oTitle->numbers = plugin_vfa::formatTitleNumbers($tRowDoc);
				// Champ de tri : déplace l'article à la fin du titre s'il existe
				$oTitle->order_title = plugin_vfa::pushArticle($oTitle->title).$oTitle->numbers;
				// Vérifie que le titre n'existe pas déjà
				if (false == $this->isDoublon($oTitleModel, $oTitle)) {
					// Sauvegarde si valide
					if($oTitle->isValid()){
						$oTitle->save();
						$oTitleModel->saveTitleDocs($oTitle->title_id, $tTitleDocs);
						_root::redirect('titles::list');
					}
				}
				else{
					$tMessage['title_docs'][] = 'isUnique';
					$oTitle->setMessages($tMessage);
				}
			}
			else{
				$tMessage['title_docs'][] = 'all-equals';
				$oTitle->setMessages($tMessage);
			}
		}
		else{
			$tMessage['title_docs'][] = 'required-selection';
			$oTitle->setMessages($tMessage);
		}
		return $oTitle;
	}

	/**
	 * Vérifie que le titre n'existe pas déjà
	 * @param model_title $poTitleModel
	 * @param row_title $poTitle
	 * @return boolean Vrai
	 */
	public function isDoublon($poTitleModel, $poTitle) {
		$bDoublon = true;
		$oTitleDoublon = $poTitleModel->findByTitleAndNumbers($poTitle->title,$poTitle->numbers);
		if ((null == $oTitleDoublon) || (true == $oTitleDoublon->isEmpty())) {
			$bDoublon = false;
		}
		else if ((null != $poTitle->getId()) && ($oTitleDoublon->getId() == $poTitle->getId())) {
			$bDoublon = false;
		}
		return $bDoublon;
	}

	public function delete(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}

		$oTitleModel=new model_title;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oTitle=$oTitleModel->findById( _root::getParam('id',null) );
			$oTitle->delete();
			$oTitleModel->deleteTitleDocs($iId);
		}
		_root::redirect('titles::list');
	}

	public function after(){
		$this->oLayout->show();
	}

}
