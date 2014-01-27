<div class="navbar navbar-default navbar-fixed-top" style="top: 50px;">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
				data-target=".navbar-bsnavbarcontext">
				<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
			</button>
			<?php  echo $this->oNavBar->toHtmlTitle();  ?>
		</div>
		<div class="navbar-collapse collapse navbar-bsnavbarcontext">
			<?php  echo $this->oNavBar->toHtml();  ?>
		</div>
	</div>
</div>
