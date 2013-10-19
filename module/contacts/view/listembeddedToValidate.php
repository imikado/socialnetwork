<?php if($this->tContacts):?>
<table>
<?php foreach($this->tContacts as $oContact):?>
<tr>
              <td><?php echo $oContact->lastname ?></td>
              <td><?php echo $oContact->firstname ?></td>
              <td>
                   <a href="<?php echo _root::getLink('contacts::accept',
                               array('id'=>$oContact->friend_id))
                               ?>">Accepter</a>
               </td>
               <td>
                   <a href="<?php echo _root::getLink('contacts::refuse',
                               array('id'=>$oContact->friend_id))
                               ?>">Refuser</a>

               </td>
          
</tr>
<?php endforeach;?>
</table>
<?php else:?>
Aucun pour le moment
<?php endif;?>
