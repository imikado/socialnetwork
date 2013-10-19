<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST"  enctype="multipart/form-data">
<input type="hidden" name="formmodule" value="profil" />

<table class="tb_edit">
	
	<tr>
		<th>mail</th>
		<td><input name="mail" value="<?php echo $this->oUsers->mail ?>" /><?php if($this->tMessage and isset($this->tMessage['mail'])): echo implode(',',$this->tMessage['mail']); endif;?></td>
	</tr>

	<tr>
		<th>profilPicture</th>
		<td><input type="file" name="profilPicture" /><?php if($this->tMessage and isset($this->tMessage['profilPicture'])): echo implode(',',$this->tMessage['profilPicture']); endif;?></td>
	</tr>

	<tr>
		<th>firstname</th>
		<td><input name="firstname" value="<?php echo $this->oUsers->firstname ?>" /><?php if($this->tMessage and isset($this->tMessage['firstname'])): echo implode(',',$this->tMessage['firstname']); endif;?></td>
	</tr>

	<tr>
		<th>lastname</th>
		<td><input name="lastname" value="<?php echo $this->oUsers->lastname ?>" /><?php if($this->tMessage and isset($this->tMessage['lastname'])): echo implode(',',$this->tMessage['lastname']); endif;?></td>
	</tr>

	<tr>
		<th>job</th>
		<td><input name="job" value="<?php echo $this->oUsers->job ?>" /><?php if($this->tMessage and isset($this->tMessage['job'])): echo implode(',',$this->tMessage['job']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Modifier" /> <a href="<?php echo module_profil::getLink('show')?>">Annuler</a>
</form>

