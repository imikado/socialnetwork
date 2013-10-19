<?php 
class module_Albumshare extends abstract_moduleembedded{
	
	public static $sModuleName='Albumshare';
	public static $sRootModule;
	public static $tRootParams;
	
	private $user_id; 
	public function setUserId($user_id){ 
		$this->user_id=(int)$user_id; 
	}
	
	
	
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
	
	
	
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_Albumshare();
	
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
		
		$tAlbums=model_Albums::getInstance()->findAllShareByUserForUser($this->user_id, _root::getAuth()->getAccount()->id);
		
		$oView=new _view('Albumshare::list');
		$oView->tAlbums=$tAlbums;
		
		

		return $oView;
	}
	
	
	
	
	
	
	public function _show(){ 
		$oAlbums=model_Albums::getInstance()->findById( module_Albumshare::getParam('id') ); 
		 
		$oView=new _view('Albumshare::show'); 
		$oView->oAlbums=$oAlbums; 
		$oView->tPictures=model_Pictures::getInstance()->findListByAlbum($oAlbums->id); 
		 
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
	
		$iId=module_Albumshare::getParam('id',null);
		if($iId==null){
			$oAlbums=new row_Albums;	
		}else{
			$oAlbums=model_Albums::getInstance()->findById( module_Albumshare::getParam('id',null) );
		}
		
		$tId=model_Albums::getInstance()->getIdTab();
		$tColumn=model_Albums::getInstance()->getListColumn();
		foreach($tColumn as $sColumn){
			 $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oAlbums->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) === null ){ 
				continue;
			}else if( in_array($sColumn,$tId)){
				 continue;
			}
			
			$oAlbums->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oAlbums->save()){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('list');
		}else{
			return $oAlbums->getListError();
		}
		
	}

	
	
	
	
	
}

/*variables
#select		$oView->tJoinAlbums=Albums::getInstance()->getSelect();#fin_select
#uploadsave $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oAlbums->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave


#methodNew
	public function _new(){
		$tMessage=$this->processSave();
	
		$oAlbums=new row_Albums;
		
		$oView=new _view('Albumshare::new');
		$oView->oAlbums=$oAlbums;
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodNew#
	
#methodEdit
	public function _edit(){
		$tMessage=$this->processSave();
		
		$oAlbums=model_Albums::getInstance()->findById( module_Albumshare::getParam('id') );
		
		$oView=new _view('Albumshare::edit');
		$oView->oAlbums=$oAlbums;
		$oView->tId=model_Albums::getInstance()->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodEdit#

#methodShow
	public function _show(){
		$oAlbums=model_Albums::getInstance()->findById( module_Albumshare::getParam('id') );
		
		$oView=new _view('Albumshare::show');
		$oView->oAlbums=$oAlbums;
		
		
		return $oView;
	}
methodShow#

#methodDelete	
	public function _delete(){
		$tMessage=$this->processDelete();

		$oAlbums=model_Albums::getInstance()->findById( module_Albumshare::getParam('id') );
		
		$oView=new _view('Albumshare::delete');
		$oView->oAlbums=$oAlbums;
		
		

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
	
		$oAlbums=model_Albums::getInstance()->findById( module_Albumshare::getParam('id',null) );
				
		$oAlbums->delete();
		//une fois enregistre on redirige (vers la page liste)
		$this->redirect('list');
		
	}
methodProcessDelete#

			
variables*/

