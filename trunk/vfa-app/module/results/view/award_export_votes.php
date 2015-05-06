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
	<?php if ($this->oAward) : ?>
		<table class="table table-striped table-hover table-bordered table-responsive">
			<thead>
			<tr>
				<th>N°</th>
				<th>Nb Votes</th>
				<th>Votant</th>
				<?php foreach ($this->toTitles as $title) : ?>
					<td><?php echo $title->toString() ?></td>
				<?php endforeach; ?>
			</tr>
			</thead>
			<tbody>
			<?php
			$cpt = 0;
			foreach ($this->toVotes as $vote) : ?>
				<?php
				$cpt++;
				$cl = '';
				if ($vote->number < 7) {
					$cl = ' class="warning"';
				}
				?>
				<tr<?php echo $cl ?>>
					<td><?php echo $cpt ?></td>
					<td><?php echo $vote->number ?></td>
					<td><?php
						$user = model_user::getInstance()->findById($vote->user_id);
						if (false == $user->isEmpty()) {
							echo $user->login;
						}
						?></td>
					<?php foreach ($this->toTitles as $title) : ?>
						<td><?php
							$item = model_vote_item::getInstance()->findByVoteIdTitleId($vote->getId(), $title->getId());
							if ($item->score > -1) {
								echo $item->score;
							}
							?></td>
					<?php endforeach; ?>
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
