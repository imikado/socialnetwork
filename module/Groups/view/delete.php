<table class="tb_delete">
	
	<tr>
		<th>name</th>
		<td><?php echo $this->oGroups->name ?></td>
	</tr>

</table>

<form action="" method="POST">
<input type="hidden" name="formmodule" value="Groups" />
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" /> <a href="<?php echo module_Groups::getLink('list')?>">Annuler</a>
</form>

