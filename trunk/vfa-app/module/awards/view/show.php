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
							<div class="col-sm-3 col-md-2 view-label">Nom</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->name ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Année</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->year ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Type</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->getTypeString() ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Affichage</div>
							<div class="col-sm-9 col-md-10 view-value"><?php echo $this->oAward->getShowString() ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Début le</div>
							<div class="col-sm-9 col-md-10 view-value">
								<?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?>
								<span class="text-muted text-nobold">à minuit</span>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3 col-md-2 view-label">Fin le</div>
							<div class="col-sm-9 col-md-10 view-value">
								<?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?>
								<span class="text-muted text-nobold">à minuit</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">Nominés
							<a class="pull-right accordion-toggle btn btn-default btn-xs" data-toggle="collapse" href="#selection"><i
									data-chevron="collapse" class="glyphicon glyphicon-collapse-up"></i></a>
						</h5>
					</div>
					<div id="selection" class="collapse in">
						<?php if ($this->toTitles): ?>
							<table class="table table-striped table-condensed table-image">
								<tbody>
								<?php foreach ($this->toTitles as $oTitle): ?>
									<tr>
										<td class="td-image">
											<?php foreach ($oTitle->findDocs() as $oDoc): ?>
												<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-xs',
													new NavLink('nominees', 'read', array('id' => $oTitle->getId(),
														'idSelection' => $this->oSelection->getId()))); ?>
											<?php endforeach; ?>
										</td>
										<td class="td-text">
											<?php echo plugin_BsHtml::showNavLabel($oTitle->toString(),
												new NavLink('nominees', 'read', array('id' => $oTitle->getId(),
													'idSelection' =>  $this->oSelection->getId()))); ?>
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						<?php else: ?>
							<div class="panel-body">
								<h4>Aucun nominé !</h4>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>