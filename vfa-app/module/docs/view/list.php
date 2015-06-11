<div class="panel panel-default panel-root">
	<!-- 	<div class="panel-heading"> -->
	<!-- 		<h3 class="panel-title">Liste des albums</h3> -->
	<!-- 	</div> -->
	<?php if ($this->tDocs): ?>
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-image">
				<thead>
				<tr>
					<th></th>
					<th>Titre</th>
					<th>N°</th>
					<th>Titre propre</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->tDocs as $oDoc): ?>
					<tr>
						<td class="td-image">
							<?php echo plugin_BsHtml::showNavImage($oDoc->image, $oDoc->toString(), 'img-xs', new NavLink('docs', 'read', array('id' => $oDoc->getId()))); ?>
						</td>
						<td
							class="td-text"><?php echo plugin_BsHtml::showNavLabel($oDoc->title, new NavLink('docs', 'read', array('id' => $oDoc->getId()))); ?></td>
						<td class="td-text"><?php echo $oDoc->number ?></td>
						<td class="td-text"><?php echo $oDoc->proper_title ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
