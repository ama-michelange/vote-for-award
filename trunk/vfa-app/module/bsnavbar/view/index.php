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
						<label for="inputIdent">Identifiant</label>
						<input type="text" id="inputIdent" name="login" class="form-control" placeholder="Mon identifiant" required="required"
								autofocus="autofocus" />
					</div>
					<div class="form-group">
						<label for="inputPassword">Mot de passe</label>
						<input type="password" id="inputPassword" name="password" class="form-control" required="required" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Ok</button>
					<button class="btn btn-default" data-dismiss="modal">Annuler</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-bsnavbar">
				<span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
			<a class="navbar-brand" href="<?php echo $this->getLink($this->tLink['Accueil'])?>">ALICES Award</a>
		</div>
		<div class="navbar-collapse collapse navbar-bsnavbar">
			<ul class="nav navbar-nav">
			<?php foreach($this->tLink as $sLibelle => $sLink): ?>
				<?php if(is_array($sLink)):
					$active = plugin_vfa::hasModuleInLinks($sLink);
				?>
					<li class="dropdown<?php if(true==$active) : echo ' active'; endif;?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $sLibelle ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
						<?php foreach($sLink as $sSousLibelle => $sSousLink): ?>
							<?php if ($sSousLink == 'separator'):?>
								<li class="divider"></li>
							<?php else:?>
								<li><a href="<?php echo $this->getLink($sSousLink)?>"><?php echo $sSousLibelle ?></a></li>
							<?php endif;?>
						<?php endforeach;?>
						</ul>
					</li>
				<?php else:?>
					<li <?php if(_root::getParamNav()==$sLink) : echo 'class="active"'; endif;?>>
						<a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a>
					</li>
				<?php endif;?>
			<?php endforeach;?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
			<?php if(_root::getAuth()->isConnected()):?>
				<?php $oUser = _root::getAuth()->getAccount()->getUser();?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user glyphicon glyphicon-white"></i> 
						<small><?php echo $oUser->username;?></small> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#registration-users"><i class="glyphicon glyphicon-user"></i> Compte</a></li>
						<li><a href="<?php echo $this->getLink('bsnavbar::logout')?>"><i class="glyphicon glyphicon-remove-sign"></i> Déconnexion</a></li>
					</ul>
				</li>
			<?php else:?>
				<li><a href="#myModal" data-toggle="modal"><i class="glyphicon glyphicon-user glyphicon glyphicon-white"></i> &nbsp;Connexion</a></li>
			<?php endif;?>
			</ul>
		</div>
	</div>
</div>

