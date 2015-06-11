<div class="panel panel-default panel-inner">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-3 col-md-2 col-lg-2 view-label">Groupe</div>
			<div class="col-sm-9 col-md-10 col-lg-10 view-value">
				<?php if (_root::getACL()->permit('groups::read')): ?>
					<a href="<?php echo $this->getLink('groups::read', array('id' => $this->oGroup->getId())) ?>">
						<?php echo $this->oGroup->toString() ?>
					</a>
				<?php else: ?>
					<?php echo $this->oGroup->toString() ?>
				<?php endif; ?>
			</div>
			<div class="col-sm-3 col-md-2 col-lg-2 view-label">Rôle</div>
			<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo($this->roles) ?></div>
			<?php if ($this->toValidAwards): $label = 'Inscrit'; ?>
				<?php foreach ($this->toValidAwards as $oAward): ?>
					<div class="col-sm-3 col-md-2 col-lg-2 view-label"><?php echo $label ?></div>
					<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo $oAward->toString() ?></div>
					<?php $label = ''; endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
