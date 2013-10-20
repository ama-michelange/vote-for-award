<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Les albums</h3>	
	</div>
	<?php if($this->tDocs):?>
	<div class="panel-body">
		<?php foreach($this->tDocs as $oDoc):?>
			<div class="panel-images">
				<div class="panel-images-body">
					<p class="panel-images-title"><strong><?php echo $oDoc->title ?></strong></p>
					<?php if ($oDoc->toStringNumberProperTitle()):?>
						<p class="panel-images-title"><small><?php echo $oDoc->toStringNumberProperTitle() ?></small></p>
					<?php else:?>
						<p class="panel-images-title">&nbsp;</p>
					<?php endif;?>
					<?php if ($oDoc->image):?>
						<img class="img-sm" src="<?php echo $oDoc->image ?>" alt="<?php echo $oDoc->toString()?>">
					<?php else:?>
						<p class="img-sm">&nbsp;</p>
					<?php endif;?>
				</div>
				<div class="caption">
					<?php if(_root::getACL()->permit(array('docs::update','docs::delete','docs::read'))):?>     		
						<div class="btn-group">
							<?php if(_root::getACL()->permit('docs::update')):?>
							<a rel="tooltip" data-original-title="Modifier <?php echo $oDoc->toString() ?>"
								href="<?php echo $this->getLink('docs::update',array('id'=>$oDoc->getId()))?>"><i class="glyphicon glyphicon-edit"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('docs::delete')):?>
							<a rel="tooltip" data-original-title="Supprimer <?php echo $oDoc->toString() ?>"
								href="<?php echo $this->getLink('docs::delete',array('id'=>$oDoc->getId()))?>"><i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('docs::read')):?>
							<a rel="tooltip" data-original-title="Voir <?php echo $oDoc->toString() ?>"
								href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>"><i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					<?php endif;?>
				</div>
			</div>
		<?php endforeach;?>
	</div>
	<?php endif;?>
</div>
