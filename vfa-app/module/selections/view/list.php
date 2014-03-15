<div class="panel panel-default">
	<?php if($this->tSelections):?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Nom</th>
	<!--					TODO A conserver ?-->
	<!--					<th>Début</th>-->
	<!--					<th>Fin</th>-->
	<!--					<th>Affichage</th>-->
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->tSelections as $oSelection):?>
					<tr>
						<td><?php echo plugin_BsHtml::showNavLabel($oSelection->name, new NavLink('selections', 'read', array( 'id'=>$oSelection->getId()))); ?></td>
	<!--					<td>--><?php //echo plugin_vfa::toStringDateShow($oSelection->start_date) ?><!--</td>-->
	<!--					<td>--><?php //echo plugin_vfa::toStringDateShow($oSelection->end_date) ?><!--</td>-->
	<!--					<td>--><?php //echo $oSelection->getShowString() ?><!--</td>-->
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

