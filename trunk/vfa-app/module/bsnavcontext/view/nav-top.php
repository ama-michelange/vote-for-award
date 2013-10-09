<div class="navbar navbar-default navbar-fixed-top" style="top:50px;">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-bsnavbarcontext">
				<span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
			<span class="navbar-brand"><?php echo $this->sTitle ?></span>
		</div>
		<div class="navbar-collapse collapse navbar-bsnavbarcontext">
			<div class="nav navbar-nav" >
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
						?>
						<?php if(false==plugin_vfa::hasParamNav($link)): ?> 
							<a class="btn btn-primary btn-sm navbar-btn" href="<?php echo $this->getLink($link) ?>">
								<?php if($icon): ?><i class="<?php echo $icon ?> with-text"></i><?php endif;?><?php echo $sLibelle ?>
							</a>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
			</div>
			<?php if ($this->exists('tButtonGroup')) :?>
			<div class="nav navbar-nav navbar-right">
				<div class="btn-group" >
					<?php foreach($this->tButtonGroup as $sLibelle => $item): ?>
						<?php
						if(is_array($item)) {
							$link = $item[0];
							$icon = $item[1];
						} 
						else {
							$link = $item;
							$icon = false;
						}
						$class = 'btn btn-info navbar-btn';
						if(true==plugin_vfa::hasParamNav($link)) {
							$class.= ' active';
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