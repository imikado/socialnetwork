<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" >
   <input type="hidden" name="formmodule" value="Posts" />
   <div style="margin-bottom:10px;float:right;padding:5px;border:4px solid #67807c;border-radius: 0px;width:400px">
   <div>
       Titre<br/>
       <input style="width:390px;border:2px solid #67807c" name="title" /><?php if($this->tMessage and isset($this->tMessage['title'])): echo implode(',',$this->tMessage['title']); endif;?></td>
   </div>
   <div>
       Message<br/>
       <textarea name="body" style="width:390px;height:50px;border:2px solid #67807c"></textarea><?php if($this->tMessage and isset($this->tMessage['body'])): echo implode(',',$this->tMessage['body']); endif;?></td>
   </div>

   <?if($this->tGroups):?>
       <?php foreach($this->tGroups as $oGroups):?>
           <input type="checkbox" name="tGroup_id[]" value="<?php echo $oGroups->id?>"/> <?php echo $oGroups->name ?><br />
       <?php endforeach;?>
   <?php endif;?>

   <input type="hidden" name="token" value="<?php echo $this->token?>" />
   <?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

   <p style="text-align:right"><input type="submit" value="Ajouter" /></p>
   </div>
   <div style="clear:both"></div>
</form>
