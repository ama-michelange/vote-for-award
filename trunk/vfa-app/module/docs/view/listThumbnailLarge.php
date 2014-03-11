<div class="panel panel-default">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Les albums</h3>	 -->
	<!-- 	</div> -->
	<div class="panel-body">
	<?php if($this->tDocs):?>
		<?php foreach($this->tDocs as $oDoc):?>
			<div class="panel-images panel-images-dark">
				<div class="panel-images-body">
					<?php if ($oDoc->image):?>
						<?php if(_root::getACL()->permit('docs::read')):?>
							<a href="<?php echo $this->getLink('docs::read',array('id'=>$oDoc->getId()))?>"><img class="img-md" src="<?php echo $oDoc->image ?>" alt="<?php echo $oDoc->toString()?>"></a>
						<?php else:?>
							<img class="img-md" src="<?php echo $oDoc->image ?>" alt="<?php echo $oDoc->toString()?>">
						<?php endif;?>
					<?php else:?>
						<p class="img-md">
							<strong><?php echo $oDoc->toString()?></strong><br />
							<i class="glyphicon glyphicon-book"></i>
						</p>
					<?php endif;?>
				</div>
			</div>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
