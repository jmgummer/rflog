<?php
$this->pageTitle=Yii::app()->name.' | Client Account';
$this->breadcrumbs=array('User Account'=>array('account/index'), 'User Details');
?>
<div class="row clearfix">
	<div class="ibox bg-green">
		<div class="ibox-title">
	        <h5>Update User Details</h5>
	        <div class="pull-right">
	            <a href="<?=Yii::app()->createUrl("account/password");?>" class="btn btn-warning btn-xs">Change Password ?</a>
	        </div>
	    </div>
		<div class="ibox-content">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>

				<div class="form-group">
					<?php echo $form->textFieldRow($model,'surname',array( 'class'=>'form-control')); ?>
				</div>
				<div class="form-group">
					<?php echo $form->textFieldRow($model,'firstname',array( 'class'=>'form-control')); ?>
				</div>
				<div class="form-group">
					<?php //echo $form->textFieldRow($model,'email',array( 'class'=>'form-control')); ?>
				</div>

			<div class="clearfix"></div>
			<div class="hr-line-dashed"></div>
			<?php echo CHtml::submitButton('Update', array('class'=>'btn btn-primary pull-right','id'=>'update')); ?>
			<?php $this->endWidget(); ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
