<?php 
class module_profil extends abstract_moduleembedded{
	
	public static $sModuleName='profil';
	public static $sRootModule;
	public static $tRootParams;
	
	private $id;
	
	public function setId($uId){
	$this->id=(int)$uId;
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
	$oModuleProfil=new module_profil();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleProfil->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleProfil->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	public function _showshare(){ 
		$oUsers=model_Users::getInstance()->findById( $this->id ); 
			 
		$oView=new _view('profil::showshare'); 
		$oView->oUsers=$oUsers; 
			 
		return $oView; 
	}
	
	
	public function _index(){
		$sAction='_'.self::getParam('Action','show');
		return $this->$sAction();
	}
	
	public function _edit(){
		$tMessage=$this->save();
		
		$oUsers=model_Users::getInstance()->findById( $this->id );
		
		$oView=new _view('profil::edit');
		$oView->oUsers=$oUsers;
		$oView->tId=model_Users::getInstance()->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}

	public function _show(){
		$oUsers=model_Users::getInstance()->findById( $this->id );
		
		$oView=new _view('profil::show');
		$oView->oUsers=$oUsers;
		
		
		return $oView;
	}

	public function save(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oUsers=model_Users::getInstance()->findById( $this->id );

		
		$tId=model_Users::getInstance()->getIdTab();
		$tColumn=model_Users::getInstance()->getListColumn();
		foreach($tColumn as $sColumn){
			 $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oUsers->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) === null ){ 
				continue;
			}else if( in_array($sColumn,$tId)){
				 continue;
			}
			
			$oUsers->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oUsers->save()){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('show');
		}else{
			return $oUsers->getListError();
		}
		
	}
	
	
}

/*variables
#select		$oView->tJoinUsers=Users::getInstance()->getSelect();#fin_select
#uploadsave $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oUsers->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave
variables*/

