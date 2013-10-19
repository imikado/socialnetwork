<?php 
class module_Groups extends abstract_moduleembedded{
	
	public static $sModuleName='Groups';
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
	$oModuleGroups=new module_Groups();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleGroups->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleGroups->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	
	public function _index(){
		$sAction='_'.self::getParam('Action','list');
		return $this->$sAction();
	}
	
	public function _list(){
		
		$tGroups=model_Groups::getInstance()->findListByUser( $this->user_id);
		
		$oView=new _view('Groups::list');
		$oView->tGroups=$tGroups;
		
		

		return $oView;
	}
	

	public function _new(){
		$tMessage=$this->save();
	
		$oGroups=new row_Groups;
		
		$oView=new _view('Groups::new');
		$oView->oGroups=$oGroups;
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
	
	
	public function _edit(){
		$tMessage=$this->save();
		
		$oGroups=model_Groups::getInstance()->findById( module_Groups::getParam('id') );
		
		$oView=new _view('Groups::edit');
		$oView->oGroups=$oGroups;
		$oView->tId=model_Groups::getInstance()->getIdTab();
		
		
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}

	public function _show(){
	   $oGroups=model_Groups::getInstance()->findById( module_Groups::getParam('id') );
	   if(_root::getRequest()->isPost() and _root::getParam('tContactId')){
		   model_UsersGroup::getInstance()->updateMemberForGroupWithTab($oGroups->id,_root::getParam('tContactId'));
	   }
	   //recuperation de la liste des contacts
	   $tContacts=model_Friends::getInstance()->findListAcceptedByUser($this->user_id);
	   //recuperation d'un tableau contenant les membres de ce groupe
	   $tIndexedMember=model_UsersGroup::getInstance()->findIndexedMemberTabByGroup( $oGroups->id);
	   $oView=new _view('Groups::show');
	   $oView->oGroups=$oGroups;
	   $oView->tContacts=$tContacts;
	   $oView->tIndexedMember=$tIndexedMember;
	   return $oView;
	}
	
	public function _delete(){
		$tMessage=$this->delete();

		$oGroups=model_Groups::getInstance()->findById( module_Groups::getParam('id') );
		
		$oView=new _view('Groups::delete');
		$oView->oGroups=$oGroups;
		
		

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
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
	
		$iId=module_Groups::getParam('id',null);
		if($iId==null){
			$oGroups=new row_Groups;	
		}else{
			$oGroups=model_Groups::getInstance()->findById( module_Groups::getParam('id',null) );
		}
		
		$tId=model_Groups::getInstance()->getIdTab();
		$tColumn=model_Groups::getInstance()->getListColumn();
		foreach($tColumn as $sColumn){
			 $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oGroups->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else  if( _root::getParam($sColumn,null) === null ){ 
				continue;
			}else if( in_array($sColumn,$tId)){
				 continue;
			}
			
			$oGroups->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		//on force le user_id avec la propriete de notre module
		$oGroups->user_id=$this->user_id;

		
		if($oGroups->save()){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('list');
		}else{
			return $oGroups->getListError();
		}
		
	}

	public function delete(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oGroups=model_Groups::getInstance()->findById( module_Groups::getParam('id',null) );
				
		$oGroups->delete();
		//une fois enregistre on redirige (vers la page liste)
		$this->redirect('list');
		
	}
	
	
	
	
}

/*variables
#select		$oView->tJoinGroups=Groups::getInstance()->getSelect();#fin_select
#uploadsave $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oGroups->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave
variables*/

