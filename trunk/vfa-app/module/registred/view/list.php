<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo $this->oGroup->toString() ?>
			<small>inscrits à</small>
			<?php
			if ($this->tAwards) {
//					echo '<small>à<small> ';
				$i = 0;
				foreach ($this->tAwards as $oAward) {
					if ($i > 0) :   echo ', '; endif;
//						echo '&laquo; '.$oAward->toString().' &raquo;';
					echo $oAward->toString();
					$i++;
				}
			}
			?>

		</h3>
	</div>
	<?php if ($this->oFirstAward && $this->tUsers): ?>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>Identifiant</th>
					<th>Email</th>
					<th>Nom</th>
					<th>Prénom</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tUsers as $oUser): ?>
					<tr>
						<!--					--><?php //if(_root::getACL()->permit('users::read')):?>
						<!--						<td><a href="--><?php //echo $this->getLink('users::read',array('id'=>$oUser->getId()))?><!--">-->
						<?php //echo wordwrap($oUser->login,20,'<br />', true) ?><!--</a></td>-->
						<!--					--><?php //else:?>
						<td><?php echo wordwrap($oUser->login, 20, '<br />', true) ?></td>
						<!--					--><?php //endif;?>
						<td><?php echo wordwrap($oUser->email, 50, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->last_name, 30, '<br />', true) ?></td>
						<td><?php echo wordwrap($oUser->first_name, 30, '<br />', true) ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="panel-body">
			<?php if (!$this->oFirstAward): ?>
				<h4>Vous n'êtes inscrit à aucun prix en cours !</h4>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
