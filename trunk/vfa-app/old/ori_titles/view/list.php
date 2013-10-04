<?php $tHidden=array('id')?>
<table>
	<tr>
		
		<th>title</th>

		<th></th>
	</tr>
	<?php if($this->tTitles):?>
	<?php foreach($this->tTitles as $oTitle):?>
	<tr>
		
		<td><?php echo $oTitle->title ?></td>

		<td>
			
			<a href="<?php echo $this->getLink('titles::edit',array('id'=>$oTitle->getId()))?>">Edit</a>
			|
			<a href="<?php echo $this->getLink('titles::show',array('id'=>$oTitle->getId()))?>">Show</a>
			|
			<a href="<?php echo $this->getLink('titles::delete',array('id'=>$oTitle->getId()))?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('titles::new') ?>">New</a></p>

