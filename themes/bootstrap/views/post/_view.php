<li class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<div class="author">
		foto di <?php echo $data->profile->first_name . ' '.$data->profile->last_name.' il ' . date('F j, Y',$data->create_time); ?>
	</div>
	<div class="photo">
		<?php echo CHtml::image('/contest/images/'.$data->filename, $data->title); ?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->content;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<b>Tags:</b>
		<?php echo implode(', ', $data->tagLinks); ?>
		<br/>
		<?php echo CHtml::link('Permalink', $data->url); ?> |
		<?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); ?> |
		<?php if( Yii::app()->user->checkAccess('PostUpdateOwn', array('userid'=>$data->author_id))): ?>
			<?php echo CHtml::link('Update', array('/post/update','id'=>$data->id)); ?> |
		<?php endif; ?>
		<?php if( Yii::app()->user->checkAccess('PostDeleteOwn', array('userid'=>$data->author_id))): ?>
			<?php echo CHtml::link('Delete', array('/post/delete','id'=>$data->id)); ?> |
		<?php endif; ?>
		Last updated on <?php echo date('F j, Y',$data->update_time); ?>
	</div>
</li>
