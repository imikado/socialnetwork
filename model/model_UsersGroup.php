<?php
class model_UsersGroup extends abstract_model{
	
	protected $sClassRow='row_UsersGroup';
	
	protected $sTable='UsersGroup';
	protected $sConfig='socialnetwork';
	
	protected $tId=array('id');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE id=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable);
	}
	public function findIndexedMemberTabByGroup($id){
	   $tMember=$this->findMany('SELECT user_id FROM UsersGroup WHERE group_id=?',$id);
	   $tIndexed=array();
	   if($tMember){
		   foreach($tMember as $oMember){
			   $tIndexed[ $oMember->user_id ]=$oMember->user_id;
		   }
	   }
	   return $tIndexed;
	}
	public function updateMemberForGroupWithTab($group_id,$tContact){
	   $this->execute('DELETE FROM UsersGroup WHERE group_id=?',(int)$group_id);
	   if($tContact)
	   foreach($tContact as $user_id){
		   $oUsersGroup=new row_UsersGroup;
		   $oUsersGroup->group_id=$group_id;
		   $oUsersGroup->user_id=$user_id;
		   $oUsersGroup->save();
	   }
	}
}

class row_UsersGroup extends abstract_row{
	
	protected $sClassModel='model_UsersGroup';
	
	/*exemple jointure 
	public function findAuteur(){
		return model_auteur::getInstance()->findById($this->auteur_id);
	}
	*/
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
