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
			<?php  echo $this->oNavBar->toHtmlTitle();  ?>
		</div>
		<div class="navbar-collapse collapse navbar-bsnavbar">
		<?php  echo $this->oNavBar->toHtml();  ?>
		</div>
	</div>
</div>

