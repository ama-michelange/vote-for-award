<form action="" method="POST" >
	<input type="hidden" name="token" value="<?php echo $this->token?>" />
	<input type="hidden" name="doc_id" value="<?php echo $this->oDoc->doc_id ?>" />
	<input type="hidden" name="date_legal" value="<?php echo $this->oDoc->date_legal ?>" />
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="<?php echo $this->iconTitle ?> with-text"></i><?php echo $this->textTitle ?></h3>
		</div>
		<div class="panel-body">
		<?php if(plugin_validation::exist($this->tMessage, 'token')):?> 
		<div class="alert alert-danger">
			<p><?php echo plugin_validation::show($this->tMessage, 'token')?></p>
			<p><a class="btn btn-sm btn-danger" href="<?php echo $this->getLink('docs::index') ?>">Fermer</a></p>
		</div>		
		<?php else:?>
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'title')?>">
				<label for="inputTitle">Titre
					<span data-rel="popover" class="btn btn-xs btn-link" data-original-title="Titre" data-content="Titre de la série ou du One-Shot">
						<i class="glyphicon glyphicon-question-sign"></i>
					</span>
				</label>				
				<input class="form-control" type="text" id="inputTitle" name="title" value="<?php echo $this->oDoc->title ?>" />
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'title')?></span>
			</div>
			<div class="<?php echo plugin_validation::addClassError('form-group', $this->tMessage, 'number')?>">
				<label for="inputNumber">Numéro
					<span class="btn btn-xs btn-link" data-rel="popover" data-original-title="Numéro" 
						data-content="Si l'album fait partie d'une série, numéro dans la série"><i class="glyphicon glyphicon-question-sign"></i>
					</span>
				</label>
				<input class="form-control" type="text" id="inputNumber" name="number" value="<?php echo $this->oDoc->number ?>" />
				<span class="help-block"><?php echo plugin_validation::show($this->tMessage, 'number')?></span>
			</div>
			<div class="form-group">
				<label for="inputProperTitle">Titre propre
					<span class="btn btn-xs btn-link" data-rel="popover" data-original-title="Titre propre"
						data-content="Si l'album fait partie d'une série, saisir le titre propre à l'album dans la série. Exemple, pour la série Astérix, 'Le combat des Chefs' est le titre propre de l'album."><i class="glyphicon glyphicon-question-sign"></i>
					</span> 
				</label>
				<input class="form-control" type="text" id="inputProperTitle" name="proper_title" value="<?php echo $this->oDoc->proper_title ?>" />
				<span class="help-block"><?php if($this->tMessage and isset($this->tMessage['proper_title'])): echo implode(',',$this->tMessage['proper_title']); endif;?></span>
			</div>
			<div class="form-group">
				<label for="inputImage">Adresse de l'image
					<span class="btn btn-xs btn-link" data-rel="popover" data-original-title="Adresse de l'image" 
						data-content="Adresse WEB complète (URL) de l'image de l'album"><i class="glyphicon glyphicon-question-sign"></i>
					</span>                                                           
				</label>
				<input class="form-control" type="text" id="inputImage" name="image" value="<?php echo $this->oDoc->image ?>" />
				<span class="help-block"><?php if($this->tMessage and isset($this->tMessage['image'])): echo implode(',',$this->tMessage['image']); endif;?></span>
			</div>
			<div class="form-group">
				<label for="inputUrl">Adresse du résumé
					<span class="btn btn-xs btn-link" data-rel="popover" data-original-title="Adresse du résumé" 
						data-content="Adresse WEB complète (URL) d'accès au résumé de l'album"><i class="glyphicon glyphicon-question-sign"></i>
					</span> 
				</label>
				<input class="form-control" type="text" id="inputUrl" name="url" value="<?php echo $this->oDoc->url ?>" />
				<span class="help-block"><?php if($this->tMessage and isset($this->tMessage['url'])): echo implode(',',$this->tMessage['url']); endif;?></span>
			</div>
		<?php endif;?>
		</div>
		<div class="panel-footer clearfix">
			<div class="pull-right">
				<?php if(trim($this->oDoc->doc_id)==false):?>
					<a class="btn btn-default" href="<?php echo $this->getLink('docs::index') ?>"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php else:?>
					<a class="btn btn-default" href="<?php echo $this->getLink('docs::read',array('id'=>$this->oDoc->doc_id)) ?>"><i class="glyphicon glyphicon-remove with-text"></i>Annuler</a>
				<?php endif;?>
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok with-text"></i>Enregistrer</button>
			</div>
		</div>
	</div>
</form>