<div class="well well-white well-small">
	<div class="row">
		<div class="col-md-12">
			<h2 ><?php echo $this->oTitle->title ?></h2>
			<?php if (null != $this->oTitle->numbers):?>
			<h4>Tomes inclus : <?php echo $this->oTitle->numbers ?></h4>
			<?php endif;?>
			<div class="row">
			<?php if($this->toDocs):?>
				<div class="accordion-group col-md-6">
					<div class="accordion-heading">
						<a class="accordion-toggle lead muted" data-toggle="collapse" href="#docs">Album constituant ce titre</a>
					</div>
					<div id="docs" class="accordion-body collapse in">
						<div class="accordion-inner bd-list">
							<?php foreach($this->toDocs as $oDoc):?>
							<div class="row">
								<div class="col-md-3">
									<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
										<img src="<?php echo $oDoc->image ?>">
									</a>
								</div>
								<div class="col-md-9">
									<h4>
										<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
											<?php echo $oDoc->title ?>	
										</a>
									</h4>
									<p><?php echo $oDoc->toStringNumberProperTitle() ?></p>
								</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>
				</div>
				<?php endif;?>
				<?php if($this->toAwards):?>
				<div class="accordion-group col-md-6">
					<div class="accordion-heading">
						<a class="accordion-toggle lead muted" data-toggle="collapse" href="#awards">Prix utilisant ce titre</a>
					</div>
					<div id="awards" class="accordion-body collapse in">
						<div class="accordion-inner">
							<ul>
								<?php foreach($this->toAwards as $oAward):?>
								<li>
									<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>">
										<?php echo $oAward->name ?>
									</a>
								</li>
								<?php endforeach;?>
							</ul>
						</div>
					</div>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
