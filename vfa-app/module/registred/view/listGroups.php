<div class="panel panel-default panel-root">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des groupes</h3> -->
	<!-- 	</div> -->
	<?php if($this->tGroups):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tGroups as $oGroup):?>
				<tr>
					<?php if(_root::getACL()->permit('groups::read')):?>
						<td><a href="<?php echo $this->getLink('groups::read',array('id'=>$oGroup->getId()))?>"><?php echo $oGroup->group_name ?></a></td>
					<?php else:?>
						<td><?php echo $oGroup->group_name ?></td>
					<?php endif;?>
					<td><?php echo $oGroup->getI18nStringType() ?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>
