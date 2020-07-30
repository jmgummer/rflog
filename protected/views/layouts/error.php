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
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="error-box">
            <div class="error-body text-center">
                <h1 class="error-title">400</h1>
                <h3 class="text-uppercase error-subtitle">Page Not Found !</h3>
                <p class="text-muted mt-4 mb-4">YOU SEEM TO BE LOST</p>
                <a href="<?=Yii::app()->createUrl("postcampaign/index");?>" class="btn btn-info btn-rounded waves-effect waves-light mb-5">Go Back Home</a>
        </div>
        <style type="text/css">
          .error-box .error-body {
            padding-top: 5%;
          }
          .text-center {
            text-align: center!important;
          }
          .error-box .error-title {
            font-size: 210px;
            font-weight: 900;
            text-shadow: 4px 4px 0 #fff, 6px 6px 0 #343a40;
            line-height: 210px;
          }
          .text-uppercase {
            text-transform: uppercase!important;
          }
        </style>
    </div>
</body>
</html>