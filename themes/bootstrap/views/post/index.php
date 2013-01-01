<?php // $this->widget('zii.widgets.CListView', array(
	//'dataProvider'=>$dataProvider,
	//'itemView'=>'_view',
	//'template'=>"{items}\n{pager}",
//)); ?>

<?php if(!empty($_GET['tag'])): ?>
<h1>Posts Tagged with <i><?php echo CHtml::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>

<ul id="posts">
<?php foreach($posts as $post): ?>
    <li class="post">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($post->title), $post->url); ?>
	</div>
	<div class="author">
		foto di <?php echo $post->profile->first_name . ' '.$post->profile->last_name.' on ' . date('F j, Y',$post->create_time); ?>
	</div>
	<div class="photo">
		<?php echo CHtml::image('/contest/images/'.$post->filename, $post->title); ?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $post->content;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<b>Tags:</b>
		<?php echo implode(', ', $post->tagLinks); ?>
		<br/>
		<?php echo CHtml::link('Permalink', $post->url); ?> |
		<?php echo CHtml::link("Comments ({$post->commentCount})",$post->url.'#comments'); ?> |
		<?php if( Yii::app()->user->checkAccess('PostUpdateOwn', array('userid'=>$post->author_id))): ?>
			<?php echo CHtml::link('Update', array('/post/update','id'=>$post->id)); ?> |
		<?php endif; ?>
		<?php if( Yii::app()->user->checkAccess('PostDeleteOwn', array('userid'=>$post->author_id))): ?>
			<?php echo CHtml::link('Delete', array('/post/delete','id'=>$post->id)); ?> |
		<?php endif; ?>
		Last updated on <?php echo date('F j, Y',$post->update_time); ?>
	</div>
</li>
<?php endforeach; ?>
</ul>
<?php $this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
    'contentSelector' => '#posts',
    'itemSelector' => 'li.post',
    'loadingText' => 'Loading...',
    'donetext' => 'This is the end... my only friend, the end',
    'pages' => $pages,
)); ?>