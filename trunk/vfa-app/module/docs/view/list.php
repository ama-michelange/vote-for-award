<div class="panel panel-default">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des albums</h3> -->
	<!-- 	</div> -->
	<?php if($this->tDocs):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Titre</th>
					<th>N°</th>
					<th>Titre propre</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tDocs as $oDoc):?>
				<tr>
					<td><?php echo plugin_BsHtml::showNavLabel($oDoc->title, new NavLink('docs', 'read', array( 'id'=>$oDoc->getId()))); ?></td>
					<td><?php echo $oDoc->number ?></td>
					<td><?php echo $oDoc->proper_title ?></td>
				</tr>	
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>
