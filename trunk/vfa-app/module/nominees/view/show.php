<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="glyphicon glyphicon-eye-open with-text"></i><?php echo $this->oTitle->toString() ?> :
				<?php if(_root::getACL()->permit('awards::read')):?>
					<a	href="<?php echo $this->getLink('awards::read',array('id'=>$this->oAward->getId()))?>">
						<?php echo $this->oAward->getTypeNameString() ?>
					</a>
				<?php else:?>
					<?php echo $this->oAward->getTypeNameString() ?>
				<?php endif;?>
		</h3>	
	</div>
	<div class="panel-body">
		<?php if($this->toDocs):?>
			<?php foreach($this->toDocs as $oDoc):?>
				<div class="col-xs-9 col-sm-5 col-md-5 bd-show">
					<h4><?php echo $oDoc->title ?></h4>
					<p><?php echo $oDoc->toStringNumberProperTitle() ?></p>
					<?php if(_root::getACL()->permit('docs::read')):?>
						<a	href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>">
							<img class="img-responsive" src="<?php echo $oDoc->image ?>" alt="">
						</a>
					<?php else:?>
						<img class="img-responsive" src="<?php echo $oDoc->image ?>" alt="">
					<?php endif;?>
				</div>
			<?php endforeach;?>
		<?php endif;?>
	</div>
</div>
