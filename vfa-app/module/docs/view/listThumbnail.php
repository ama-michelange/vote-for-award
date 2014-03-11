<div class="panel panel-default">
	<?php if($this->tDocs):?>
	<div class="panel-body">
		<?php foreach($this->tDocs as $oDoc):?>
			<div class="panel-images">
			<div class="panel-images-body">
				<p class="panel-images-title">
					<strong><?php echo plugin_BsHtml::showNavLabel($oDoc->title, new NavLink('docs', 'read', array( 'id'=>$oDoc->getId()))); ?></strong>
				</p>
				<?php if ($oDoc->toStringNumberProperTitle()):?>
					<p class="panel-images-title">
						<small><?php echo $oDoc->toStringNumberProperTitle() ?></small>
					</p>
				<?php else:?>
					<p class="panel-images-title">&nbsp;</p>
				<?php endif;?>
				<?php if ($oDoc->image):?>
					<?php if(_root::getACL()->permit('docs::read')):?>
						<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>"><img class="img-sm" src="<?php echo $oDoc->image ?>" alt="<?php echo $oDoc->toString()?>"></a>
					<?php else:?>
						<img class="img-sm" src="<?php echo $oDoc->image ?>" alt="<?php echo $oDoc->toString()?>">
					<?php endif;?>
				<?php else:?>
					<p class="img-sm">
						<i class="glyphicon glyphicon-book"></i>
					</p>
				<?php endif;?>
			</div>
		</div>
		<?php endforeach;?>
	</div>
	<?php endif;?>
</div>
