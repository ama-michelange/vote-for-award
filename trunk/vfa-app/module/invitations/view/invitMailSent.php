<form class="form-horizontal" action="" method="POST">
	<input type="hidden" name="phase" value="<?php echo $this->oRegistry->phase ?>"/> <input
		type="hidden" name="type" value="<?php echo $this->oRegistry->type ?>"/> <input type="hidden"
																												  name="email"
																												  value="<?php echo $this->oRegistry->email ?>"/>
	<?php if ($this->oRegistry->award_id): ?>
		<input type="hidden" name="award_id" value="<?php echo $this->oRegistry->award_id ?>"/>
	<?php endif; ?>
	<?php if ($this->oRegistry->awards_ids): ?>
		<?php foreach ($this->oRegistry->awards_ids as $id): ?>
			<input type="hidden" name="awards_ids[]" value="<?php echo $id ?>"/>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ($this->oRegistry->group_id): ?>
		<input type="hidden" name="group_id" value="<?php echo $this->oRegistry->group_id ?>"/>
	<?php endif; ?>


	<div class="panel panel-success panel-root">
		<div class="panel-body panel-condensed">
			<div class="panel panel-success panel-inner">
				<div class="panel-heading">
					<h3 class="panel-title">Mail d'invitation envoyé</h3>
				</div>
				<div class="panel-body panel-condensed">
					<dl class="dl-horizontal">
						<dt>A</dt>
						<dd><?php echo $this->oRegistry->email ?></dd>
						<dt>En copie</dt>
						<dd><?php echo _root::getAuth()->getUserSession()->getUser()->email ?></dd>
					</dl>
					<dl class="dl-horizontal">
						<dt>Contenant</dt>
						<dd>
							<a href="<?php echo plugin_vfa::generateURLInvitation($this->oRegistry->invit) ?>"
								target="_new">Adresse d'inscription</a>
						</dd>
					</dl>
				</div>
			</div>

			<div class="panel panel-success panel-inner">
				<div class="panel-heading">
					<h3 class="panel-title">Pour l'inscription d'un <?php echo $this->oRegistry->toStringType() ?></h3>
				</div>
				<div class="panel-body panel-condensed">
					<dl class="dl-horizontal">
						<dt>au Prix</dt>
						<?php foreach ($this->tAwards as $oAward): ?>
							<dd><?php echo $oAward->toString() ?></dd>
						<?php endforeach; ?>
					</dl>
					<dl class="dl-horizontal">
						<dt>au Groupe</dt>
						<dd><?php echo $this->oGroup->group_name ?></dd>
					</dl>
				</div>
			</div>

		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<button class="btn btn-default" type="submit">
					<i class="glyphicon glyphicon-repeat with-text"></i>Autre invitation
				</button>
			</div>
		</div>
	</div>
</form>
