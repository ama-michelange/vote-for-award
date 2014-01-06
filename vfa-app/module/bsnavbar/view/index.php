<!-- Modal -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3 id="myModalLabel">S'identifier ...</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="inputIdent">Identifiant</label> <input type="text" id="inputIdent" name="login"
							class="form-control" placeholder="Mon identifiant" required="required" autofocus="autofocus" />
					</div>
					<div class="form-group">
						<label for="inputPassword">Mot de passe</label> <input type="password" id="inputPassword"
							name="password" class="form-control" required="required" />
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">
						<i class="glyphicon glyphicon-remove with-text"></i>Annuler
					</button>
					<button type="submit" class="btn btn-primary">
						<i class="glyphicon glyphicon-ok with-text"></i>Ok
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-bsnavbar">
				<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $this->oBar->get('left')->get('Accueil')->getHref()?>"><?php echo _root::getConfigVar('vfa-app.title')?></a>
		</div>
		<div class="navbar-collapse collapse navbar-bsnavbar">
			<ul class="nav navbar-nav">
			<?php foreach($this->oBar->get('left')->getItems() as $item): ?>
				<?php
					$active = $item->isActivePage();
					if ($item->hasChildren()) : 
					?>
					<li class="dropdown<?php if($active) : echo ' active'; endif;?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php if($item->hasIcon()):?><i class="glyphicon <?php echo $item->getIcon() ?> with-text"></i><?php endif;?><?php echo $item->getLabel() ?> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
						<?php foreach($item->getChildren() as $childItem): ?>
							<?php if ($childItem->isSeparator()):?>
								<li class="divider"></li>
							<?php else:?>
								<li>
									<a href="<?php echo $childItem->getHref()?>">
										<?php if($childItem->hasIcon()):?><i class="glyphicon <?php echo $childItem->getIcon() ?> with-text"></i><?php endif;?><?php echo $childItem->getLabel() ?>
									</a>
								</li>
							<?php endif;?>
						<?php endforeach;?>
						</ul>
					</li>
				<?php else:?>
					<li <?php if($active):echo 'class="active"'; endif;?>>
						<a href="<?php echo $item->getHref() ?>">
							<?php if($item->hasIcon()):?><i class="glyphicon <?php echo $item->getIcon() ?> with-text"></i><?php endif;?><?php echo $item->getLabel() ?>
						</a>
					</li>
				<?php endif;?>
			<?php endforeach;?>
			</ul>
			<?php /*
			<ul class="nav navbar-nav navbar-right">
			<?php if(_root::getAuth()->isConnected()):?>
				<?php $oUser = _root::getAuth()->getAccount()->getUser();?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="glyphicon glyphicon-user with-text"></i><small><?php echo $oUser->username;?></small>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#registration-users"><i class="glyphicon glyphicon-user with-text"></i>Compte</a></li>
						<li>
							<a href="<?php echo $this->getLink('bsnavbar::logout')?>"><i class="glyphicon glyphicon-remove-sign with-text"></i>Déconnexion</a>
						</li>
					</ul></li>
			<?php else:?>
				<li><a href="#myModal" data-toggle="modal"><i
						class="glyphicon glyphicon-user  with-text"></i>Connexion</a></li>
			<?php endif;?>
			</ul>
			*/ ?>
			<ul class="nav navbar-nav navbar-right">
			<?php foreach($this->oBar->get('right')->getItems() as $item): ?>
				<?php
					$active = $item->isActivePage();
					if ($item->hasChildren()) : 
					?>
					<li class="dropdown<?php if($active) : echo ' active'; endif;?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php if($item->hasIcon()):?><i class="glyphicon <?php echo $item->getIcon() ?> with-text"></i><?php endif;?><?php echo $item->getLabel() ?> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
						<?php foreach($item->getChildren() as $childItem): ?>
							<?php if ($childItem->isSeparator()):?>
								<li class="divider"></li>
							<?php else:?>
								<li>
									<a href="<?php echo $childItem->getHref()?>">
										<?php if($childItem->hasIcon()):?><i class="glyphicon <?php echo $childItem->getIcon() ?> with-text"></i><?php endif;?><?php echo $childItem->getLabel() ?>
									</a>
									<?php echo $childItem->buildHtmlLink()?>
								</li>
							<?php endif;?>
						<?php endforeach;?>
						</ul>
					</li>
				<?php else:?>
					<li <?php if($active):echo 'class="active"'; endif;?>>
						<?php echo $item->buildHtmlLink()?>
					</li>
				<?php endif;?>
			<?php endforeach;?>
			</ul>
			</div>
	</div>
</div>

