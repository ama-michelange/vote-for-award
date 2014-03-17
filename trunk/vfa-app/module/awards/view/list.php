<div class="panel panel-default">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des prix</h3> -->
	<!-- 	</div> -->
	<?php if($this->tAwards):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Année</th>
					<th>Nom</th>
					<th>Type</th>
					<th>Début</th>
					<th>Fin</th>
					<th>Affichage</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tAwards as $oAward):?>
				<tr>
					<td><?php echo plugin_BsHtml::showNavLabel($oAward->toString(), new NavLink('awards', 'read', array( 'id'=>$oAward->getId()))); ?></td>
					<td><?php echo $oAward->year ?></td>
					<td><?php echo $oAward->name ?></td>
					<td><?php echo $oAward->getTypeString() ?></td>
					<td><?php echo plugin_vfa::toStringDateShow($oAward->start_date) ?></td>
					<td><?php echo plugin_vfa::toStringDateShow($oAward->end_date) ?></td>
					<td><?php echo $oAward->getShowString() ?></td>
				</tr>	
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>

