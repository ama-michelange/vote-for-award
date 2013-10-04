<ul class="nav nav-tabs nav-stacked">
<?php foreach($this->tLink as $sLibelle => $item): ?>
	<?php
	if(is_array($item)) {
		$link = $item[0];
		$icon = $item[1];
	} 
	else {
		$link = $item;
		$icon = false;
	}
	?>
	<li <?php if(plugin_vfa::hasParamNav($link)) : echo 'class="active"'; endif;?>>
		<a href="<?php echo $this->getLink($link) ?>">
		<?php if($icon): ?><i class="<?php echo $icon ?>"></i><?php endif;?> 
		<?php echo $sLibelle ?>
		</a>
	</li>
	<?php endforeach;?>
</ul>
<p>COUCOU</p>
