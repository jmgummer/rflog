<?php
$this->pageTitle=Yii::app()->name.' | Users';
$this->breadcrumbs=array('User Accounts'=>array('account/users'), 'User Details');
?>
<?php //echo $model->firstname; ?>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Update User Details</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->textFieldRow($model,'firstname',array('required'=>'required', 'class'=>'input-xs','placeholder'=>'')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'surname',array('required'=>'required', 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php //echo $form->textFieldRow($model,'email',array('required'=>'required', 'class'=>'input-xs')); ?>
			</label>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-success')); ?>
		</footer>
		<?php $this->endWidget(); ?>




		</div>
	</div>
</div>
