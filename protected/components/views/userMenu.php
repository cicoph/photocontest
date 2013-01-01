<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list',
	'items'=>array(
		array(
			'label'=>'Create New Post', 
			'icon'=>'plus',
			'active' => Yii::app()->controller->id =='create',
			'url'=>array('/post/create'), 
			'visible'=>Yii::app()->user->checkAccess('Post.Create'),
		),
		array(
			'label'=>'Admin Controller Posts', 
			'url'=>array('/post/admin'), 
			'icon'=>'user',
			'visible'=>Yii::app()->user->checkAccess('Post.Admin'),
			'active'=>Yii::app()->controller->id =='admin',
		),
		array(
			'label'=>'My Pic',
			'url'=>array('/post/manage'),
			'icon'=>'pencil',
			'visible'=>Yii::app()->user->checkAccess('Post.Manage'),
			'active'=>Yii::app()->controller->id =='manage',
		),
		array(
			'label'=>Yii::t('blog', 'Approve Comments (:commentCount)', array(':commentCount'=>Comment::model()->pendingCommentCount)), 
			'url'=>array('/comment/index'), 
			'visible'=>Yii::app()->user->checkAccess('Comment.Approve')
		),
		array(
			'label'=>'Logout', 
			'url'=>array('/user/logout'), 
			'visible'=>!Yii::app()->user->isGuest,
			'icon' => 'off'
		),
	),
));