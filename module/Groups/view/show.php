<h1><?php echo $this->oGroups->name ?></h1>
<form action="" method="POST">
<?php if($this->tContacts):?>
	<?php foreach($this->tContacts as $oContact):?>
	<input <?php if(isset($this->tIndexedMember[ $oContact->id])):
		?>checked="checked"<?php 
		endif;?> type="checkbox" name="tContactId[]" value="<?php echo $oContact->id?>" />
		<?php if(isset($this->tIndexedMember[ $oContact->id])):?><strong><?php echo $oContact->lastname ?> <?php echo $oContact->firstname ?></strong><?php else:?><?php echo $oContact->lastname ?> <?php echo $oContact->firstname ?><?php endif;?><br />
	<?php endforeach;?>
<?php endif;?>
<input type="submit" value="Modifier"/>
</form>
