<?php
$sClassSpanDetail = 'col-md-12';
$bImage = '' != trim($this->oDoc->image);
if (true == $bImage) {
	$sClassSpanDetail = 'col-sm-7';
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oDoc->toString() ?></h3>
	</div>
	<div class="panel-body">
		<?php if (true == $bImage): ?>
			<div class="col-sm-5 bd-show">
				<img class="img-responsive" src="<?php echo $this->oDoc->image ?>">
			</div>
		<?php endif; ?>

		<div class="<?php echo $sClassSpanDetail ?>">
			<h2><?php echo $this->oDoc->title ?></h2>
			<h4><?php echo $this->oDoc->toStringNumberProperTitle() ?></h4>
			<?php if (trim($this->oDoc->url) != false): ?>
				<p>
					<a class="btn btn-sm" href="<?php echo $this->oDoc->url ?>" target="resume"><i
							class="glyphicon glyphicon-globe"></i>&nbsp;&nbsp;Résumé de l'album</a>
				</p>
			<?php endif; ?>

			<?php /*
			<?php if ($this->toSelections): ?>
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							Liaisons de l'album
							<a class="pull-right accordion-toggle btn btn-default btn-xs" data-toggle="collapse" href="#selections"><i
									data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
						</h5>
					</div>
					<div id="selections" class="collapse in">
						<table class="table table-striped table-condensed">
							<tbody>
								<?php foreach ($this->toSelections as $oSelection): ?>
									<tr>
										<td>
											<div class="btn-group">
												<?php if (_root::getACL()->permit('selections::read')): ?>
													<a class="btn btn-default btn-link dropdown-toggle" data-toggle="dropdown">
														<?php echo $oSelection->toString() ?><span class="caret with-text"></span>
													</a>
												<?php else: ?>
													<?php echo $oSelection->toString() ?>
												<?php endif; ?>
												<ul class="dropdown-menu">
													<?php if (_root::getACL()->permit('selections::read')): ?>
														<li><a href="<?php echo $this->getLink('selections::read', array('id' => $oSelection->getId())) ?>">
																<i class="glyphicon glyphicon-eye-open with-text"></i>Voir la sélection</a>
														</li>
													<?php endif; ?>

													<?php if (_root::getACL()->permit('nominees::list')): ?>
														<li><a href="<?php echo $this->getLink('nominees::list', array('idSelection' => $oSelection->getId())) ?>">
																<i class="glyphicon glyphicon-eye-open with-text"></i>Voir la liste des sélections</a>
														</li>
													<?php endif; ?>
													<?php if (_root::getACL()->permit('nominees::read')): ?>
														<li><a href="<?php echo $this->getLink('nominees::readWithDoc',
																array('idSelection' => $oSelection->getId(), 'idDoc' => $this->oDoc->getId())) ?>">
																<i class="glyphicon glyphicon-eye-open with-text"></i>Voir la sélection</a>
														</li>
													<?php endif; ?>
												</ul>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif; ?>
 */ ?>
		</div>
	</div>
</div>

