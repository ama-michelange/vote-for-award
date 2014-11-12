<div class="panel panel-default panel-root">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Les albums</h3>	 -->
	<!-- 	</div> -->
	<div class="panel-body">
	<?php if($this->tDocs):?>
		<?php foreach($this->tDocs as $oDoc):?>
			<div class="panel-images panel-images-upper">
				<div class="panel-images-body">
					<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-md',new NavLink('docs', 'read', array( 'id'=>$oDoc->getId())),true); ?>
				</div>
			</div>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
