<div class="navbar navbar-default navbar-fixed-top" style="top: 50px;">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
				data-target=".navbar-bsnavbarcontext">
				<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $this->getLink($this->tTitles[1])?>"><?php echo $this->tTitles[0] ?></a>
		</div>
		<div class="navbar-collapse collapse navbar-bsnavbarcontext">
			<div class="nav navbar-nav">
				<?php if ($this->__isset('tLink')) :?>
					<?php foreach($this->tLink as $sLibelle => $item): ?>
						<?php
						$separator = false;
						if (is_array($item)) {
							$link = $item[0];
							$icon = $item[1];
						} else {
							if ('_separator_' == $item) {
								$separator = true;
							} else {
								$link = $item;
								$icon = false;
							}
						}
						?>
						<?php if(true==$separator): ?> 
							&nbsp;&nbsp;
						<?php elseif(false==plugin_vfa::hasParamNav($link)): ?> 
							<a class="btn btn-default btn-sm navbar-btn" href="<?php echo $this->getLink($link) ?>">
								<?php if($icon): ?><i class="<?php echo $icon ?> with-text"></i><?php endif;?><?php echo $sLibelle?>
							</a>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
			</div>
			<?php if ($this->__isset('tButtonGroup')) :?>
			<div class="nav navbar-nav navbar-right">
				<div class="btn-group">
					<?php foreach($this->tButtonGroup as $sLibelle => $item): ?>
						<?php
					if (is_array($item)) {
						$link = $item[0];
						$icon = $item[1];
					} else {
						$link = $item;
						$icon = false;
					}
					$class = 'btn btn-default btn-sm navbar-btn';
					if (true == plugin_vfa::hasParamNav($link)) {
						$class .= ' active';
					}
					?>
						<a class="<?php echo $class ?>" href="<?php echo $this->getLink($link) ?>">
						<?php if($icon): ?><span class="<?php echo $icon ?>"></span><?php endif;?> 
						</a>
					<?php endforeach;?>
					</div>	
				<?php endif;?>	      
			</div>
		</div>
	</div>
</div>
