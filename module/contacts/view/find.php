 
<hr/>
<h1>Rechercher</h1>
<form action="" method="POST">
<p>Rechercher <input type="text" name="pattern" /> <input type="submit"
value="Rechercher"/></p>
</form>
<?php if(_root::getRequest()->isPost() and _root::getParam('pattern')):?>
   <h1>R&eacute;sultat(s) de recherche</h1>
   <?php if($this->tUserFound):?>
       <table>
       <?php foreach($this->tUserFound as $oUserFound):?>
			<?php $sPicture=_root::getConfigVar('path.data').'/img/default.png'; if($oUserFound->profilPicture!=''){ $sPicture= $oUserFound->profilPicture;}?>
           <tr>
			<td><img style="height:20px" src="<?php echo $sPicture;?>"/></td>
			<td>
			<a href="<?php echo _root::getLink('mainShare::profil',array('user_id'=>$oUserFound->id))?>">
			<?php echo $oUserFound->lastname?> <?php echo $oUserFound->firstname?>
			</a>
			</td>
			<td><a href="<?php echo _root::getLink('contacts::ask',
									array('id'=>$oUserFound->id))
									?>">demander en contact</a></td>
           </tr>
       <?php endforeach;?>
       </table>
   <?php else:?>
       <p>Aucun r&eacute;sultats</p>
   <?php endif;?>
<?php endif;?>
