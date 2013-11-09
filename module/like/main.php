<?php 
class module_like extends abstract_moduleembedded{
	
	public static $sModuleName='like';
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
	public function _index(){
		$sAction='_'.self::getParam('Action','list');
		return $this->$sAction();
	}
	
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_like();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleExamplemodule->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleExamplemodule->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	
	
	/* #debutaction#
	public function _exampleaction(){
	
		$oView=new _view('like::exampleaction');
		
		return $oView;
	}
	#finaction# */
	
	
	public function _show($iPost_id){
		
		//on verifie si le formulaire n'a pas ete soumis
		$this->processAdd($iPost_id);
		
		//on recupere le nombre de "j'aime"
		$iCount=model_Likes::getInstance()->countByPost($iPost_id);
	
		$oView=new _view('like::show');
		$oView->iCount=$iCount;
		$oView->iPost_id=$iPost_id;
		
		return $oView;
	}
	
	private function processAdd($iPost_id){
		
		//si le formulaire n'a pas ete soumis on ne fait rien
		if(!_root::getRequest()->isPost()){
			return null;
		}
		
		//cette ligne c'est bien ajouter un "j'aime" sur le post et pas tous les posts
		if(_root::getParam('post_id')!=$iPost_id){
			return null;
		}
		
		$iUser_id=_root::getauth()->getAccount()->id;
		
		//ajout d'un "j'aime" sur le post
		model_Likes::getInstance()->addOnPost($iPost_id,$iUser_id);
		
	}
	
	
	
	
	
}
