<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$this->pageTitle=Yii::app()->name . ' - Request Demo';
?>
<div class="row">
    <div class="col-md-6 text-center" style="background-size: cover;background-image: url(<?php echo Yii::app()->request->baseUrl; ?>/images/photo-long-3.jpg)">
        <div class="pl-3 auth-right">
            <div class="auth-logo text-center mt-4">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" alt="">
            </div>
            <div class="flex-grow-1"></div>
            <div class="w-100 mb-4">
                <a class="btn btn-rounded btn-outline-primary btn-outline-email btn-block btn-icon-text" href="<?php echo Yii::app()->createUrl("site/login"); ?>">
                    <i class="i-Mail-with-At-Sign"></i> Sign In
                </a>
                <a class="btn btn-rounded btn-outline-primary btn-outline-google btn-block btn-icon-text" href="<?php echo Yii::app()->createUrl("site/forgotpassword"); ?>">
                    Forgot Password?
                </a>
            </div>
            <div class="flex-grow-1"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-4">
            <h1 class="mb-3 text-18">Sign Up</h1>
            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'m-t'))); ?>
                <div class="form-group">
                    <label for="email">Your username</label>
                    <?php echo $form->textField($model,'username', array('class'=>'form-control form-control-rounded','placeholder'=>'Username')); ?>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <?php echo $form->passwordField($model,'username', array('class'=>'form-control form-control-rounded','placeholder'=>'Email')); ?>
                </div>
                <div class="form-group">
                    <label for="email">Your Password</label>
                    <?php echo $form->passwordField($model,'password', array('class'=>'form-control form-control-rounded','placeholder'=>'Password')); ?>
                </div>
                <div class="form-group">
                    <label for="email">Repeat Password</label>
                    <?php echo $form->passwordField($model,'password', array('class'=>'form-control form-control-rounded','placeholder'=>'Repeat Password')); ?>
                </div>
                <button class="btn btn-primary btn-block btn-rounded mt-3">Sign Up</button>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>