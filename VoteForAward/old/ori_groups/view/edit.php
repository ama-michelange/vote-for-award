<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" >
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oGroup->$sColumn ?>" />
	<?php if($this->tMessage and isset($this->tMessage[$sColumn])): echo implode(',',$this->tMessage[$sColumn]); endif;?>
<?php endforeach;?>	
<table>
	
	<tr>
		<th>group_name</th>
		<td><input name="group_name" value="<?php echo $this->oGroup->group_name ?>" /><?php if($this->tMessage and isset($this->tMessage['group_name'])): echo implode(',',$this->tMessage['group_name']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Modifier" />
</form>

