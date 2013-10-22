<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oAward->getTypeNameString() ?></h3>
	</div>
	<div class="panel-body">
		<div class="col-sm-6 col-md-6">
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
		<div class="col-sm-6 col-md-6">
			<div class="panel panel-default panel-inner">
				<div class="panel-heading">
					<h5 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" href="#nominees">Titres sélectionnés</a>
					</h5>
				</div>
				<div id="nominees" class="collapse in">
					<?php if($this->toTitles):?>
					<table class="table table-striped">
						<tbody>
							<?php foreach($this->toTitles as $oTitle):?>
							<tr>
								<td>
									<?php if(_root::getACL()->permit('nominees::read')):?>
										<a href="<?php echo $this->getLink('nominees::read',array( 'id'=>$oTitle->getId(), 'idAward'=>$this->oAward->getId()))?>"
											rel="tooltip" data-original-title="Voir le titre : <?php echo $oTitle->toString() ?>"> 
											<i	class="glyphicon glyphicon-eye-open with-text"></i></a>
									<?php endif;?>
									<?php echo $oTitle->toString() ?> 
								</td>
							</tr>	
							<?php endforeach;?>
						</tbody>
					</table>
					<?php else:?>
					<div class="panel-body"><h4 class="label label-warning"><i class="glyphicon glyphicon-warning-sign with-text"></i>Aucun titre sélectionné !</h4></div>
					<?php endif;?>	
				</div>
			</div>
		</div>
	</div>
</div>