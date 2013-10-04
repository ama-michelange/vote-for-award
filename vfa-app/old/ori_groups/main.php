<?php 
class module_groups extends abstract_module{
	
	public function before(){
		_root::getAuth()->enable();
		$this->oLayout=new _layout('template_default');
		$this->oLayout->addModule('authent','authent::index');
		$this->oLayout->addModule('menu','menu::index');
	}
	
	
	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}
	
	public function _list(){
		
		$oGroupModel=new model_group;
		$tGroups=$oGroupModel->findAll();
		
		$oView=new _view('groups::list');
		$oView->tGroups=$tGroups;
		$oView->tColumn=$oGroupModel->getListColumn();//array('id','titre');//
		
		$this->oLayout->add('work',$oView);
	}
	

	public function _new(){
		$tMessage=$this->save();
	
		$oGroupModel=new model_group;
		$oGroup=new row_group;
		
		$oView=new _view('groups::new');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
				
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
	}
	
	
	public function _edit(){
		$tMessage=$this->save();
		
		$oGroupModel=new model_group;
		$oGroup=$oGroupModel->findById( _root::getParam('id') );
		
		$oView=new _view('groups::edit');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
	}

	public function _show(){
		$oGroupModel=new model_group;
		$oGroup=$oGroupModel->findById( _root::getParam('id') );
		
		$oView=new _view('groups::show');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
		$this->oLayout->add('work',$oView);
	}
	
	public function _delete(){
		$tMessage=$this->delete();

		$oGroupModel=new model_group;
		$oGroup=$oGroupModel->findById( _root::getParam('id') );
		
		$oView=new _view('groups::delete');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
		

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('work',$oView);
	}

	public function save(){
		if(!_root::getRequest()->isPost() ){ 
			//si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oGroupModel=new model_group;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oGroup=new row_group;	
		}else{
			$oGroup=$oGroupModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oGroupModel->getListColumn() as $sColumn){
			 if(isset($_FILES[$sColumn])){
				$sNewFileName='data/upload/'.$sColumn.'_'.date('Ymdhis');

				$oPluginUpload=new plugin_upload($_FILES[$sColumn]);
				$oPluginUpload->saveAs($sNewFileName);
				$oExamplemodule->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) ==null ){ 
				continue;
			}else if( in_array($sColumn,$oGroupModel->getIdTab())){
				 continue;
			}
			
			$oGroup->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oGroup->isValid()){

			$oGroup->save();
			
			_root::redirect('groups::list');
		}else{
			return $oGroup->getListError();
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
	
		$oGroupModel=new model_group;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oGroup=$oGroupModel->findById( _root::getParam('id',null) );
		}
			
		$oGroup->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('groups::list');
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}

/*variables
#select		$oView->tJoinvfa_groups=groups::getInstance()->getSelect();#fin_select
#uploadsave if(isset($_FILES[$sColumn])){
				$sNewFileName='data/upload/'.$sColumn.'_'.date('Ymdhis');

				$oPluginUpload=new plugin_upload($_FILES[$sColumn]);
				$oPluginUpload->saveAs($sNewFileName);
				$oGroup->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave
variables*/

