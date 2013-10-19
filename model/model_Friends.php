<?php
class model_Friends extends abstract_model{
	
	protected $sClassRow='row_Friends';
	
	protected $sTable='Friends';
	protected $sConfig='socialnetwork';
	
	protected $tId=array('id');
	
	const STATE_PENDING=0;
	const STATE_ACCEPTED=1;
	const STATE_REFUSED=2;

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE id=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable);
	}
	
	public function findListAcceptedByUser($user_id){
       return $this->findMany(
           'SELECT *, Friends.id as friend_id FROM '.$this->sTable.',Users
           WHERE
           (Friends.state='.self::STATE_ACCEPTED.'
           and Users.id=Friends.user_id2 and Friends.user_id=?)
           or
           (Friends.state='.self::STATE_ACCEPTED.'
           and Users.id=Friends.user_id and Friends.user_id2=?)
           '
           ,(int)$user_id
           ,(int)$user_id
       );
   }
   public function findListPendingByUser($user_id){
       return $this->findMany(
           'SELECT * FROM '.$this->sTable.',Users
           WHERE Users.id=Friends.user_id2
           AND Friends.user_id=?
           AND Friends.state=? '
           ,(int)$user_id
           ,self::STATE_PENDING
       );
   }
   public function findListToValidateByUser($user_id){
       return $this->findMany(
           'SELECT *, Friends.id as friend_id
           FROM '.$this->sTable.',Users
           WHERE Users.id=Friends.user_id
           AND Friends.user_id2=?
           AND Friends.state=? '
           ,(int)$user_id
           ,self::STATE_PENDING
       );
	}
	public function accept($oContactAsked){
	   $oContactAsked->state=self::STATE_ACCEPTED;
	   $oContactAsked->save();
	}
	public function refuse($oContactAsked){
	   $oContactAsked->state=self::STATE_REFUSED;
	   $oContactAsked->save();
	}
	
	public function isUserFiendsWith($user_id,$user_id2){ 
		$oRow=$this->findOne('SELECT count(*) as total FROM Friends where 
					(user_id=? AND user_id2=? AND state='.self::STATE_ACCEPTED.') 
					OR 
					(user_id2=? AND user_id=? AND state='.self::STATE_ACCEPTED.') 
					', 
					$user_id,$user_id2,$user_id,$user_id2); 
						 
		if($oRow->total >0){ 
			return true; 
		}else{ 
			return false; 
		} 
	}
	
}

class row_Friends extends abstract_row{
	
	protected $sClassModel='model_Friends';
	
	/*exemple jointure 
	public function findAuteur(){
		return model_auteur::getInstance()->findById($this->auteur_id);
	}
	*/
	
	public function accept(){
       model_Friends::getInstance()->accept($this);
   }
   	public function refuse(){
		model_Friends::getInstance()->refuse($this);
	}
	
	/*exemple test validation*/
	private function getCheck(){
		$oPluginValid=new plugin_valid($this->getTab());
		/* renseigner vos check ici
		$oPluginValid->isEqual('champ','valeurB','Le champ n\est pas &eacute;gal &agrave; '.$valeurB);
		$oPluginValid->isNotEqual('champ','valeurB','Le champ est &eacute;gal &agrave; '.$valeurB);
		$oPluginValid->isUpperThan('champ','valeurB','Le champ n\est pas sup&eacute; &agrave; '.$valeurB);
		$oPluginValid->isUpperOrEqualThan('champ','valeurB','Le champ n\est pas sup&eacute; ou &eacute;gal &agrave; '.$valeurB);
		$oPluginValid->isLowerThan('champ','valeurB','Le champ n\est pas inf&eacute;rieur &agrave; '.$valeurB);
		$oPluginValid->isLowerOrEqualThan('champ','valeurB','Le champ n\est pas inf&eacute;rieur ou &eacute;gal &agrave; '.$valeurB);
		$oPluginValid->isEmpty('champ','Le champ n\'est pas vide');
		$oPluginValid->isNotEmpty('champ','Le champ ne doit pas &ecirc;tre vide');
		$oPluginValid->isEmailValid('champ','L\email est invalide');
		$oPluginValid->matchExpression('champ','/[0-9]/','Le champ n\'est pas au bon format');
		$oPluginValid->notMatchExpression('champ','/[a-zA-Z]/','Le champ ne doit pas &ecirc;tre a ce format');
		*/

		return $oPluginValid;
	}

	public function isValid(){
		return $this->getCheck()->isValid();
	}
	public function getListError(){
		return $this->getCheck()->getListError();
	}
	public function save(){
		if(!$this->isValid()){
			return false;
		}
		parent::save();
		return true;
	}

}
