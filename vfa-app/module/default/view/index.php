<div class="row">
	<div class="col-md-6">
		<div class="jumbotron">
			<h1 class="text-center margin-bottom-max">Bureau de votes
				<small class="text-nowrap">du Prix de la BD</small>
				<small class="text-nowrap">ALICES</small>
			</h1>
		</div>
	</div>
<!--	<div class="col-md-1 text-center">avec</div>-->
	<div class="col-md-6">
		<div id="default">
			<div class="center-block pub-block">
				<div class="clearfix pub-images">
					<p class="pull-left">avec</p>
					<p class="pull-right">
						<a href="https://www.facebook.com/bdfuguegrenoble" target="partner2">
							<img id="bdfugue"
									 src="site/img/LogoBdFugueGrenoble.jpg"
									 alt="Logo BD Fugue Grenoble"></a></p>

					<p class="pub-alices pull-left"><a href="https://www.alices-interce.fr/" target="partner1">
							<img id="alices" src="https://www.alices-interce.fr/commun/modeles/commun/structure/img/logo-alices.gif"
									 alt="Logo Alices"></a></p>
				</div>
			</div>
		</div>
	</div>
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

