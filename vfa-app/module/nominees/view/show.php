<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oAward->getTypeNameString()?>
			<small><i class="glyphicon glyphicon-chevron-right"></i></small>
			<?php echo $this->oTitle->toString()?>
		</h3>
	</div>
	<div class="panel-body">
		<?php if($this->toDocs):?>
			<?php foreach($this->toDocs as $oDoc):?>
				<div class="col-xs-9 col-sm-5 col-md-5 bd-show">

			<h4><?php if(_root::getACL()->permit('docs::read')):?>
						<a class="pull-left"
					href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>" rel="tooltip"
					data-original-title="Voir l'album : <?php echo $oDoc->toString() ?>"> <i
					class="glyphicon glyphicon-eye-open with-text"></i></a>
					<?php endif;?><?php echo $oDoc->title ?></h4>
			<p><?php echo $oDoc->toStringNumberProperTitle() ?></p>
					<?php if(_root::getACL()->permit('docs::read')):?>
						<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>"> <img
				class="img-responsive" src="<?php echo $oDoc->image ?>" alt="">
			</a>
					<?php else:?>
						<img class="img-responsive" src="<?php echo $oDoc->image ?>" alt="">
					<?php endif;?>
				</div>
			<?php endforeach;?>
		<?php endif;?>
	</div>
</div>
