<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$this->pageTitle=Yii::app()->name . ' - Login';
?>
<div class="row">
    <div class="col-md-12">
        <div class="p-4">
            <h1 class="mb-3 text-18">Sign In</h1>
            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'m-t'))); ?>
            	<div class="form-group">
                    <label for="email">Your username</label>
                    <?php echo $form->textField($model,'username', array('class'=>'form-control form-control-rounded','placeholder'=>'Username')); ?>
                </div>
                <div class="form-group">
                    <label for="email">Your Password</label>
                    <?php echo $form->passwordField($model,'password', array('class'=>'form-control form-control-rounded','placeholder'=>'Password')); ?>
                </div>
				<?php echo CHtml::submitButton('Sign In', array('class'=>'btn btn-rounded btn-primary btn-block mt-2')); ?>
			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>