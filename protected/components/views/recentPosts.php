<ul>
<?php foreach($this->getRecentPosts() as $post): ?>
<li>
<?php echo CHtml::link(CHtml::encode($post->title),$post->url); ?>
&nbsp;on&nbsp; ciao
<?php echo CHTml::link(date('M j', $post->create_time), array('post/PostedOnDate', 'time'=>$post->create_time)); ?>
</li>
<?php endforeach; ?>
</ul>