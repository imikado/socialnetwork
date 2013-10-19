<?php
class module_mainPublic extends abstract_module{
	
	//longueur maximum du mot de passe
	private $maxPasswordLength=100;
	
	public function before(){
		//on active l'authentification
		_root::getAuth()->enable();

		$this->oLayout=new _layout('template1');
	}

	public function _login(){
		
		$sMessage=$this->checkLoginPass();
		
		$oView=new _view('mainPublic::login');
		$oView->sError=$sMessage;

		$this->oLayout->add('main',$oView);

	}
	private function checkLoginPass(){
		//si le formulaire n'est pas envoye on s'arrete la
		if(!_root::getRequest()->isPost() ){
			return null;
		}
		
		$sLogin=_root::getParam('login');
		$sPassword=_root::getParam('password');
		
		if(strlen($sPassword) > $this->maxPasswordLength){
			return 'Mot de passe trop long';
		}
		
		//on stoque les mots de passe hashe dans la classe model_Users
		$sHashPassword=model_Users::getInstance()->hashPassword($sPassword);
		$tAccount=model_Users::getInstance()->getListAccount();
		
		//on va verifier que l'on trouve dans le tableau retourne par notre model
		//l'entree $tAccount[ login ][ mot de passe hashe ]
		if(!_root::getAuth()->checkLoginPass($tAccount,$sLogin,$sHashPassword)){
			return 'Mauvais login/mot de passe';
		}
		
		_root::redirect('mainPrivate::index');
	}
	public function _inscription(){
		$message=$this->processInscription();

		$oView=new _view('mainPublic::inscription');
		$oView->message=$message;

		$this->oLayout->add('main',$oView);
	}
	private function processInscription(){
		if(!_root::getRequest()->isPost()){
			return null;
		}
		
		$sPassword=_root::getParam('password');

		if($sPassword!=_root::getParam('password2')){
			return 'Les deux mots de passe doivent etre identique';
		}elseif(_root::getParam('login')==''){
			return 'Vous devez remplir le nom d utilisateur';
		}elseif($sPassword==''){
			return 'Vous devez remplir le mot de passe';
		}elseif(strlen($sPassword) > $this->maxPasswordLength){
			return 'Mot de passe trop long';
		}

		$oExample=new row_Users;
		$oExample->login=_root::getParam('login');
		$oExample->pass=model_Users::getInstance()->hashPassword(_root::getParam('password'));
		$oExample->save();

		return 'Votre compte a bien &eacute;t&eacute; cr&eacute;&eacute;';

	}

	public function _logout(){
		_root::getAuth()->logout();
	}

	public function after(){
		$this->oLayout->show();
	}
}
