<script> 
function hidePicture(){ 
	var a=getById('popup'); 
	if(a){ 
		a.style.display='none'; 
	} 
} 
function showPicture(sPath){ 
	var a=getById('popup'); 
	if(a){ 
		a.style.display='block'; 
		var b=getById('img'); 
		if(b){ 
			b.innerHTML='<img src="'+sPath+'"/>'; 
		} 
	} 
} 
</script> 
<h1>Album <?php echo $this->oAlbums->name ?></h1> 

<form action="" method="post" enctype="multipart/form-data"> 
Choisir une photo <input type="file" name="path" /><input type="submit" value="Uploader"/> 
</form> 
<br/>
<?php foreach($this->tPictures as $oPicture):?> 
<a href="#" onclick="showPicture('<?php echo $oPicture->path?>');"><img style="width:100px;height:100px;border:4px solid gray" src="<?php echo $oPicture->path?>" /></a> 
<?php endforeach;?> 

<form action="" method="POST"> 
<input type="hidden" name="share" value="1"/> 
<?php foreach($this->tGroups as $oGroups):?> 
<input <?php if(isset($this->tSharedGroups[$oGroups->id])):?>checked="checked"<?php endif;?> type="checkbox" name="tGroup[]" value="<?php echo $oGroups->id?>"/>
<?php if(isset($this->tSharedGroups[$oGroups->id])):?><strong><?php echo $oGroups->name?></strong><?php else:?><?php echo $oGroups->name?><?php endif;?><br /> 
<?php endforeach;?> 
<p><input type="submit" value="partager"/></p> 
</form> 

<div id="popup" style="position:absolute;display:none;border:1px solid gray;background:white"> 
<p style="margin:0px;background:black;text-align:right"><a style="color:white" href="#" onclick="hidePicture()" >Fermer</a></p> 
<div id="img"></div> 
</div> 

<p><a href="<?php echo module_Albums::getLink('list')?>">Retour</a></p> 
