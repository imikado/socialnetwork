<?php 
class module_Albums extends abstract_moduleembedded{
	
	public static $sModuleName='Albums';
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
	
	public function showForFil( $album_id){ 
		$oAlbums=model_Albums::getInstance()->findById( $album_id ); 

		$oView=new _view('Albums::showForFil'); 
		$oView->oAlbums=$oAlbums; 
			 
		return $oView; 
	} 
	
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_Albums();
	
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
		
		$tAlbums=model_Albums::getInstance()->findAllByUser($this->user_id);
		
		$oView=new _view('Albums::list');
		$oView->tAlbums=$tAlbums;
		
		

		return $oView;
	}
	
	
	public function _new(){
		$tMessage=$this->processSave();
	
		$oAlbums=new row_Albums;
		
		$oView=new _view('Albums::new');
		$oView->oAlbums=$oAlbums;
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}

	
	
	public function _edit(){
		$tMessage=$this->processSave();
		
		$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id') );
		
		$oView=new _view('Albums::edit');
		$oView->oAlbums=$oAlbums;
		$oView->tId=model_Albums::getInstance()->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}


	
	public function _show(){ 
		$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id') ); 

		$oView=new _view('Albums::show'); 
		$oView->oAlbums=$oAlbums; 
		$oView->tPictures=model_Pictures::getInstance()->findListByAlbum($oAlbums->id); 
		 
		//recuperons les groupes 
		$oView->tGroups=model_Groups::getInstance()->findListByUser( $this->user_id); 
		//recuperons les groupes avec lesquels on partage cet album 
		$oView->tSharedGroups=model_Share::getInstance()->findListAlbumsIndexedByAlbum( $oAlbums->id); 

		if(_root::getRequest()->isPost() and isset($_FILES['path'])){ 
			$sNewFileName='../data/upload/album_'.$oAlbums->id.'_'.date('Ymdhis'); 

			$oPluginUpload=new plugin_upload('path'); 
			if($oPluginUpload->isValid()){
				$oPluginUpload->saveAs($sNewFileName); 

				$oPicture=new row_Pictures(); 
				$oPicture->album_id=$oAlbums->id; 
				$oPicture->path=$oPluginUpload->getPath(); 
				$oPicture->save(); 
				 
				_root::redirect('mainPrivate::pictures',array('mode'=>'show','id'=>$oAlbums->id)); 
			}
		} 
		if(_root::getParam('share')){ 
		 
			$oViewAlbum=$this->showForFil($oAlbums->id); 
				 
			$oPosts=new row_Posts; 
			$oPosts->title='Partage de photos'; 
			$oPosts->body=$oViewAlbum->show(); 
			$oPosts->date=date('Y-m-d'); 
			$oPosts->time=date('H:i:s'); 
			$oPosts->user_id=$this->user_id; 
			$oPosts->save(); 
				 
			$tPostedGroups=_root::getParam('tGroup'); 
					 
			//partge du post avec les groups 
			model_Share::getInstance()->shareUserPostWithGroups($this->user_id,$oPosts->id,$tPostedGroups); 
					 
			model_Share::getInstance()->shareUserAlbumsWithGroups($this->user_id,$oAlbums->id,$tPostedGroups); 
					 
			self::redirect('show',array('id'=>$oAlbums->id)); 
		}

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
	
		$iId=module_Albums::getParam('id',null);
		if($iId==null){
			$oAlbums=new row_Albums;	
		}else{
			$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id',null) );
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
		//on force le user_id avec la propriete de notre module 
		$oAlbums->user_id=$this->user_id; 
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
		
		$oView=new _view('Albums::new');
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
		
		$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id') );
		
		$oView=new _view('Albums::edit');
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
		$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id') );
		
		$oView=new _view('Albums::show');
		$oView->oAlbums=$oAlbums;
		
		
		return $oView;
	}
methodShow#

#methodDelete	
	public function _delete(){
		$tMessage=$this->processDelete();

		$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id') );
		
		$oView=new _view('Albums::delete');
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
	
		$oAlbums=model_Albums::getInstance()->findById( module_Albums::getParam('id',null) );
				
		$oAlbums->delete();
		//une fois enregistre on redirige (vers la page liste)
		$this->redirect('list');
		
	}
methodProcessDelete#

			
variables*/

