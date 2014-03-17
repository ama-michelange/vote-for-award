<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oAward->toString() ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Type</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->getTypeString() ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Année</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->year ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Nom</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->name ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Affichage</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->getShowString() ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Début le</div>
							<div class="col-sm-9 col-md-10 view-value">
								<?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?>
								<small class="text-muted">à minuit</small>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Fin le</div>
							<div class="col-sm-9 col-md-10 view-value">
								<?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?>
								<small class="text-muted">à minuit</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">Sélection
							<a class="pull-right accordion-toggle btn btn-default btn-xs" data-toggle="collapse" href="#nominees"><i data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
						</h5>
					</div>
					<div id="nominees" class="collapse in">
						<?php if($this->toTitles):?>
							<table class="table table-striped table-image">
								<tbody>
								<?php foreach($this->toTitles as $oTitle):?>
									<tr>
										<td class="td-image">
											<?php foreach($oTitle->findDocs() as $oDoc):?>
												<?php echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-xs',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->getId()))); ?>
											<?php endforeach;?>
										</td>
										<td class="td-text">
											<?php echo plugin_BsHtml::showNavLabel($oTitle->toString(), new NavLink('nominees', 'read', array( 'id'=>$oTitle->getId(), 'idAward'=>$this->oAward->getId()))); ?>
										</td>
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

<!--		<div class="row">-->
<!--			<div class="col-sm-6 col-md-6">-->
<!--				<div class="panel panel-default panel-inner">-->
<!--					<div class="panel-body">-->
<!--						<h2>--><?php //echo $this->oAward->toString() ?><!--</h2>-->
<!--						<h4>--><?php //echo $this->oAward->getTypeShowString()?><!--</h4>-->
<!--						<h4>-->
<!--							<small>Début le </small>--><?php //echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?><!--<small>-->
<!--								à minuit</small>-->
<!--						</h4>-->
<!--						<h4>-->
<!--							<small>Fin le </small>--><?php //echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?><!--<small>-->
<!--								à minuit</small>-->
<!--						</h4>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="col-sm-6 col-md-6">-->
<!--				<div class="panel panel-default panel-inner">-->
<!--					<div class="panel-heading">-->
<!--						<h5 class="panel-title">Sélection-->
<!--							<a class="pull-right accordion-toggle btn btn-default btn-xs" data-toggle="collapse" href="#nominees"><i data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>-->
<!--						</h5>-->
<!--					</div>-->
<!--					<div id="nominees" class="collapse in">-->
<!--						--><?php //if($this->toTitles):?>
<!--						<table class="table table-striped table-image">-->
<!--							<tbody>-->
<!--								--><?php //foreach($this->toTitles as $oTitle):?>
<!--								<tr>-->
<!--									<td class="td-image">-->
<!--										--><?php //foreach($oTitle->findDocs() as $oDoc):?>
<!--											--><?php //echo plugin_BsHtml::showNavImage($oDoc->image,$oDoc->toString(),'img-xs',new NavLink('nominees', 'read', array('id'=>$oTitle->getId(),'idAward'=>$this->oAward->getId()))); ?>
<!--										--><?php //endforeach;?>
<!--									</td>-->
<!--									<td class="td-text">-->
<!--										--><?php //echo plugin_BsHtml::showNavLabel($oTitle->toString(), new NavLink('nominees', 'read', array( 'id'=>$oTitle->getId(), 'idAward'=>$this->oAward->getId()))); ?>
<!--									</td>-->
<!--								</tr>-->
<!--								--><?php //endforeach;?>
<!--							</tbody>-->
<!--						</table>-->
<!--						--><?php //else:?>
<!--							<div class="panel-body">-->
<!--								<h3>Aucun titre sélectionné !</h3>-->
<!--							</div>-->
<!--						--><?php //endif;?><!--	-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
	</div>
</div>