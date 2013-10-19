<h1>Groupes</h1>
<table >
	
	<?php if($this->tGroups):?>
	<?php foreach($this->tGroups as $oGroups):?>
	<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
		
		<td style="width:150px"><?php echo $oGroups->name ?></td>

		<td>
			
			
<a href="<?php echo module_Groups::getLink('edit',array(
										'id'=>$oGroups->getId()
									) 
							)?>">Edit</a>
| 
<a href="<?php echo module_Groups::getLink('delete',array(
										'id'=>$oGroups->getId()
									) 
							)?>">Delete</a>
| 
<a href="<?php echo module_Groups::getLink('show',array(
										'id'=>$oGroups->getId()
									) 
							)?>">Show</a>

			
			
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>

<p><a href="<?php echo module_Groups::getLink('new') ?>">New</a></p>



