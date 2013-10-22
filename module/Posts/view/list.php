<p>&nbsp;</p>
<?php if($this->tPosts):?>
   <?php foreach($this->tPosts as $oPosts):?>
       <div>
           <div style="float:right;padding:2px;border:4px solid #67807c;border-radius: 0px 0px;width:400px;margin-bottom:4px;">

           <h1 style="text-align:right;margin-top:0px;border-radius: 0px 0px;"><?php echo $oPosts->title ?></h1>
           <p style="font-style:italic;margin:0px;text-align:right">le <?php echo $oPosts->date ?> &agrave; <?php echo $oPosts->time ?> </p>
           <p><?php echo $oPosts->body ?></p>
       </div>
       <div style="float:right;margin-right:10px;text-align:right">
           <h2><a href="<?php echo _root::getLink('mainShare::profil',array('user_id'=>$oPosts->user_id))?>"><?php echo $this->tIndexdUsers[$oPosts->user_id]->firstname ?> <?php echo $this->tIndexdUsers[$oPosts->user_id]->lastname ?></a></h2>
           <img style="width:50px" src="<?php echo $this->tIndexdUsers[$oPosts->user_id]->profilPicture ?>"/>
       </div>
       <div style="clear:both"></div>
   </div>
   <?php endforeach;?>
<?php endif;?>
