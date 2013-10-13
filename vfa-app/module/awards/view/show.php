<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oAward->getTypeNameString() ?></h3> 
	</div>
	<div class="panel-body">
		<div class="col-sm-6 col-md-6">
			<h4><?php echo $this->oAward->getTypeShowString()?></h4>
			<h4><small>Début le </small><?php echo plugin_vfa::toStringDateShow($this->oAward->start_date) ?><small> à minuit</small></h4>
			<h4><small>Fin le </small><?php echo plugin_vfa::toStringDateShow($this->oAward->end_date) ?><small> à minuit</small></h4>
		</div>
		<div class="col-sm-6 col-md-6">
			<div class="panel panel-default panel-inner">
				<div class="panel-heading">
					<h5 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" href="#nominees">Titres sélectionnés</a>
						<?php if($this->toTitles):?>
							<?php if(_root::getACL()->permit('nominees::list')):?>
								<a class="pull-right" href="<?php echo $this->getLink('nominees::list',array('idAward'=>$this->oAward->getId()))?>">
									<i class="glyphicon glyphicon-new-window with-text"></i></a>
							<?php endif;?>
						<?php else :?>
							<?php if(_root::getACL()->permit('nominees::create')):?>
								<a class="pull-right" href="<?php echo $this->getLink('nominees::create',array('idAward'=>$this->oAward->getId()))?>">
									<i class="glyphicon glyphicon-plus"></i></a>
							<?php endif;?>
						<?php endif;?>	
					</h5>
				</div>
				<div id="nominees" class="collapse in">
				<?php if($this->toTitles):?>
					<table class="table table-striped table-condensed">
						<tbody>
							<?php foreach($this->toTitles as $oTitle):?>
							<tr>
								<td><?php echo $oTitle->toString() ?></td>
							</tr>	
							<?php endforeach;?>
						</tbody>
					</table>
				<?php endif;?>	
				</div>		
			</div>
		</div>
	</div>
</div>