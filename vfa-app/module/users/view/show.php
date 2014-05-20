<div class="panel panel-default panel-root">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->oUser->login ?></h3>
	</div>
	<div class="panel-body panel-condensed">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<?php echo $this->oViewShowUser->show();?>
			</div>
			<?php if($this->__isset('oViewShowReaderGroup')):?>
				<div class="col-sm-6 col-md-6">
					<?php echo $this->oViewShowReaderGroup->show();?>
				</div>
			<?php endif; ?>
			<?php if($this->__isset('oViewShowBoardGroup')):?>
				<div class="col-sm-6 col-md-6">
					<?php echo $this->oViewShowBoardGroup->show();?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
