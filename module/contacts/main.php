<?php 
class module_contacts extends abstract_moduleembedded{
	
	public static $sModuleName='contacts';
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
	public function _index(){
		$sAction='_'.self::getParam('Action','list');
		return $this->$sAction();
	}
	
	public function before(){
		
	}
	
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_contacts();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleExamplemodule->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleExamplemodule->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	
	
	/* #debutaction#
	public function _exampleaction(){
	
		$oView=new _view('contacts::exampleaction');
		
		return $oView;
	}
	#finaction# */
	
	
	public function _list(){
	
		$oViewContactsAccepted=$this->getList(model_Friends::getInstance()->findListAcceptedByUser($this->user_id));
		$oViewContactsPending=$this->getList(model_Friends::getInstance()->findListPendingByUser($this->user_id));
		$oViewContactsToValidate=$this->getListToValidate(model_Friends::getInstance()->findListToValidateByUser($this->user_id));
		
		$oView=new _view('contacts::list');
		$oView->oViewContactsAccepted=$oViewContactsAccepted;
		$oView->oViewContactsPending=$oViewContactsPending;
		$oView->oViewContactsToValidate=$oViewContactsToValidate;
		
		return $oView;
	}
	
	public function getList($tContacts){
		$oView=new _view('contacts::listembedded');
		$oView->tContacts=$tContacts;
		return $oView;
	}
	public function getListToValidate($tContacts){
	   $oView=new _view('contacts::listembeddedToValidate');
	   $oView->tContacts=$tContacts;
	   return $oView;
	}
	
	public function _find(){
		$tUserFound=null;
		if(_root::getRequest()->isPost() and _root::getParam('pattern')){
		   $tUserFound=model_Users::getInstance()->findListByPattern( _root::getParam('pattern') );
		}
		$oView=new _view('contacts::find');
		$oView->tUserFound=$tUserFound;
		return $oView;
	}
	
	public function _ask(){
	   if(_root::getParam('id')){
		   $oUserToAsk=model_Users::getInstance()->findById( _root::getParam('id' ));
		   if($oUserToAsk){
			   //si l'utilisateur existe, on cree une demande
			   $oNewContact=new row_Friends();
			   $oNewContact->user_id=_root::getAuth()->getAccount()->id;
			   $oNewContact->user_id2=$oUserToAsk->id;
			   $oNewContact->state=model_Friends::STATE_PENDING;
			   $oNewContact->save();
			   _root::redirect('mainPrivate::friends');
		   }
	   }
	}
	
	public function _accept(){
		$this->user_id=_root::getAuth()->getAccount()->id;
		$oContactAsked=model_Friends::getInstance()->findById( _root::getParam(    'id') );
		if($oContactAsked and $oContactAsked->user_id2==$this->user_id){
		   //on check qu'on a bien trouve la demande et qu'elle nous est bien adresse
		   $oContactAsked->accept();
		   _root::redirect('mainPrivate::friends');
		}
	}
	
	public function _refuse(){
		$this->user_id=_root::getAuth()->getAccount()->id;
		$oContactAsked=model_Friends::getInstance()->findById( _root::getParam('id') );
		if($oContactAsked and $oContactAsked->user_id2==$this->user_id){
		   //on check qu'on a bien trouve la demande et qu'elle nous est bien adresse
		   $oContactAsked->refuse();
		   _root::redirect('mainPrivate::friends');
		}
	}
	
	
	
	
	
}
