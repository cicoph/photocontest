<?php
/* @var $this AuthItemController */
/* @var $type string */
/* @var $dataProvider AuthItemDataProvider */

$this->breadcrumbs = array(
	ucfirst($this->getItemTypeText($type, true)),
);
?>

<h1><?php echo ucfirst($this->getItemTypeText($type, true)); ?></h1>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'type' => 'primary',
    'label' => Yii::t('AuthModule.main', 'Add {type}', array('{type}' => $this->getItemTypeText($type))),
    'url' => array('create', 'type' => $type),
)); ?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped hover',
    'dataProvider' => $dataProvider,
    'emptyText' => Yii::t('AuthModule.main', 'No {type} found.', array('{type}'=>$this->getItemTypeText($type, true))),
	'template'=>"{items}\n{pager}",
    'columns' => array(
		array(
			'name' => 'name',
			'type'=>'raw',
			'header' => Yii::t('AuthModule.main', 'System name'),
			'htmlOptions' => array('class'=>'auth-item-name-column'),
			'value' => "CHtml::link(\$data->name, array('view', 'name'=>\$data->name))",
		),
		array(
			'name' => 'description',
			'header' => Yii::t('AuthModule.main', 'Description'),
			'htmlOptions' => array('class'=>'auth-item-description-column'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'viewButtonUrl' => "Yii::app()->controller->createUrl('view', array('name'=>\$data->name))",
			'updateButtonUrl' => "Yii::app()->controller->createUrl('update', array('name'=>\$data->name))",
			'deleteButtonUrl' => "Yii::app()->controller->createUrl('delete', array('name'=>\$data->name))",
			'deleteConfirmation' => Yii::t('AuthModule.main', 'Are you sure you want to delete this item?'),
		),
    ),
)); ?>
