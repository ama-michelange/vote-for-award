<?php 
class module_awards extends abstract_module{
	
	public function before(){
		_root::getAuth()->enable();
		$this->oLayout=new _layout('tpl_bs_bar');
		$this->oLayout->addModule('bsnavbar','bsnavbar::index');
	}
	
	
	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}
	
	public function _list(){
		
		$oAwardModel=new model_award;
		$tAwards=$oAwardModel->findAll();
		
		$oView=new _view('awards::list');
		$oView->tAwards=$tAwards;
		$oView->tColumn=$oAwardModel->getListColumn();//array('id','titre');//

		$this->oLayout->add('work',$oView);
	}
	

	public function _new(){
		$tMessage=$this->save();
	
		$oAwardModel=new model_award;
		$oAward=new row_award;
		
		$oView=new _view('awards::new');
		$oView->oAward=$oAward;
		$oView->tColumn=$oAwardModel->getListColumn();
		$oView->tId=$oAwardModel->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
		// Liste complete
		$this->_list();
	}
	
	
	public function _edit(){
		$tMessage=$this->save();
		
		$oAwardModel=new model_award;
		$oAward=$oAwardModel->findById( _root::getParam('id') );
		
		$oView=new _view('awards::edit');
		$oView->oAward=$oAward;
		$oView->tColumn=$oAwardModel->getListColumn();
		$oView->tId=$oAwardModel->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
		// Liste complete
		$this->_list();
	}

	public function _show(){
		$oAwardModel=new model_award;
		$oAward=$oAwardModel->findById( _root::getParam('id') );
		
		$oView=new _view('awards::show');
		$oView->oAward=$oAward;
		$oView->tColumn=$oAwardModel->getListColumn();
		$oView->tId=$oAwardModel->getIdTab();
		
		
		$this->oLayout->add('work',$oView);
		// Liste complete
		$this->_list();
	}
	
	public function _delete(){
		$tMessage=$this->delete();

		$oAwardModel=new model_award;
		$oAward=$oAwardModel->findById( _root::getParam('id') );
		
		$oView=new _view('awards::delete');
		$oView->oAward=$oAward;
		$oView->tColumn=$oAwardModel->getListColumn();
		$oView->tId=$oAwardModel->getIdTab();
		
		

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
		// Liste complete
		$this->_list();
	}

	public function save(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oAwardModel=new model_award;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oAward=new row_award;	
		}else{
			$oAward=$oAwardModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oAwardModel->getListColumn() as $sColumn){
			 if(isset($_FILES[$sColumn])){
				$sNewFileName='data/upload/'.$sColumn.'_'.date('Ymdhis');

				$oPluginUpload=new plugin_upload($_FILES[$sColumn]);
				$oPluginUpload->saveAs($sNewFileName);
				$oExamplemodule->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) ==null ){ 
				continue;
			}else if( in_array($sColumn,$oAwardModel->getIdTab())){
				 continue;
			}
			
			$oAward->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oAward->isValid()){

			$oAward->save();
			//une fois enregistre on redirige (vers la page d'edition)
			_root::redirect('awards::list');
		}else{
			return $oAward->getListError();
		}
		
	}

	public function delete(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oAwardModel=new model_award;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oAward=$oAwardModel->findById( _root::getParam('id',null) );
		}
			
		$oAward->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('awards::list');
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}

/*variables
#select		$oView->tJoinawards=awards::getInstance()->getSelect();#fin_select
#uploadsave if(isset($_FILES[$sColumn])){
				$sNewFileName='data/upload/'.$sColumn.'_'.date('Ymdhis');

				$oPluginUpload=new plugin_upload($_FILES[$sColumn]);
				$oPluginUpload->saveAs($sNewFileName);
				$oAward->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave
variables*/

