<?php
$sClassSpanDetail = 'col-md-12';
$bImage = '' != trim($this->oDoc->image);
if (true == $bImage) {
	$sClassSpanDetail = 'col-xs-12 col-sm-7 col-md-7';
}
?>
<!-- <div class="panel panel-default"> -->
<!-- 	<div class="panel-body"> -->
		<?php if(true == $bImage):?>
		<div class="col-xs-12 col-sm-5 col-md-5">
			<div class="thumbnail bd-show"> 
				<img class="img-responsive" src="<?php echo $this->oDoc->image ?>">
			</div>
		</div>
		<?php endif;?>
		<div class="<?php echo $sClassSpanDetail ?>">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" href="#doc">Album</a>
					</h5> 
				</div>
				<div id="doc" class="panel-collapse collapse in">
				<div class="panel-body">
					<h2><?php echo $this->oDoc->title ?></h2>
					<h4><?php echo $this->oDoc->toStringNumberProperTitle() ?></h4>
					<?php if(trim($this->oDoc->url) != false):?>
						<p><a class="btn btn-sm" href="<?php echo $this->oDoc->url ?>" target="resume"><i class="glyphicon glyphicon-globe"></i>&nbsp;&nbsp;Résumé de l'album</a></p>
					<?php endif;?>	
				</div>		
				</div>		
			</div>
			<?php if($this->toAwards):?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" href="#awards">Prix utilisant cet album</a>
						</h5>
					</div>
					<div id="awards" class="panel-collapse collapse in">
						<div class="panel-body">
							<table class="table table-stripped table-condensed">
								<tbody>
									<?php foreach($this->toAwards as $oAward):?>
									<tr>
										<td>
											<?php if(_root::getACL()->permit('awards::read')):?>
												<a href="<?php echo $this->getLink('awards::read',array('id'=>$oAward->getId()))?>"><?php echo $oAward->getTypeNameString() ?></a>
											<?php else:?>
												<?php echo $oAward->getTypeNameString() ?>
											<?php endif;?>
										</td>
										<td>
											<?php if(_root::getACL()->permit('nominees::list')):?>
												<a href="<?php echo $this->getLink('nominees::list',array('idAward'=>$oAward->getId()))?>">Sélectionnés de <?php echo $oAward->getTypeNameString() ?></a>
											<?php else:?>
												Sélectionnés de <?php echo $oAward->getTypeNameString() ?>
											<?php endif;?>
										</td>
									</tr>	
									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php endif;?>
		</div>
<!-- 	</div> -->
<!-- </div> -->