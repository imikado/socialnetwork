<ul class="menu">
<?php foreach($this->tLink as $sNav => $sLabel):?>
   <li <?php if(_root::getParamNav()==$sNav):?>class="selectionne"<?php endif;?>><a href="<?php echo _root::getLink($sNav,array('user_id'=>_root::getParam('user_id')))?>"><?php echo $sLabel?></a></li>
<?php endforeach; ?>
   <li><a href="<?php echo _root::getLink('mainPrivate::index')?>">Retour</a></li>
</ul>
