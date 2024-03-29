<?php
/* @var $this AuthItemController */
/* @var $model AuthItemForm */
/* @var $item CAuthItem */
/* @var $form TbActiveForm */

$this->breadcrumbs = array(
	ucfirst($this->getItemTypeText($item->type, true)) => array('index', 'type' => $item->type),
	$item->description => array('view', 'name' => $item->name),
	Yii::t('AuthModule.main', 'Edit'),
);
?>

<h1>
	<?php echo CHtml::encode($item->description); ?>
	<small><?php echo $this->getItemTypeText($item->type); ?></small>
</h1>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'type'=>'horizontal',
)); ?>

<?php echo $form->hiddenField($model, 'type'); ?>
<?php echo $form->textFieldRow($model, 'name', array(
	'disabled'=>true,
	'title'=>Yii::t('AuthModule.main', 'System name cannot be changed after creation.'),
)); ?>
<?php echo $form->textFieldRow($model, 'description'); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type' => 'primary',
		'label' => Yii::t('AuthModule.main', 'Save'),
	)); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type' => 'link',
		'label' => Yii::t('AuthModule.main', 'Cancel'),
		'url' => array('view', 'name' => $item->name),
	)); ?>
</div>

<?php $this->endWidget(); ?>