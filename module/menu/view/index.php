<ul>
<?php foreach($this->tLink as $sLibelle => $sLink): ?>
	<?php if($sLink==''):?>
		<li class="sep"></li>
	<?php elseif(_root::getParamNav()==$sLink):?>
		<li class="selectionne"><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
	<?php else:?>
		<li><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
	<?php endif;?>
	
<?php endforeach;?>
</ul>

