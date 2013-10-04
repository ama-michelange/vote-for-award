<div class="well well-white well-small">
	<div class="row">
		<div class="col-md-12">
			<h3>Invitation</h3>
			<dl class="dl-horizontal inverse">
				<dt>Destinataire</dt>
		  		<dd><?php echo $this->oInvitation->email ?></dd>
		  		<dt>Envoyée le</dt>
		  		<dd><?php echo plugin_vfa::toStringDateShow($this->oInvitation->created_date) ?></dd>
		  	</dl>
			<h3 class="text-info">Inscription</h3>
		  	<dl class="dl-horizontal inverse">
	  			<dt>Prix</dt>
				<?php foreach($this->tAwards as $oAward):?>
				<dd><?php echo $oAward->getTypeNameString() ?></dd>
				<?php endforeach;?>
	  			<dt>Groupe</dt>
	  			<dd><?php echo $this->oGroup->group_name ?></dd>
	  		</dl>
		</div>
	</div>
</div>