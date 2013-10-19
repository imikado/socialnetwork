<?php
class module_mainPrivate extends abstract_module{

	public function before(){
		$this->oLayout=new _layout('template1');
		$this->oLayout->addModule('menu','menu::index');
		//$this->oLayout->addModule('menu','menu::index');
	}
	/* #debutaction#
	public function _exampleaction(){

		$oView=new _view('examplemodule::exampleaction');

		$this->oLayout->add('main',$oView);
	}
	#finaction# */


	public function _index(){

		_root::redirect('mainPrivate::posts');
	}

	public function _profil(){
		
		$oModuleProfil=new module_profil();
		//on initialise ici le profil à utiliser avec l’id du user connecté
		$oModuleProfil->setId( _root::getAuth()->getAccount()->id );
		
		//recuperation de la vue du module profil enrichie
		$oView=$oModuleProfil->_index();
		$this->oLayout->add('main',$oView);
	}

	public function _posts(){ 
	 
		$oModulePosts=new module_Posts; 
		$oModulePosts->setUserId( _root::getAuth()->getAccount()->id ); 
		//recupere la vue du module 
		$oView=$oModulePosts->_new(); 

		//assigner la vue retournee a votre layout 
		$this->oLayout->add('main',$oView); 

		//recupere la vue du module 
		$oView=$oModulePosts->_list(); 

		//assigner la vue retournee a votre layout 
		$this->oLayout->add('main',$oView); 
	}

	public function _pictures(){
		$oModuleAlbums=new module_Albums; 
		//on initialise ici le profil à utiliser avec l'id du user connecte 
		$oModuleAlbums->setUserId( _root::getAuth()->getAccount()->id ); 
		//recupere la vue du module 
		$oView=$oModuleAlbums->_index(); 

		$this->oLayout->add('main',$oView);
	}

	public function _friends(){
		$oModuleGroups=new module_Groups();
		$oModuleGroups->setUserId( _root::getAuth()->getAccount()->id );
		$oViewGroups=$oModuleGroups->_index();
		$this->oLayout->add('main',$oViewGroups);
		
		$oModuleContacts=new module_contacts();
		$oModuleContacts->setUserId( _root::getAuth()->getAccount()->id );
		$oViewContacts=$oModuleContacts->_list();
		$this->oLayout->add('main',$oViewContacts);
		
		$oViewFind=$oModuleContacts->_find();
		$this->oLayout->add('main',$oViewFind);
	}


	public function after(){
		$this->oLayout->show();
	}


}
