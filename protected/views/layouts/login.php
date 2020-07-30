<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/lite-purple.min.css">
</head>

<body>
    <div class="auth-layout-wrap" style="background-image: url(<?php echo Yii::app()->request->baseUrl; ?>/images/photo-wide-4.jpg)">
        <div class="auth-content">
            <div class="card o-hidden">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/script.min.js"></script>
</body>

</html>