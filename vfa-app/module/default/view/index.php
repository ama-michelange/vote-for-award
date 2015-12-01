<div class="jumbotron">
	<h1 class="text-center margin-bottom-max">Bureau de votes
		<small class="text-nowrap">du Prix de la BD INTER CE</small>
	</h1>
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
<div id="default">
	<p class="text-center">avec</p>

	<div class="center-block pub-block">
		<div class="clearfix pub-images">
			<p class="pull-right">
				<a href="https://www.facebook.com/bdfuguegrenoble" target="partner2">
					<img id="bdfugue"
							 src="https://fbcdn-sphotos-b-a.akamaihd.net/hphotos-ak-frc3/v/t1.0-9/575392_204119646441826_1062881568_n.jpg?oh=440504a27b1ee70e86267b75ef595af0&oe=56F26647&__gda__=1457706931_38feb497811d0542041f447e503da521"
							 alt="Logo BD Fugue Grenoble"></a></p>

			<p class="pull-left"><a href="http://www.alices.fr/" target="partner1">
					<img id="alices" src="http://www.alices.fr/commun/modeles/commun/structure/img/logo-alices.gif"
							 alt="Logo Alices"></a></p>
		</div>
	</div>
</div>

