<div class="jumbotron">
	<h1 class="text-center margin-bottom-max">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
	<!--	<p class="text-center">Pour voter ou voir les résultats, c'est simple, identifiez-vous !</p>-->
	<!--	<p class="text-center">-->
	<!--		<a href="#modalLogin" class="btn btn-default btn-lg" data-toggle="modal"><i class="glyphicon glyphicon-user with-text"></i>S'identifier</a>-->
	<!--	</p>-->
</div>
<?php if (count($this->toTitles) > 0) : ?>
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php for ($i = 0; $i < count($this->toTitles); $i++) : ?>
				<li data-target="#myCarousel" data-slide-to="<?php echo $i ?>"<?php if ($i == 0
				): echo ' class="active"'; endif; ?>></li>
			<?php endfor; ?>
		</ol>
		<!-- Carousel items -->
		<div class="carousel-inner">
			<?php for ($i = 0; $i < count($this->toTitles); $i++) : ?>
				<?php $oTitle = $this->toTitles[$i]; ?>
				<div class="item<?php if ($i == 0): echo ' active'; endif; ?>">
					<div class="text-center">
						<p class="mess hidden">?</p>
						<?php foreach ($oTitle->findDocs() as $oDoc): ?>
							<?php echo plugin_BsHtml::showNavImage($oDoc->image, null, 'img-md', null, true); ?>
						<?php endforeach; ?>
					</div>
					<div class="carousel-caption">
						<h3><?php echo $oTitle->toString() ?></h3>
						<?php echo $oTitle->order ?> en <?php echo $oTitle->year ?><br/>
					</div>
				</div>
			<?php endfor; ?>
		</div>
		<!-- Controls -->
		<a class="left carousel-control" href="#myCarousel" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
		<a class="right carousel-control" href="#myCarousel" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</a>
	</div>
<?php endif; ?>