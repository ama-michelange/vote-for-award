<?php $tHidden=array('id')?>
<table>
	<tr>
		<th>group_name</th>
		<th></th>
	</tr>
	<?php if($this->tGroups):?>
	<?php foreach($this->tGroups as $oGroup):?>
	<tr>	
		<td><?php echo $oGroup->group_name ?></td>
		<td>
			<a href="<?php echo $this->getLink('groups::edit',array('id'=>$oGroup->getId()))?>">Edit</a>
			-
			<a href="<?php echo $this->getLink('groups::show',array('id'=>$oGroup->getId()))?>">Show</a>
			-
			<a href="<?php echo $this->getLink('groups::delete',array('id'=>$oGroup->getId()))?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('groups::new') ?>">New</a></p>

