<?php
Class module_menu extends abstract_moduleembedded{
		
	public function _index(){
		
		$tLink=array(
			
			//'Accueil' => 'mainPrivate::index',
			'Votre profil' => 'mainPrivate::profil',
			'Votre fil' => 'mainPrivate::posts',
			'Vos photos' => 'mainPrivate::pictures',
			'Vos contacts' => 'mainPrivate::friends',
			
			'',
			
			'Se deconnecter' => 'mainPublic::logout',

		);
		
		$oView=new _view('menu::index');
		$oView->tLink=$tLink;
		
		return $oView;
	}
	
	public function _share(){
	  
	   $tLink=array(
		   'mainShare::profil' => 'Son profil',
		   'mainShare::photos' => 'Ses photos',
	   );
	  
	   $oView=new _view('menu::share');
	   $oView->tLink=$tLink;
		  
	   return $oView;
		  
	}
}
