<div class="row">
	<table class="table table-striped">
		<tr>
			<th>Nom du prix</th>
			<th>Date de début du prix</th>
			<th>Date de fin du prix</th>
			<th></th>
		</tr>
		<?php if($this->tAwards):?>
		<?php foreach($this->tAwards as $oAward):?>
		<tr>
			<td><?php echo $oAward->name ?></td>
			<td><?php echo $oAward->start_date ?></td>
			<td><?php echo $oAward->end_date ?></td>
			<td>
				<a class="btn btn-sm" rel="tooltip" data-original-title="Modifier : <?php echo $oAward->name ?>" href="<?php echo $this->getLink('awards::edit',array('id'=>$oAward->getId()))?>">
					<i class="glyphicon glyphicon-edit"></i></a>
				<a class="btn btn-sm" href="<?php echo $this->getLink('awards::show',array('id'=>$oAward->getId()))?>"><i class="glyphicon glyphicon-check"></i> Voir</a>
				<a class="btn btn-sm" rel="tooltip" data-original-title="Supprimer : <?php echo $oAward->name ?>" href="<?php echo $this->getLink('awards::delete',array('id'=>$oAward->getId()))?>">
					<i class="glyphicon glyphicon-remove"></i></a>
			</td>
		</tr>	
		<?php endforeach;?>
		<?php endif;?>
	</table>
	<p><a class="btn btn-sm" href="<?php echo $this->getLink('awards::new') ?>"><i class="glyphicon glyphicon-plus"></i> Ajouter un prix</a></p>
</div>