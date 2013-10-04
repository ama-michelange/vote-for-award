<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" >

<table>
	
	<tr>
		<th>group_name</th>
		<td><input name="group_name" /><?php if($this->tMessage and isset($this->tMessage['group_name'])): echo implode(',',$this->tMessage['group_name']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Ajouter" />
</form>

