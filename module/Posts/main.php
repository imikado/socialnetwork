<?php 
class module_Posts extends abstract_moduleembedded{
	
	public static $sModuleName='Posts';
	public static $sRootModule;
	public static $tRootParams;
	
	public function __construct(){
		self::setRootLink(_root::getParamNav(),null);
	}
	public static function setRootLink($sRootModule,$tRootParams=null){
		self::$sRootModule=$sRootModule;
		self::$tRootParams=$tRootParams;
	}
	public static function getLink($sAction,$tParam=null){
		return parent::_getLink(self::$sRootModule,self::$tRootParams,self::$sModuleName,$sAction,$tParam);
	}
	public static function getParam($sVar,$uDefault=null){
		return parent::_getParam(self::$sModuleName,$sVar,$uDefault);
	}
	public static function redirect($sModuleAction,$tModuleParam=null){
		return parent::_redirect(self::$sRootModule,self::$tRootParams,self::$sModuleName,$sModuleAction,$tModuleParam);
	}
	
	private $user_id;
	public function setUserId($user_id){
	   $this->user_id=(int)$user_id;
	}
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_Posts();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleExamplemodule->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleExamplemodule->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	
	public function _index(){
		$sAction='_'.self::getParam('Action','list');
		return $this->$sAction();
	}
	
	public function _list(){
      
	   $tPosts=model_Posts::getInstance()->findListByUser($this->user_id);
		  
	   $oView=new _view('Posts::list');
	   $oView->tPosts=$tPosts;
	   $oView->tIndexdUsers=model_Users::getInstance()->getListIndexed();

	   return $oView;
	}
	
	
	public function _new(){
	   $tMessage=$this->processSave();
	   $oPosts=new row_Posts;
		  
	   $oView=new _view('Posts::new');
	   $oView->oPosts=$oPosts;
		  
	   $oView->tGroups=model_Groups::getInstance()->findListByUser( $this->user_id);
		  
	   $oPluginXsrf=new plugin_xsrf();
	   $oView->token=$oPluginXsrf->getToken();
	   $oView->tMessage=$tMessage;
		  
	   return $oView;
	}

	
	
	
	
	
	
	

	

	public function processSave(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$iId=module_Posts::getParam('id',null);
		if($iId==null){
			$oPosts=new row_Posts;	
		}else{
			$oPosts=model_Posts::getInstance()->findById( module_Posts::getParam('id',null) );
		}
		
		$tId=model_Posts::getInstance()->getIdTab();
		$tColumn=model_Posts::getInstance()->getListColumn();
		foreach($tColumn as $sColumn){
			 $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oPosts->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) === null ){ 
				continue;
			}else if( in_array($sColumn,$tId)){
				 continue;
			}
			
			$oPosts->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		$oPosts->date=date('Y-m-d');
		$oPosts->time=date('H:i:s');
		$oPosts->user_id=$this->user_id;
		if($oPosts->save()){
		   //partage du post avec les groups
		   model_Share::getInstance()->shareUserPostWithGroups($this->user_id,$oPosts->id,_root::getParam('tGroup_id'));
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('list');
		}else{
			return $oPosts->getListError();
		}
		
	}

	
	
	
	
	
}

/*variables
#select		$oView->tJoinPosts=Posts::getInstance()->getSelect();#fin_select
#uploadsave $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oPosts->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave


#methodNew
	public function _new(){
		$tMessage=$this->processSave();
	
		$oPosts=new row_Posts;
		
		$oView=new _view('Posts::new');
		$oView->oPosts=$oPosts;
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodNew#
	
#methodEdit
	public function _edit(){
		$tMessage=$this->processSave();
		
		$oPosts=model_Posts::getInstance()->findById( module_Posts::getParam('id') );
		
		$oView=new _view('Posts::edit');
		$oView->oPosts=$oPosts;
		$oView->tId=model_Posts::getInstance()->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodEdit#

#methodShow
	public function _show(){
		$oPosts=model_Posts::getInstance()->findById( module_Posts::getParam('id') );
		
		$oView=new _view('Posts::show');
		$oView->oPosts=$oPosts;
		
		
		return $oView;
	}
methodShow#

#methodDelete	
	public function _delete(){
		$tMessage=$this->processDelete();

		$oPosts=model_Posts::getInstance()->findById( module_Posts::getParam('id') );
		
		$oView=new _view('Posts::delete');
		$oView->oPosts=$oPosts;
		
		

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodDelete#

#methodProcessDelete
	public function processDelete(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oPosts=model_Posts::getInstance()->findById( module_Posts::getParam('id',null) );
				
		$oPosts->delete();
		//une fois enregistre on redirige (vers la page liste)
		$this->redirect('list');
		
	}
methodProcessDelete#

			
variables*/

