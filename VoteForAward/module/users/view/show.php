<div class="well well-white well-small">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo $this->oUser->username ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="accordion-group col-md-7">
			<div class="accordion-heading">
				<a class="accordion-toggle lead muted" data-toggle="collapse" href="#user">Détail</a>
			</div>
			<div id="user" class="accordion-body collapse in">
				<div class="accordion-inner">
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label">Pseudo</label>
							<div class="controls">
								<span class="input-xlarge uneditable-input"><?php echo $this->oUser->username ?></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Email</label>
							<div class="controls">
								<span class="input-xlarge uneditable-input"><?php echo $this->oUser->email ?></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Nom</label>
							<div class="controls">
								<span class="input-xlarge uneditable-input"><?php echo $this->oUser->last_name ?></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Prénom</label>
							<div class="controls">
								<span class="input-xlarge uneditable-input"><?php echo $this->oUser->first_name ?></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Année de naissance</label>
							<div class="controls">
								<span class="input-mini uneditable-input"><?php echo $this->oUser->birthyear ?></span>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Genre</label>
							<div class="controls">
								<span class="input-mini uneditable-input"><?php echo plugin_vfa::getTextGender($this->oUser)?></span>
							</div>
						</div>
						<?php  /*
						<div class="control-group">
							<label class="control-label">Electeur</label>
							<div class="controls">
								<span class="input-mini uneditable-input"><?php if ($this->oUser->vote): ?><i class="glyphicon glyphicon-ok"></i><?php endif;?></span>
							</div>
						</div>
						*/ ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<?php if($this->toGroups):?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle lead muted" data-toggle="collapse" href="#groups">Groupe associé</a>
				</div>
				<div id="groups" class="accordion-body collapse in">
					<div class="accordion-inner">
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
			</div>
			<?php endif;?>
			<?php if($this->toAwards):?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle lead muted" data-toggle="collapse" href="#awards">Prix associé</a>
				</div>
				<div id="awards" class="accordion-body collapse in">
					<div class="accordion-inner">
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
			</div>
			<?php endif;?>
			<?php if(_root::getACL()->permit('roles')):?>
				<?php if($this->toRoles):?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle lead muted" data-toggle="collapse" href="#roles">Role associé</a>
					</div>
					<div id="roles" class="accordion-body collapse in">
						<div class="accordion-inner">
							<ul>
								<?php foreach($this->toRoles as $oRole):?>
								<li><?php echo $oRole->role_name ?></li>
								<?php endforeach;?>
							</ul>
						</div>
					</div>
				</div>
				<?php endif;?>
			<?php endif;?>
		</div>
	</div>
</div>
