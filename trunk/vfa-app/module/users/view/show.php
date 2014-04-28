<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oUser->login ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="panel panel-default panel-inner">
			<div class="panel-body">
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
				<div class="row">
					<div class="col-sm-3 col-md-3 col-lg-2 view-label">Nom Prénom</div>
					<div class="col-sm-9 col-md-9 col-lg-10 view-value">
						<?php echo $this->oUser->last_name?>
						<?php echo $this->oUser->first_name?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-md-3 col-lg-2 view-label">Année de naissance</div>
					<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo $this->oUser->birthyear ?></div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-md-3 col-lg-2 view-label">Genre</div>
					<div class="col-sm-9 col-md-9 col-lg-10 view-value"><?php echo plugin_vfa::getTextGender($this->oUser)?></div>
				</div>
			</div>
		</div>
		<div class="row">
			<?php if($this->toGroups):?>
			<div class="col-sm-4 col-md-4">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							Groupes <a class="pull-right accordion-toggle" data-toggle="collapse" href="#groups"><i
								data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
						</h5>
					</div>
					<div id="groups" class="collapse in">
						<table class="table table-striped">
							<tbody>
								<?php foreach($this->toGroups as $oGroup):?>
								<tr>
									<td>
										<?php if(_root::getACL()->permit('groups::read')):?>
											<a href="<?php echo $this->getLink('groups::read',array('id'=>$oGroup->getId()))?>"
										rel="tooltip" data-original-title="Voir le groupe : <?php echo $oGroup->group_name ?>"><i
											class="glyphicon glyphicon-eye-open with-text"></i></a>
										<?php endif;?>
										<?php echo $oGroup->group_name?>
									</td>
								</tr>	
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php endif;?>
			<?php if($this->toAwards):?>
			<div class="col-sm-4 col-md-4">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							Prix <a class="pull-right accordion-toggle" data-toggle="collapse" href="#awards"><i
								data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
						</h5>
					</div>
					<div id="awards" class="collapse in">
						<table class="table table-striped">
							<tbody>
								<?php foreach($this->toAwards as $oAward):?>
								<tr>
									<td>
										<?php if(_root::getACL()->permit('awards::read')):?>
											<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>"
										rel="tooltip" data-original-title="Voir le prix : <?php echo $oAward->name ?>"><i
											class="glyphicon glyphicon-eye-open with-text"></i></a>
										<?php endif;?>
										<?php echo $oAward->name?>
									</td>
								</tr>	
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php endif;?>
			<?php if(_root::getACL()->permit('roles')):?>
				<?php if($this->toRoles):?>
				<div class="col-sm-4 col-md-4">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							Rôles <a class="pull-right accordion-toggle" data-toggle="collapse" href="#roles"><i
								data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
						</h5>
					</div>
					<div id="roles" class="collapse in">
						<table class="table table-striped">
							<tbody>
									<?php foreach($this->toRoles as $oRole):?>
									<tr>
									<td>
											<?php if(_root::getACL()->permit('roles::read')):?>
												<a href="<?php echo $this->getLink('roles::read',array('id'=>$oRole->getId()))?>"
										rel="tooltip" data-original-title="Voir le role : <?php echo $oRole->role_name ?>"><i
											class="glyphicon glyphicon-eye-open with-text"></i></a>
											<?php endif;?>
											<?php echo $oRole->role_name?>
										</td>
								</tr>	
									<?php endforeach;?>
								</tbody>
						</table>
					</div>
				</div>
			</div>
				<?php endif;?>
			<?php endif;?>
		</div>
	</div>
</div>
