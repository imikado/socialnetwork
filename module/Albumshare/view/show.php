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

<?php foreach($this->tPictures as $oPicture):?> 
<a href="#" onclick="showPicture('<?php echo $oPicture->path?>');"><img style="width:100px;height:100px;border:4px solid gray" src="<?php echo $oPicture->path?>" /></a> 
<?php endforeach;?> 

<div id="popup" style="position:absolute;display:none;border:1px solid gray;background:white"> 
<p style="margin:0px;background:black;text-align:right"><a style="color:white" href="#" onclick="hidePicture()" >Fermer</a></p> 
<div id="img"></div> 
</div> 

<p><a href="<?php echo module_Albumshare::getLink('list')?>">Retour</a></p> 
