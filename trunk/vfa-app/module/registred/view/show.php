<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oUser->login ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="panel panel-default panel-inner">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="row">
							<div class="col-sm-3 col-md-3 col-lg-2 view-label">Identifiant</div>
							<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $this->oUser->login ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-3 col-lg-2 view-label">Email</div>
							<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $this->oUser->email ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-3 col-lg-2 view-label">Alias</div>
							<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $this->oUser->alias ?></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Nom</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value">
								<?php echo $this->oUser->last_name?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-lg-3 view-label">Prénom</div>
							<div class="col-sm-7 col-md-8 col-lg-9 view-value">
								<?php echo $this->oUser->first_name?>
							</div>
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
		</div>
		<?php if(false==$this->oReaderGroup->isEmpty()):?>
			<div class="panel panel-default panel-inner">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-5 col-md-4 col-lg-2 view-label">Groupe de lecteurs</div>
						<div class="col-sm-7 col-md-8 col-lg-10 view-value"><?php echo $this->oReaderGroup->toString() ?></div>
						<?php if($this->responsibleRole):?>
							<div class="col-sm-5 col-md-4 col-lg-2 view-label"></div>
							<div class="col-sm-7 col-md-8 col-lg-10 view-value">Correspondant</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if(false==$this->oBoardGroup->isEmpty()):?>
			<div class="panel panel-default panel-inner">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-5 col-md-4 col-lg-2 view-label">Groupe de sélection</div>
						<div class="col-sm-7 col-md-8 col-lg-10 view-value"><?php echo $this->oBoardGroup->toString() ?></div>
						<?php if($this->boardRole):?>
							<div class="col-sm-5 col-md-4 col-lg-2 view-label"></div>
							<div class="col-sm-7 col-md-8 col-lg-10 view-value">Membre</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row">
			<?php if($this->toValidAwards):?>
			<div class="col-sm-4 col-md-4">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							Prix <a class="pull-right accordion-toggle" data-toggle="collapse" href="#awards"><i
								data-chevron="collapse" class="glyphicon glyphicon-collapse-up"></i></a>
						</h5>
					</div>
					<div id="awards" class="collapse in">
						<table class="table table-striped">
							<tbody>
								<?php foreach($this->toValidAwards as $oAward):?>
								<tr>
									<td>
										<?php if(_root::getACL()->permit('awards::read')):?>
											<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>"
										rel="tooltip" data-original-title="Voir le prix : <?php echo $oAward->toString() ?>"><i
											class="glyphicon glyphicon-eye-open with-text"></i></a>
										<?php endif;?>
										<?php echo $oAward->toString() ?>
									</td>
								</tr>	
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>
