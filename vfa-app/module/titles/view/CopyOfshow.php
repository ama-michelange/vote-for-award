<div class="well well-white well-small">
	<div class="clearfix">
		<div class="row">
			<h3 class="span"><?php echo $this->oTitle->title ?></h3>
		</div>
		<?php if (null != $this->oTitle->numbers):?>
		<div class="row">
			<p class="span">Tomes inclus : <?php echo $this->oTitle->numbers ?></p>
		</div>
		<?php endif;?>
		<div class="row">
		<?php if($this->toDocs):?>
			<div class="accordion-group span">
				<div class="accordion-heading">
					<a class="accordion-toggle lead muted" data-toggle="collapse" href="#docs">Album constituant ce
						titre</a>
				</div>
				<div id="docs" class="accordion-body collapse in">
					<div class="accordion-inner bd-list">
						<?php foreach($this->toDocs as $oDoc):?>
						<div class="row">
							<div class="span">
								<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>"> <img
									src="<?php echo $oDoc->image ?>">
								</a>
							</div>
							<div class="span">
								<div>
									<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
										<?php echo plugin_vfa::formatDoc($oDoc)?>
									</a>
								</div>
								<div>
									<?php echo $oDoc->proper_title ?>	
								</div>
							</div>
						</div>
						<?php endforeach;?>
					</div>
				</div>
			</div>
			<?php endif;?>
			<?php if($this->toAwards):?>
			<div class="accordion-group span">
				<div class="accordion-heading">
					<a class="accordion-toggle lead muted" data-toggle="collapse" href="#awards">Prix utilisant ce
						titre</a>
				</div>
				<div id="awards" class="accordion-body collapse in">
					<div class="accordion-inner">
						<ul>
							<?php foreach($this->toAwards as $oAward):?>
							<li><a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>">
									<?php echo $oAward->name?>
								</a></li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>
