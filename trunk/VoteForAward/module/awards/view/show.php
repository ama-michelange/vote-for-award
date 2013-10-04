<div class="well well-white">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo $this->oAward->getTypeNameString() ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php if($this->toTitles):?>
				<?php if(_root::getACL()->permit('nominees::list')):?>
				<div class="pull-right">
					<a class="btn btn-info btn-sm" href="<?php echo $this->getLink('nominees::list',array('idAward'=>$this->oAward->getId()))?>">
						<i class="glyphicon glyphicon-eye-open"></i>
						&nbsp;Détail</a>
				</div>
				<?php endif;?>
			<?php endif;?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle lead muted" data-toggle="collapse" href="#docs">Sélectionnés</a>
				</div>
				<div id="docs" class="accordion-body collapse in">
					<div class="accordion-inner bd-list">
						<?php if($this->toTitles):?>
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
		<div class="col-md-6">
			<p>Type : <strong><?php echo $this->oAward->getTypeShowString()?></strong></p>
			<p>Ouvert le <strong><?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?></strong> à minuit</p>
			<p>Clos le <strong><?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?></strong> à minuit</p>
		</div>		
	</div>
</div>