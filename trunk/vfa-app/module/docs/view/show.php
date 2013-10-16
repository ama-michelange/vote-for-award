<?php
$sClassSpanDetail = 'col-md-12';
$bImage = '' != trim($this->oDoc->image);
if (true == $bImage) {
	$sClassSpanDetail = 'col-sm-7';
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="glyphicon glyphicon-eye-open with-text"></i><?php echo $this->oDoc->toStringNumber() ?></h3> 
<!-- 		<h3 class="panel-title">Détail de l'album</h3>  -->
	</div>
	<div class="panel-body">
		<?php if(true == $bImage):?>
			<div class="col-sm-5 bd-show">
				<img class="img-responsive" src="<?php echo $this->oDoc->image ?>">
			</div>
		<?php endif;?>
	
		<div class="<?php echo $sClassSpanDetail ?>">
			<h2><?php echo $this->oDoc->title ?></h2>
			<h4><?php echo $this->oDoc->toStringNumberProperTitle() ?></h4>
			<?php if(trim($this->oDoc->url) != false):?>
				<p><a class="btn btn-sm" href="<?php echo $this->oDoc->url ?>" target="resume"><i class="glyphicon glyphicon-globe"></i>&nbsp;&nbsp;Résumé de l'album</a></p>
			<?php endif;?>	
			
			<?php if($this->toAwards):?>
				<div class="panel panel-default panel-inner">
					<div class="panel-heading">
						<h5 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" href="#awards">Liaisons de l'album</a>
						</h5>
					</div>
	 				<div id="awards" class="collapse in">
						<table class="table table-stripped table-condensed">
							<tbody>
								<?php foreach($this->toAwards as $oAward):?>
								<tr>
									<td>
										<?php if(_root::getACL()->permit('awards::read')):?>
											<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>">
												<?php echo $oAward->getTypeNameString() ?>
												
											</a>
										<?php else:?>
											<?php echo $oAward->getTypeNameString() ?>
										<?php endif;?>
									</td>
									<td>
										<div class="btn-group">
											<?php if(_root::getACL()->permit('awards::read')):?>
												<a class="btn btn-default btn-link dropdown-toggle" data-toggle="dropdown">
													<?php echo $oAward->getTypeNameString() ?>
													<span class="caret"></span>
												</a>
											<?php else:?>
												<?php echo $oAward->getTypeNameString() ?>
											<?php endif;?>
											<ul class="dropdown-menu">
												<?php if(_root::getACL()->permit('awards::read')):?>
												<li>
													<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>">
														<i class="glyphicon glyphicon-new-window with-text"></i>Prix</a>
												</li>
												<?php endif;?>
											
												<?php if(_root::getACL()->permit('nominees::list')):?>
												<li>
													<a href="<?php echo $this->getLink('nominees::list',array('idAward'=>$oAward->getId()))?>">
														<i class="glyphicon glyphicon-new-window with-text"></i>Liste des titres sélectionnés</a>
												</li>
												<?php endif;?>
												<?php if(_root::getACL()->permit('nominees::read')):?>
												<li>
													<a href="<?php echo $this->getLink('nominees::readWithDoc',array('idAward'=>$oAward->getId(),'idDoc'=>$this->oDoc->getId()))?>">
														<i class="glyphicon glyphicon-new-window with-text"></i>Titre sélectionné</a>
												</li>
												<?php endif;?>
											</ul>
										</div>
									</td>
									<td>
										<div class="btn-group">
											<?php if(_root::getACL()->permit('awards::read')):?>
												<a class="btn btn-default" href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>"><?php echo $oAward->getTypeNameString() ?></a>
											<?php else:?>
												<?php echo $oAward->getTypeNameString() ?>
											<?php endif;?>
											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<?php if(_root::getACL()->permit('nominees::list')):?>
												<li>
													<a href="<?php echo $this->getLink('nominees::list',array('idAward'=>$oAward->getId()))?>">
														<i class="glyphicon glyphicon-new-window with-text"></i>Liste des titres sélectionnés</a>
												</li>
												<?php endif;?>
												<?php if(_root::getACL()->permit('nominees::read')):?>
												<li>
													<a href="<?php echo $this->getLink('nominees::readWithDoc',array('idAward'=>$oAward->getId(),'idDoc'=>$this->oDoc->getId()))?>">
														<i class="glyphicon glyphicon-new-window with-text"></i>Titre sélectionné</a>
												</li>
												<?php endif;?>
											</ul>
										</div>
									</td>
								</tr>	
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif;?>
		</div>
	</div>		
</div>

