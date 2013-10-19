<?php if($this->tContacts):?> 
<table> 
	<?php foreach($this->tContacts as $oContact):?> 
		<tr> 
			<td><a href="<?php echo _root::getLink('mainShare::profil',array('user_id'=>$oContact->id))?>"><?php echo $oContact->lastname ?></a></td> 
			<td><a href="<?php echo _root::getLink('mainShare::profil',array('user_id'=>$oContact->id))?>"><?php echo $oContact->firstname ?></a></td> 
		</tr> 
	<?php endforeach;?> 
</table> 
<?php else:?> 
Aucun pour le moment 
<?php endif;?>
