<?php
$sClassSpanDetail = 'col-md-12';
$bImage = '' != trim($this->oDoc->image);
if (true == $bImage) {
	$sClassSpanDetail = 'col-sm-7';
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Album : <?php echo $this->oDoc->toString() ?></h3>
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
				<p>
				<a class="btn btn-sm" href="<?php echo $this->oDoc->url ?>" target="resume"><i
					class="glyphicon glyphicon-globe"></i>&nbsp;&nbsp;Résumé de l'album</a>
			</p>
			<?php endif;?>	
			
			<?php if($this->toAwards):?>
				<div class="panel panel-default panel-inner">
				<div class="panel-heading">
					<h5 class="panel-title">
						Liaisons de l'album
						<a class="pull-right accordion-toggle btn btn-default btn-xs" data-toggle="collapse" href="#awards"><i data-chevron="collapse" class="glyphicon glyphicon-chevron-up"></i></a>
					</h5>
				</div>
				<div id="awards" class="collapse in">
					<table class="table table-striped table-condensed">
						<tbody>
								<?php foreach($this->toAwards as $oAward):?>
								<tr>
								<td>
									<div class="btn-group">
											<?php if(_root::getACL()->permit('awards::read')):?>
												<a class="btn btn-default btn-link dropdown-toggle" data-toggle="dropdown">
													<?php echo $oAward->getTypeNameString()?><span class="caret with-text"></span>
												</a>
											<?php else:?>
												<?php echo $oAward->getTypeNameString()?>
											<?php endif;?>
											<ul class="dropdown-menu">
												<?php if(_root::getACL()->permit('awards::read')):?>
												<li><a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>">
													<i class="glyphicon glyphicon-eye-open with-text"></i>Voir le prix</a>
												</li>
												<?php endif;?>
											
												<?php if(_root::getACL()->permit('nominees::list')):?>
												<li><a href="<?php echo $this->getLink('nominees::list',array('idAward'=>$oAward->getId()))?>">
													<i class="glyphicon glyphicon-eye-open with-text"></i>Voir la liste des sélections</a>
												</li>
												<?php endif;?>
												<?php if(_root::getACL()->permit('nominees::read')):?>
												<li><a href="<?php echo $this->getLink('nominees::readWithDoc',array('idAward'=>$oAward->getId(),'idDoc'=>$this->oDoc->getId()))?>">
													<i class="glyphicon glyphicon-eye-open with-text"></i>Voir la sélection</a>
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

