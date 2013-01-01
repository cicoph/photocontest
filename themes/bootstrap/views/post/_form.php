<div class="form">
<style>
.red{color:red;}
</style>
 <?php $form=$this->beginWidget('CActiveForm', array(
            'enableAjaxValidation'=>false,
			'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo CHtml::activeTextArea($model,'content',array('class'=>'description', 'style'=>'min-width:250px;max-width:250px;min-height:80px;max-height:80px;', 'rows'=>4, 'cols'=>70)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>
	<span class="countdown"></span>
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo CHtml::activeFileField($model, 'image');?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'tags',
			'url'=>array('suggestTags'),
			'multiple'=>true,
			'htmlOptions'=>array('size'=>50),
		)); ?>
		<p class="hint">Please separate different tags with commas.</p>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('PostStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

<script>
function updateCountdown() {
    // 140 is the max message length
    var remaining = 140 - $('.description').val().length;
    $('.countdown').text('Rimangono ' + remaining + ' caratteri.');
	if(remaining<10)
		$('.countdown').addClass('red');
	else
		$('.countdown').removeClass('red');
}

$(document).ready(function($) {
    updateCountdown();
    $('.description').change(updateCountdown);
    $('.description').keyup(updateCountdown);
});
</script>
</div><!-- form -->