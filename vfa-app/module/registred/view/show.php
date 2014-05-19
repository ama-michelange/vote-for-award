<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oUser->login ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Identifiant</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->login ?></div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Adresse Email</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->email ?></div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Nom</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->last_name?></div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Prénom</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->first_name?></div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Année de naissance</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->birthyear ?></div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Genre</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo plugin_vfa::getTextGender($this->oUser)?></div>
						</div>
					</div>
				</div>
			</div>
			<?php if(false==$this->oReaderGroup->isEmpty()):?>
				<div class="col-sm-6 col-md-6">
					<div class="panel panel-default panel-inner">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-3 col-md-2 col-lg-2 view-label">Groupe</div>
								<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo $this->oReaderGroup->toString() ?></div>
								<div class="col-sm-3 col-md-2 col-lg-2 view-label">Rôle</div>
								<div class="col-sm-9 col-md-10 col-lg-10 view-value">
									Lecteur
									<?php if($this->responsibleRole):?>et Correspondant<?php endif; ?>
								</div>
								<?php if($this->toValidReaderAwards): $label='Inscrit';?>
									<?php foreach($this->toValidReaderAwards as $oAward):?>
										<div class="col-sm-3 col-md-2 col-lg-2 view-label"><?php echo $label ?></div>
										<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo $oAward->toString() ?></div>
									<?php $label=''; endforeach;?>
								<?php endif; ?>
							</div>
						</div>

					</div>
				</div>
			<?php endif; ?>
			<?php if(false==$this->oBoardGroup->isEmpty()):?>
				<div class="col-sm-6 col-md-6">
					<div class="panel panel-default panel-inner">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-3 col-md-2 col-lg-2 view-label">Groupe</div>
								<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo $this->oBoardGroup->toString() ?></div>
								<div class="col-sm-3 col-md-2 col-lg-2 view-label">Rôle</div>
								<div class="col-sm-9 col-md-10 col-lg-10 view-value">
									<?php if($this->boardRole):?>Lecteur<?php endif; ?>
								</div>
								<?php if($this->toValidBoardAwards): $label='Inscrit'; ?>
									<?php foreach($this->toValidBoardAwards as $oAward):?>
										<div class="col-sm-3 col-md-2 col-lg-2 view-label"><?php echo $label ?></div>
										<div class="col-sm-9 col-md-10 col-lg-10 view-value"><?php echo $oAward->toString() ?></div>
									<?php $label=''; endforeach;?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
