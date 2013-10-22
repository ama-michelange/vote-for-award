<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oUser->username ?></h3> 
	</div>
	<div class="panel-body">
		<div class="col-sm-8 col-md-8">
			<div class="row">
				<div class="col-sm-3 view-label">Pseudo</div>
				<div class="col-sm-9 view-value"><?php echo $this->oUser->username ?></div>
			</div>
			<div class="row">
				<div class="col-sm-3 view-label">Email</div>
				<div class="col-sm-9 view-value"><?php echo $this->oUser->email ?></div>
			</div>
			<div class="row">
				<div class="col-sm-3 view-label">Nom Prénom</div>
				<div class="col-sm-9 view-value">
					<?php echo $this->oUser->last_name ?>
					<?php echo $this->oUser->first_name ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3 view-label">Année de naissance</div>
				<div class="col-sm-9 view-value"><?php echo $this->oUser->birthyear ?></div>
			</div>
			<div class="row">
				<div class="col-sm-3 view-label">Genre</div>
				<div class="col-sm-9 view-value"><?php echo plugin_vfa::getTextGender($this->oUser)?></div>
			</div>
		</div>
		<div class="col-sm-4 col-md-4">
			<?php if($this->toGroups):?>
			<div class="panel panel-default panel-inner">
				<div class="panel-heading">
					<h5 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" href="#groups">Groupes</a>
					</h5>
				</div>
				<div id="groups" class="collapse in">
					<ul>
						<?php foreach($this->toGroups as $oGroup):?>
						<li>
							<?php if(_root::getACL()->permit('groups::read')):?>
								<a href="<?php echo $this->getLink('groups::read',array('id'=>$oGroup->getId()))?>"><?php echo $oGroup->group_name ?></a>
							<?php else:?>
								<?php echo $oGroup->group_name ?>
							<?php endif;?>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>
			<?php endif;?>
			<?php if($this->toAwards):?>
			<div class="panel panel-default panel-inner">
				<div class="panel-heading">
					<h5 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" href="#awards">Prix</a>
					</h5>
				</div>
				<div id="awards" class="collapse in">
					<ul>
						<?php foreach($this->toAwards as $oAward):?>
						<li>
							<?php if(_root::getACL()->permit('awards::read')):?>
								<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>"><?php echo $oAward->name ?></a>
							<?php else:?>
								<?php echo $oAward->name ?>
							<?php endif;?>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>
			<?php endif;?>
			<?php if(_root::getACL()->permit('roles')):?>
				<?php if($this->toRoles):?>
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" href="#roles">Roles</a>
						</h5>
					</div>
					<div id="roles" class="collapse in">
						<ul>
							<?php foreach($this->toRoles as $oRole):?>
							<li>
								<?php if(_root::getACL()->permit('roles::read')):?>
									<a href="<?php echo $this->getLink('roles::read',array('id'=>$oRole->getId()))?>"><?php echo $oRole->role_name ?></a>
								<?php else:?>
									<?php echo $oRole->role_name ?>
								<?php endif;?>
							</li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>
				<?php endif;?>
			<?php endif;?>
		</div>
	</div>
</div>
