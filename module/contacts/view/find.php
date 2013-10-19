 
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
           <tr>
           <td><?php echo $oUserFound->lastname?></td>
           <td><?php echo $oUserFound->firstname?></td>
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
