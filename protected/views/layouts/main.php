<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/lite-purple.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/classic.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/classic.date.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/csuite.css">
</head>
<body>
    <div class="app-admin-wrap">
    <div class="main-header">
            <div class="logo">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rpplogo.png" alt="MediaX">
            </div>
            <div style="margin: auto"></div>
            <div class="header-part-right">
                <!-- Full screen toggle -->
                <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>
                <!-- User avatar dropdown -->
                <div class="dropdown">
                    <div class="user col align-self-end">
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/avatars/male.png" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1"></i> <?php echo Yii::app()->user->FullName; ?>
                            </div>
                            <a class="dropdown-item">Account settings</a>
                            <a class="dropdown-item" href="<?php echo Yii::app()->createUrl("site/logout"); ?>">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Menu -->
        <?php echo Menu::GetMenu(1); ?>
        <!--=============== Left side End ================-->
        <!-- ============ Body content start ============= -->
        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <div class="breadcrumb">
                <?php if(isset($this->breadcrumbs)):?>
                    <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array('links'=>$this->breadcrumbs,)); ?>
                <?php endif?>
            </div>
            <div class="separator-breadcrumb border-top"></div>
            <?php
                $this->widget('bootstrap.widgets.TbAlert', 
                    array(
                        'fade'=>false,
                        'closeText'=>'&times;',
                        'alerts'=>array(
                            'success'=>array('block'=>false, 'fade'=>false, 'closeText'=>'&times;'),
                            'info'=>array('block'=>false, 'fade'=>false, 'closeText'=>'&times;'),
                            'warning'=>array('block'=>false, 'fade'=>false, 'closeText'=>'&times;'),
                            'error'=>array('block'=>false, 'fade'=>false, 'closeText'=>'&times;'),
                            'danger'=>array('block'=>false, 'fade'=>false, 'closeText'=>'&times;')
                        )
                    )
                );
            ?>
            <?php echo $content; ?>
        </div>
        <!-- ============ Body content End ============= -->
    </div>
    <!-- ============ Search UI End ============= -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/app.js"></script>
</body>
</html>