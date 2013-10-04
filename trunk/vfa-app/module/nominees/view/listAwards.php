<div class="well well-small well-white">
	<?php if($this->tAwards):?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Prix</th>
				<th>Début</th>
				<th>Fin</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tAwards as $oAward):?>
			<tr>
				<td><a href="<?php echo $this->getLink('nominees::list',array('idAward'=>$oAward->getId()))?>">
					<?php echo $oAward->getTypeString().' '.$oAward->name ?></a>
				</td>
				<td><?php echo plugin_vfa::toStringDateShow($oAward->start_date) ?></td>
				<td><?php echo plugin_vfa::toStringDateShow($oAward->end_date) ?></td>
			</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>

