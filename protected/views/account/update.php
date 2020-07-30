<?php
$this->pageTitle=Yii::app()->name.' | Client Account';
$this->breadcrumbs=array('User Account'=>array('account/index'), 'Change Password');
?>

<div class="row clearfix">
	<div class="ibox bg-green">
		<div class="ibox-title">
	        <h5>Your Password</h5>
	    </div>
		<div class="ibox-content">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
			<div class="form-group">
				<?php echo $form->passwordFieldRow($model,'dummypass',array('required'=>'required', 'class'=>'form-control','placeholder'=>'')); ?>
			</div>
			<div class="form-group">
				<?php echo $form->passwordFieldRow($model,'dummypass2',array('required'=>'required', 'class'=>'form-control')); ?>
			</div>
			<div class="form-group">
				<?php echo $form->passwordFieldRow($model,'dummypass3',array('required'=>'required', 'class'=>'form-control')); ?>
			</div>

			<div class="clearfix"></div>
			<div class="hr-line-dashed"></div>
			<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-success')); ?>
			<?php $this->endWidget(); ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>