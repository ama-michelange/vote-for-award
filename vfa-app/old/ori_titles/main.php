<?php 
class module_titles extends abstract_module{
	
	public function before(){
		_root::getAuth()->enable();
		$this->oLayout=new _layout('tpl_bs_bar');
		$this->oLayout->addModule('authent','authent::index');
		$this->oLayout->addModule('menu','menu::index');
	}
	
	
	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}
	
	public function _list(){
		$oTitleModel=new model_title;
		$tTitles=$oTitleModel->findAll();
		
		$oView=new _view('titles::list');
		$oView->tTitles=$tTitles;
		$oView->tColumn=$oTitleModel->getListColumn();//array('id','titre');//
		
		$this->oLayout->add('work',$oView);
	}
	

	public function _new(){
		$tMessage=$this->save();
	
		$oTitleModel=new model_title;
		$oTitle=new row_title;
		
		$oView=new _view('titles::new');
		$oView->oTitle=$oTitle;
		$oView->tColumn=$oTitleModel->getListColumn();
		$oView->tId=$oTitleModel->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
	}
	
	
	public function _edit(){
		$tMessage=$this->save();
		
		$oTitleModel=new model_title;
		$oTitle=$oTitleModel->findById( _root::getParam('id') );
		
		$oView=new _view('titles::edit');
		$oView->oTitle=$oTitle;
		$oView->tColumn=$oTitleModel->getListColumn();
		$oView->tId=$oTitleModel->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
	}

	public function _show(){
		$oTitleModel=new model_title;
		$oTitle=$oTitleModel->findById( _root::getParam('id') );
		
		$oView=new _view('titles::show');
		$oView->oTitle=$oTitle;
		$oView->tColumn=$oTitleModel->getListColumn();
		$oView->tId=$oTitleModel->getIdTab();
		
		
		$this->oLayout->add('work',$oView);
	}
	
	public function _delete(){
		$tMessage=$this->delete();

		$oTitleModel=new model_title;
		$oTitle=$oTitleModel->findById( _root::getParam('id') );
		
		$oView=new _view('titles::delete');
		$oView->oTitle=$oTitle;
		$oView->tColumn=$oTitleModel->getListColumn();
		$oView->tId=$oTitleModel->getIdTab();
		
		

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
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oTitleModel=new model_title;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oTitle=new row_title;	
		}else{
			$oTitle=$oTitleModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oTitleModel->getListColumn() as $sColumn){
			 if(isset($_FILES[$sColumn])){
				$sNewFileName='data/upload/'.$sColumn.'_'.date('Ymdhis');

				$oPluginUpload=new plugin_upload($_FILES[$sColumn]);
				$oPluginUpload->saveAs($sNewFileName);
				$oExamplemodule->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) ==null ){ 
				continue;
			}else if( in_array($sColumn,$oTitleModel->getIdTab())){
				 continue;
			}
			
			$oTitle->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oTitle->isValid()){

			$oTitle->save();
			//une fois enregistre on redirige (vers la page d'edition)
			_root::redirect('titles::list');
		}else{
			return $oTitle->getListError();
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
	
		$oTitleModel=new model_title;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oTitle=$oTitleModel->findById( _root::getParam('id',null) );
		}
			
		$oTitle->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('titles::list');
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}

/*variables
#select		$oView->tJoinvfa_titles=titles::getInstance()->getSelect();#fin_select
#uploadsave if(isset($_FILES[$sColumn])){
				$sNewFileName='data/upload/'.$sColumn.'_'.date('Ymdhis');

				$oPluginUpload=new plugin_upload($_FILES[$sColumn]);
				$oPluginUpload->saveAs($sNewFileName);
				$oTitle->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave
variables*/

