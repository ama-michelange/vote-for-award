<div id="modalLogin" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" action="" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>S'identifier ...</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="inputIdent">Identifiant</label>
						<input type="text" id="inputIdent" name="login" class="form-control" placeholder="Mon identifiant" required
									 autofocus/>
					</div>
					<div class="form-group">
						<label for="inputPassword">Mot de passe</label>
						<input type="password" id="inputPassword" name="password" class="form-control" required/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" name="submitLogin">
						<i class="glyphicon glyphicon-ok with-text"></i>Ok
					</button>
					<button class="btn btn-default" data-dismiss="modal">
						<i class="glyphicon glyphicon-remove with-text"></i>Annuler
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
