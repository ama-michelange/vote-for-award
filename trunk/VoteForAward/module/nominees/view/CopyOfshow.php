<div class="well well-white well-small">
	<div class="row">
		<div class="col-md-12">
			<h4>
				Une sélection de <a
					href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->getId()))?>">
					<?php echo $this->oAward->getTypeString().' '.$this->oAward->name ?>
				</a>
			</h4>
			<h2>
				<?php echo $this->oTitle->title ?>
				<?php if (null != $this->oTitle->numbers): echo '('.$this->oTitle->numbers.')'; endif;?>
			</h2>
			<div class="row">
				<?php if($this->toDocs):?>
				<div class="accordion-group col-md-12">
					<div class="accordion-heading">
						<a class="accordion-toggle lead muted" data-toggle="collapse"
							href="#docs">Sélectionnés</a>
					</div>
					<div id="docs" class="accordion-body collapse in">
						<div class="accordion-inner bd-list">
							<?php foreach($this->toDocs as $oDoc):?>
							<div class="row">
								<div class="col-md-2">
									<a
										href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
										<img src="<?php echo $oDoc->image ?>">
									</a>
								</div>
								<div class="col-md-10">
									<h4>
										<a
											href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
											<?php echo $oDoc->title ?>
										</a>
									</h4>
									<p>
										<?php echo plugin_vfa::formatDocNumberProperTitle($oDoc) ?>
									</p>
								</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
