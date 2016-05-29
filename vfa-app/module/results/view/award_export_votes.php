<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php if ($this->oAward) : ?>
				<?php echo $this->oAward->toString() ?>
			<?php else : ?>
				Aucun prix ...
			<?php endif; ?>
		</h3>
	</div>
	<?php if ($this->oAward && count($this->toVotes) > 0) : ?>
		<table class="table table-striped table-hover table-bordered table-responsive">
			<thead>
			<tr>
				<th>N°</th>
				<?php foreach ($this->toTitles as $title) : ?>
					<td><?php echo $title->toString() ?></td>
				<?php endforeach; ?>
				<th>Nb Votes</th>
				<th>Valide</th>
				<th>Votant</th>
				<th>Groupe</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Genre</th>
				<th>Naissance</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$cpt = 0;
			foreach ($this->toVotes as $vote) : ?>
				<?php
				$cpt++;
				$cl = '';
				$valide = 'oui';
				if ($vote->number < 7) {
					$cl = ' class="warning"';
					$valide = 'NON';
				}
				?>
				<tr<?php echo $cl ?>>
					<td><?php echo $cpt ?></td>
					<?php foreach ($this->toTitles as $title) : ?>
						<td><?php
							$item = model_vote_item::getInstance()->findByVoteIdTitleId($vote->getId(), $title->getId());
							if ($item->score > -1) {
								echo $item->score;
							}
							?></td>
					<?php endforeach; ?>
					<td><?php echo $vote->number ?></td>
					<td><?php echo $valide ?></td>
					<td><?php
						$user = model_user::getInstance()->findById($vote->user_id);
						if (false == $user->isEmpty()) {
							echo $user->login;
						}
						?></td>
					<td><?php
						if (false == $user->isEmpty()) {
							$group = $user->findGroupByRoleName(plugin_vfa::ROLE_READER);
							if (false == $group->isEmpty()) {
								echo $group->toString();
							}
						}
						?></td>
					<td><?php
						if (false == $user->isEmpty()) {
							echo $user->last_name;
						}
						?></td>
					<td><?php
						if (false == $user->isEmpty()) {
							echo $user->first_name;
						}
						?></td>
					<td><?php
						if (false == $user->isEmpty()) {
							echo $user->gender;
						}
						?></td>
					<td><?php
						if (false == $user->isEmpty()) {
							echo $user->birthyear;
						}
						?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="panel-body">
			<h4>... pas de résultat</h4>
		</div>
	<?php endif; ?>
</div>

