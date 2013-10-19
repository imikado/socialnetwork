<?php
class model_Share extends abstract_model{
	
	protected $sClassRow='row_Share';
	
	protected $sTable='Share';
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
	public function shareUserPostWithGroups($user_id,$post_id,$tGroup_id){
	   if($tGroup_id){
		   foreach($tGroup_id as $group_id){
			   $oGroups=new row_Share;
			   $oGroups->user_id=(int)$user_id;
			   $oGroups->post_id=(int)$post_id;
			   $oGroups->group_id=(int)$group_id;
			   $oGroups->date=date('Y-m-d');
			   $oGroups->save();
		   }
	   }
	}
	public function findListByAlbum($album_id){ 
		return $this->findMany('SELECT * FROM '.$this->sTable.' WHERE album_id=?',$album_id); 
	} 
	public function findListAlbumsIndexedByAlbum($album_id){ 
		$tSharedGroup=$this->findListByAlbum($album_id); 
		$tIndexed=array(); 
		foreach($tSharedGroup as $oShare){ 
			$tIndexed[$oShare->group_id]=$oShare; 
		} 
		return $tIndexed; 
	}
	public function shareUserAlbumsWithGroups($user_id,$album_id,$tGroup_id){ 
		//on supprime d'abord tous les partages pour les recreer ensuite 
		$this->execute('DELETE FROM '.$this->sTable.' WHERE user_id=? AND album_id=?',$user_id,$album_id); 

		if($tGroup_id){ 
			foreach($tGroup_id as $group_id){ 
				$oGroups=new row_Share; 
				$oGroups->user_id=(int)$user_id; 
				$oGroups->album_id=(int)$album_id; 
				$oGroups->group_id=(int)$group_id; 
				$oGroups->date=date('Y-m-d'); 
				$oGroups->save(); 
			} 
		} 
	}
}

class row_Share extends abstract_row{
	
	protected $sClassModel='model_Share';
	
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
