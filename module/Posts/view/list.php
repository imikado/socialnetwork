<p>&nbsp;</p>
<?php if($this->tPosts):?>
   <?php foreach($this->tPosts as $oPosts):?>
		<?php $oDateTime=new plugin_datetime($oPosts->date.' '.$oPosts->time);
		$iDelta= time() - $oDateTime->getMkTime();
		
		$sDelta=null;
		
		$iDuration=(60*60*24);
		$iDay= (int)($iDelta / $iDuration );
		if( $iDay ){
			$sDelta = $iDay.'j ';
			
			$iDelta -= ($iDuration * $iDay);
		}
		
		$iDuration=(60*60);
		$iHour= (int)($iDelta / $iDuration );
		if( $iHour ){
			$sDelta .= $iHour.'h ';
			
			$iDelta -= ($iDuration * $iHour);
		}
		
		$iDuration=(60);
		$iMinute= (int)($iDelta / $iDuration );
		if( $iMinute ){
			$sDelta .= $iMinute.'m ';
			
			$iDelta -= ($iDuration * $iMinute);
		}
		
		if($iDelta > 0){
			$sDelta .= $iDelta.'s ';	
		}
		
	
		?>
   
       <div>
           <div style="float:right;padding:2px;border:4px solid #67807c;border-radius: 0px 0px;width:400px;margin-bottom:4px;">

           <h1 style="text-align:right;margin-top:0px;border-radius: 0px 0px;"><?php echo $oPosts->title ?></h1>
           <p style="font-style:italic;margin:0px;text-align:right">le <?php echo $oPosts->date ?> &agrave; <?php echo $oPosts->time ?> (il y a <?php echo $sDelta?>) </p>
           <p><?php echo $oPosts->body ?></p>
           
           <?php echo $this->oModuleLike->_show($oPosts->id)->show()?>
           
       </div>
       <div style="float:right;margin-right:10px;text-align:right">
           <h2><a href="<?php echo _root::getLink('mainShare::profil',array('user_id'=>$oPosts->user_id))?>"><?php echo $this->tIndexdUsers[$oPosts->user_id]->firstname ?> <?php echo $this->tIndexdUsers[$oPosts->user_id]->lastname ?></a></h2>
           <img style="width:50px" src="<?php echo $this->tIndexdUsers[$oPosts->user_id]->profilPicture ?>"/>
       </div>
       <div style="clear:both"></div>
   </div>
   <?php endforeach;?>
<?php endif;?>
