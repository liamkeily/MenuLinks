<?php echo $this->Html->script('MenuLinks.script',array('inline'=>'false')); ?>

<script>
var linklist = JSON.parse('<?php echo addslashes(json_encode($linklist)); ?>');
</script>

<div class="row-fluid padded">

	<p>Add Link To Menu? <input id="addlink" type="checkbox" name="addlink" <?php if($currentlink['Link']['id']){ echo 'checked'; } ?> /></p>

	<div class="linkform">
	<?php
	echo $this->Form->input('Link.id',array('value'=>$currentlink['Link']['id'],'type'=>'hidden'));
	echo $this->Form->input('Link.link',array('value'=>$currentlink['Link']['link'],'type'=>'hidden'));
	echo $this->Form->input('Link.menu_id',array('class'=>'linkmenu','options'=>$menulist,'default'=>$currentlink['Link']['menu_id']));
	echo $this->Form->input('Link.parent_id',array('class'=>'linkparent','options'=>$linklist[$currentlink['Link']['menu_id']],'empty'=>'-- Top Level --','default'=>$currentlink['Link']['parent_id']));
	echo $this->Form->input('Link.title',array('value'=>$currentlink['Link']['title'],'default'=>$this->data['Node']['title']));
	echo $this->Form->input('Link.status',array('default'=>$currentlink['Link']['status'],'type'=>'hidden'));
	echo $this->Form->input('Link.visibility_roles',array('default'=>$currentlink['Link']['visibility_roles'],'type'=>'hidden'));
	echo $this->Form->input('Link.params',array('default'=>$currentlink['Link']['params'],'type'=>'hidden'));
	echo $this->Form->input('Link.description',array('default'=>$currentlink['Link']['description'],'type'=>'hidden'));
	?>
	</div>
</div>
