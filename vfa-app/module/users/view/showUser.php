<div class="panel panel-default panel-inner">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Nom</div>
			<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->last_name ?></div>
		</div>
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Prénom</div>
			<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->first_name ?></div>
		</div>
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Adresse Email</div>
			<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->email ?></div>
		</div>
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Identifiant</div>
			<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->login ?></div>
		</div>
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Créé</div>
			<div
				class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo plugin_vfa::toStringDateShow($this->oUser->created_date) ?></div>
		</div>
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Modifié</div>
			<div
				class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo plugin_vfa::toStringDateShow($this->oUser->modified_date) ?></div>
		</div>
	</div>
</div>
<div class="panel panel-default panel-inner">
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Année de naissance</div>
			<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo $this->oUser->birthyear ?></div>
		</div>
		<div class="row">
			<div class="col-sm-5 col-md-4 col-lg-3 view-label">Genre</div>
			<div class="col-sm-7 col-md-8 col-lg-9 view-value"><?php echo plugin_vfa::getTextGender($this->oUser) ?></div>
		</div>
	</div>
</div>
