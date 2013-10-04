<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" >

<table>
	
	<tr>
		<th>title</th>
		<td><input name="title" /><?php if($this->tMessage and isset($this->tMessage['title'])): echo implode(',',$this->tMessage['title']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Ajouter" />
</form>

