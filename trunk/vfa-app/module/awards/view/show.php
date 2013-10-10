<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oAward->getTypeNameString() ?></h3> 
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4"><h4><strong><?php echo $this->oAward->getTypeShowString()?></strong></h4></div>
			<div class="col-md-4"><h4>Début le <strong><?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?></strong> à minuit</h4></div>
			<div class="col-md-4"><h4>Fin le <strong><?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?></strong> à minuit</h4></div>
		</div>
		
		<div class="panel panel-default panel-inner">
			<div class="panel-heading">
				<h5 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" href="#nominees">Titres sélectionnés</a>
					<?php if(_root::getACL()->permit('nominees::list')):?>
						<a class="pull-right" href="<?php echo $this->getLink('nominees::list',array('idAward'=>$this->oAward->getId()))?>">
							<i class="glyphicon glyphicon-new-window with-text"></i></a>
					<?php endif;?>
				</h5>
			</div>
			<?php if($this->toTitles):?>
			<div id="nominees" class="collapse in">
				<table class="table table-striped table-condensed">
					<thead>
						<tr>
							<th>Titre</th>
							<th>Tome</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->toTitles as $oTitle):?>
						<tr>
							<td><?php echo $oTitle->title ?></td>
							<td><?php echo $oTitle->numbers ?></td>
							</tr>	
						<?php endforeach;?>
					</tbody>
				</table>
			<?php else :?>
				<?php if(_root::getACL()->permit('nominees::create')):?>
				<div class="col-md-6 col-md-offset-3">
					<a class="btn btn-info btn-sm" href="<?php echo $this->getLink('nominees::create',array('idAward'=>$this->oAward->getId()))?>">
						<i class="glyphicon glyphicon-eye-open"></i> Ajouter un sélectionné</a>
				</div>
				<?php endif;?>
			<?php endif;?>	
			</div>		
		</div>
	</div>
</div>