<div class="panel panel-default">
	<div class="panel-body panel-condensed">
		<div class="panel panel-default panel-inner">
			<div class="panel-heading">
				<h3 class="panel-title">Invitation</h3> 
			</div>
			<div class="panel-body">		
				<dl class="dl-horizontal">
			  		<dt>Envoyée le</dt>
			  		<dd><?php echo plugin_vfa::toStringDateShow($this->oInvitation->created_date) ?></dd>
					<dt>Par</dt>
			  		<dd><?php echo $this->oCreatedUser->username ?> (<?php echo $this->oCreatedUser->email ?>)</dd>
			  	</dl>
			  	<dl class="dl-horizontal">
			  		<dt>Destinataire</dt>
			  		<dd><?php echo $this->oInvitation->email ?></dd>
				</dl>
				<dl class="dl-horizontal">
		 				<dt>Contenant</dt>
			  			<dd><a href="<?php echo plugin_vfa::generateURLInvitation($this->oInvitation)?>" target="_new">Adresse d'inscription</a></dd>
		  			</dl>
			</div>
		</div>
		<div class="panel panel-default panel-inner">
			<div class="panel-heading">
				<h3 class="panel-title">Inscription</h3> 
			</div>
			<div class="panel-body">		
			 	<dl class="dl-horizontal">
		  			<dt>Type</dt>
		  			<dd><?php echo $this->oInvitation->showFullType() ?></dd>
			  		<dt>Etat</dt>
			  		<dd><?php echo $this->oInvitation->showState() ?></dd>
		  		</dl> 	
		  		<dl class="dl-horizontal">
		  			<dt>au Prix</dt>
					<?php foreach($this->tAwards as $oAward):?>
					<dd><?php echo $oAward->getTypeNameString() ?></dd>
					<?php endforeach;?>
				</dl>
				<dl class="dl-horizontal">
		  			<dt>au Groupe</dt>
		  			<dd><?php echo $this->oGroup->group_name ?></dd>
		  		</dl>
	  		</div>
		</div>
	</div>
</div>