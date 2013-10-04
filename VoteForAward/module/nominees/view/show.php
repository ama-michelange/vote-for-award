<div class="well well-white well-small">
	<div class="row">
		<h4>
			Une sélection de 
			<?php if(_root::getACL()->permit('awards::read')):?>
				<a	href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->getId()))?>">
					<?php echo $this->oAward->getTypeNameString() ?>
				</a>
			<?php else:?>
				<?php echo $this->oAward->getTypeNameString() ?>
			<?php endif;?>
		</h4>
		<h2>
			<?php echo $this->oTitle->toString() ?>
		</h2>
	</div>
	<div class="row">
		<?php if($this->toDocs):?>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle lead muted" data-toggle="collapse"
					href="#docs">Albums</a>
			</div>
			<div id="docs" class="accordion-body collapse in">
				<div class="accordion-inner bd-list">
					<ul class="thumbnails">
						<?php foreach($this->toDocs as $oDoc):?>
						<li class="col-md-6">
							<div class="img-thumbnail clearfix">
								<div class="col-md-4">
									<?php if(_root::getACL()->permit('docs::read')):?>
										<a	href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
											<img src="<?php echo $oDoc->image ?>" alt="">
										</a>
									<?php else:?>
										<img src="<?php echo $oDoc->image ?>" alt="">
									<?php endif;?>
								</div>
								<div class="col-md-8">
								<h4>
									<?php if(_root::getACL()->permit('docs::read')):?>
										<a	href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
											<?php echo $oDoc->title ?>
										</a>
									<?php else:?>
										<?php echo $oDoc->title ?>
									<?php endif;?>
								</h4>
								<p><?php echo $oDoc->toStringNumberProperTitle() ?></p>
								</div>
							</div>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
