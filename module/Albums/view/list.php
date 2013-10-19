<table > 
	<?php if($this->tAlbums):?>
	<?php foreach($this->tAlbums as $oAlbums):?>
	<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
		
		<td style="width:150px"><?php echo $oAlbums->name ?></td>

		<td>
			
			
<a href="<?php echo module_Albums::getLink('edit',array(
										'id'=>$oAlbums->getId()
									) 
							)?>">Edit</a>
| 
<a href="<?php echo module_Albums::getLink('show',array(
										'id'=>$oAlbums->getId()
									) 
							)?>">Show</a>

			
			
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>

<p><a href="<?php echo module_Albums::getLink('new') ?>">New</a></p>



