<div id="login">
	<div class="autobloc">
		<div class="line">Connecté</div>
		<div class="line">
			<p>
				<?php $oUser = _root::getAuth()->getAccount()->getUser();?>
				Login : <?php echo $oUser->username;?>,
				Prénom : <?php echo $oUser->first_name;?>,
				Nom : <?php echo $oUser->last_name;?>
			</p>
			<?php $oRoles = _root::getAuth()->getAccount()->getRoles();?>
			<?php if (null != $oRoles): ?>
				<?php $first = true;?>
				<p>Roles :
					<?php foreach(_root::getAuth()->getAccount()->getNameRoles() as $role):?>
						<?php if (false == $first): ?>, <?php endif;?>
						<span><?php echo $role;?></span>
						<?php $first = false;?>
					<?php endforeach;?>
				</p>
			<?php endif;?>
			<?php $oGroups = _root::getAuth()->getAccount()->getGroups();?>
			<?php if (null != $oGroups): ?>
				<?php $first = true;?>
				<p>Groups :
					<?php foreach(_root::getAuth()->getAccount()->getNameGroups() as $group):?>
						<?php if (false == $first): ?>, <?php endif;?>
						<span><?php echo $group;?></span>
						<?php $first = false;?>
					<?php endforeach;?>
				</p>
			<?php endif;?>
			<?php if (_root::getACL()->isInRole('owner')): ?>
				<p>OWNER</p>
			<?php endif;?>
		</div>
		<div class="line">
			<a href="<?php echo _root::getLink('authent::logout');?>">Déconnexion</a>
		</div>
	</div>
</div>
