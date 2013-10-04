<div class="row">
<table>
	
	<tr>
		<th>name</th>
		<td><?php echo $this->oAward->name ?></td>
	</tr>

	<tr>
		<th>start_date</th>
		<td><?php echo $this->oAward->start_date ?></td>
	</tr>

	<tr>
		<th>end_date</th>
		<td><?php echo $this->oAward->end_date ?></td>
	</tr>

</table>

<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" />
</form>
</div>
