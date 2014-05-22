<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->title ?></h3>
	</div>
	<?php if($this->tUsers):?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Identifiant</th>
					<th class="col-xs-1" style="text-align:center;" data-rel="tooltip" data-original-title="Correspondant de son groupe">
						Corres.
					</th>
					<th>Inscrit</th>
					<th>Email</th>
					<th>Nom</th>
					<th>Prénom</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->tUsers as $oUser):?>
					<tr>
						<?php if(_root::getACL()->permit('users::read')):?>
							<td><a href="<?php echo $this->getLink('users::read',array('id'=>$oUser->getId()))?>"><?php echo wordwrap($oUser->login,20,'<br />', true) ?></a></td>
						<?php else:?>
							<td><?php echo wordwrap($oUser->login,20,'<br />', true) ?></td>
						<?php endif;?>
						<td style="text-align:center;">
							<?php if ($oUser->isInRole(plugin_vfa::TYPE_RESPONSIBLE)) :	?>
								<span class="glyphicon glyphicon-check"></span>
							<?php endif; ?>
						</td>
						<td>
							<?php
								$tAwards = model_award::getInstance()->findAllValidByUserId($oUser->user_id);
								$i = 0;
								foreach ($tAwards as $oAward) {
									if ($i > 0) :	echo ', '; endif;
									echo $oAward->toString();
									$i ++;
								}
							?>
						</td>
						<td><?php echo wordwrap($oUser->email, 30, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->last_name, 30, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->first_name, 30, '<br />', true) ?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<?php endif;?>
</div>
