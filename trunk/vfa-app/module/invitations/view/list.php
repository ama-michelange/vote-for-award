<div class="well well-small well-white">
	<?php if($this->tInvitations):?>
	<table class="table table-hover table-condensed table-color">
		<thead>
			<tr>
				<?php if(_root::getACL()->permit(array('invitations::update','invitations::delete','invitations::read'))):?>
					<th></th>
				<?php endif;?>
				<th>Destinataire</th>	
				<th>Etat</th>
				<th>Type</th>
				<th>Prix</th>
				<th>Groupe</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->tInvitations as $oInvitation):?>
			<?php 
			switch ($oInvitation->type){
				case 'BOARD': $color='error'; break;
				case 'RESPONSIBLE': $color='warning'; break;
				case 'READER': $color='info'; break;
				default: $color=''; break;
			}				
			?>
			<tr class="<?php echo $color ?>">
				<?php if(_root::getACL()->permit(array('invitations::update','invitations::delete','invitations::read'))):?>
					<td class="col-md-1">
						<div class="btn-group">
							<?php if(_root::getACL()->permit('invitations::update')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Modifier <?php echo $oInvitation->group_name ?>" 
								href="<?php echo $this->getLink('invitations::update',array('id'=>$oInvitation->getId()))?>">
								<i class="glyphicon glyphicon-pencil"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('invitations::delete')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Supprimer <?php echo $oInvitation->group_name ?>" 
								href="<?php echo $this->getLink('invitations::delete',array('id'=>$oInvitation->getId()))?>">
								<i class="glyphicon glyphicon-trash"></i></a>
							<?php endif;?>
							<?php if(_root::getACL()->permit('invitations::read')):?>
							<a class="btn btn-xs" rel="tooltip" data-original-title="Voir <?php echo $oInvitation->group_name ?>" 
								href="<?php echo $this->getLink('invitations::read',array('id'=>$oInvitation->getId()))?>">
								<i class="glyphicon glyphicon-eye-open"></i></a>
							<?php endif;?>
						</div>
					</td>
				<?php endif;?>
				<td><?php echo $oInvitation->email ?></td>
				<td>
				<?php 
				switch ($oInvitation->state){
					case 'OPEN': $label='label-info'; break;
					case 'CLOSE': $label='label-success'; break;
					default: $label=''; break;
				}				
				echo '<span class="label '.$label.'">'.$oInvitation->showState().'</span>';
				?>
				</td>
				<td>
				<?php /*
				switch ($oInvitation->type){
					case 'BOARD': $label='label-important'; break;
					case 'RESPONSIBLE': $label='label-warning'; break;
					case 'READER': $label='label-info'; break;
					default: $label=''; break;
				}				
				echo '<span class="label '.$label.'">'.$oInvitation->showType().'</span>';
				*/ ?>
				<?php echo $oInvitation->showType() ?>
				</td>
				
				<td>
				<?php 
					$tAwards = $oInvitation->findAwards();
					$i=0;
					foreach ($tAwards as $award) { 
						if ($i > 0) {
							echo '<br>';
						};
						echo $award->getTypeNameString();
						$i++;
					}
				?>
				</td>
				<td>
				<?php 
					$oGroup = $oInvitation->findGroup();
					echo $oGroup->group_name;
				?>
				</td>
			</tr>	
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>
