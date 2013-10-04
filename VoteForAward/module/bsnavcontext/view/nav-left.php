<?php /*
<div class="navbar">
	<div class="navbar-inner">
		<span class="navbar-brand">
			<?php if ($this->exists('sTitle')) :?>
			<?php echo $this->sTitle ?>
			<?php endif;?>
		</span>
	</div>
</div>
*/ ?>
<ul class="nav nav-tabs nav-stacked">
<?php if ($this->exists('tLink')) :?>
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
	if ($this->bActiveByModule) {
		$bActive = plugin_vfa::hasModule($link);
	}
	else {
		$bActive = plugin_vfa::hasParamNav($link);
	}
	?>
	<?php if($link=='separator'):?>
	</ul>
	<ul class="nav nav-tabs nav-stacked">
	<?php else:?>
	<li <?php if($bActive) : echo 'class="active"'; endif;?>>
		<a href="<?php echo $this->getLink($link) ?>">
		<?php if($icon): ?><i class="<?php echo $icon ?>"></i><?php endif;?> 
		<?php echo $sLibelle ?>
		</a>
	</li>
	<?php endif;?>
<?php endforeach;?>
<?php endif;?>
</ul>

