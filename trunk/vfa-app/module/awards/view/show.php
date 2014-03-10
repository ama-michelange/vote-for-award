<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oAward->getTypeNameString() ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class=" row">
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-body">
						<h2><?php echo $this->oAward->getTypeNameString() ?></h2>
						<h4><?php echo $this->oAward->getTypeShowString()?></h4>
						<h4>
							<small>Début le </small><?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?><small>
								à minuit</small>
						</h4>
						<h4>
							<small>Fin le </small><?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?><small>
								à minuit</small>
						</h4>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">Titres sélectionnés
							<a class="pull-right accordion-toggle btn btn-default btn-xs" data-toggle="collapse" href="#nominees"><i data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
						</h5>
					</div>
					<div id="nominees" class="collapse in">
						<?php if($this->toTitles):?>
						<table class="table table-striped">
							<tbody>
								<?php foreach($this->toTitles as $oTitle):?>
								<tr>
									<td><?php echo plugin_BsHtml::showNavLabel($oTitle->toString(), new NavLink('nominees', 'read', array( 'id'=>$oTitle->getId(), 'idAward'=>$this->oAward->getId()))); ?></td>
								</tr>	
								<?php endforeach;?>
							</tbody>
						</table>
						<?php else:?>
						<div class="panel-body">
							<h3>Aucun titre sélectionné !</h3>
						</div>
						<?php endif;?>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>