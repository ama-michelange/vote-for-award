<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" >
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oTitle->$sColumn ?>" />
	<?php if($this->tMessage and isset($this->tMessage[$sColumn])): echo implode(',',$this->tMessage[$sColumn]); endif;?>
<?php endforeach;?>	
<table>
	
	<tr>
		<th>title</th>
		<td><input name="title" value="<?php echo $this->oTitle->title ?>" /><?php if($this->tMessage and isset($this->tMessage['title'])): echo implode(',',$this->tMessage['title']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Modifier" />
</form>

