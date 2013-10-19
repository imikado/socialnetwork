<?php 
class module_mainShare extends abstract_module{
	
	public function before(){ 
		if(!model_Friends::getInstance()->isUserFiendsWith(_root::getParam('user_id'), _root::getAuth()->getAccount()->id) ){ 
			//si  on est pas un contact de cette personne, on est redirige 
			_root::redirect('mainPrivate::profil'); 
		} 
	 
		$this->oLayout=new _layout('template1'); 
		 
		$this->oLayout->addModule('menu','menu::share'); 
	}
	/* #debutaction#
	public function _exampleaction(){
	
		$oView=new _view('examplemodule::exampleaction');
		
		$this->oLayout->add('main',$oView);
	}
	#finaction# */
	
	
	public function _profil(){ 
	 
		$oModuleProfil=new module_profil; 
		$oModuleProfil->setId(_root::getParam('user_id')); 
		$oView=$oModuleProfil->_showshare(); 
			 
		$this->oLayout->add('main',$oView); 
	}
	
	public function _photos(){ 
		
		$user_id=_root::getParam('user_id');
	 
		$oModuleAlbum=new module_Albumshare; 
		$oModuleAlbum->setUserId($user_id); 
		$oModuleAlbum->setRootLink('mainShare::photos',array('user_id'=>$user_id));
		$oView=$oModuleAlbum->_index(); 
		 
		$this->oLayout->add('main',$oView); 
	}
	
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
