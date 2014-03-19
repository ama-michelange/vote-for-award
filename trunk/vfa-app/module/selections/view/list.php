<div class="panel panel-default">
	<?php if($this->tSelections):?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Sélection</th>
						<th class="col-sm-2 col-md-1">Année</th>
						<th>Nom</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->tSelections as $oSelection):?>
					<tr>
						<td><?php echo plugin_BsHtml::showNavLabel($oSelection->toString(), new NavLink('selections', 'read', array( 'id'=>$oSelection->getId()))); ?></td>
						<td><?php echo $oSelection->year ?></td>
						<td><?php echo $oSelection->name ?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<h3>Aucune sélection !</h3>
		</div>
	<?php endif;?>
</div>

